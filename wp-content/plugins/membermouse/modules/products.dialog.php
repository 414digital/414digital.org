<?php 
/**
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */

$product = new MM_Product($p->id);

$enableNumberOfPayments = ($product->doLimitPayments()) ? "" : "disabled='disabled'";

$associatedMembership = $product->getAssociatedMembership();
$associatedBundle = $product->getAssociatedBundle();
$noAccessAssociation = (!$associatedMembership->isValid() && !$associatedBundle->isValid()) ? true : false;

if($associatedMembership->isValid())
{
	$lastAccessAssociationType = "membership";
	$lastAccessAssociationId = $associatedMembership->getId();
}
else if($associatedBundle->isValid())
{
	$lastAccessAssociationType = "bundle";
	$lastAccessAssociationId = $associatedBundle->getId();
}
else
{
	$lastAccessAssociationType = "";
	$lastAccessAssociationId = "";
}

$hasBeenPurchased = false;

if($product->isValid())
{
	$hasBeenPurchased = MM_Product::hasBeenPurchased($product->getId());
}

$periodsArr = array(
	'days'=>'days',
	'weeks'=>'weeks',
	'months'=>'months',
	'years'=>'years',
);

$trialFrequencyList = MM_HtmlUtils::generateSelectionsList($periodsArr, $product->getTrialFrequency());
$rebillFrequencyList = MM_HtmlUtils::generateSelectionsList($periodsArr, $product->getRebillFrequency());
$membershipList = MM_HtmlUtils::getMemberships($associatedMembership->getId(), false, MM_MembershipLevel::$SUB_TYPE_PAID);
$bundleList = MM_HtmlUtils::getBundles($associatedBundle->getId(), false, MM_Bundle::$SUB_TYPE_PAID);
$commissionProfileList = MM_HtmlUtils::getCommissionProfilesList($product->getCommissionProfileId());

function renderFieldOption($optionId, $affiliateId, $profileId)
{
	?>
	<div id="mm-partner-container-<?php echo $optionId; ?>">
	<input id="mm-partner-<?php echo $optionId; ?>" type="text" size="15" class="field-option" value="<?php echo $affiliateId; ?>" />
	<select id='mm-commission-profile-<?php echo $optionId; ?>'>
		<option value="<?php MM_CommissionProfile::$DFLT_COMMISSION_PROFILE_ID; ?>" <?php echo ($profileId == MM_CommissionProfile::$DFLT_COMMISSION_PROFILE_ID) ? "selected":""; ?>>
			&mdash; Use system default &mdash;
		</option>
		<?php echo MM_HtmlUtils::getCommissionProfilesList($profileId); ?>
	</select>
	<a href="javascript:mmjs.addFieldOption('<?php echo htmlentities(MM_Utils::getIcon('plus-circle', 'green', '1.2em', '1px'), ENT_QUOTES); ?>', '<?php echo htmlentities(MM_Utils::getIcon('trash-o', 'red', '1.2em', '1px'), ENT_QUOTES); ?>');"><?php echo MM_Utils::getIcon('plus-circle', 'green', '1.2em', '1px'); ?></a>
		
	<?php if($optionId > 1) { ?>
	<a href="javascript:mmjs.removeFieldOption('mm-partner-container-<?php echo $optionId; ?>');"><?php echo MM_Utils::getIcon('trash-o', 'red', '1.2em', '1px'); ?></a>
	<?php } ?>
	</div>
<?php
}
?>
<script>
function insertBasicTemplate()
{	
	var str = "[MM_Form type='1clickPurchase']\n";
	str += "Are you sure you want to purchase [MM_Form_Data name='productName']?\n";
	str += "<br/><br/>\n";
	str += "Your card will be billed [MM_Form_Data name='totalPrice'].\n";
	str += "[/MM_Form]\n";
				
	insertTemplate(str);
}

