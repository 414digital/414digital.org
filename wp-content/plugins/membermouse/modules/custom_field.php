<?php
/**
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */

$view = new MM_CustomFieldView();
$dataGrid = new MM_DataGrid($_REQUEST, "id", "desc", 10);
$data = $view->getViewData($dataGrid);
$dataGrid->setTotalRecords($data);
$dataGrid->recordName = "custom field";

$rows = array();

foreach($data as $key=>$item)
{		
	$customField = new MM_CustomField($item->id);
	
    // Actions
	$editActionUrl = 'onclick="mmjs.edit(\'mm-custom-fields-dialog\', \''.$customField->getId().'\', 475, 335)"';
	$deleteActionUrl = 'onclick="mmjs.remove(\''.$customField->getId().'\')"';
	$actions = MM_Utils::getEditIcon("Edit Custom Field", '', $editActionUrl);
	
	if(!MM_CustomField::isBeingUsed($customField->getId()))
	{
		$actions .= MM_Utils::getDeleteIcon("Delete Custom Field", 'margin-left:5px;', $deleteActionUrl);
	}
	else
	{
		$actions .= MM_Utils::getDeleteIcon("This custom field is currently being used and cannot be deleted", 'margin-left:5px;', '', true);
	}
	
	if($item->show_on_my_account)
	{
		$myAcctPage = MM_Utils::getCheckIcon("Show on My Account Page");
	}
	else
	{
		$myAcctPage = MM_Utils::getCrossIcon("Hide on My Account Page");
	}
	
	$smartTags = '<a title="Show Form SmartTag" onclick="mmjs.showCheckoutFormSmartTags('.$customField->getId().',\''.addslashes($customField->getDisplayName()).'\')" class="mm-ui-button" style="margin:0px;">'.MM_Utils::getIcon('tag', '', '1.2em', '1px', '', 'margin-right:0px;').'</a>';
	
    $rows[] = array
    (
    	array('content' => "<span title='ID [".$customField->getId()."]'>".$customField->getDisplayName()."</span>"),	
    	array('content' => MM_CustomField::getFieldTypeName($item->type)),
    	array('content' => $myAcctPage),
    	array('content' => $smartTags),
    	array('content' => $actions),
    );
}

$headers = array
(
		'name'					=> array('content' => 'Name'),
		'type'					=> array('content' => 'Type', "attr" => "style='width:110px;'"),
		'show_on_my_account'	=> array('content' => 'My Account Page', "attr" => "style='width:125px;'"),
		''						=> array('content' => 'Form SmartTag', "attr" => "style='width:145px;'"),
		'actions'				=> array('content' => 'Actions', "attr" => "style='width:60px;'")
);

$dataGrid->setHeaders($headers);
$dataGrid->setRows($rows);
$dataGrid->width = "750px";

$dgHtml = $dataGrid->generateHtml();

if($dgHtml == "") {
	$dgHtml = "<p><i>No custom fields.</i></p>";
}
?>
<div class="mm-wrap">
	<div class="mm-button-container">
		<a onclick="mmjs.create('mm-custom-fields-dialog', 475, 335)" class="mm-ui-button green"><?php echo MM_Utils::getIcon('plus-circle', '', '1.2em', '1px'); ?> Create Custom Field</a>
	</div>
	
	<div class="clear"></div>
	
	<?php echo $dgHtml; ?>
</div>