<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/payment.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td><?php echo $entry_test; ?></td>
            <td><?php if ($easypay_test) { ?>
              <input type="radio" name="easypay_test" value="1" checked="checked" />
              <?php echo $text_yes; ?>
              <input type="radio" name="easypay_test" value="0" />
              <?php echo $text_no; ?>
              <?php } else { ?>
              <input type="radio" name="easypay_test" value="1" />
              <?php echo $text_yes; ?>
              <input type="radio" name="easypay_test" value="0" checked="checked" />
              <?php echo $text_no; ?>
              <?php } ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $entry_email; ?></td>
            <td><input type="text" name="easypay_email" value="<?php echo $easypay_email; ?>" />
              <?php if ($error_email_client_number) { ?>
              <span class="error"><?php echo $error_email_client_number; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $entry_client_number; ?></td>
            <td><input type="text" name="easypay_client_number" value="<?php echo $easypay_client_number; ?>" />
              <?php if ($error_email_client_number) { ?>
              <span class="error"><?php echo $error_email_client_number; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $entry_secret_key; ?></td>
            <td><input type="text" name="easypay_secret_key" value="<?php echo $easypay_secret_key; ?>" />
              <?php if ($error_secret_key) { ?>
              <span class="error"><?php echo $error_secret_key; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_description; ?></td>
            <td><input type="text" name="easypay_description" maxlength="100" value="<?php echo $easypay_description; ?>" />
              <?php if ($error_description) { ?>
              <span class="error"><?php echo $error_description; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $entry_expired_days; ?></td>
            <td><input type="text" name="easypay_expired_days" value="<?php echo $easypay_expired_days; ?>" />
              <?php if ($error_expired_days) { ?>
              <span class="error"><?php echo $error_expired_days; ?></span>
              <?php } ?></td>
          </tr>
          <?php foreach ($languages as $language) { ?>
          <tr>
            <td><?php echo $entry_instruction; ?></td>
            <td><textarea name="easypay_instruction_<?php echo $language['language_id']; ?>" cols="80" rows="10"><?php echo isset(${'easypay_instruction_' . $language['language_id']}) ? ${'easypay_instruction_' . $language['language_id']} : ''; ?></textarea>
              <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" style="vertical-align: top;" /><br /></td>
          </tr>
          <?php } ?>
          <tr>
            <td><?php echo $entry_total; ?></td>
            <td><input type="text" name="easypay_total" value="<?php echo $easypay_total; ?>" /></td>
          </tr>
          <tr>
            <td><?php echo $entry_order_status_pending; ?></td>
            <td><select name="easypay_order_status_pending_id">
                <?php foreach ($order_statuses as $order_status) { ?>
                <?php if ($order_status['order_status_id'] == $easypay_order_status_pending_id) { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_order_status; ?></td>
            <td><select name="easypay_order_status_id">
                <?php foreach ($order_statuses as $order_status) { ?>
                <?php if ($order_status['order_status_id'] == $easypay_order_status_id) { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_order_status_denied; ?></td>
            <td><select name="easypay_order_status_denied_id">
                <?php foreach ($order_statuses as $order_status) { ?>
                <?php if ($order_status['order_status_id'] == $easypay_order_status_denied_id) { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_order_status_expired; ?></td>
            <td><select name="easypay_order_status_expired_id">
                <?php foreach ($order_statuses as $order_status) { ?>
                <?php if ($order_status['order_status_id'] == $easypay_order_status_expired_id) { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_geo_zone; ?></td>
            <td><select name="easypay_geo_zone_id">
                <option value="0"><?php echo $text_all_zones; ?></option>
                <?php foreach ($geo_zones as $geo_zone) { ?>
                <?php if ($geo_zone['geo_zone_id'] == $easypay_geo_zone_id) { ?>
                <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_status; ?></td>
            <td><select name="easypay_status">
                <?php if ($easypay_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_sort_order; ?></td>
            <td><input type="text" name="easypay_sort_order" value="<?php echo $easypay_sort_order; ?>" size="3" /></td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?>