function insertAdvancedTemplate()
{	
	var str = "[MM_Form type='1clickPurchase']\n";
	str += "Product Name: [MM_Form_Data name='productName']<br/>\n";
	str += "Product Description: [MM_Form_Data name='productDescription']<br/>\n";
	str += "Product Price: [MM_Form_Data name='productPrice']<br/>\n";
	str += "Shipping Price: [MM_Form_Data name='shippingPrice']<br/>\n";
	str += "Discount: [MM_Form_Data name='discount']<br/>\n";
	str += "Total Price: [MM_Form_Data name='totalPrice']\n\n";

	str += "[MM_Form_Section type='shippingInfo']\n";
	str += "<br/><br/>\n";
	str += "Shipping Information<br/>\n";
	str += "Shipping Method: [MM_Form_Field name='shippingMethod']\n";
	str += "<br/><br/>\n";
	str += "Address: [MM_Form_Field name='shippingAddress']<br/>\n";
	str += "City: [MM_Form_Field name='shippingCity']<br/>\n";
	str += "State: [MM_Form_Field name='shippingState']<br/>\n";
	str += "Zip Code: [MM_Form_Field name='shippingZipCode']<br/>\n";
	str += "Country: [MM_Form_Field name='shippingCountry']\n";
	str += "[/MM_Form_Section]\n\n";

	str += "[MM_Form_Section type='coupon']\n";
	str += "<br/><br/>\n";
	str += "Coupon Code: [MM_Form_Field name='couponCode']\n";
	str += "<a href=\"[MM_Form_Button type='applyCoupon']\">Apply Coupon</a><br/><br/>\n";
	str += "[MM_Form_Message type='couponSuccess']\n";
	str += "[MM_Form_Message type='couponError']\n";
	str += "[/MM_Form_Section]\n";
	str += "[/MM_Form]";
	
	insertTemplate(str);
}

function insertTemplate(str)
{
	jQuery("#purchase_confirmation_message").val(str);
}

function unlockProduct()
{
	jQuery("#mm-associated-access-none").prop('disabled', false);
	jQuery("#mm-associated-access-membership").prop('disabled', false);
	jQuery("#mm-associated-access-bundle").prop('disabled', false);
	jQuery("#mm-membership-access-selector").prop('disabled', false);
	jQuery("#mm-bundle-access-selector").prop('disabled', false);
	jQuery("#mm-has_trial").prop('disabled', false);
	jQuery("#mm-trial_amount").prop('disabled', false);
	jQuery("#mm-trial_duration").prop('disabled', false);
	jQuery("#mm-trial_frequency").prop('disabled', false);
	jQuery("#mm-is_recurring").prop('disabled', false);
	jQuery("#mm-rebill_period").prop('disabled', false);
	jQuery("#mm-rebill_frequency").prop('disabled', false);
	jQuery("#mm-limit_payments").prop('disabled', false);
	jQuery("#mm-number_of_payments").prop('disabled', false);
	jQuery("#mm-price").prop('disabled', false);
}
</script>

<style>
.noticeMessage {
	background-color: #FFFFE0;
    border-color: #E6DB55;
    border-style: solid;
    border-width: 1px;
	padding: 5px 10px;
	border-radius: 3px;
	font-size: 12px;
	color: #333;
}
</style>

