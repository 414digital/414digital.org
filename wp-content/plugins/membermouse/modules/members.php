<?php
/**
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */

$view = new MM_MembersView();

$showSearch = false;

//only show 'export csv' option if current user is an administrator
global $current_user;

$showCsvExportButton = false;
if (isset($current_user) && isset($current_user->ID))
{
	$employee = MM_Employee::findByUserId($current_user->ID);
	if($employee->isValid() && $employee->getRoleId() == MM_Role::$ROLE_ADMINISTRATOR)
	{
		$showCsvExportButton = true;
	}
	
	echo "<input type='hidden' id='mm-admin-id' value='{$current_user->ID}' />";
	
	// determine if this user's preference is to have the advanced search open
	$showSearchOptionName = MM_OptionUtils::$OPTION_KEY_SHOW_MBRS_SEARCH."-".$current_user->ID;
	$showSearchOptionValue = MM_OptionUtils::getOption($showSearchOptionName);
	
	if($showSearchOptionValue == "1")
	{
		$showSearch = true;
	}
}
?>
<div class="mm-wrap">
	<?php if(count(MM_MembershipLevel::getMembershipLevelsList()) > 0) { ?>
		<div style="margin-top:20px;" class="mm-button-container">			
			<a id="mm-show-search-btn" onclick="mmjs.showSearch()" class="mm-ui-button blue" <?php echo ($showSearch) ? "style=\"display:none;\"" : ""; ?>><?php echo MM_Utils::getIcon('search-plus'); ?> Advanced Search</a>
			<a id="mm-hide-search-btn" onclick="mmjs.hideSearch()" class="mm-ui-button" <?php echo ($showSearch) ? "" : "style=\"display:none;\""; ?>><?php echo MM_Utils::getIcon('search-minus'); ?> Advanced Search</a>
			
			<a onclick="mmjs.create('mm-create-member-dialog', 500, 360)" class="mm-ui-button green" style="margin-left:15px;"><?php echo MM_Utils::getIcon('user'); ?> Create Member</a>
			
			<a href="<?php echo MM_ModuleUtils::getUrl(MM_MODULE_MANAGE_MEMBERS, MM_MODULE_IMPORT_WIZARD); ?>" class="mm-ui-button" style="margin-left:15px;"><?php echo MM_Utils::getIcon('upload'); ?> Import Members</a>
		
			<?php 
				if($showCsvExportButton) { 
			?>
			<a class="mm-ui-button" onclick="mmjs.csvExport(0);" style="margin-left:15px;"><?php echo MM_Utils::getIcon('download'); ?> Export Members</a>
			<?php } ?>
		</div>
	<?php } ?>
	
	<div style="width: 98%; margin-top: 10px; margin-bottom: 0px;" class="mm-divider"></div> 
	
	<div id="mm-advanced-search" <?php echo ($showSearch) ? "" : "style=\"display:none;\""; ?>>
		<div id="mm-advanced-search-container" style="width:98%">
		<?php echo $view->generateSearchForm($_POST); ?>
		</div>
		<div style="width: 98%; margin-top: 0px; margin-bottom: 10px;" class="mm-divider"></div> 
	</div>
	
	<div id='mm_members_csv'></div>
	<div id="mm-grid-container" style="width:98%">
		<?php echo $view->generateDataGrid($_POST); ?>
	</div>
</div>