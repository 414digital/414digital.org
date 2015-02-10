<?php

$availablePaymentServices = MM_PaymentServiceFactory::getPaymentServicesArray(false);

$onsiteServices = array();
$offsiteServices = array();

$onsiteSelected = false;
foreach ($availablePaymentServices as $currentoken=>$aService)
{
	if ($aService->supportsFeature(MM_PaymentServiceFeatures::OFFSITE_SERVICE))
	{
		$offsiteServices[$currentoken] = $aService;
	}
	else 
	{
		//assume services that fail the feature check are onsite, ie. only one onsite at a time can be active
		$onsiteServices[$currentoken] = $aService;
		if ((!$onsiteSelected) && ($aService->isActive()))
		{
			$onsiteSelected = true; //if no onsite services are selected, this will indicate to check the 'none' option
		}
	}
}
$currentCurrency = MM_CurrencyUtil::getActiveCurrency();
$unsupportedCurrencyWarning = "<div style='background-color: #ff0000; color: #ffffff;'>".
							  "This payment service does not support the configured currency. ".
							  "To change the configured currency click ".
							  "<a href='".MM_ModuleUtils::getUrl(MM_MODULE_PRODUCT_SETTINGS, MM_MODULE_CHECKOUT_OTHER_SETTINGS)."'>here</a></div>"
?>
<style>
.mm-payment-service-box {
  margin-bottom: 5px;
}
</style>
<form method='post'>
<div class="mm-wrap" id="mm-form-container">
	<div style='padding-left: 10px;'>
	    <div style='width:650px'>
			<p>
			With MemberMouse you can configure two types of payment methods: onsite and offsite. Onsite payment methods allow
			you to collect credit card information right from your site which means that customers can complete the entire
			checkout process without leaving your site. Authorize.net and Stripe are examples of onsite payment methods.
			</p>
			
			<p> 
			With offsite payment methods, MemberMouse sends the customer to a secure 3rd party website to complete their purchase.
			Following the successful completion of their order on the 3rd party site, MemberMouse will be notified and the
			appropriate account-related actions taken. PayPal and ClickBank are examples of offsite payment methods.
			</p>
			
			<p>You can have multiple payment methods active at the same time which is valuable because it gives your
			customers multiple ways to pay. For example, by using Authorize.net and PayPal, customers can either pay you by credit
			card on your site or by checking out with PayPal. Only one onsite payment method can be configured at a time and you 
			can activate as many offsite payment methods as you want.</p>
			
			<p><strong>IMPORTANT:</strong> Once you've configured your payment settings, click the button below to run a diagnostic on your
			site to discover if there's any security settings on your server that will interfer with payment processing.</p>
			
			<p><a href="http://membermouse.com/diagnostic/?url=<?php echo site_url(); ?>" class="mm-ui-button green" target="_blank">Run Diagnostic</a>
			
			<p class="mm-section-header">Onsite Payment Method</p>
			<p id="mm-https-notice" style="font-size:11px; display:none;">
			<?php echo MM_Utils::getIcon('warning', 'yellow', '1.3em', '2px'); ?> <em>When collecting sensitive data on your site 
			you need to ensure that you have an SSL certificate configured for your domain and that you're using HTTPS on pages that collect 
			sensitive data (i.e. checkout pages). Read this article for 
			<a href="https://membermouse.uservoice.com/knowledgebase/articles/319197-securing-your-site-with-https" target="_blank">steps you can take to secure your site</a>.</em>
			</p>
			<table width='750px'>
				<?php foreach ($onsiteServices as $token=>$service) { 
					$token = strtolower($token);
				?>
				<tr>
					<td width='25px'>
						<input type='radio' id='onsite_payment_service_<?php echo $token; ?>' name='onsite_payment_service'  value='<?php echo $token; ?>' onchange="pymtSettings_js.toggleOnsitePaymentOption('<?php echo $token; ?>')" <?php echo (($service->isActive())?"checked":""); ?>  />
					</td>
					
					<td>	
						<?php echo $service->getName(); ?>
					</td>
				</tr>
				<tr>
					<td colspan='2'>
						<div id='payment_service_<?php echo $token; ?>' class='mm-payment-service-box' style='<?php echo ($service->isActive())?"":"display:none;"; ?> margin-left: 10px; border: 1px solid #eee; background-color: #eee'>
							<?php if (!$service->isSupportedCurrency($currentCurrency)) { echo $unsupportedCurrencyWarning; } ?>
							<?php echo $service->displayConfigOptions(); ?>
						</div>
					</td>
				</tr>
				<?php } //end onsite foreach ?>
				<tr>
					<td width='25px'>
						<input type='radio' id='onsite_payment_service_none' name='onsite_payment_service'  value='none' onchange="pymtSettings_js.toggleOnsitePaymentOption('none')" <?php echo ((!$onsiteSelected)?"checked":""); ?> />
					</td>
					
					<td>	
						None
					</td>
				</tr>
			</table>
			
			<script>
				pymtSettings_js.toggleHttpsNotice();
			</script>
			
			<p class="mm-section-header" style="padding-top:15px; padding-bottom:10px;">Offsite Payment Methods</p>
			
			<table width='750px'>
				<?php foreach ($offsiteServices as $token=>$service) { 
					$token = strtolower($token);
				?>
				<tr>
					<td width='25px'>
						<input type='checkbox' id='offsite_payment_service_<?php echo $token; ?>' name='offsite_payment_services[]' value='<?php echo $token; ?>' onChange="pymtSettings_js.toggleOffsitePaymentOption('<?php echo $token; ?>')" <?php echo (($service->isActive())?"checked":""); ?> />
					</td>
					<td>
						<?php echo $service->getName(); ?>
					</td>
				</tr>
				<tr>
					<td colspan='2'>
						<div id='payment_service_<?php echo $token; ?>' class='mm-payment-service-box' style='<?php echo ($service->isActive())?"":"display:none;"; ?> margin-left: 10px; border: 1px solid #eee; background-color: #eee'>
							<?php if (!$service->isSupportedCurrency($currentCurrency)) { echo $unsupportedCurrencyWarning; } ?>
							<?php echo $service->displayConfigOptions(); ?>
						</div>
					</td>
				</tr>
				<?php } ?>
			</table>
			
	    	<div style='clear:both; height: 10px;'></div>
	</div>
</div>
</div>
	<input type='button' name='submit' value='Save Payment Methods' class="mm-ui-button blue" onClick="pymtSettings_js.paymentOptionsSave(jQuery('#mm-form-container :input').serializeArray()); return false;"/>
</form>