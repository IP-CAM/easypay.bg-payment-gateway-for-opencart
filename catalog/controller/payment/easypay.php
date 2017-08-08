<?php

class ControllerPaymentEasypay extends Controller {
	protected function index() {
		$this->load->model('checkout/order');

		$this->language->load('payment/easypay');

		$this->data['text_code'] = $this->language->get('text_code');
		$this->data['text_payment'] = $this->language->get('text_payment');
		$this->data['text_failed'] = $this->language->get('text_failed');

		$this->data['button_confirm'] = $this->language->get('button_confirm');

		$this->data['text_instruction'] = nl2br($this->config->get('easypay_instruction_' . $this->config->get('config_language_id')));

		if (!$this->config->get('easypay_test')) {
			$action = 'https://www.epay.bg/ezp/reg_bill.cgi';
		} else {
			$action = 'https://demo.epay.bg/ezp/reg_bill.cgi';
		}

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		if (strlen(utf8_decode($this->config->get('easypay_client_number'))) > 0) {
			$min_email = 'MIN=' . $this->config->get('easypay_client_number');
		} elseif (strlen(utf8_decode($this->config->get('easypay_email'))) > 0) {
			$min_email = 'EMAIL=' . $this->config->get('easypay_email');
		}

		$secret = $this->config->get('easypay_secret_key');
		$invoice = $this->session->data['order_id'];
		//$sum = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], FALSE);
		$sum = $this->currency->format($order_info['total'], 'BGN', '', FALSE); //ePay.bg allows only BGN
		$exp_date = date('d.m.Y', mktime(0, 0, 0, date('m'), date('d') + $this->config->get('easypay_expired_days'), date('Y')));
		$description = $this->config->get('easypay_description');

		$data = <<<DATA
{$min_email}
INVOICE={$invoice}
AMOUNT={$sum}
EXP_TIME={$exp_date}
DESCR={$description}
ENCODING=utf-8
DATA;

		$encoded = base64_encode($data);
		$checksum = $this->hmac('sha1', $encoded, $secret);

		$this->data['code'] = '';
		$this->data['failed'] = '';

		$response = @file_get_contents($action . '?ENCODED=' . urlencode($encoded) . '&CHECKSUM=' . urlencode($checksum)); //IDN=1234567890 (payment code - 10 digits) or ERR=Error description

		if ($response) {
			$response = trim($response);

			if (strpos($response, 'IDN=') !== false) {
				$this->data['code'] = substr($response, 4);
			} elseif (strpos($response, 'ERR=') !== false) {
				$this->data['failed'] = mb_convert_encoding(substr($response, 4), 'UTF-8', 'Windows-1251');
			}
		}

		$this->data['continue'] = $this->url->link('checkout/success');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/easypay.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/easypay.tpl';
		} else {
			$this->template = 'default/template/payment/easypay.tpl';
		}

		$this->render();
	}

	public function confirm() {
		$this->language->load('payment/easypay');

		$this->load->model('checkout/order');

		$comment = $this->config->get('easypay_instruction_' . $this->config->get('config_language_id')) . "\n\n";
		$comment .= $this->language->get('text_code') . ' ' . $this->request->get['code'] . "\n\n";
		$comment .= $this->language->get('text_payment');

		$this->model_checkout_order->confirm($this->session->data['order_id'], $this->config->get('easypay_order_status_pending_id'), $comment, true);
	}

	public function callback() {
		if (isset($this->request->post['encoded'])) {
			$encoded  = $this->request->post['encoded'];
		} else {
			$encoded  = '';
		}

		if (isset($this->request->post['checksum'])) {
			$checksum = $this->request->post['checksum'];
		} else {
			$checksum = '';
		}

		$secret = $this->config->get('easypay_secret_key');
		$hmac = $this->hmac('sha1', $encoded, $secret);

		if ($hmac == $checksum) {
			$data = base64_decode($encoded);
			$lines_arr = explode("\n", $data);
			$info_data = '';

			foreach ($lines_arr as $line) {
				if (preg_match("/^INVOICE=(\d+):STATUS=(PAID|DENIED|EXPIRED)(:PAY_TIME=(\d+):STAN=(\d+):BCODE=([0-9a-zA-Z]+))?$/", $line, $regs)) {
					$invoice  = $regs[1];
					$status   = $regs[2];
					//$pay_date = $regs[4]; # XXX if PAID
					//$stan     = $regs[5]; # XXX if PAID
					//$bcode    = $regs[6]; # XXX if PAID

					if ($status == 'PAID' || $status == 'DENIED' || $status == 'EXPIRED') {
						$this->load->model('checkout/order');

						$order_id = $invoice;
						$order_info = $this->model_checkout_order->getOrder($order_id);

						if ($order_info) {
							if ($order_info['order_status_id'] > 0) {
								$easypay = true;
								$epay = false;
							} else {
								$easypay = false;
								$epay = true;
							}

							if ($status == 'PAID') {
								if ($epay && $this->config->get('epay_order_status_id')) {
									$order_status = $this->config->get('epay_order_status_id');
								} else {
									$order_status = $this->config->get('easypay_order_status_id');
								}
							} elseif ($status == 'DENIED') {
								if ($epay && $this->config->get('epay_order_status_denied_id')) {
									$order_status = $this->config->get('epay_order_status_denied_id');
								} else {
									$order_status = $this->config->get('easypay_order_status_denied_id');
								}
							} elseif ($status == 'EXPIRED') {
								if ($epay && $this->config->get('epay_order_status_expired_id')) {
									$order_status = $this->config->get('epay_order_status_expired_id');
								} else {
									$order_status = $this->config->get('easypay_order_status_expired_id');
								}
							} else {
								$order_status = 0;
							}

							if ($order_status) {
								if ($epay) {
									$this->model_checkout_order->confirm($order_id, $order_status);
								} else {
									$this->model_checkout_order->update($order_id, $order_status);
								}
							}

							$info_data .= "INVOICE=$invoice:STATUS=OK\n";
						} else {
							$info_data .= "INVOICE=$invoice:STATUS=NO\n";
						}
					} else {
						$info_data .= "INVOICE=$invoice:STATUS=ERR\n";
					}
				}
			}

			echo $info_data, "\n";
		} else {
			echo "ERR=Not valid CHECKSUM\n";
		}
		exit;
	}

	private function hmac($algo, $data, $passwd){
		/* md5 and sha1 only */
		$algo=strtolower($algo);
		$p=array('md5'=>'H32','sha1'=>'H40');
		if(strlen($passwd)>64) $passwd=pack($p[$algo],$algo($passwd));
		if(strlen($passwd)<64) $passwd=str_pad($passwd,64,chr(0));

		$ipad=substr($passwd,0,64) ^ str_repeat(chr(0x36),64);
		$opad=substr($passwd,0,64) ^ str_repeat(chr(0x5C),64);

		return($algo($opad.pack($p[$algo],$algo($ipad.$data))));
	}
}
?>