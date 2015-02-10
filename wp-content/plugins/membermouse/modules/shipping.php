<?php
/**
 *
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */

$view = new MM_ShippingMethodsView();
$dataGrid = new MM_DataGrid($_REQUEST, "id", "asc", 10);
$data = $view->getViewData($dataGrid);
$dataGrid->setTotalRecords($data);
$dataGrid->width = "500px;";
$dataGrid->recordName = "shipping method";

$rows = array();

$headers = array
(	    
   	'name'				=> array('content' => 'Name'),
   	'rate'				=> array('content' => 'Rate'),
   	'actions'			=> array('content' => 'Actions')
);

foreach($data as $key=>$item)
{	
	
    // Actions
	$editActionUrl = 'onclick="mmjs.edit(\'mm-shipping-dialog\', {id:'.$item->id.',mm_setting_type:\'shipping\'}, 300,195)"';
	$deleteActionUrl = 'onclick="mmjs.remove(\''.$item->id.'\')"';
	$actions = MM_Utils::getEditIcon("Edit Shipping Method", '', $editActionUrl);
	$actions .= MM_Utils::getDeleteIcon("Delete Shipping Method", 'margin-left:5px;', $deleteActionUrl);
	
	$rate = (floatval($item->rate)>=0)?_mmf($item->rate,$item->currency):MM_NO_DATA;
	
	$rows[] = array
    (
    	array('content' => MM_Utils::getIcon('key', 'yellow', '1.2em', '1px', MM_ShippingMethod::$FLATRATE_SHIPPING."-{$item->id}", "margin-right:2px;")." <span title='ID [".$item->id."]'>".$item->option_name."</span>"),
    	array('content' => $rate),
    	array('content' => $actions),
    );
}

$dataGrid->setHeaders($headers);
$dataGrid->setRows($rows);

$dgHtml = $dataGrid->generateHtml();

if($dgHtml == "") {
	$dgHtml = "<p><i>No shipping methods.</i></p>";
}
?>
<div class="mm-wrap">
	<div class="mm-button-container">
		<a onclick="mmjs.create('mm-shipping-dialog', 300,195)" class="mm-ui-button green"><?php echo MM_Utils::getIcon('plus-circle', '', '1.2em', '1px'); ?> Create Shipping Method</a>
	</div>
	
	<div class="clear"></div>
	
	<?php echo $dgHtml; ?>
</div>