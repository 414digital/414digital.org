<?php 
/**
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */
	
	$profile = new MM_CommissionProfile($p->id);
	
	$initialPaymentChecked = $profile->initialCommissionEnabled() ? "checked" : "";
	$reverseCommissionsChecked = $profile->doReverseCommissions() ? "checked" : "";
	$defaultCommissionSelected = $profile->getRebillCommissionType() == MM_CommissionProfile::$COMMISSION_TYPE_DEFAULT ? "checked":"";
	$customCommissionSelected = $profile->getRebillCommissionType() == MM_CommissionProfile::$COMMISSION_TYPE_DEFAULT ? "":"checked";
	$flatrateSelected = $profile->getRebillCommissionType() == MM_CommissionProfile::$COMMISSION_TYPE_FLATRATE ? "selected":"";
	$percentSelected = $profile->getRebillCommissionType() == MM_CommissionProfile::$COMMISSION_TYPE_PERCENT ? "selected":"";
	$limitRebillsChecked = $profile->doLimitRebills() ? "checked" : "";
?>
<div id="mm-form-container">
	<input id='id' type='hidden' value='<?php if($profile->getId() != 0) { echo $profile->getId(); } ?>' />
	<input id='mm-enable-initial-commission' type='hidden' />
	<input id='mm-enable-rebill-commissions' type='hidden' />
	<input id='mm-limit-rebill-commissions' type='hidden' />
	<input id='mm-enable-reverse-commissions' type='hidden' />
	<input id='mm-rebill-commission-type' type='hidden' />
				
	<table cellspacing="10">
		<tr>
			<td width="135">Name*</td>
			<td><input id="mm-display-name" type="text" style="width:100%;" value='<?php echo htmlentities($profile->getName(),ENT_QUOTES, 'UTF-8', true); ?>'/></td>
		</tr>
		
		<tr>
			<td>Description</td>
			<td>
				<textarea id="mm-description" name='description' style="width:100%;"><?php echo $profile->getDescription(); ?></textarea>
			</td>
		</tr>
		
		<tr>
			<td colspan="2">
			<div style="width: 600px; margin-top: 8px;" class="mm-divider"></div>
			</td>
		</tr>
		
		<tr>
			<td>Commission Options</td>
			<td>	
				<input type='checkbox' id='mm-enable-initial-commission-checkbox' name='mm-enable-initial-commission-checkbox' <?php echo $initialPaymentChecked; ?> /> Enable commission on initial payment
				
				<p>
					<input type="checkbox" name="mm-enable-rebill-commissions-checkbox" id="mm-enable-rebill-commissions-checkbox" onclick="mmjs.renderRebillOptions()" <?php echo ($profile->rebillCommissionsEnabled() ? "checked":""); ?>  /> Enable Rebill Commissions
				</p>
				
				<div style="margin-top:5px; margin-left:20px; display: none;" id='rebill_commission_options'>
					<?php 
						$activeAffiliateProvider = MM_AffiliateProviderFactory::getActiveProvider();
						
						if($activeAffiliateProvider->supportsFeature(MM_AffiliateProviderFeatures::CUSTOM_REBILL_COMMISSIONS))
						{
					?>
					<p style="line-height:30px;" id="rebill_commission_type_selection">
						<input id="rebill_commission_selector" name="rebill_commission_selector" value='default' type="radio" <?php echo $defaultCommissionSelected; ?> />
						Use same commission as initial payment 
						<?php echo MM_Utils::getInfoIcon('The commission used on initial payment is based on the commission settings defined for the affiliate in the 3rd party affiliate configuration.'); ?>
					
						<br/>
						
						<input id="rebill_commission_selector" name="rebill_commission_selector" value='custom' type="radio" <?php echo $customCommissionSelected; ?> />
						Use the following commission on rebills 
						<input id="mm_rebill_commission_value" type="text" size="6" value="<?php echo $profile->getRebillCommissionValue(); ?>" onkeydown="return mmjs.checkRebillCommission(event)" onchange="mmjs.rebillCommissionChangeHandler()" />
						
						<select id="rebill_commission_type_selector" name="rebill_commission_type_selector">
							<option value="<?php echo MM_CommissionProfile::$COMMISSION_TYPE_PERCENT; ?>" <?php echo $percentSelected; ?>>%</option>
							<option value="<?php echo MM_CommissionProfile::$COMMISSION_TYPE_FLATRATE; ?>" <?php echo $flatrateSelected ?>>Flat Rate</option>
						</select>
						<?php echo MM_Utils::getInfoIcon('For flat rate commissions, express this value in the same currency that is set in your 3rd party affiliate configuration.'); ?>
					</p>
					<?php } ?>
					
					<p>
						<input type='checkbox' id='mm-limit-rebill-commissions-checkbox' name='mm-limit-rebill-commissions-checkbox' <?php echo $limitRebillsChecked; ?> onchange="mmjs.renderRebillOptions()" /> 
						Limit rebill commissions
					</p>
					
					<div style="margin-top:5px; margin-left:20px; display: none;" id='limit_rebill_commission_options'>
						<p>
							Limit to 
							<input id="mm_limit_rebill_commission_value" type="text" size="3" value="<?php echo $profile->getRebillCommissionLimit(); ?>" onkeydown="return mmjs.checkRebillCommission(event)" onchange="mmjs.rebillCommissionChangeHandler()" />
							rebill payments
						</p>
					</div>
				</div>
			</td>
		</tr>
		
		<tr>
			<td colspan="2">
			<div style="width: 600px; margin-top: 8px;" class="mm-divider"></div>
			</td>
		</tr>
		
		<tr>
			<td>Refund Options</td>
			<td>
				<div>
					<input type='checkbox' id='mm-enable-reverse-commissions-checkbox' name='mm-enable-reverse-commissions-checkbox' <?php echo $reverseCommissionsChecked; ?> /> 
					Cancel commission when customer is refunded
					<?php echo MM_Utils::getInfoIcon('Only commissions that have not been paid out yet can be canceled.'); ?>
				</div>
			</td>
		</tr>
	</table>
	
	<script type='text/javascript'>
	mmjs.renderRebillOptions();
	</script>
</div>

<div class="mm-dialog-footer-container">
<div class="mm-dialog-button-container">
<a href="javascript:mmjs.save();" class="mm-ui-button blue">Save Commission Profile</a>
<a href="javascript:mmjs.closeDialog();" class="mm-ui-button">Cancel</a>
</div>
</div>