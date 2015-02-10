<script>
function stripeTestModeChangeHandler()
{
	if(jQuery("#stripe_test_mode").is(":checked"))
	{
		jQuery("#stripe-test-account-info").show();
		jQuery("#stripe-api-key-label").html("API Test Secret Key");
		jQuery(".stripe-test").show();
		jQuery(".stripe-live").hide();
	}
	else
	{
		jQuery(".stripe-test").hide();
		jQuery(".stripe-live").show();
		jQuery("#stripe-test-account-info").hide();
		jQuery("#stripe-api-key-label").html("API Live Secret Key");
	}
}

jQuery(function() {
	stripeTestModeChangeHandler();
});

function showStripeTestCardNumbers()
{
	var str = "";

	str += "You can use the following test credit card numbers when testing payments.\n";
	str += "The expiration date must be set to the present date or later:\n\n";
	str += "- Visa: 4242424242424242\n";
	str += "- Visa: 4012888888881881\n";
	str += "- MasterCard: 5555555555554444\n";
	str += "- MasterCard: 5105105105105100\n";
	str += "- American Express: 378282246310005\n";
	str += "- American Express: 371449635398431\n";
	str += "- Discover: 6011111111111117\n";
	str += "- Discover: 6011000990139424\n";
	str += "- Diners Club: 30569309025904\n";
	str += "- Diners Club: 38520000023237\n";
	str += "- JCB: 3530111333300000\n";
	str += "- JCB: 3566002020360505\n";
	alert(str);
}
</script>

<div style="padding:10px;">
<img src='https://dl.dropboxusercontent.com/u/265387542/plugin_images/logos/stripe.png' />

<div style="margin-top:5px; margin-bottom:10px;">
<a href='https://membermouse.uservoice.com/knowledgebase/articles/319041-configuring-stripe' target='_blank'>Need help configuring Stripe?</a>
</div>

<div style="margin-bottom:10px;">
	<input type='checkbox' value='true' <?php echo (($p->isInTestMode()==true)?"checked":""); ?> id='stripe_test_mode' name='payment_service[stripe][test_mode]' onClick="stripeTestModeChangeHandler()" />
	Enable Test Mode
</div>

<div id="stripe-test-account-info" style="margin-bottom:10px; margin-left:10px; <?php echo (($p->isInTestMode()==true)?"":"display:none;"); ?>">
	<div style="margin-bottom:5px;">
		<?php echo MM_Utils::getIcon('external-link', 'blue', '1.2em', '2px', '', "margin-right:3px;"); ?> 
		<a href="https://manage.stripe.com/account/apikeys" target="_blank">Set up or retrieve your Stripe API Keys</a>
	</div>
	<div style="margin-bottom:5px;">
		<?php echo MM_Utils::getIcon('external-link', 'blue', '1.2em', '2px', '', "margin-right:3px;"); ?> 
		<a href="https://manage.stripe.com/dashboard" target="_blank">Log Into your Stripe dashboard</a>
	</div>
	<div style="margin-bottom:5px;">
		<?php echo MM_Utils::getIcon('credit-card', 'blue', '1.3em', '1px', "Test Credit Card Numbers", "margin-right:3px;"); ?>
		<a href="javascript:showStripeTestCardNumbers()">Test Credit Card Numbers</a>
	</div>
	<div>
		<?php echo MM_Utils::getIcon('flask', 'blue', '1.3em', '1px', 'Setup Test Data', "margin-right:3px;"); ?>
		<a href="<?php echo MM_ModuleUtils::getUrl(MM_ModuleUtils::getPage(), MM_MODULE_TEST_DATA); ?>" target="_blank">Configure Test Data</a>
	</div>
</div>

<div style="margin-bottom:10px;">
	<span class="stripe-test" id="stripe-test-api-key-label">API Test Secret Key</span>
	
	<p class="stripe-test" style="margin-left:10px; font-family:courier; font-size:11px;">
		<input type='text' value='<?php echo $p->getTestAPIKey(); ?>' id='stripe_test_api_key' name='payment_service[stripe][test_api_key]' style='width: 275px;' />
	</p>
	
	<span class="stripe-live" id="stripe-live-api-key-label">API Live Secret Key</span>
	
	<p class="stripe-live" style="margin-left:10px; font-family:courier; font-size:11px;">
		<input type='text' value='<?php echo $p->getLiveAPIKey(); ?>' id='stripe_live_api_key' name='payment_service[stripe][live_api_key]' style='width: 275px;' />
	</p>
</div>

</div>