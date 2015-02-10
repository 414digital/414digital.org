<script>
function litleEnvChangeHandler()
{
	var crntEnv = jQuery("#litle-environment").val();

	switch(crntEnv)
	{
		case "<?php echo MM_LitleService::$ENVIRONMENT_SANDBOX; ?>":
			jQuery("#litle-test-info").show();
			jQuery("#litle-url-type-section").hide();
			jQuery("#litle-credentials-section").hide();
			break; 

		case "<?php echo MM_LitleService::$ENVIRONMENT_PRELIVE; ?>":
			jQuery("#litle-test-info").show();
			jQuery("#litle-url-type-section").show();
			jQuery("#litle-credentials-section").show();

			jQuery("#litle_prelive_merchant_id").show();
			jQuery("#litle_prod_merchant_id").hide();
			jQuery("#litle_postlive_merchant_id").hide();

			jQuery("#litle_prelive_username").show();
			jQuery("#litle_prod_username").hide();
			jQuery("#litle_postlive_username").hide();

			jQuery("#litle_prelive_password").show();
			jQuery("#litle_prod_password").hide();
			jQuery("#litle_postlive_password").hide();
			break;

		case "<?php echo MM_LitleService::$ENVIRONMENT_PRODUCTION; ?>":
			jQuery("#litle-test-info").hide();
			jQuery("#litle-url-type-section").show();
			jQuery("#litle-credentials-section").show();
			
			jQuery("#litle_prelive_merchant_id").hide();
			jQuery("#litle_prod_merchant_id").show();
			jQuery("#litle_postlive_merchant_id").hide();

			jQuery("#litle_prelive_username").hide();
			jQuery("#litle_prod_username").show();
			jQuery("#litle_postlive_username").hide();

			jQuery("#litle_prelive_password").hide();
			jQuery("#litle_prod_password").show();
			jQuery("#litle_postlive_password").hide();
			break;

		case "<?php echo MM_LitleService::$ENVIRONMENT_POSTLIVE; ?>":
			jQuery("#litle-test-info").hide();
			jQuery("#litle-url-type-section").show();
			jQuery("#litle-credentials-section").show();
			
			jQuery("#litle_prelive_merchant_id").hide();
			jQuery("#litle_prod_merchant_id").hide();
			jQuery("#litle_postlive_merchant_id").show();

			jQuery("#litle_prelive_username").hide();
			jQuery("#litle_prod_username").hide();
			jQuery("#litle_postlive_username").show();

			jQuery("#litle_prelive_password").hide();
			jQuery("#litle_prod_password").hide();
			jQuery("#litle_postlive_password").show();
			break;
	}
}

jQuery(function() {
	jQuery("#litle-environment").val('<?php echo $p->getEnvironment(); ?>');
	jQuery("#litle-url-type").val('<?php echo $p->getUrlType(); ?>');
	litleEnvChangeHandler();
});

function showLitleTestCardNumbers()
{
	switch(jQuery("#litle-environment").val())
	{
		case "<?php echo MM_LitleService::$ENVIRONMENT_SANDBOX; ?>":
			window.open("http://litleco.github.io/litle-sandbox/",'_blank');
			break; 

		case "<?php echo MM_LitleService::$ENVIRONMENT_PRELIVE; ?>":
			var str = "";

			str += "You can use the following test credit card number when testing payments.\n";
			str += "The expiration date must be set to the present date or later:\n\n";
			str += "- Visa: 4000000000000001\n";

			alert(str);
			break;
	}
}
</script>

<div style="padding:10px;">
<img src='https://dl.dropboxusercontent.com/u/265387542/plugin_images/logos/litle.png' />

<div style="margin-top:5px; margin-bottom:10px;">
<a href='http://membermouse.uservoice.com/knowledgebase/articles/325583' target='_blank'>Need help configuring Litle?</a>
</div>

<div style="margin-bottom:5px;">
	Environment
	<select id="litle-environment" name="payment_service[litle][environment]" style="margin-left:10px; font-family:courier; font-size:11px;" onchange="litleEnvChangeHandler();">
		<option value="<?php echo MM_LitleService::$ENVIRONMENT_SANDBOX?>">Sandbox</option>
		<option value="<?php echo MM_LitleService::$ENVIRONMENT_PRELIVE?>">Pre-Live</option>
		<option value="<?php echo MM_LitleService::$ENVIRONMENT_PRODUCTION?>">Production</option>
		<option value="<?php echo MM_LitleService::$ENVIRONMENT_POSTLIVE?>">Post-Live</option>
	</select>
</div>

