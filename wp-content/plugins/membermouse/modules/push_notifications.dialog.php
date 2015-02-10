<?php 
$memberFilePath = MM_TEMPLATE_BASE."/member_notification_script.php";
$bundleFilePath = MM_TEMPLATE_BASE."/bundle_notification_script.php";
$paymentFilePath = MM_TEMPLATE_BASE."/payment_notification_script.php";
$action = new MM_Action($p->id);
$actionValue = $action->getActionValue();
$eventAttributes = $action->getEventAttributes();
$status = $action->getStatus();

// set defaults
$params = array("scriptUrl", "emailToId", "emailFromId", "emailCC", "emailBody", "emailSubject");
foreach($params as $key)
{
	if(!isset($actionValue[$key]))
	{
		if($key == "emailToId")
		{
			$actionValue[$key] = MM_Action::$CURRENT_MEMBER_PLACEHOLDER;
		}
		else
		{
			$actionValue[$key] = "";
		}
	}
}
?>
<script>
function insertMemberInfoTemplate()
{
	var str = "New Member Signup ([MM_Member_Data name='email'])";
	jQuery("#mm-send-email-subject").val(str);
	
	var str = "Congratulations! A new member has just signed up.\n\n";

	str += "Here are their details:\n";
	str += "ID: [MM_Member_Data name='id']\n";
	str += "Name: [MM_Member_Data name='firstName'] [MM_Member_Data name='lastName']\n";
	str += "Email: [MM_Member_Data name='email']\n";
	str += "Membership Level: [MM_Member_Data name='membershipName']\n";
	str += "Status: [MM_Member_Data name='statusName']";
	
	insertTemplate(str);
}

function insertOrderReceiptTemplate()
{
	var str = "Thank you for your order (#[MM_Order_Data name='id'])";
	jQuery("#mm-send-email-subject").val(str);
	
	var str = "Hi [MM_Member_Data name='firstName'],\n\n";

	str += "Thank you for your purchase!\n\n";
	
	str += "Here are your order details:\n";
	str += "Order ID: [MM_Order_Data name='id']\n";
	str += "Product Name: [MM_Order_Data name='productName']\n";
	str += "Subtotal: [MM_Order_Data name='subtotal' doFormat='true']\n";
	str += "Discount: [MM_Order_Data name='discount' doFormat='true']\n";
	str += "Shipping: [MM_Order_Data name='shipping' doFormat='true']\n";
	str += "Order Total: [MM_Order_Data name='total' doFormat='true']\n\n";
	
	str += "Billing Address:\n";
	str += "[MM_Order_Data name='billingAddress']\n";
	str += "[MM_Order_Data name='billingCity'], [MM_Order_Data name='billingState'] [MM_Order_Data name='billingZipCode']\n";
	str += "[MM_Order_Data name='billingCountry']\n\n";

	str += "Shipping Address:\n";
	str += "[MM_Order_Data name='shippingAddress']\n";
	str += "[MM_Order_Data name='shippingCity'], [MM_Order_Data name='shippingState'] [MM_Order_Data name='shippingZipCode']\n";
	str += "[MM_Order_Data name='shippingCountry']\n\n";

	str += "Your order will be shipped via [MM_Order_Data name='shippingMethod']\n\n";
	str += "If you have any questions concerning your order, feel free to contact us at [MM_Employee_Data name='email'].";
	
	insertTemplate(str);
}

function insertOverduePaymentTemplate()
{
	var str = "Your Account Is Past Due";
	jQuery("#mm-send-email-subject").val(str);
	
	var str = "[MM_Member_Data name='firstName'],\n\n";

	str += "Your recent payment was declined.\n\n";
	
	str += "Please update your billing information to reactivate your account.\n";
	str += "To update your credit card details, please click the link below:\n\n";
	
	str += "[MM_CorePage_Link type='myaccount' autoLogin='true']\n\n";
	
	str += "Thank you for your prompt attention to this matter.";
	
	insertTemplate(str);
}

function insertTemplate(str)
{
	jQuery("#mm-send-email-body").val(str);
}
</script>

