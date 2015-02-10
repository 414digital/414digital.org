<form method='post' >
<?php
require_once("homepage_redirect.php");
require_once("wp_menu_settings.php");
require_once("wordpress_user_settings.php");
require_once("account_sharing_prevention.php");
require_once("limit_login_attempts.php");
require_once("content_protection_settings.php");
require_once("login_token_settings.php");
require_once("payment_confirmation_settings.php");
require_once("preview_bar_settings.php");
require_once("smarttag-version.php");
require_once("jquery_ui_settings.php");
require_once("core_page_css_settings.php");
require_once("activity_log_settings.php");
?>
<input type='submit' value='Save Settings' class="mm-ui-button blue" />
</form>


<script type='text/javascript'>
<?php if(!empty($error)){ ?>
alert('<?php echo $error; ?>');
<?php  } else if(isset($_POST["mm_acct_sharing_max_ips"])) { ?>
alert("Settings saved successfully");
<?php } ?>
</script>