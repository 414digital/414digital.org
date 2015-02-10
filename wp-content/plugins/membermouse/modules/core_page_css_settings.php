<?php
/**
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */

if(isset($_POST["mm_use_mm_css_checkout"]))
{
	MM_OptionUtils::setOption(MM_OptionUtils::$OPTION_KEY_USE_MM_CSS_CHECKOUT, $_POST["mm_use_mm_css_checkout"]);
	MM_OptionUtils::setOption(MM_OptionUtils::$OPTION_KEY_USE_MM_CSS_MY_ACCOUNT, $_POST["mm_use_mm_css_my_account"]);
	MM_OptionUtils::setOption(MM_OptionUtils::$OPTION_KEY_USE_MM_CSS_LOGIN, $_POST["mm_use_mm_css_login"]);
	MM_OptionUtils::setOption(MM_OptionUtils::$OPTION_KEY_USE_MM_CSS_FORGOT_PASSWORD, $_POST["mm_use_mm_css_forgot_pass"]);
}

$useMMCSSCheckout = MM_OptionUtils::getOption(MM_OptionUtils::$OPTION_KEY_USE_MM_CSS_CHECKOUT);
$useMMCSSMyAccount = MM_OptionUtils::getOption(MM_OptionUtils::$OPTION_KEY_USE_MM_CSS_MY_ACCOUNT);
$useMMCSSLogin = MM_OptionUtils::getOption(MM_OptionUtils::$OPTION_KEY_USE_MM_CSS_LOGIN);
$useMMCSSForgotPassword = MM_OptionUtils::getOption(MM_OptionUtils::$OPTION_KEY_USE_MM_CSS_FORGOT_PASSWORD);
?>
<div style="width: 600px; margin-top: 8px;" class="mm-divider"></div> 

<script>
function updateCorePageCSSForm()
{	
	if(jQuery("#mm_use_mm_css_checkout_cb").is(":checked")) 
	{
		jQuery("#mm_use_mm_css_checkout").val("1");
	} 
	else 
	{
		jQuery("#mm_use_mm_css_checkout").val("0");
	}

	if(jQuery("#mm_use_mm_css_my_account_cb").is(":checked")) 
	{
		jQuery("#mm_use_mm_css_my_account").val("1");
	} 
	else 
	{
		jQuery("#mm_use_mm_css_my_account").val("0");
	}

	if(jQuery("#mm_use_mm_css_login_cb").is(":checked")) 
	{
		jQuery("#mm_use_mm_css_login").val("1");
	} 
	else 
	{
		jQuery("#mm_use_mm_css_login").val("0");
	}

	if(jQuery("#mm_use_mm_css_forgot_pass_cb").is(":checked")) 
	{
		jQuery("#mm_use_mm_css_forgot_pass").val("1");
	} 
	else 
	{
		jQuery("#mm_use_mm_css_forgot_pass").val("0");
	}
}
</script>

<div class="mm-wrap">
    <p class="mm-header-text">Core Page CSS Settings <span style="font-size:12px;"><a href="http://support.membermouse.com/knowledgebase/articles/389569" target="_blank">Learn more</a></span></p>

   	<div style="margin-top:10px;">
		<strong>Use MemberMouse CSS on the following core pages:</strong>
	</div>
	
	<div style="margin-top:10px; margin-left:20px;">
		<input id="mm_use_mm_css_checkout_cb" type="checkbox" <?php echo (($useMMCSSCheckout=="1")?"checked":""); ?> onchange="updateCorePageCSSForm();" />
		Checkout &amp; Redeem Gift
		<input id="mm_use_mm_css_checkout" name="mm_use_mm_css_checkout" type="hidden" value="<?php echo $useMMCSSCheckout; ?>" />
	</div>
	
	<div style="margin-top:10px; margin-left:20px;">
		<input id="mm_use_mm_css_my_account_cb" type="checkbox" <?php echo (($useMMCSSMyAccount=="1")?"checked":""); ?> onchange="updateCorePageCSSForm();" />
		My Account
		<input id="mm_use_mm_css_my_account" name="mm_use_mm_css_my_account" type="hidden" value="<?php echo $useMMCSSMyAccount; ?>" />
	</div>
	
	<div style="margin-top:10px; margin-left:20px;">
		<input id="mm_use_mm_css_login_cb" type="checkbox" <?php echo (($useMMCSSLogin=="1")?"checked":""); ?> onchange="updateCorePageCSSForm();" />
		Login
		<input id="mm_use_mm_css_login" name="mm_use_mm_css_login" type="hidden" value="<?php echo $useMMCSSLogin; ?>" />
	</div>
	
	<div style="margin-top:10px; margin-left:20px;">
		<input id="mm_use_mm_css_forgot_pass_cb" type="checkbox" <?php echo (($useMMCSSForgotPassword=="1")?"checked":""); ?> onchange="updateCorePageCSSForm();" />
		Forgot Password
		<input id="mm_use_mm_css_forgot_pass" name="mm_use_mm_css_forgot_pass" type="hidden" value="<?php echo $useMMCSSForgotPassword; ?>" />
	</div>
</div>