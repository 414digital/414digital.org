<?php 
/**
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */

$fieldInfo = array();

$field = new stdClass();
$field->fieldId = "mm_field_cc_number";
$field->fieldName = "ccnumber";
$field->label = "Credit Card";
$fieldInfo[] = $field;

$field = new stdClass();
$field->fieldId = "mm_field_cc_exp_month";
$field->fieldName = "ccexpirationdate";
$field->label = "Exp. Date";
$fieldInfo[] = $field;

$field = new stdClass();
$field->fieldId = "mm_field_cc_exp_year";
$field->fieldName = "ccexpirationdate";
$field->label = "Exp. Date";
$fieldInfo[] = $field;

$field = new stdClass();
$field->fieldId = "mm_field_cc_cvv";
$field->fieldName = "ccsecuritycode";
$field->label = "Security Code";
$fieldInfo[] = $field;
$field = new stdClass();
$field->fieldId = "mm_field_billing_address";
$field->fieldName = "billingaddress";
$field->label = "Address";
$fieldInfo[] = $field;

$field = new stdClass();
$field->fieldId = "mm_field_billing_city";
$field->fieldName = "billingcity";
$field->label = "City";
$fieldInfo[] = $field;

$field = new stdClass();
$field->fieldId = "mm_field_billing_state";
$field->fieldName = "billingstate";
$field->label = "State";
$fieldInfo[] = $field;

$field = new stdClass();
$field->fieldId = "mm_field_billing_zip";
$field->fieldName = "billingzipcode";
$field->label = "Zip Code";
$fieldInfo[] = $field;

$field = new stdClass();
$field->fieldId = "mm_field_billing_country";
$field->fieldName = "billingcountry";
$field->label = "Country";
$fieldInfo[] = $field;


$orderItem = new MM_OrderItem($p->orderItemId);
$order = new MM_Order($orderItem->getOrderId());
$billingAddress = $order->getBillingAddress();


$data = array();
	
$data["mm_field_billing_address"] = $billingAddress->getAddressLine1();
$data["mm_field_billing_city"] = $billingAddress->getCity();
$data["mm_field_billing_state"] = $billingAddress->getState();
$data["mm_field_billing_zip"] = $billingAddress->getPostalCode();
$data["mm_field_billing_country"] = $billingAddress->getCountry();

$form = new MM_CheckoutForm();

if(!isset($data["mm_field_billing_country"]))
{
	$data["mm_field_billing_country"] = "";
}
$form->billingCountryList = MM_HtmlUtils::getCountryList($data["mm_field_billing_country"]);

$ccExpDateRendered = false;

echo $form->getJavascriptIncludes(false);
?>

<div id="mm-form-container">
<input type="hidden" id="mm-order-item-id" value="<?php echo $p->orderItemId; ?>" />
<table>
<?php 
	foreach($fieldInfo as $field) 
	{ 	
		if(($field->fieldName != "ccexpirationdate") || ($field->fieldName == "ccexpirationdate" && !$ccExpDateRendered))
		{
?>
<tr>
	<td><span class="mm-myaccount-dialog-label"><?php echo $field->label; ?></span></td>
	<td>
	<span style="font-family:courier; font-size:11px; margin-top:5px;">
		<?php 
			if(!isset($data[$field->fieldId]))
			{
				$data[$field->fieldId] = "";
			}
			
			echo MM_CheckoutForm::generateInputFormField($field->fieldName, $data, $form, "mm-myaccount-form-field");
			
			if($field->fieldName == "ccexpirationdate")
			{
				$ccExpDateRendered = true;
			}
		?>
	</span>
	</td>
</tr>
<?php if($field->fieldName == "ccsecuritycode") { ?>
<tr><td>&nbsp;</td></tr>
<?php } ?>
<?php 
		}
	} 
?>
</table>
</div>

<div class="mm-dialog-footer-container">
<div class="mm-dialog-button-container">
<a href="javascript:myaccount_js.updateSubscriptionBilling();" class="mm-ui-button blue">Update</a>
<a href="javascript:myaccount_js.closeDialog();" class="mm-ui-button">Cancel</a>
</div>
</div>
<?php echo $form->getInitJavascript($data); ?>