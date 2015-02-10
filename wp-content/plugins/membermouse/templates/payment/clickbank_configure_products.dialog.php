<?php 
$productsExist = false;
if (isset($p->clickbank_products) && is_array($p->clickbank_products) && (count($p->clickbank_products) > 0)) { 
	$productsExist = true;
	$membermouseProducts = array();
	$membermouseProducts['none'] = "(none)";
	if (isset($p->membermouse_products) && is_array($p->membermouse_products))
	{
		foreach ($p->membermouse_products as $productId=>$productObj)
		{
			$productPrice = number_format($productObj->price,2,'.','');
			$membermouseProducts[$productId] = "[ID: {$productObj->id}] {$productObj->name} (\${$productPrice})";
		}
	}
}

$skuName = "@sku";
?>
<div id="mm-clickbank-configure-products-container">
    <?php if ($productsExist) { ?>
	<table id="mm-clickbank-configure-products-table" class="widefat">
		<thead>
		<tr>
			<th>ClickBank Product</th>
			<th>&nbsp;</th>
			<th>MemberMouse Product</th>
		</tr>
		</thead>
		<?php foreach ($p->clickbank_products as $cbp) { ?>
		<tr>
			<td width="200">
				ID:  <?php echo $cbp->$skuName; ?></br>
				Title: <?php echo $cbp->title; ?>
			</td>
			<td>&nbsp;</td>
			<td>
				<select name='clickbank_product_mapping[<?php echo $cbp->$skuName; ?>]'>
					<?php echo MM_HtmlUtils::generateSelectionsList($membermouseProducts,$cbp->mapped?$cbp->membermouse_product_id:null); ?>
				</select>
			</td>
		</tr>
		<?php } ?>
	</table>
	<?php } else { ?>
		<!-- There were no clickbank products retrieved -->
		There are no products configured in ClickBank.
	<?php } ?>
</div>
	
<div class="mm-dialog-footer-container">
	<div class="mm-dialog-button-container">
		<a onClick="saveClickbankProductMappings()" class="mm-ui-button blue">Save Product Mappings</a>
		<a onClick="jQuery('#clickbank-configure-products-dialog').dialog('close');" class="mm-ui-button">Cancel</a>
	</div>
</div>