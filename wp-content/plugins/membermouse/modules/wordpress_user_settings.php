<?php
/**
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */

if(isset($_POST["mm_hide_admin_bar"]))
{
	MM_OptionUtils::setOption(MM_OptionUtils::$OPTION_KEY_HIDE_ADMIN_BAR, $_POST["mm_hide_admin_bar"]);
	MM_OptionUtils::setOption(MM_OptionUtils::$OPTION_KEY_ENABLE_USERNAME_CHANGE, $_POST["mm_enable_username_change"]);
}

$hideAdminBar = MM_OptionUtils::getOption(MM_OptionUtils::$OPTION_KEY_HIDE_ADMIN_BAR);
$hideAdminBarDesc = "When this is checked MemberMouse will configure new mebers so that the WordPress admin bar is not displayed on the front page. When this is unchecked, whether or not the admin bar will be shown for new members will be based on settings in WordPress or another plugin.";

$enableUsernameChange = MM_OptionUtils::getOption(MM_OptionUtils::$OPTION_KEY_ENABLE_USERNAME_CHANGE);
$enableUsernameChangeDesc = "By default, WordPress doesn't allow user's to change their username once their account is created. MemberMouse bypasses this restriction and allows members to change their username from the My Account page. Check this box to allow members to change their username from the My Account page. Uncheck this check box, if you want MemberMouse to hide the username field from the My Account page.";
?>
<div style="width: 600px; margin-top: 8px;" class="mm-divider"></div> 

<script>
function udpateWPUserForm()
{	
	if(jQuery("#mm_hide_admin_bar_cb").is(":checked")) 
	{
		jQuery("#mm_hide_admin_bar").val("1");
	} 
	else 
	{
		jQuery("#mm_hide_admin_bar").val("0");
	}

	if(jQuery("#mm_enable_username_change_cb").is(":checked")) 
	{
		jQuery("#mm_enable_username_change").val("1");
	} 
	else 
	{
		jQuery("#mm_enable_username_change").val("0");
	}
}
</script>

<div class="mm-wrap">
    <p class="mm-header-text">WordPress User Options</p>
    
	<div style="margin-top:10px;">
		<input id="mm_hide_admin_bar_cb" type="checkbox" <?php echo (($hideAdminBar=="1")?"checked":""); ?> onchange="udpateWPUserForm();" />
		Hide the admin bar for new members<?php echo MM_Utils::getInfoIcon($hideAdminBarDesc); ?>
		<input id="mm_hide_admin_bar" name="mm_hide_admin_bar" type="hidden" value="<?php echo $hideAdminBar; ?>" />
		
		<span style="font-size:12px;"><a href="https://membermouse.uservoice.com/knowledgebase/articles/319085-hide-the-wordpress-admin-bar-from-new-members" target="_blank">Learn more</a></span>
	</div>
	
	<div style="margin-top:10px;">
		<input id="mm_enable_username_change_cb" type="checkbox" <?php echo (($enableUsernameChange=="1")?"checked":""); ?> onchange="udpateWPUserForm();" />
		Allow members to change their username<?php echo MM_Utils::getInfoIcon($enableUsernameChangeDesc); ?>
		<input id="mm_enable_username_change" name="mm_enable_username_change" type="hidden" value="<?php echo $enableUsernameChange; ?>" />
		
		<span style="font-size:12px;"><a href="https://membermouse.uservoice.com/knowledgebase/articles/319089-configure-if-member-can-change-their-username" target="_blank">Learn more</a></span>
	</div>
</div>