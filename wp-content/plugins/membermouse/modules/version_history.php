<?php
/**
 *
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */

if(isset($_POST["clear-upgrade-notice"]) && $_POST["clear-upgrade-notice"] == "1")
{
	MM_OptionUtils::setOption(MM_OptionUtils::$OPTION_KEY_UPGRADE_NOTICE, "");
}

$versions = array();

$view = new MM_ManageInstallView();
$dataGrid = new MM_DataGrid($_REQUEST, "id", "desc", 10);
$dataGrid->showPagingControls = false;
$data = $view->getViewData($dataGrid);
$dataGrid->setTotalRecords($data);
$dataGrid->width = "500px";
$dataGrid->recordName = "version";

$rows = array();

$headers = array
(	    
   	'version'		=> array('content' => '<a onclick="mmjs.sort(\'version\');" href="#">Version</a>'),
   	'date_added'	=> array('content' => '<a onclick="mmjs.sort(\'date_added\');" href="#">Date</a>')
);

foreach($data as $key=>$item)
{	
    $rows[] = array
    (
    	array('content' => $item->version),
    	array('content' => MM_Utils::dateToLocal($item->date_added))
    );
}

$dataGrid->setHeaders($headers);
$dataGrid->setRows($rows);

$dgHtml = $dataGrid->generateHtml();

if($dgHtml == "") {
	$dgHtml = "<p><i>No version history.</i></p>";
}
?>
<div class="mm-wrap">
    <p class="mm-header-text" style="margin-bottom:15px;">Version History</p>

    <?php if(MM_OptionUtils::getOption(MM_OptionUtils::$OPTION_KEY_UPGRADE_NOTICE)) { ?>
    <form method="post" style="margin-top:10px;">
    	<input type="hidden" name="clear-upgrade-notice" value="1" />
   		<input type="submit" value="Clear Upgrade Notice" class="mm-ui-button blue" />
    </form>
    <?php } else if(isset($_POST["clear-upgrade-notice"]) && $_POST["clear-upgrade-notice"] == "1") { ?>
    <p style="margin-top:10px; font-size:14px;"><em>Upgrade notice cleared successfully. Changes will be reflected on next page load.</em></p>
    <?php } ?>
	
	<div class="clear"></div>
	<div id='mm-release-notes-dialog'></div>
	<?php echo $dgHtml; ?>
	
	<div style="font-size:11px; color:#bbb; width:500px; text-align:right; margin-top:5px;">
	<?php echo MM_ReleaseVerificationUtils::getVersionInfo(); ?>
	</div>
</div>