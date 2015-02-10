<script>
function authNetCIMTestModeChangeHandler()
{
	if(jQuery("#authorizenetcim_use_test_gateway").is(":checked"))
	{
		jQuery("#auth-net-cim-test-account-info").show();
		jQuery("#auth-net-cim-login-label").html("Test API Login ID");
		jQuery("#auth-net-cim-transaction-key-label").html("Test Transaction Key");
		jQuery("#authorizenetcim_test_api_login").show();
		jQuery("#authorizenetcim_live_api_login").hide();
		jQuery("#authorizenetcim_test_transaction_key").show();
		jQuery("#authorizenetcim_live_transaction_key").hide();
	}
	else
	{
		jQuery("#auth-net-cim-test-account-info").hide();
		jQuery("#auth-net-cim-login-label").html("API Login ID");
		jQuery("#auth-net-cim-transaction-key-label").html("Transaction Key");
		jQuery("#authorizenetcim_test_api_login").hide();
		jQuery("#authorizenetcim_live_api_login").show();
		jQuery("#authorizenetcim_test_transaction_key").hide();
		jQuery("#authorizenetcim_live_transaction_key").show();
	}
}

jQuery(function() {
	authNetCIMTestModeChangeHandler();
});

function showAuthNetCIMTestCardNumbers()
{
	var str = "";

	str += "You can use the following test credit card numbers when testing payments.\n";
	str += "The expiration date must be set to the present date or later:\n\n";
	str += "- American Express: 370000000000002\n";
	str += "- Discover: 6011000000000012\n";
	str += "- Visa: 4007000000027\n";
	str += "- Second Visa: 4012888818888\n";
	str += "- JCB: 3088000000000017\n";
	str += "- Diners Club/Carte Blanche: 38000000000006";

	alert(str);
}
</script>

<div style="padding:10px;">
<img src='https://dl.dropboxusercontent.com/u/265387542/plugin_images/logos/authorizenet-cim.png' />

<div style="margin-top:5px; margin-bottom:10px;">
<a href='https://membermouse.uservoice.com/knowledgebase/articles/319050-configuring-authorize-net-cim' target='_blank'>Need help configuring Authorize.net CIM?</a>
</div>

<div style="margin-bottom:10px;">
	<input type='checkbox' value='true' <?php echo (($p->isInTestMode()==true)?"checked":""); ?> id='authorizenetcim_use_test_gateway' name='payment_service[authorizenetcim][test_mode]' onclick="authNetCIMTestModeChangeHandler()" />
	Enable Test Mode
</div>

<div id="auth-net-cim-test-account-info" style="margin-bottom:10px; margin-left:10px; <?php echo (($p->isInTestMode()==true)?"":"display:none;"); ?>">
	<div style="margin-bottom:5px;">
		<?php echo MM_Utils::getIcon('external-link', 'blue', '1.2em', '2px', '', "margin-right:3px;"); ?>
		<a href="https://developer.authorize.net/testaccount/" target="_blank">Sign Up for a Test Gateway Account</a>
	</div>
	<div style="margin-bottom:5px;">
		<?php echo MM_Utils::getIcon('external-link', 'blue', '1.2em', '2px', '', "margin-right:3px;"); ?>
		<a href="https://sandbox.authorize.net/" target="_blank">Log Into Test Merchant Interface</a>
	</div>
	<div style="margin-bottom:5px;">
		<?php echo MM_Utils::getIcon('credit-card', 'blue', '1.3em', '1px', "Test Credit Card Numbers", "margin-right:3px;"); ?>
		<a href="javascript:showAuthNetCIMTestCardNumbers()">Test Credit Card Numbers</a>
	</div>
	<div>
		<?php echo MM_Utils::getIcon('flask', 'blue', '1.3em', '1px', 'Setup Test Data', "margin-right:3px;"); ?>
		<a href="<?php echo MM_ModuleUtils::getUrl(MM_ModuleUtils::getPage(), MM_MODULE_TEST_DATA); ?>" target="_blank">Configure Test Data</a>
	</div>
</div>

<div style="margin-bottom:10px;">
	<span id="auth-net-cim-login-label">API Login ID</span>
	
	<p style="margin-left:10px; font-family:courier; font-size:11px;">
		<input type='text' value='<?php echo $p->getTestAPILogin(); ?>' id='authorizenetcim_test_api_login' name='payment_service[authorizenetcim][test_api_login]' style='width: 275px;' />
	</p>
	
	<p style="margin-left:10px; font-family:courier; font-size:11px;">
		<input type='text' value='<?php echo $p->getLiveAPILogin(); ?>' id='authorizenetcim_live_api_login' name='payment_service[authorizenetcim][live_api_login]' style='width: 275px;' />
	</p>
</div>

<div style="margin-bottom:10px;">
	<span id="auth-net-cim-transaction-key-label">Transaction Key</span>
	
	<p style="margin-left:10px; font-family:courier; font-size:11px;">
		<input type='text' value='<?php echo $p->getTestTransactionKey(); ?>' id='authorizenetcim_test_transaction_key' name='payment_service[authorizenetcim][test_transaction_key]' style='width: 275px;' />
	</p>
	
	<p style="margin-left:10px; font-family:courier; font-size:11px;">
		<input type='text' value='<?php echo $p->getLiveTransactionKey(); ?>' id='authorizenetcim_live_transaction_key' name='payment_service[authorizenetcim][live_transaction_key]' style='width: 275px;' />
	</p>
</div>
</div>