<div id="mm-form-container">
<div id="mm-status-container" style="margin-top:5px;">
<span class="mm-section-header">Status</span> 
<input type="radio" name="status" value="1" onclick="mmjs.statusChangeHandler()" <?php echo (($status == "1") ? "checked" : ""); ?> /> Active
<input type="radio" name="status" value="0" onclick="mmjs.statusChangeHandler()" <?php echo (($status == "0") ? "checked" : ""); ?> /> Inactive
<input id="mm-action-status" type="hidden" value="<?php echo $status; ?>" />
</div>

<input type='hidden' id='mm-action-id' value='<?php echo $action->getId(); ?>' />
<p><span class="mm-section-header">When the following event occurs...</span></p>
<p style="margin-left:20px;">
	<select id="mm-event-type" onchange="mmjs.eventChangeHandler();">
	<?php echo MM_HtmlUtils::getEventTypesList($action->getEventType()); ?>
	</select>
</p>

<!-- Insert second tier options for events -->
<div id="mm_member_add_attributes" style="display: none;">
	<p style="margin-left: 20px;">
	When membership level is...<br/>
	<select id="mm-member-add-selector">
		<option value="">Any Membership Level</option>
	<?php 
		$membershipLevelId = "";
		
		if(is_array($eventAttributes) && isset($eventAttributes["membership_level_id"]))
		{
			$membershipLevelId = $eventAttributes["membership_level_id"];
		}
		echo MM_HtmlUtils::getMemberships($membershipLevelId);
	?>
	</select>
	</p>
</div>

<div id="mm_member_status_change_attributes" style="display: none;">
	<p style="margin-left: 20px;">
	When account status is...<br/>
	<select id="mm-member-status-selector">
		<option value="">Any Status</option>
	<?php 
		$statusId = "";
		
		if(is_array($eventAttributes) && isset($eventAttributes["status_id"]))
		{
			$statusId = $eventAttributes["status_id"];
		}
		echo MM_HtmlUtils::generateSelectionsList(MM_Status::getStatusTypesList(), $statusId);
	?>
	</select>
	</p>
</div>

<div id="mm_bundles_add_attributes" style="display: none;">
	<p style="margin-left: 20px;">
	When bundle is...<br/>
	<select id="mm-bundle-add-selector">
		<option value="">Any Bundle</option>
	<?php 
		$bundleId = "";
		
		if(is_array($eventAttributes) && isset($eventAttributes["bundle_id"]))
		{
			$bundleId = $eventAttributes["bundle_id"];
		}
		echo MM_HtmlUtils::getBundles($bundleId);
	?>
	</select>
	</p>
</div>

<div id="mm_bundles_status_change_attributes" style="display: none;">
	<p style="margin-left: 20px;">
	When bundle status is...<br/>
	<select id="mm-bundle-status-selector">
		<option value="">Any Status</option>
	<?php 
		$statusId = "";
		
		if(is_array($eventAttributes) && isset($eventAttributes["status_id"]))
		{
			$statusId = $eventAttributes["status_id"];
		}
		echo MM_HtmlUtils::generateSelectionsList(MM_Status::getStatusTypesList(), $statusId);
	?>
	</select>
	</p>	
</div>


<p><span class="mm-section-header">Perform the following action...</span></p>
<p style="margin-left:20px;">
	<select id="mm-action-type" onchange="mmjs.actionChangeHandler();">
	<?php echo MM_HtmlUtils::getActionsList($action->getActionType()); ?>
	</select>
</p>
	
<div id="mm-action-call-script" style="display:none; margin-left:20px;">
<p style="font-size:11px;">
	Enter the URL of your custom script below:
</p>
<input type='text' id='mm-script-url' value='<?php echo $actionValue["scriptUrl"]; ?>'  style="width:400px; font-family:courier !important; font-size:11px;" />
<p style="font-size:11px;">
	When the event selected above occurs, MemberMouse will call the script passing an event type and any relevant data. 
	Download the sample scripts below to see how to respond to different events and access the data passed.
</p>
<p>
	<a href="https://dl.dropboxusercontent.com/u/265387542/files/member_notification_script.php" class="mm-ui-button">
		<?php echo MM_Utils::getIcon('user', '', '1.3em', '1px'); ?> Member Notification Script
	</a>
	<span style="margin-left:10px;"></span>
	<a href="https://dl.dropboxusercontent.com/u/265387542/files/bundle_notification_script.php" class="mm-ui-button">
		<?php echo MM_Utils::getIcon('cube', '', '1.3em', '1px'); ?> Bundle Notification Script
	</a>