<div id="litle-url-type-section" style="margin-bottom:10px; display:none;">
	<?php 
		$urlTypeDesc = "Litle offers two ways to connect to each environment. In order to use the Whitelisted connection type, you'll need to work with Litle to whitelist your server's IP address. If you don't want to or can't whitelist the IP address of your server then you'll need to use the Open connection type. When using the Open connection type your credentials will automatically be reset by Litle every 3 months.";
	?>
	Connection Type<?php echo MM_Utils::getInfoIcon($urlTypeDesc); ?>
	<select id="litle-url-type" name="payment_service[litle][url_type]" style="margin-left:10px; font-family:courier; font-size:11px;">
		<option value="<?php echo MM_LitleService::$URL_TYPE_OPEN?>">Open</option>
		<option value="<?php echo MM_LitleService::$URL_TYPE_WHITELISTED?>">Whitelisted</option>
	</select>
	
	<p>
		Report Group (<em>optional</em>) 
		<input type='text' value='<?php echo $p->getReportGroup(); ?>' id='litle_report_group' name='payment_service[litle][report_group]' style='width: 160px; font-family:courier; font-size:11px;' />
	</p>
</div>

<div id="litle-test-info" style="margin-bottom:10px; margin-left:10px;">
	<div style="margin-bottom:5px;">
		<?php echo MM_Utils::getIcon('credit-card', 'blue', '1.3em', '1px', "Test Credit Card Numbers", "margin-right:3px;"); ?> 
		<a href="javascript:showLitleTestCardNumbers()">Test Credit Card Numbers</a>
	</div>
	<div>
		<?php echo MM_Utils::getIcon('flask', 'blue', '1.3em', '1px', 'Setup Test Data', "margin-right:3px;"); ?> 
		<a href="<?php echo MM_ModuleUtils::getUrl(MM_ModuleUtils::getPage(), MM_MODULE_TEST_DATA); ?>" target="_blank">Configure Test Data</a>
	</div>
</div>

<div id="litle-credentials-section">
<div style="margin-bottom:10px;">
	<span>Merchant ID</span>
	
	<p style="margin-left:10px; font-family:courier; font-size:11px;">
		<input type='text' value='<?php echo $p->getPreLiveMerchantId(); ?>' id='litle_prelive_merchant_id' name='payment_service[litle][prelive_merchant_id]' style='width: 275px;' />
	</p>
	<p style="margin-left:10px; font-family:courier; font-size:11px;">
		<input type='text' value='<?php echo $p->getProdMerchantId(); ?>' id='litle_prod_merchant_id' name='payment_service[litle][prod_merchant_id]' style='width: 275px;' />
	</p>
	<p style="margin-left:10px; font-family:courier; font-size:11px;">
		<input type='text' value='<?php echo $p->getPostLiveMerchantId(); ?>' id='litle_postlive_merchant_id' name='payment_service[litle][postlive_merchant_id]' style='width: 275px;' />
	</p>
</div>

<div style="margin-bottom:10px;">
	<span>XML Username</span>
	
	<p style="margin-left:10px; font-family:courier; font-size:11px;">
		<input type='text' value='<?php echo $p->getPreLiveUsername(); ?>' id='litle_prelive_username' name='payment_service[litle][prelive_username]' style='width: 275px;' />
	</p>
	<p style="margin-left:10px; font-family:courier; font-size:11px;">
		<input type='text' value='<?php echo $p->getProdUsername(); ?>' id='litle_prod_username' name='payment_service[litle][prod_username]' style='width: 275px;' />
	</p>
	<p style="margin-left:10px; font-family:courier; font-size:11px;">
		<input type='text' value='<?php echo $p->getPostLiveUsername(); ?>' id='litle_postlive_username' name='payment_service[litle][postlive_username]' style='width: 275px;' />
	</p>
</div>

<div style="margin-bottom:10px;">
	<span>XML Password</span>
	
	<p style="margin-left:10px; font-family:courier; font-size:11px;">
		<input type='text' value='<?php echo $p->getPreLivePassword(); ?>' id='litle_prelive_password' name='payment_service[litle][prelive_password]' style='width: 275px;' />
	</p>
	<p style="margin-left:10px; font-family:courier; font-size:11px;">
		<input type='text' value='<?php echo $p->getProdPassword(); ?>' id='litle_prod_password' name='payment_service[litle][prod_password]' style='width: 275px;' />
	</p>
	<p style="margin-left:10px; font-family:courier; font-size:11px;">
		<input type='text' value='<?php echo $p->getPostLivePassword(); ?>' id='litle_postlive_password' name='payment_service[litle][postlive_password]' style='width: 275px;' />
	</p>
</div>
</div>
</div>