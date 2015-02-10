<?php 
/**
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */
	$limeLightShippingMethod = new MM_LimeLightShippingMethod($p->id);
	$limeLightService = MM_PaymentServiceFactory::getPaymentService(MM_PaymentService::$LIMELIGHT_SERVICE_TOKEN);
?>
<div id="mm-form-container">
	<table cellspacing="10">
		<tr>
			<td width="200">MemberMouse Shipping Method</td>
			<td>
				<select name='mm_option_key' id='mm_option_key'>
				<?php 
					$unmappedShippingMethods = MM_LimeLightShippingMethod::getUnmappedShippingMethods($limeLightShippingMethod->getMMOptionKey());
					
					echo MM_HtmlUtils::generateSelectionsList($unmappedShippingMethods, $limeLightShippingMethod->getMMOptionKey()); 
				?>
				</select>
			</td>
		</tr>
		
		<tr>
			<td colspan="2">
			<div style="width: 98%; margin-top: 8px;" class="mm-divider"></div>
			</td>
		</tr>
		
		<tr>
			<td width="150">Lime Light Shipping Method</td>
			<td>
				<select name='limelight_shipping_method_id_selector' id='limelight_shipping_method_id_selector'>
				<?php echo MM_HtmlUtils::generateSelectionsList($limeLightService->getShippingMethods(), $limeLightShippingMethod->getLimeLightShippingMethodId()); ?>
				</select>
				<a href="javascript:mmjs.getLimeLightShippingDescription('');" title="View Lime Light Shipping Method Info"><?php echo MM_Utils::getIcon("info-circle", "blue", "1.3em", "2px;"); ?></a>
			</td>
		</tr>
	</table>
	
	<input id='id' type='hidden' value='<?php if($limeLightShippingMethod->getId() != 0) { echo $limeLightShippingMethod->getId(); } ?>' />
	<input id='limelight_shipping_method_name' name='limelight_shipping_method_name' type='hidden' value='<?php echo $limeLightShippingMethod->getLimeLightShippingMethodName(); ?>' />
	<input id='limelight_shipping_method_id' name='limelight_shipping_method_id' type='hidden' value='<?php echo $limeLightShippingMethod->getLimeLightShippingMethodId(); ?>' />
</div>

<div class="mm-dialog-footer-container">
<div class="mm-dialog-button-container">
<a href="javascript:mmjs.save();" class="mm-ui-button blue">Save Shipping Method Mapping</a>
<a href="javascript:mmjs.closeDialog();" class="mm-ui-button">Cancel</a>
</div>
</div>