</p>
<p>
	<a href="https://dl.dropboxusercontent.com/u/265387542/files/payment_notification_script.php" class="mm-ui-button">
		<?php echo MM_Utils::getIcon('money', '', '1.3em', '1px'); ?> Payment Notification Script
	</a>
	<span style="margin-left:10px;"></span>
	<a href="https://dl.dropboxusercontent.com/u/265387542/files/affiliate_notification_script.php" class="mm-ui-button">
		<?php echo MM_Utils::getIcon('bullhorn', '', '1.3em', '1px'); ?> Affiliate Notification Script
	</a>
</p>

</div>

<div id="mm-action-send-email" style="display:none; margin-left:20px;">
	<div>
		To
		<select id="mm-send-email-to" class="medium-text" style="margin-left:19px;">
		<option value="<?php echo MM_Action::$CURRENT_MEMBER_PLACEHOLDER; ?>" <?php echo ($actionValue["emailToId"] == MM_Action::$CURRENT_MEMBER_PLACEHOLDER) ? "selected":""; ?>>Current Member</option>
		<?php echo MM_HtmlUtils::getEmployees($actionValue["emailToId"]); ?>
		</select>
	</div>
	
	<div>
		From
		<select id="mm-send-email-from" class="medium-text">
		<?php echo MM_HtmlUtils::getEmployees($actionValue["emailFromId"]); ?>
		</select>
	</div>
	
	<div>
		CC<?php echo MM_Utils::getInfoIcon("Enter emails addresses to CC separated by commas"); ?>
		<input id="mm-send-email-cc" type="text" style="width:295px; font-family:courier; font-size:11px;" value="<?php echo $actionValue["emailCC"]; ?>"/>
	</div>

	<div style="margin-top:5px">
		Subject*
		<input id="mm-send-email-subject" type="text" style="width:386px; font-family:courier; font-size:11px;" value="<?php echo $actionValue["emailSubject"]; ?>"/>
	</div>
	
	<div style="margin-top:5px">
		Body* <?php echo MM_SmartTagLibraryView::smartTagLibraryButtons("mm-send-email-body"); ?>
		<span style="font-size:11px; color:#666666;">
		Insert template: <a href="javascript:insertMemberInfoTemplate();" style="color:#21759B;">Member Added</a>, <a href="javascript:insertOrderReceiptTemplate();" style="color:#21759B;">Order Receipt</a>,
		<a href="javascript:insertOverduePaymentTemplate();" style="color:#21759B;">Account Overdue Notice</a>
		<?php 
			$validSmartTags = "Only the following SmartTags can be used here:\n";
			$validSmartTags .= "[MM_Access_Decision] (you must provide an ID)\n";
			$validSmartTags .= "[MM_Content_Data] (you must provide an ID)\n";
			$validSmartTags .= "[MM_Content_Link] (you must provide an ID)\n";
			$validSmartTags .= "[MM_CorePage_Link]\n";
			$validSmartTags .= "[MM_CustomField_Data]\n";
			$validSmartTags .= "[MM_Employee_Data]\n";
			$validSmartTags .= "[MM_Member_Data]\n";
			$validSmartTags .= "[MM_Member_Decision]\n";
			$validSmartTags .= "[MM_Member_Link]\n";
			$validSmartTags .= "[MM_Order_Data] (only for payment-related events)\n";
			$validSmartTags .= "[MM_Order_Decision] (only for payment-related events)\n";
			$validSmartTags .= "[MM_Purchase_Link]";
		?>
		<br/>
		<em>Note: Only certain SmartTags can be used here</em><?php echo MM_Utils::getInfoIcon($validSmartTags); ?>
		</span>
	</div>
	
	<div style="margin-top:5px">
		<textarea id="mm-send-email-body" style="width:450px; font-family:courier; font-size:11px;" rows="6"><?php echo htmlentities($actionValue["emailBody"], ENT_QUOTES, 'UTF-8', true); ?></textarea>
	</div>
</div>

</div>

<script>
mmjs.eventChangeHandler();
mmjs.actionChangeHandler();
</script>

<div class="mm-dialog-footer-container">
<div class="mm-dialog-button-container">
<a href="javascript:mmjs.save();" class="mm-ui-button blue">Save Push Notification</a>
<a href="javascript:mmjs.closeDialog();" class="mm-ui-button">Cancel</a>
</div>
</div>