<?php
/**
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */
global $current_user;

$view = new MM_DiagnosticsView();
$currentMode = MM_DiagnosticLog::getMode();
$modeList = MM_DiagnosticLog::getModeLabels();
$modeListHtml = MM_HtmlUtils::generateSelectionsList($modeList,$currentMode);

// determine if this user's preference is to have the advanced search open
$showFilterOptionName = MM_OptionUtils::$OPTION_KEY_SHOW_DIAGNOSTICS_LOG_FILTERS."-".$current_user->ID;
$showFilterOptionValue = MM_OptionUtils::getOption($showFilterOptionName);

$showFilters = ($showFilterOptionValue == "1");
?>
<div class="mm-wrap">
	<div>
		<table>
			<tr>
				<td>Current Mode:</td>
				<td><?php echo $modeList[$currentMode]; ?></td>
			</tr>
			<tr>
				<td>Change mode to: </td>
				<td><select id="newMode" name="newMode">
						<?php echo $modeListHtml; ?>
					</select>
				</td>
			</tr>
			<tr>
				<td colspan="2"><a onclick="mmjs.setDiagnosticsMode('<?php echo $currentMode;?>',jQuery('#newMode').val());" class="mm-ui-button green"> Update</a></td>
			</tr>
		</table>
	</div>
	
	<div class="mm-button-container">
		<a id="mm-show-filters-btn" href="javascript:mmjs.showFilters();" class="mm-ui-button blue" <?php echo ($showFilters) ? "style=\"display:none;\"" : ""; ?>><?php echo MM_Utils::getIcon('search-plus'); ?>Filter</a>
		<a id="mm-hide-filters-btn" href="javascript:mmjs.hideFilters();" class="mm-ui-button" <?php echo (!$showFilters) ? "style=\"display:none;\"" : ""; ?>><?php echo MM_Utils::getIcon('search-minus'); ?>Filter</a>
		<a onclick="mmjs.clearLog()" class="mm-ui-button" style="margin-left:15px;">Delete Diagnostic Data</a>
	</div>
	<div id="mm-filter-criteria" <?php echo ($showFilters) ? "" : "style=\"display:none;\""; ?>>
		<div id="mm-filter-criteria-container" style="width:98%">
			<?php echo $view->generateFilterCriteriaForm($_POST); ?>
		</div>
		<div style="width: 98%; margin-top: 0px; margin-bottom: 10px;" class="mm-divider"></div> 
	</div>
	<div class="clear"></div>
	<input type='hidden' id='mm-admin-id' value='<?php echo $current_user->ID;?>'>
	<div id="mm-grid-container" style="width:98%">
		<?php echo $view->generateDataGrid($_POST); ?>
	</div>
</div>