<?php

class ControllerPaymentEasypay extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('payment/easypay');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->model_setting_setting->editSetting('easypay', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');

		$this->data['entry_test'] = $this->language->get('entry_test');
		$this->data['entry_email'] = $this->language->get('entry_email');
		$this->data['entry_client_number'] = $this->language->get('entry_client_number');
		$this->data['entry_secret_key'] = $this->language->get('entry_secret_key');
		$this->data['entry_description'] = $this->language->get('entry_description');
		$this->data['entry_expired_days'] = $this->language->get('entry_expired_days');
		$this->data['entry_instruction'] = $this->language->get('entry_instruction');
		$this->data['entry_total'] = $this->language->get('entry_total');
		$this->data['entry_order_status_pending'] = $this->language->get('entry_order_status_pending');
		$this->data['entry_order_status'] = $this->language->get('entry_order_status');
		$this->data['entry_order_status_denied'] = $this->language->get('entry_order_status_denied');
		$this->data['entry_order_status_expired'] = $this->language->get('entry_order_status_expired');
		$this->data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->error['email_client_number'])) {
			$this->data['error_email_client_number'] = $this->error['email_client_number'];
		} else {
			$this->data['error_email_client_number'] = '';
		}

		if (isset($this->error['secret_key'])) {
			$this->data['error_secret_key'] = $this->error['secret_key'];
		} else {
			$this->data['error_secret_key'] = '';
		}

		if (isset($this->error['description'])) {
			$this->data['error_description'] = $this->error['description'];
		} else {
			$this->data['error_description'] = '';
		}

		if (isset($this->error['expired_days'])) {
			$this->data['error_expired_days'] = $this->error['expired_days'];
		} else {
			$this->data['error_expired_days'] = '';
		}

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_payment'),
			'href'      => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('payment/easypay', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$this->data['action'] = $this->url->link('payment/easypay', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['easypay_test'])) {
			$this->data['easypay_test'] = $this->request->post['easypay_test'];
		} else {
			$this->data['easypay_test'] = $this->config->get('easypay_test');
		}

		if (isset($this->request->post['easypay_email'])) {
			$this->data['easypay_email'] = $this->request->post['easypay_email'];
		} else {
			$this->data['easypay_email'] = $this->config->get('easypay_email');
		}

		if (isset($this->request->post['easypay_client_number'])) {
			$this->data['easypay_client_number'] = $this->request->post['easypay_client_number'];
		} else {
			$this->data['easypay_client_number'] = $this->config->get('easypay_client_number');
		}

		if (isset($this->request->post['easypay_secret_key'])) {
			$this->data['easypay_secret_key'] = $this->request->post['easypay_secret_key'];
		} else {
			$this->data['easypay_secret_key'] = $this->config->get('easypay_secret_key');
		}

		if (isset($this->request->post['easypay_description'])) {
			$this->data['easypay_description'] = $this->request->post['easypay_description'];
		} else {
			$this->data['easypay_description'] = $this->config->get('easypay_description');
		}

		if (isset($this->request->post['easypay_expired_days'])) {
			$this->data['easypay_expired_days'] = $this->request->post['easypay_expired_days'];
		} else {
			$this->data['easypay_expired_days'] = $this->config->get('easypay_expired_days');
		}

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		foreach ($languages as $language) {
			if (isset($this->request->post['easypay_instruction_' . $language['language_id']])) {
				$this->data['easypay_instruction_' . $language['language_id']] = $this->request->post['easypay_instruction_' . $language['language_id']];
			} else {
				$this->data['easypay_instruction_' . $language['language_id']] = $this->config->get('easypay_instruction_' . $language['language_id']);
			}
		}

		$this->data['languages'] = $languages;

		if (isset($this->request->post['easypay_total'])) {
			$this->data['easypay_total'] = $this->request->post['easypay_total'];
		} else {
			$this->data['easypay_total'] = $this->config->get('easypay_total');
		}

		if (isset($this->request->post['easypay_order_status_pending_id'])) {
			$this->data['easypay_order_status_pending_id'] = $this->request->post['easypay_order_status_pending_id'];
		} else {
			$this->data['easypay_order_status_pending_id'] = $this->config->get('easypay_order_status_pending_id');
		}

		if (isset($this->request->post['easypay_order_status_id'])) {
			$this->data['easypay_order_status_id'] = $this->request->post['easypay_order_status_id'];
		} else {
			$this->data['easypay_order_status_id'] = $this->config->get('easypay_order_status_id');
		}

		if (isset($this->request->post['easypay_order_status_denied_id'])) {
			$this->data['easypay_order_status_denied_id'] = $this->request->post['easypay_order_status_denied_id'];
		} else {
			$this->data['easypay_order_status_denied_id'] = $this->config->get('easypay_order_status_denied_id');
		}

		if (isset($this->request->post['easypay_order_status_expired_id'])) {
			$this->data['easypay_order_status_expired_id'] = $this->request->post['easypay_order_status_expired_id'];
		} else {
			$this->data['easypay_order_status_expired_id'] = $this->config->get('easypay_order_status_expired_id');
		}

		$this->load->model('localisation/order_status');

		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['easypay_geo_zone_id'])) {
			$this->data['easypay_geo_zone_id'] = $this->request->post['easypay_geo_zone_id'];
		} else {
			$this->data['easypay_geo_zone_id'] = $this->config->get('easypay_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['easypay_status'])) {
			$this->data['easypay_status'] = $this->request->post['easypay_status'];
		} else {
			$this->data['easypay_status'] = $this->config->get('easypay_status');
		}

		if (isset($this->request->post['easypay_sort_order'])) {
			$this->data['easypay_sort_order'] = $this->request->post['easypay_sort_order'];
		} else {
			$this->data['easypay_sort_order'] = $this->config->get('easypay_sort_order');
		}

		$this->template = 'payment/easypay.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'payment/easypay')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['easypay_email'] && !$this->request->post['easypay_client_number']) {
			$this->error['email_client_number'] = $this->language->get('error_email_client_number');
		}

		if (!$this->request->post['easypay_secret_key']) {
			$this->error['secret_key'] = $this->language->get('error_secret_key');
		}

		if (strlen(utf8_decode($this->request->post['easypay_description'])) > 100) {
			$this->error['description'] = $this->language->get('error_description');
		}

		$pattern = '/^[1-9]+[0-9]*$/';

		if (!preg_match($pattern, $this->request->post['easypay_expired_days'])) {
			$this->error['expired_days'] = $this->language->get('error_expired_days');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}
?>