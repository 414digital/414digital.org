<?php
/**
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */

if(isset($_POST["mm_allow_logged_out_purchases"]))
{
	MM_OptionUtils::setOption(MM_OptionUtils::$OPTION_KEY_ALLOW_LOGGED_OUT_PURCHASES, $_POST["mm_allow_logged_out_purchases"]);
}

$allowLoggedOutPurchases = MM_OptionUtils::getOption(MM_OptionUtils::$OPTION_KEY_ALLOW_LOGGED_OUT_PURCHASES);
?>
<script>
function allowPurchasesChangeHandler()
{
	if(jQuery("#mm_allow_logged_out_purchases_cb").is(":checked")) 
	{
		jQuery("#mm_allow_logged_out_purchases").val("1");
	} 
	else 
	{
		jQuery("#mm_allow_logged_out_purchases").val("0");
	}
}
</script>
<div class="mm-wrap">
    <p class="mm-header-text">Allow Logged Out Purchases <span style="font-size:12px;"><a href="https://membermouse.uservoice.com/knowledgebase/articles/319029-allow-logged-out-member-s-to-make-purchases" target="_blank">Learn more</a></span></p>
    <div style="clear:both; height: 10px;"></div>
    <div style="margin-bottom:10px; width:550px;">Checking the checkbox below indicates that when a logged out user attempts 
    to make a purchase using an email address associated with an existing account that the purchase should be associated with the existing
    account. If the option below is unchecked, an error will be displayed to the user indicating that an account already exists with that
    email address and they'll need to log in to make a purchase associated with that email address.</div>
	
	<div>
		<label>
			<input id="mm_allow_logged_out_purchases_cb" value='1' type="checkbox" <?php echo ($allowLoggedOutPurchases == "1") ? "checked":""; ?> onchange="allowPurchasesChangeHandler();" />
			<input id="mm_allow_logged_out_purchases" name="mm_allow_logged_out_purchases" value='<?php echo $allowLoggedOutPurchases; ?>' type="hidden" />
			Allow existing members to make a purchase when they're logged out
		</label>
	</div>
</div>