<div style="padding:10px;">
<img src="https://dl.dropboxusercontent.com/u/265387542/plugin_images/logos/coinbase.png" />

<div style="margin-bottom:10px;">
	<span id="coinbaseminimal-creds-label">API Credentials</span>
	<p style="margin-left:10px; font-family:courier; font-size:11px;">
		<label for="coinbaseminimal_api_key" style="display:block; float:left; width:15em;">API Key</label>
		<input type="text" value="<?php echo $p->getAPIKey(); ?>" id="coinbaseminimal_api_key" name="payment_service[coinbaseminimal][api_key]" style="width: 380px;" /><br/>
		<label for="coinbaseminimal_api_secret" style="display:block; float:left; width:15em;">API Secret</label>
		<input type="text" value="<?php echo $p->getAPISecret(); ?>" id="coinbaseminimal_api_secret" name="payment_service[coinbaseminimal][api_secret]" style="width: 380px;" /><br/>
	</p>
	
	<p style="font-size:11px; margin-left:10px; padding-right:20px;">
		<?php echo MM_Utils::getInfoIcon("", ""); ?>
		This version of the Coinbase gateway allows customers to purchase recurring products directly from any Bitcoin wallet without needing to have a Coinbase account/wallet.
		Payments will still be handled behind the scenes by Coinbase, and you, the merchant, will still receive payments into your Coinbase wallet, so you'll need to create a Coinbase API
		key and secret at <a href="https://coinbase.com/account/api">https://coinbase.com/account/api</a> (can be the same as the normal Coinbase gateway above if you have that enabled).
		You should enable "merchant", "buttons", "orders", and "recurring payments" in the list of available permissions when creating the API key.
	</p>
</div>

<div style="margin-bottom:10px;">
	<div>
	<?php foreach(array(0,1,2) as $emailNum): ?>
	<span id="coinbaseminimal-reminder-label-<?php echo $emailNum; ?>" style="display:block; padding:4px 8px; background:#CCC; cursor:pointer; margin: 2px 0;" onclick="jQuery('#coinbaseminimal-reminder-label-<?php echo $emailNum; ?>').nextUntil('span').slideToggle();">
		Payment Reminder Email #<?php echo $emailNum+1; ?> (click to edit)
	</span>
	<p style="margin-left:10px; font-size:11px; display:none;">
		<label for="coinbaseminimal_reminder_time_before_<?php echo $emailNum; ?>" style="display:block; float:left; width:15em;">Number of days notice</label>
		<input type="text" value="<?php echo $p->getReminderTimeBefore($emailNum); ?>" id="coinbaseminimal_reminder_time_before_<?php echo $emailNum; ?>" name="payment_service[coinbaseminimal][reminder_time_before][<?php echo $emailNum; ?>]" style="width: 55px; font-family: courier;" /><br/>
	</p>	
	<p style="font-size:11px; margin-left:10px; padding-right:20px; display:none;">
		<?php echo MM_Utils::getInfoIcon("", ""); ?>
		This setting controls how many days before their expiry date they will be notified by email (with a wallet address to send to) that their payment is due. To send no reminder, set this to 0.
	</p>
	<p style="margin-left:10px; font-size:11px; display:none;">
		<label for="coinbaseminimal_reminder_subject_<?php echo $emailNum; ?>" style="display:block; float:left; width:15em;">Subject of notification</label>
		<input type="text" value="<?php echo $p->getReminderSubject($emailNum); ?>" id="coinbaseminimal_reminder_subject_<?php echo $emailNum; ?>" name="payment_service[coinbaseminimal][reminder_subject][<?php echo $emailNum; ?>]" style="width: 380px; font-family: courier;" /><br/>
	</p>		
	<p style="font-size:11px; margin-left:10px; padding-right:20px; display:none;">
		<?php echo MM_Utils::getInfoIcon("", ""); ?>
		This will be the subject of the notification email. You can use the [MM_Member_Data], [MM_Order_Data] and [MM_Employee_Data] SmartTags. 
	</p>
	
	<p style="margin-left:10px; font-size:11px; display:none;">
		<label for="coinbaseminimal_reminder_text_<?php echo $emailNum; ?>" style="display:block; float:left; width:15em;">Content of notification</label>
		<textarea id="coinbaseminimal_reminder_text_<?php echo $emailNum; ?>" name="payment_service[coinbaseminimal][reminder_text][<?php echo $emailNum; ?>]" style="width: 380px; height:200px; font-family: courier;"><?php echo $p->getReminderText($emailNum); ?></textarea>
	</p>	
	<p style="font-size:11px; margin-left:10px; padding-right:20px; display:none;">
		<?php echo MM_Utils::getInfoIcon("", ""); ?>
		This will be the text/content of the notification email. You can use the [MM_Member_Data], [MM_Order_Data] and [MM_Employee_Data] SmartTags here as well,
		and you MUST use the [MM_Bitcoin_Payment_Details] SmartTag which will output the wallet ID and amount that the user should send.
	</p>
	<?php endforeach; ?>
	</div>	
	<p style="font-size:11px; margin-left:10px; padding-right:20px;">
		<?php echo MM_Utils::getInfoIcon("", ""); ?>
		Since bitcoin is a push, not pull, technology, when paying by straight Bitcoin a customer must initiate their payment for a recurring subscription each time that it is due.
		These reminder emails can be configured to go out to members to ensure that happens.
	</p>
</div>

</div>
