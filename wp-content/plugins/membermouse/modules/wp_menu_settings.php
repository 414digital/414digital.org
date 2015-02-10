<?php
/**
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */

if(isset($_POST["mm_hide_menu_items"]))
{
	MM_OptionUtils::setOption(MM_OptionUtils::$OPTION_KEY_HIDE_PROTECTED_MENU_ITEMS, $_POST["mm_hide_menu_items"]);
}

if(isset($_POST["mm_show_login_logout_link"]))
{
	MM_OptionUtils::setOption(MM_OptionUtils::$OPTION_KEY_SHOW_LOGIN_LOGOUT_LINK, $_POST["mm_show_login_logout_link"]);
}

$hideMenuItems = MM_OptionUtils::getOption(MM_OptionUtils::$OPTION_KEY_HIDE_PROTECTED_MENU_ITEMS);
$hideMenuItemsDesc = "When this is checked MemberMouse will automatically show/hide protected pages in the WordPress menu based on the access rights of the logged in member.";
$showLoginLogoutLink = MM_OptionUtils::getOption(MM_OptionUtils::$OPTION_KEY_SHOW_LOGIN_LOGOUT_LINK);
$showLinkDesc = "When this is checked MemberMouse will automatically add a login/logout link to the primary WordPress menu. NOTE: In order for this to work, your theme should support WordPress 3.0 menus and you should create a primary menu for your site in Appearance > Menus.";
?>
<div style="width: 600px; margin-top: 8px;" class="mm-divider"></div> 
<div class="mm-wrap">
    <p class="mm-header-text">WordPress Menu Options</p>
    
	<div style="margin-top:10px;">
		<input id="mm_hide_menu_items_cb" type="checkbox" <?php echo (($hideMenuItems=="1")?"checked":""); ?> onchange="mmjs.updateWPMenuSettingsForm();" />
		Hide Protected Menu Items<?php echo MM_Utils::getInfoIcon($hideMenuItemsDesc); ?>
		<input id="mm_hide_menu_items" name="mm_hide_menu_items" type="hidden" value="<?php echo $hideMenuItems; ?>" />
		<a href="https://membermouse.uservoice.com/knowledgebase/articles/319082-hide-protected-wordpress-pages-from-menus" target="_blank">Learn more</a>
	</div>
	<div style="margin-top:10px;">
		<input id="mm_show_login_logout_link_cb" type="checkbox" <?php echo (($showLoginLogoutLink=="1")?"checked":""); ?> onchange="mmjs.updateWPMenuSettingsForm();" />
		Show Login/Logout Link<?php echo MM_Utils::getInfoIcon($showLinkDesc); ?>
		<input id="mm_show_login_logout_link" name="mm_show_login_logout_link" type="hidden" value="<?php echo $showLoginLogoutLink; ?>" />
		<a href="https://membermouse.uservoice.com/knowledgebase/articles/319081-add-login-logout-link-to-main-menu" target="_blank">Learn more</a>
	</div>
</div>