<div style="padding:10px;">
<img src="https://dl.dropboxusercontent.com/u/265387542/plugin_images/logos/coinbase.png" />

<div style="margin-bottom:10px;">
	<span id="coinbase-creds-label">API Credentials</span>
	<p style="margin-left:10px; font-family:courier; font-size:11px;">
		<label for="coinbase_api_key" style="display:block; float:left; width:15em;">API Key</label>
		<input type="text" value="<?php echo $p->getAPIKey(); ?>" id="coinbase_api_key" name="payment_service[coinbase][api_key]" style="width: 380px;" /><br/>
		<label for="coinbase_api_secret" style="display:block; float:left; width:15em;">API Secret</label>
		<input type="text" value="<?php echo $p->getAPISecret(); ?>" id="coinbase_api_secret" name="payment_service[coinbase][api_secret]" style="width: 380px;" /><br/>
	</p>
	
	<p style="font-size:11px; margin-left:10px; padding-right:20px;">
		<?php echo MM_Utils::getInfoIcon("", ""); ?>
		Coinbase API credentials allow Bitcoin payments via Coinbase to be offered as a payment option when checking out. You can create an API key and secret at <a href="https://coinbase.com/account/api">https://coinbase.com/account/api</a>.
		You should enable "merchant", "buttons", "orders", and "recurring payments" in the list of available permissions when creating the API key.
	</p>
</div>

<div style="margin-bottom:10px;">
	Notification URL
	
	<p style="margin-left:10px;">
		<?php $ipnUrl = MM_PLUGIN_URL."/x.php?service=coinbase&hash=".wp_hash("coinbase_ipn_auth"); ?>		 
		<span style="font-family:courier; font-size:11px; margin-top:5px;">
			 <input id="mm-coinbase-ipn-url" type="text" value="<?php echo $ipnUrl; ?>" style="width:550px; background-color:#fff;" readonly onclick="jQuery('#mm-coinbase-ipn-url').focus(); jQuery('#mm-coinbase-ipn-url').select();" />
		</span>
	</p>
	
	<p style="font-size:11px; margin-left:10px; padding-right:20px;">
		<?php echo MM_Utils::getInfoIcon("", ""); ?>
		Coinbase uses <abbr title="Instant Payment Notifications">IPNs</abbr> to inform 3rd party systems when events happen within Coinbase
		such as successful payments, subscription cancellations, etc. MemberMouse keeps member accounts in sync with Coinbase by listening for these notifications.
		In order for this to work, <strong style="background:#FE9;">you must copy the notification URL above and enter it as your Coinbase callback URL</strong>
		at <a href="https://coinbase.com/merchant_settings">https://coinbase.com/merchant_settings</a>.
	</p>
</div>

</div>