<div id="mm-form-container">
	<?php 
	if($hasBeenPurchased)
	{
		echo "<p class='noticeMessage'>";
		echo "This product has been purchased so some properties have been locked to avoid accidental editing. ";
		echo "If you want to edit these properties, <a href=\"javascript:unlockProduct()\">click here to unlock them</a>.";
		echo "</p>";
	}
	?>
	<input type='hidden' id='mm-id' value='<?php echo $p->id; ?>' />
	<table style="width:100%" cellpadding="2">
	<tr>
		<td width="140">Name*</td>
		<td><input type='text' id='mm-name' value='<?php echo $product->getName(); ?>' class="long-text"/></td>
	</tr>
	
	<tr>
		<td>Status<?php echo MM_Utils::getInfoIcon("Inactive products cannot be purchased. Changing the status of a product has no impact on members who have already purchased the product."); ?></td>
		<td>
			<div id="mm-status-container">
				<input type="radio" name="status" value="active" onclick="mmjs.processForm()" <?php echo (($product->getStatus()=="1")?"checked":""); ?> /> Active &nbsp;
				<input type="radio" name="status" value="inactive" onclick="mmjs.processForm()" <?php echo (($product->getStatus()=="0")?"checked":""); ?> /> Inactive
			</div>
			
			<input id="mm-status" type="hidden" />
		</td>
	</tr>
	
	<tr>
		<td>SKU</td>
		<td><input type='text' id='mm-sku' value='<?php echo $product->getSku(); ?>'  style='width: 125px;'/></td>
	</tr>
	<tr>
		<td colspan="2">
		<div style="width: 540px; margin-top: 5px; margin-bottom:4px;" class="mm-divider"></div>
		</td>
	</tr>
	<tr>
		<td>Associated Access</td>
		<td>
			<input id="mm-associated-access-value" type="hidden" />
			<input id="mm-last-associated-access-type" type="hidden" value="<?php echo $lastAccessAssociationType; ?>" />
			<input id="mm-last-associated-access-id" type="hidden" value="<?php echo $lastAccessAssociationId; ?>" />
			
			<div id="mm-access-container">
				<input id="mm-associated-access-none" type="radio" name="associated-access" value="none" onclick="mmjs.accessChangeHandler()" <?php echo ($noAccessAssociation ? "checked":""); ?> <?php echo ($hasBeenPurchased) ? "disabled" : ""; ?> /> None &nbsp;
				<input id="mm-associated-access-membership" type="radio" name="associated-access" value="membership" onclick="mmjs.accessChangeHandler()" <?php echo ($associatedMembership->isValid() ? "checked" : ""); ?> <?php echo ($hasBeenPurchased) ? "disabled" : ""; ?> /> Membership Level &nbsp; 
				<input id="mm-associated-access-bundle" type="radio" name="associated-access" value="bundle" onclick="mmjs.accessChangeHandler()" <?php echo ($associatedBundle->isValid() ? "checked" : ""); ?> <?php echo ($hasBeenPurchased) ? "disabled" : ""; ?> /> Bundle &nbsp;
			</div>
			
			<div id="mm-membership-access-container" style="padding-top:10px; <?php echo (!$associatedMembership->isValid() ? "display:none;" : ""); ?>">
				<select id='mm-membership-access-selector' <?php echo ($hasBeenPurchased) ? "disabled" : ""; ?>>
					<?php echo $membershipList; ?>
				</select>
			</div>
			
			<div id="mm-bundle-access-container" style="padding-top:10px; <?php echo (!$associatedBundle->isValid() ? "display:none;" : ""); ?>">
				<select id='mm-bundle-access-selector' <?php echo ($hasBeenPurchased) ? "disabled" : ""; ?>>
					<?php echo $bundleList; ?>
				</select>
			</div>
		</td>
	</tr>
	<tr>
		<td colspan="2">
		<div style="width: 540px; margin-top: 5px; margin-bottom:4px;" class="mm-divider"></div>
		</td>
	</tr>
	<tr>
		<td>Price*</td>
		<td><input type='text' id='mm-price' value='<?php echo $product->getPrice(false); ?>'  style='width: 125px;' <?php echo ($hasBeenPurchased) ? "disabled" : ""; ?>/> <?php echo MM_CurrencyUtil::getActiveCurrency(); ?></td>
	</tr>
	<tr>
		<td colspan="2">
		<div style="width: 540px; margin-top: 5px; margin-bottom:4px;" class="mm-divider"></div>
		</td>
	</tr>
	<tr>
		<td>Trial</td>
		<td>
			<input type='checkbox' id='mm-has_trial' onchange="mmjs.toggleTrial();" <?php echo ($product->hasTrial() ? 'checked':''); ?> <?php echo ($hasBeenPurchased) ? "disabled" : ""; ?> />
			<input type='hidden' id='mm-has_trial_val' value='<?php echo ($product->hasTrial() ? '1':'0'); ?>' />
		</td>
	</tr>
	<tr>
		<td></td>
		<td>
			<div id='mm_has_trial_row' style='display:none;'>
				<p style="margin:5px 0px 0px 0px;">
					Trial Price 
					<input type='text' id='mm-trial_amount' value='<?php echo $product->getTrialAmount(false); ?>'  style='margin-left: 10px; width: 125px;' <?php echo ($hasBeenPurchased) ? "disabled" : ""; ?>/> <?php echo MM_CurrencyUtil::getActiveCurrency(); ?>
				</p>
				<p style="margin:5px 0px 5px 0px;">
					Trial Period
					<input type='text' id='mm-trial_duration' value='<?php echo $product->getTrialDuration(); ?>'  style='margin-left: 10px; width: 50px;' <?php echo ($hasBeenPurchased) ? "disabled" : ""; ?>/> 
					<select id='mm-trial_frequency' style='width:100px;' <?php echo ($hasBeenPurchased) ? "disabled" : ""; ?>>
					<?php echo $trialFrequencyList; ?>
					</select>
					
				</p>
			</div>
		</td>
	</tr>
	<tr>
		<td colspan="2">
		<div style="width: 540px; margin-top: 5px; margin-bottom:4px;" class="mm-divider"></div>
		</td>
	</tr>
	<tr>
		<td>Subscription</td>
		<td>
			<input type='checkbox' onchange="mmjs.toggleRecurring();" id='mm-is_recurring' <?php echo (($product->isRecurring())?'checked':''); ?> <?php echo ($hasBeenPurchased) ? "disabled" : ""; ?>/>
			<input type='hidden' id='mm-is_recurring_val' value='<?php echo (($product->isRecurring())?'1':'0'); ?>' />
		</td>
	</tr>
	<tr>
		<td></td>
		<td>
			<div id='mm_rebill_row' style='display:none;'>
				<p style="margin:5px 0px 0px 0px;">
					Rebill Period
					<input type='text' id='mm-rebill_period' value='<?php echo $product->getRebillPeriod(); ?>'  style='margin-left: 10px; width: 50px' <?php echo ($hasBeenPurchased) ? "disabled" : ""; ?>/> 
					<select id='mm-rebill_frequency' style='width:100px;' <?php echo ($hasBeenPurchased) ? "disabled" : ""; ?>>
					<?php echo $rebillFrequencyList; ?>
				</select>
				</p>
				<p style="margin:5px 0px 5px 0px;">
					Payment Plan
				    <span style='margin-left: 10px; vertical-align:middle;'>
				    	<input type='checkbox' id='mm-limit_payments' value='1' <?php echo ($product->doLimitPayments() ? 'checked':''); ?> onchange="mmjs.enableLimitPayments();" <?php echo ($hasBeenPurchased) ? "disabled" : ""; ?>/> limit to 
				    	<input type='text' id='mm-number_of_payments' value='<?php echo $product->getNumberOfPayments(); ?>'  style='width: 50px' <?php echo $enableNumberOfPayments;?> <?php echo ($hasBeenPurchased) ? "disabled" : ""; ?> /> 
				    	payments
				    </span>
				    <input type='hidden' id='mm-do_limit_payments' value='<?php echo ($product->doLimitPayments() ? '1':'0'); ?>' />
				</p>
			</div>
		</td>
	</tr>
	<tr>
		<td colspan="2">
		<div style="width: 540px; margin-top: 5px; margin-bottom:4px;" class="mm-divider"></div>
		</td>
	</tr>
	<tr>
		<td>Requires Shipping</td>
		<td>
			<input type='checkbox' id='mm-is_shippable' <?php echo ($product->isShippable() ?'checked':''); ?> onchange="mmjs.changeOption('mm-is_shippable');" />
			<input type='hidden' id='mm-is_shippable_val' value='<?php echo ($product->isShippable() ? '1':'0'); ?>' />
		</td>
	</tr>
	
	<tr>
		<td colspan="2">
		<div style="width: 540px; margin-top: 5px; margin-bottom:4px;" class="mm-divider"></div>
		</td>
	</tr>
	<tr>
		<td>Description<?php echo MM_Utils::getInfoIcon("Enter a description for this product. It will be displayed in checkout forms when the [MM_Form_Data name='productDescription'] SmartTag is used."); ?></td> 
		<td><textarea id='mm-description' cols='55' rows='3'><?php echo $product->getDescription(); ?></textarea></td>
	</tr>
	
	<tr>
		<td colspan="2">
		<div style="width: 540px; margin-top: 5px; margin-bottom:4px;" class="mm-divider"></div>
		</td>
	</tr>
	
	<?php 
		$affiliateProvider = MM_AffiliateProviderFactory::getActiveProvider(); 
		
		if(!$affiliateProvider->supportsFeature(MM_AffiliateProviderFeatures::COMMISSION_TRACKING))
		{
	?>
	<tr>
		<td colspan="2">
			<p class="noticeMessage" style="font-size:10px;">
				Automatic commission tracking isn't supported for the affiliate provider you're integrated with. In order 
				to take advantage of commission profiles and partner payouts you'll need to use affiliate 
				push notifications or WordPress hooks. Read this article to <a href="https://membermouse.uservoice.com/knowledgebase/articles/319250-manually-integrate-with-an-affiliate-system" target="_blank">learn more about integrating with your affiliate system</a>.
			</p>
		</td>
	</tr>
	<?php } ?>
	
	<tr>
		<td>Commissions</td> 
		<td>
			<div style="line-height:30px;">
				Commission Profile<?php echo MM_Utils::getInfoIcon("This is the commission profile that will be used when a customer who was referred by an affiliate purchases this product."); ?>
				<br/>
				<select id='mm-commission-profile-selector'>
					<option value="<?php MM_CommissionProfile::$DFLT_COMMISSION_PROFILE_ID; ?>" <?php echo ($product->getCommissionProfileId() == MM_CommissionProfile::$DFLT_COMMISSION_PROFILE_ID) ? "selected":""; ?>>
						&mdash; Use system default &mdash;
					</option>
					<?php echo $commissionProfileList; ?>
				</select>
				<?php 
					$commissionProfileUrl = MM_ModuleUtils::getUrl(MM_MODULE_AFFILIATE_SETTINGS, MM_MODULE_COMMISSION_PROFILES);
				?>
				<a href="<?php echo $commissionProfileUrl ?>" style="font-size:10px" target="_blank">add commission profile</a>
			</div>
			
			<div id="mm-commission-profile-options-container" style="display:none">
				<option value="<?php MM_CommissionProfile::$DFLT_COMMISSION_PROFILE_ID; ?>">
					&mdash; Use system default &mdash;
				</option>
				<?php echo MM_HtmlUtils::getCommissionProfilesList(); ?>
			</div>
			
			
			<div style="margin-top:20px;">
				Partner Payouts<?php echo MM_Utils::getInfoIcon("A partner payout is a commission paid to the affiliate specified on every sale of this product. Partner payouts will be paid in addition to a standard affiliate referral if it exists. For example, if a customer was referred by an affiliate and there is one or more partner payout, commissions will be paid to the referring affiliate and each partner."); ?>
				
				<div style="margin-top:5px;">
					<span style="margin-left:2px;">Affiliate ID</span>
					<span style="margin-left:54px;">Commission Profile</span>
				</div>
				
				<div id="mm-partners" style="margin-top:5px;">
					<?php 
						$partners = $product->getPartners();
						
						if(count($partners) > 0)
						{
							$crntPartner = 1;
							foreach($partners as $partner)
							{
								renderFieldOption($crntPartner, $partner->affiliateId, $partner->commissionProfileId);
								$crntPartner++;
							}
						} 
						else 
						{ 
							renderFieldOption(1, "", MM_CommissionProfile::$DFLT_COMMISSION_PROFILE_ID);
						} 
					?>
				</div>
			</div>
		</td>
	</tr>
	
	<?php 
		$onsiteService = MM_PaymentServiceFactory::getOnsitePaymentService();
		
		if($onsiteService && $onsiteService->isActive() && $onsiteService->supportsFeature(MM_PaymentServiceFeatures::CARD_ON_FILE))
		{
	?>
	<tr>
		<td colspan="2">
		<div style="width: 540px; margin-top: 5px; margin-bottom:4px;" class="mm-divider"></div>
		</td>
	</tr>
	<tr>
		<td>Purchase Confirmation Message<?php echo MM_Utils::getInfoIcon("Enter a purchase confirmation message for this product. It will be displayed to the member when they click to purchase a product using a card on file."); ?></td> 
		<td>
			<div>
				<?php echo MM_SmartTagLibraryView::smartTagLibraryButtons("purchase_confirmation_message"); ?>
				<?php 
					$validSmartTags = "Only the following SmartTags can be used here:\n";
					$validSmartTags .= "[MM_Access_Decision] (you must provide an ID)\n";
					$validSmartTags .= "[MM_Content_Data] (you must provide an ID)\n";
					$validSmartTags .= "[MM_Content_Link] (you must provide an ID)\n";
					$validSmartTags .= "[MM_CorePage_Link]\n";
					$validSmartTags .= "[MM_CustomField_Data]\n";
					$validSmartTags .= "[MM_Employee_Data]\n";
					$validSmartTags .= "[MM_Form] (1clickPurchase type only)\n";
					$validSmartTags .= "[MM_Member_Data]\n";
					$validSmartTags .= "[MM_Member_Decision]\n";
					$validSmartTags .= "[MM_Member_Link]\n";
					$validSmartTags .= "[MM_Purchase_Link]";
				?>
				<span style="font-size:11px; color:#666666; margin-left: 5px;">
				Insert template: <a href="javascript:insertBasicTemplate();" style="color:#21759B;">Product Information</a>, <a href="javascript:insertAdvancedTemplate();" style="color:#21759B;">Collect Shipping Info and Coupon Code</a>
				<br/>
				<em>Note: Only certain SmartTags can be used here</em></span>
				<?php echo MM_Utils::getInfoIcon($validSmartTags, ""); ?>
			</div>
			
			<div style="margin-top:5px">
				<textarea id='mm-purchase_confirmation_message' style="width:100%; height:140px; font-family:courier; font-size: 11px;"><?php echo htmlentities($product->getPurchaseConfirmationMessage(),ENT_QUOTES, 'UTF-8', true); ?></textarea>
			</div>	
		</td>
	</tr>
	<?php 
		}
	?>
	</table>
</div>
<script type='text/javascript'>
mmjs.toggleTrial();
mmjs.toggleRecurring();
</script>

<div class="mm-dialog-footer-container">
<div class="mm-dialog-button-container">
<a href="javascript:mmjs.saveProduct();" class="mm-ui-button blue">Save Product</a>
<a href="javascript:mmjs.closeDialog();" class="mm-ui-button">Cancel</a>
</div>
</div>