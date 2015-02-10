<?php 
/**
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */
	$limeLightProduct = new MM_LimeLightProduct($p->id);
	$limeLightService = MM_PaymentServiceFactory::getPaymentService(MM_PaymentService::$LIMELIGHT_SERVICE_TOKEN);
?>
<div id="mm-form-container">
	<table cellspacing="10">
		<tr>
			<td width="150">MemberMouse Product</td>
			<td>
				<select name='mm_product_id' id='mm_product_id' onchange="mmjs.getMMProductDescription();">
				<?php 
					$unmappedProducts = MM_LimeLightProduct::getUnmappedProducts();
					
					// add currently selected product
					if($limeLightProduct->isValid())
					{
						$product = new MM_Product($limeLightProduct->getMMProductId());
						
						if($product->isValid())
						{
							$unmappedProducts[$limeLightProduct->getMMProductId()] = $product->getName();
						}
					}
					
					echo MM_HtmlUtils::generateSelectionsList($unmappedProducts, $limeLightProduct->getMMProductId()); 
				?>
				</select>
				
				<div id="mm_product_description" style="margin-top:10px;"></div>
			</td>
		</tr>
		
		<tr>
			<td colspan="2">
			<div style="width: 98%; margin-top: 8px;" class="mm-divider"></div>
			</td>
		</tr>
		
		<tr>
			<td width="150">Lime Light Campaign</td>
			<td>
				<select name='limelight_campaign_id' id='limelight_campaign_id' onchange='mmjs.getLimeLightProducts();'>
				<?php echo MM_HtmlUtils::generateSelectionsList($limeLightService->getCampaigns(), $limeLightProduct->getLimeLightCampaignId()); ?>
				</select>
			</td>
		</tr>
		
		<tr>
			<td width="150">Lime Light Product</td>
			<td>
				<div id="limelight_product_display_section">
					<?php 
						$productId = $limeLightProduct->getLimeLightProductId();
						if(!empty($productId)) { 
					?>
					<span style="font-family:courier;"><?php echo $limeLightProduct->getLimeLightProductName(); ?> [<?php echo $limeLightProduct->getLimeLightProductId(); ?>]</span>
					<?php } else { ?>
					<a href="javascript:mmjs.getLimeLightProducts();">Load Lime Light Products</a>
					<?php } ?>
					<a href="javascript:mmjs.getLimeLightProducts();" title="Get Lime Light Products"><?php echo MM_Utils::getIcon("download", "green", "1.4em", "2px;"); ?></a>
					<a href="javascript:mmjs.getLimeLightProductDescription('');" title="View Lime Light Product Info"><?php echo MM_Utils::getIcon("info-circle", "blue", "1.3em", "1px;"); ?></a>
				</div>
				
				<div id="limelight_select_product_section" style="display:none;">
					<select name='limelight_product_id_selector' id='limelight_product_id_selector' style="display:none;">
					</select>
					<a href="javascript:mmjs.getLimeLightProductDescription('');" title="View Lime Light Product Info"><?php echo MM_Utils::getIcon("info-circle", "blue", "1.3em", "1px;"); ?></a>
				</div>
			</td>
		</tr>
	</table>
	
	<input id='id' type='hidden' value='<?php if($limeLightProduct->getId() != 0) { echo $limeLightProduct->getId(); } ?>' />
	<input id='limelight_campaign_name' name='limelight_campaign_name' type='hidden' />
	<input id='limelight_product_name' name='limelight_product_name' type='hidden' value='<?php echo $limeLightProduct->getLimeLightProductName(); ?>' />
	<input id='limelight_product_id' name='limelight_product_id' type='hidden' value='<?php echo $limeLightProduct->getLimeLightProductId(); ?>' />
</div>

<div class="mm-dialog-footer-container">
<div class="mm-dialog-button-container">
<a href="javascript:mmjs.save();" class="mm-ui-button blue">Save Product Mapping</a>
<a href="javascript:mmjs.closeDialog();" class="mm-ui-button">Cancel</a>
</div>
</div>
<script>
jQuery( document ).ready(function() {
	mmjs.getMMProductDescription();
});
</script>