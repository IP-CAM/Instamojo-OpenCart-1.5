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
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
        <form id="form" action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-free-checkout" class="form-horizontal">
		<table class="form">	
		  <tr class="form-group">
            <td><label class="col-sm-2 control-label" for="input-order-status"><?php echo $entry_order_status; ?></label>
            <td class="col-sm-10">
              <select name="instamojo_order_status_id" id="input-order-status" class="form-control">
                <?php foreach ($order_statuses as $order_status) { ?>
                <?php if ($order_status['order_status_id'] == $instamojo_order_status_id or $order_status['name'] =="Processing") { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </td>
          </tr>

          <tr class="form-group">
            <td><label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
            <td class="col-sm-10">
              <input type="text" name="instamojo_sort_order" value="<?php echo $instamojo_sort_order; ?>" id="input-sort-order" class="form-control" />
            </td>
          </tr>

          <tr class="form-group">
           <td> <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_text_instamojo_checkout_label; ?></label>
            <td class="col-sm-10">
              <input type="text" name="instamojo_checkout_label" value="<?php echo $instamojo_checkout_label; ?>" id="input-sort-order" class="form-control" />
            </td>
          </tr>

          <tr class="form-group">
           <td> <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_text_instamojo_client_id; ?></label>
            <td class="col-sm-10">
              <input type="text" name="instamojo_client_id" value="<?php echo $instamojo_client_id; ?>" id="input-sort-order" class="form-control" />
            </td>
          </tr>

          <tr class="form-group">
           <td> <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_text_instamojo_client_secret; ?></label>
            <td class="col-sm-10">
              <input type="text" name="instamojo_client_secret" value="<?php echo $instamojo_client_secret; ?>" id="input-sort-order" class="form-control" />
            </td>
          </tr>
		  
		 <tr class="form-group">
           <td> <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_test_mode; ?></label>
            <td class="col-sm-10">
              <select name="instamojo_testmode" id="input-status" class="form-control">
                <?php if ($instamojo_testmode) { ?>
                <option value="1" selected="selected"><?php echo $entry_test_mode_on; ?></option>
                <option value="0"><?php echo $entry_test_mode_off; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $entry_test_mode_on; ?></option>
                <option value="0" selected="selected"><?php echo $entry_test_mode_off; ?></option>
                <?php } ?>
              </select>
            </td>
          </tr>
		  
		  
		  
          <tr class="form-group">
           <td> <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <td class="col-sm-10">
              <select name="instamojo_status" id="input-status" class="form-control">
                <?php if ($instamojo_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </td>
          </tr>
		</table>
        </form>
      </div>
    </div>
  </div>

<?php echo $footer; ?> 

