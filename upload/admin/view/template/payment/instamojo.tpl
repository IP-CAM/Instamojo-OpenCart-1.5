<?php echo $header; ?>
<div id="content">
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/payment.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td><?php echo $entry_order_status; ?></td>
            <td><select name="instamojo_order_status_id">
                <?php foreach ($order_statuses as $order_status) { ?>
                <?php if ($order_status['order_status_id'] == $instamojo_order_status_id) { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select></td>
          </tr>

          <tr>
            <td><?php echo $entry_text_instamojo_checkout_label; ?></td>
            <td><input type="text" size="100" name="instamojo_checkout_label" value="<?php echo $instamojo_checkout_label; ?>"/></td>
          </tr>

          <tr>
            <td><?php echo $entry_text_instamojo_api_key; ?></td>
            <td><input type="text" size="100" name="instamojo_api_key" value="<?php echo $instamojo_api_key; ?>"/></td>
          </tr>
          <tr>
            <td><?php echo $entry_text_instamojo_auth_token; ?></td>
            <td><input type="text" size="100" name="instamojo_auth_token" value="<?php echo $instamojo_auth_token; ?>"/></td>
          </tr>
          <tr>
            <td><?php echo $entry_text_instamojo_private_salt; ?></td>
            <td><input type="text" size="100" name="instamojo_private_salt" value="<?php echo $instamojo_private_salt; ?>"/></td>
          </tr>
          <tr>
            <td><?php echo $entry_text_instamojo_payment_link; ?></td>
            <td><input type="text" size="100" name="instamojo_payment_link" value="<?php echo $instamojo_payment_link; ?>"/></td>
          </tr>
          <tr>
            <td><?php echo $entry_text_instamojo_custom_field; ?></td>
            <td><input type="text" size="20" name="instamojo_custom_field" value="<?php echo $instamojo_custom_field; ?>"/></td>
          </tr>
           
          <tr>
            <td><?php echo $entry_status; ?></td>
            <td><select name="instamojo_status">
                <?php if ($instamojo_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select></td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?>