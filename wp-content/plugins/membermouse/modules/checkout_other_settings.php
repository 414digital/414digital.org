<form method='post'>
<?php
require_once("default_currency.php");
require_once("default_checkout_item.php");
require_once("proration_settings.php");
require_once("allow_logged_out_purchases.php");
require_once("duplicate_subscription_settings.php");
require_once("purchase_link_options.php");
require_once("captcha.php");
require_once("checkout_processing_message.php");
?>
<input type='submit' value='Save Settings' class="mm-ui-button blue" />
</form>

<script type='text/javascript'>
<?php if(!empty($error)){ ?>
alert('<?php echo $error; ?>');
<?php  } else if(isset($_POST["mm_checkout_item_type"])) { ?>
alert("Settings saved successfully");
<?php } ?>
</script>