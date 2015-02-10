<?php
/**
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */

// check if Limit Login Attempts plugin is active
$plugins = get_option('active_plugins');
$required_plugin = "limit-login-attempts/limit-login-attempts.php";
$pluginActive = false;

if(in_array($required_plugin, $plugins))
{
	$pluginActive = true;
}
?>
<div style="width: 600px; margin-top: 8px;" class="mm-divider"></div> 
<div class="mm-wrap">
    <p class="mm-header-text">Limit Login Attempts</p>
   
	<div style="margin-top:10px;">
		<p>MemberMouse integrates with a popular plugin for <a href="http://wordpress.org/plugins/limit-login-attempts/" target="_blank">limiting login attempts</a> written by <a href="http://devel.kostdoktorn.se/" target="_blank">Johan Eenfeldt</a>.</p>
		
		<?php if($pluginActive) { ?>
		<p>
			<?php echo MM_Utils::getCheckIcon(); ?> <strong>Plugin activated</strong>
		</p>
		<?php } else { ?>
		<p>
			<a href="<?php echo esc_url( network_admin_url('plugin-install.php?tab=plugin-information&plugin=limit-login-attempts&TB_iframe=true&width=650&height=600' ) ); ?>" class="mm-ui-button green thickbox">Install Plugin</a>
			<a href="<?php echo esc_url( network_admin_url('plugin-install.php?tab=plugin-information&plugin=limit-login-attempts&TB_iframe=true&width=650&height=600' ) ); ?>" class="mm-ui-button thickbox">Learn more</a> 
		</p>
		<?php } ?>
	</div>
</div>