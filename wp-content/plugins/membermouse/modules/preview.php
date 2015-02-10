<?php
/**
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */

$cssStyles = is_admin_bar_showing() ? "margin-top:28px; " : "margin-top:0px; ";

if(MM_OptionUtils::getOption(MM_OptionUtils::$OPTION_KEY_SHOW_PREVIEW_BAR) == "0")
{
	$cssStyles .= "display: none;";
}

$membershipsList = $p->memberTypes;
$bundleCount = $p->count_tags;
$appliedBundleCount = $p->count_applied;
$daysAsMemberList = $p->days;
$bundlesList = $p->accessTags;
$day = "";
$previewSettingsInfo = "When you're logged in as an administrator you can use the options to the right to preview your site with different access rights and at different times during the membership.";
$previewSettingsInfo .= "The options available to you will dynamically change based on what access rights you've used to protect content and the drip content schedules you've defined.";
?>

<div id="mm-preview-settings-bar" style="<?php echo $cssStyles; ?>">
	<div style="float:left;">
		<span style="margin-right:10px;">
			MM Preview Settings <?php echo MM_Utils::getIcon('info-circle', 'grey', '1.3em', '2px', $previewSettingsInfo); ?>
		</span>
		<?php echo MM_Utils::getIcon('user', 'grey', '1.3em', '2px', "Select your membership level", 'margin-right:4px;'); ?>
		<select name="mm-preview-member_type" id='mm-preview-member_type' onchange="mmPreviewJs.changeMembershipLevel()">
			<?php echo $membershipsList; ?>
		</select> 
	</div>
	
	<div id="mm-member-options" style="float:left; margin-left:5px;">
		<div id='mm-show-at' style="float:left; margin-left:15px;">
		<?php if($bundleCount>0){ ?>
			<div style='float:left; vertical-align:middle;'><a id='mm-showhide-preview-link' onclick="mmPreviewJs.showAccessTags()" style='cursor: pointer;' title='Select the bundles you have applied'><?php echo MM_Utils::getIcon('cube', 'grey', '1.3em', '2px'); ?></a></div>
			<div id='mm-applied-tag-count' style='float:left; padding-left: 5px; padding-right: 5px; vertical-align:middle;'><?php echo $appliedBundleCount; ?></div> <div style='float:left;  vertical-align:middle;'>bundles applied</div>
		<?php } ?>
		<span style="margin-left: 15px;">
			<?php echo MM_Utils::getIcon('calendar', 'grey', '1.3em', '1px', "Select the number of days you've been a member"); ?>
			<select name="mm-preview-days" id='mm-preview-days' onchange="mmPreviewJs.enableChangeButton();" >
				<?php echo $daysAsMemberList; ?>
			</select>
		</span>
		</div>
		<a onclick="mmPreviewJs.savePreview()" class="mm-button small black" style="margin-left: 15px; box-shadow: 0 0px 1px #EAEAEA, 0 1px 0 #868686 inset">Save</a> 
		
		<?php 
			global $wp_query;
			
			if(isset($wp_query->post->ID) && MM_CorePageEngine::isErrorCorePage($wp_query->post->ID)) 
			{
		?>
			<span style="margin-left:20px;">
				<?php echo MM_Utils::getIcon('warning', 'yellow', '1.3em', '2px'); ?>
				Seeing the error page unexpectedly? <a href="https://membermouse.uservoice.com/knowledgebase/articles/319144-when-logged-in-as-an-administrator-i-m-seeing-an-e" target="_blank" style="color:rgb(62, 172, 207);">Make sure your settings are commited.</a>
			</span>
		<?php } ?>
		
		<?php if($bundleCount>0) { ?>
			<div id='mm-preview-access-tags' style='height: 70px;'>
				<div id='mm-preview-access-tag-results'	>
				<select multiple="multiple" rows='6' style='width: 98%;' id='preview_access_tags'  name='preview_access_tags[]'  onchange="mmPreviewJs.changeBundles();">
					<?php echo $bundlesList; ?>
				</select>
				</div>
			</div>
		<?php } ?>
	</div>	
	
	<div style="float:right; margin-right:20px; ">
		<a href="<?php echo MM_ModuleUtils::getUrl(MM_MODULE_GENERAL_SETTINGS, MM_MODULE_OTHER_SETTINGS); ?>#preview-settings-bar-options" target="_blank" class="mm-button small black" style="margin-left: 15px; box-shadow: 0 0px 1px #EAEAEA, 0 1px 0 #868686 inset">Hide this bar</a>
	</div>
</div>