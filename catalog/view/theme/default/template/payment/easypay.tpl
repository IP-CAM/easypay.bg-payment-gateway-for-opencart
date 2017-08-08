<?php if ($code) { ?>
<p><?php echo $text_instruction; ?></p>
<p><?php echo $text_code; ?> <b><?php echo $code; ?></b></p>
<p><?php echo $text_payment; ?></p>
<div class="buttons">
  <div class="right"><a id="button-confirm" class="button"><span><?php echo $button_confirm; ?></span></a></div>
</div>
<script type="text/javascript"><!--
$('#button-confirm').bind('click', function() {
	$.ajax({
		type: 'GET',
		url: 'index.php?route=payment/easypay/confirm&code=<?php echo $code; ?>',
		success: function() {
			location = '<?php echo $continue; ?>';
		}
	});
});
//--></script>
<?php } elseif ($failed) { ?>
<p><b><?php echo $failed; ?></b></p>
<?php } else { ?>
<p><?php echo $text_failed; ?></p>
<?php } ?>