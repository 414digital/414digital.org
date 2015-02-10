<?php
/**
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */

$view = new MM_ProductView();
$dataGrid = new MM_DataGrid($_REQUEST, "id", "desc", 10);
$data = $view->getViewData($dataGrid);
$dataGrid->setTotalRecords($data);
$dataGrid->recordName = "product";

$rows = array();

foreach($data as $key=>$item)
{	
	$product = new MM_Product($item->id);
	
	// Actions
	$editActionUrl = 'onclick="mmjs.edit(\'mm-products-dialog\', \''.$product->getId().'\', 580, 600)"';
   	$deleteActionUrl = 'onclick="mmjs.remove(\''.$product->getId().'\')"';
   	$actions = MM_Utils::getEditIcon("Edit Product", '', $editActionUrl);
	
	if(!MM_Product::isBeingUsed($product->getId()) && !MM_Product::hasBeenPurchased($product->getId()))
	{
  		$actions .= MM_Utils::getDeleteIcon("Delete Product", 'margin-left:5px;', $deleteActionUrl);
	}
	else
	{
  		$actions .= MM_Utils::getDeleteIcon("This product is currently being used and cannot be deleted", 'margin-left:5px;', '', true);
	}
	
	$purchaseLinks = '<a title="Get purchase links" onclick="mmjs.showPurchaseLinks('.$product->getId().',\''.addslashes($product->getName()).'\')" class="mm-ui-button" style="margin:0px;">'.MM_Utils::getIcon('money', '', '1.3em', '1px', '', 'margin-right:0px;').'</a>';
	
	
	// Associated Access
	$accessGranted = "";
	
	$membership = $product->getAssociatedMembership();
	
	if($membership->isValid())
	{
		$accessGranted .= MM_Utils::getAccessIcon(MM_OrderItemAccess::$ACCESS_TYPE_MEMBERSHIP);
		$accessGranted .= " <a href='".MM_ModuleUtils::getUrl(MM_MODULE_PRODUCT_SETTINGS, MM_MODULE_MEMBERSHIP_LEVELS)."&autoload=".$membership->getId()."'>".$membership->getName()."</a>";
	}
	
	if(empty($accessGranted))
	{
		$bundle = $product->getAssociatedBundle();
	
		if($bundle->isValid())
		{
			$accessGranted .= MM_Utils::getAccessIcon(MM_OrderItemAccess::$ACCESS_TYPE_BUNDLE);
			$accessGranted .= " <a href='".MM_ModuleUtils::getUrl(MM_MODULE_PRODUCT_SETTINGS, MM_MODULE_BUNDLES)."&autoload=".$bundle->getId()."'>".$bundle->getName()."</a>";
		}
	}
	
	if(empty($accessGranted))
	{
		$accessGranted = MM_NO_DATA;
	}
	
	
	// Attributes
	$attributes = "";
	
	if($product->hasTrial())
	{
		$attributes .= MM_Utils::getIcon('clock-o', 'beige', '1.3em', '0px', 'Has Trial', 'margin-right:5px;');
	}
	else
	{
		$attributes .= "<img title='No Trial' style='margin-right:5px;' src='".MM_Utils::getImageUrl("clear")."' />";
	}
	
	if($product->isRecurring())
	{
		if($product->doLimitPayments())
		{
			$attributes .= MM_Utils::getIcon('calendar-o', 'beige', '1.3em', '0px', 'Payment Plan', 'margin-right:5px;');
		}
		else 
		{
			$attributes .= MM_Utils::getIcon('refresh', 'beige', '1.3em', '0px', 'Subscription', 'margin-right:5px;');
		}
	}
	else
	{
		$attributes .= "<img title='No Recurring' style='margin-right:5px;' src='".MM_Utils::getImageUrl("clear")."' />";
	}
	
	if($product->isShippable())
	{
		$attributes .= MM_Utils::getIcon('truck', 'beige', '1.3em', '0px', 'Requires Shipping', 'margin-right:5px;');
	}
	else
	{
		$attributes .= "<img title='No Shipping Required' style='margin-right:5px;' src='".MM_Utils::getImageUrl("clear")."' />";
	}
	
	if($product->getSku() != "")
	{
		$attributes .= MM_Utils::getIcon('barcode', 'beige', '1.3em', '0px', "SKU [".$product->getSku()."]", 'margin-right:5px;');
	}
	else
	{
		$attributes .= "<img title='No SKU' style='margin-right:5px;' src='".MM_Utils::getImageUrl("clear")."' />";
	}
	
	
    $rows[] = array
    (
    	array('content' => "<span title='ID [".$product->getId()."]'>".$product->getName()."</span>"),
    	array('content' => $product->getBillingDescription()),
    	array('content' => $attributes),
    	array('content' => $accessGranted),
    	array('content' => $purchaseLinks),
    	array('content' => MM_Utils::getStatusImage($product->getStatus())),
    	array('content' => $actions)
    );
}

$headers = array
(
		'name'			=> array('content' => '<a onclick="mmjs.sort(\'name\');" href="#">Name</a>'),
		'billing'		=> array('content' => 'Billing Description'),
		'attributes'	=> array('content' => 'Attributes'),
		'access'		=> array('content' => 'Associated Access'),
		'links'			=> array('content' => 'Purchase Links'),
		'status'		=> array('content' => '<a onclick="mmjs.sort(\'status\');" href="#">Status</a>'),
		'actions'		=> array('content' => 'Actions')
);

$dataGrid->setHeaders($headers);
$dataGrid->setRows($rows);

$dgHtml = $dataGrid->generateHtml();

if($dgHtml == "") 
{
	$dgHtml = "<p><i>No products found.</i></p>";
}
?>
<div class="mm-wrap">
	<div class="mm-button-container">
		<a onclick="mmjs.create('mm-products-dialog', 580, 600)" class="mm-ui-button green"><?php echo MM_Utils::getIcon('plus-circle', '', '1.2em', '1px'); ?> Create Product</a>
	</div>
	
	<div class="clear"></div>
	
	<div style="width:98%">
	<?php echo $dgHtml; ?>
	</div>
</div>

<?php if(isset($_REQUEST["autoload"])) { ?>
<script type='text/javascript'>
jQuery(document).ready(function() {
	<?php
	if($_REQUEST["autoload"] == "new")
	{
		 echo 'mmjs.create(\'mm-products-dialog\', 580, 600);';
	}
	else
	{
		echo 'mmjs.edit(\'mm-products-dialog\', \''.$_REQUEST["autoload"].'\', 580, 600);';
	}
	?>
});
</script>
<?php } ?>