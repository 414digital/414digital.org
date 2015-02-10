<?php
/**
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */


$view = new MM_LimeLightShippingMethodsView();
$dataGrid = new MM_DataGrid($_REQUEST, "limelight_shipping_method_name", "asc", 10);
$data = $view->getViewData($dataGrid);
$dataGrid->setTotalRecords($data);
$dataGrid->recordName = "shipping method mapping";

$rows = array();

foreach($data as $key => $item)
{
	$split = explode("-", $item->membermouse_shipping_option_key);
	$token = $split[0];
	$mmShippingMethodName = MM_NO_DATA;
	
	//get the shipping method
	$shippingMethod = MM_ShippingMethod::getShippingMethodByToken($token);
	
	if($shippingMethod)
	{
		$optionRetrievalResponse = $shippingMethod->getShippingOptionByKey(new MM_Order(), $item->membermouse_shipping_option_key);
		$shippingOption = $optionRetrievalResponse->message;
		
		if($shippingOption)
		{
			$mmShippingMethodName = "{$shippingOption->getName()} ({$shippingOption->getRate(true)})";
		}
	}
	
    // Actions
   	$editActionUrl = 'onclick="mmjs.edit(\'mm-limelight-shipping-methods-dialog\', \''.$item->id.'\', 550, 235)"';
   	$deleteActionUrl = 'onclick="mmjs.remove(\''.$item->id.'\')"';
   	$actions = MM_Utils::getEditIcon("Edit Shipping Method Mapping", '', $editActionUrl);
   	$actions .= MM_Utils::getDeleteIcon("Delete Shipping Method Mapping", 'margin-left:5px;', $deleteActionUrl);
   
    // Lime Light Shipping Method
    $llShippingMethod = "{$item->limelight_shipping_method_name} [{$item->limelight_shipping_method_id}]";
    $llShippingMethod .= '<a href="javascript:mmjs.getLimeLightShippingDescription(\''.$item->limelight_shipping_method_id.'\');" style="margin-left: 5px; cursor:pointer;" title="View Lime Light Shipping Method Info">'.MM_Utils::getIcon("info-circle", "blue", "1.3em", "2px;").'</a>';
    
    
	$rows[] = array
    (
    	array( 'content' => $llShippingMethod),
    	array( 'content' => $mmShippingMethodName),
    	array( 'content' => $actions),
    );
}

$headers = array
(
	'limelight_shipping_method_name'	=> array('content' => '<a onclick="mmjs.sort(\'limelight_shipping_method_name\');" href="#">Lime Light Shipping Method</a>'),
	'membermouse_shipping_method_id'	=> array('content' => 'MemberMouse Shipping Method'),
	'actions'							=> array('content' => 'Actions', 'attr'=>'style="width:50px;"')
);

$dataGrid->setHeaders($headers);
$dataGrid->setRows($rows);

$dgHtml = $dataGrid->generateHtml();

if($dgHtml == "") 
{
	$dgHtml = "<p><em>No shipping method mappings.</em></p>";
}
?>
<div class="mm-wrap">
	<?php 
		if(MM_MemberMouseService::hasPermission(MM_MemberMouseService::$PYMT_SERVICE_LIMELIGHT)) 
		{
			$shippingMethods = MM_ShippingMethod::getAvailableShippingOptions();
			
			if(count($shippingMethods) > 0)
			{ 
				$unmappedShippingMethods = MM_LimeLightShippingMethod::getUnmappedShippingMethods();
				
				if(count($unmappedShippingMethods) > 0)
				{
	?>
				<div class="mm-button-container">
					<a onclick="mmjs.create('mm-limelight-shipping-methods-dialog', 550, 235)" class="mm-ui-button green"><?php echo MM_Utils::getIcon('plus-circle', '', '1.2em', '1px'); ?> Create Shipping Method Mapping</a>
				</div>
				<?php } ?>
			
				<div class="clear"></div>
				
				<div style="width:65%;">
					<?php echo $dgHtml; ?>
				</div>
	<?php 
			}
			else 
			{
				echo "<p><em>No MemberMouse shipping methods created.</em></p>";
			}
		} 
		else 
		{ 
	?>
		<?php echo MM_Utils::getIcon('lock', 'yellow', '1.3em', '2px'); ?>
		This feature is not available on your current plan. To get access, <a href="<?php echo MM_MemberMouseService::getUpgradeUrl(MM_MemberMouseService::$PYMT_SERVICE_LIMELIGHT); ?>" target="_blank">upgrade your plan now</a>.
	<?php } ?>
</div>