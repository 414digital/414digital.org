
<style>
.mm-payment-service-box {
  margin-bottom: 5px;
}
</style>

<div id="mm-test-data">
<form action="<?php echo MM_Utils::constructPageUrl(); ?>" method="post">
<?php 
	$fieldInfo = array();
	$field = new stdClass();
	$field->fieldId = "mm_field_first_name";
	$field->fieldName = "firstname";
	$field->label = "FirstName";
	$fieldInfo[] = $field;
	
	$field = new stdClass();
	$field->fieldId = "mm_field_last_name";
	$field->fieldName = "lastname";
	$field->label = "Last Name";
	$fieldInfo[] = $field;
	
	$field = new stdClass();
	$field->fieldId = "mm_field_email";
	$field->fieldName = "email";
	$field->label = "Email";
	$fieldInfo[] = $field;
	
	$field = new stdClass();
	$field->fieldId = "mm_field_username";
	$field->fieldName = "username";
	$field->label = "Username";
	$fieldInfo[] = $field;
	
	$field = new stdClass();
	$field->fieldId = "mm_field_password";
	$field->fieldName = "password";
	$field->label = "Password";
	$fieldInfo[] = $field;
	
	$field = new stdClass();
	$field->fieldId = "mm_field_phone";
	$field->fieldName = "phone";
	$field->label = "Phone";
	$fieldInfo[] = $field;
	
	$field = new stdClass();
	$field->fieldId = "mm_field_billing_address";
	$field->fieldName = "billingaddress";
	$field->label = "Billing Address";
	$fieldInfo[] = $field;
	
	$field = new stdClass();
	$field->fieldId = "mm_field_billing_city";
	$field->fieldName = "billingcity";
	$field->label = "Billing City";
	$fieldInfo[] = $field;
	
	$field = new stdClass();
	$field->fieldId = "mm_field_billing_state";
	$field->fieldName = "billingstate";
	$field->label = "Billing State";
	$fieldInfo[] = $field;
	
	$field = new stdClass();
	$field->fieldId = "mm_field_billing_zip";
	$field->fieldName = "billingzipcode";
	$field->label = "Billing Zip Code";
	$fieldInfo[] = $field;
	
	$field = new stdClass();
	$field->fieldId = "mm_field_billing_country";
	$field->fieldName = "billingcountry";
	$field->label = "Billing Country";
	$fieldInfo[] = $field;
	
	$field = new stdClass();
	$field->fieldId = "mm_field_shipping_address";
	$field->fieldName = "shippingaddress";
	$field->label = "Shipping Address";
	$fieldInfo[] = $field;
	
	$field = new stdClass();
	$field->fieldId = "mm_field_shipping_city";
	$field->fieldName = "shippingcity";
	$field->label = "Shipping City";
	$fieldInfo[] = $field;
	
	$field = new stdClass();
	$field->fieldId = "mm_field_shipping_state";
	$field->fieldName = "shippingstate";
	$field->label = "Shipping State";
	$fieldInfo[] = $field;
	
	$field = new stdClass();
	$field->fieldId = "mm_field_shipping_zip";
	$field->fieldName = "shippingzipcode";
	$field->label = "Shipping Zip Code";
	$fieldInfo[] = $field;
	
	$field = new stdClass();
	$field->fieldId = "mm_field_shipping_country";
	$field->fieldName = "shippingcountry";
	$field->label = "Shipping Country";
	$fieldInfo[] = $field;
	
	$field = new stdClass();
	$field->fieldId = "mm_field_cc_number";
	$field->fieldName = "ccnumber";
	$field->label = "CC Number";
	$fieldInfo[] = $field;
	
	$field = new stdClass();
	$field->fieldId = "mm_field_cc_exp_month";
	$field->fieldName = "ccexpirationdate";
	$field->label = "CC Exp. Date";
	$fieldInfo[] = $field;
	
	$field = new stdClass();
	$field->fieldId = "mm_field_cc_exp_year";
	$field->fieldName = "ccexpirationdate";
	$field->label = "CC Exp. Date";
	$fieldInfo[] = $field;
	
	$field = new stdClass();
	$field->fieldId = "mm_field_cc_cvv";
	$field->fieldName = "ccsecuritycode";
	$field->label = "CC Security Code";
	$fieldInfo[] = $field;
	
	if(isset($_POST["mm_use_test_data"]))
	{
		MM_OptionUtils::setOption(MM_OptionUtils::$OPTION_KEY_USE_CHECKOUT_FORM_TEST_DATA, $_POST["mm_use_test_data"]);
		
		if($_POST["mm_use_test_data"] == "1")
		{
			// collect/store form information
			$data = array();
			
			foreach($fieldInfo as $field)
			{
				$data[$field->fieldId] = $_POST[$field->fieldId];
			}
			
			MM_TestDataUtils::saveCheckoutFormTestData($data);
		}
	}
	
	$useTestData = MM_OptionUtils::getOption(MM_OptionUtils::$OPTION_KEY_USE_CHECKOUT_FORM_TEST_DATA);
	$testData = MM_TestDataUtils::getCheckoutFormTestData();

	$form = new MM_CheckoutForm();
	if(!isset($testData["mm_field_shipping_country"])) 
	{
		$testData["mm_field_shipping_country"] = "";
	}
	$form->shippingCountryList = MM_HtmlUtils::getCountryList($testData["mm_field_shipping_country"]);	
	
	if(!isset($testData["mm_field_billing_country"]))
	{
		$testData["mm_field_billing_country"] = "";
	}
	$form->billingCountryList = MM_HtmlUtils::getCountryList($testData["mm_field_billing_country"]);
	
	$ccExpDateRendered = false;
	
	echo $form->getJavascriptIncludes();
?>

<p style="width:650px">
	By filling out the test data below you're instructing MemberMouse to prepopulate checkout forms with the data entered. This can be 
	very helpful when you're doing testing as it means you don't have to manually type in data for each test. 
</p>
<p>
	<input id="mm_use_test_data_cb" type="checkbox" <?php echo (($useTestData=="1")?"checked":""); ?> onchange="toggleTestDataForm();" />
	Use Test Data
	<input id="mm_use_test_data" name="mm_use_test_data" type="hidden" value="<?php echo $useTestData; ?>" />
</p>
<div id="mm-test-data-form" class='mm-payment-service-box' style="display:none; margin-left: 10px; border: 1px solid #eee; background-color: #eee; width:625px; padding-left:10px;">
<table>
	<?php 
		foreach($fieldInfo as $field) 
		{ 	
			if(($field->fieldName != "ccexpirationdate") || ($field->fieldName == "ccexpirationdate" && !$ccExpDateRendered))
			{
	?>
	<tr>
		<td width="110"><?php echo $field->label; ?></td>
		<td>
		<span style="font-family:courier; font-size:11px; margin-top:5px;">
			<?php 
				if(!isset($testData[$field->fieldId]))
				{
					$testData[$field->fieldId] = "";
				}
				
				echo MM_CheckoutForm::generateInputFormField($field->fieldName, $testData, $form);
				
				if($field->fieldName == "ccexpirationdate")
				{
					$ccExpDateRendered = true;
				}
				else if($field->fieldName == "email")
				{
					echo MM_Utils::getInfoIcon("Click for more information", "", "javascript:showEmailInfo();");
				}
				else if($field->fieldName == "username")
				{
					echo MM_Utils::getInfoIcon("Enter the base username to use for test accounts. MemberMouse will automatically add a series of numbers to the end of the base username to ensure that it's unique.", "");	
				}
			?>
		</span>
		</td>
	</tr>
	<?php 
			}
		} 
	?>
</table>
</div>
<p><input type='submit' name='submit' value='Save Test Data' class="mm-ui-button blue" /></p>
</form>
</div>

<div id="mm-email-test-data-info" style="display:none;" title="Test Email Creation" style="font-size:11px;">
<p>When creating accounts in MemberMouse, email addresses need to be unique so when you go to a checkout form,
MemberMouse automatically modifies the test email you provide here to ensure it's unique. The result is that when you type in 
<code>test@gmail.com</code> here, when you go to a checkout form something like <code>test<?php echo MM_TestDataUtils::$UNIQUE_TEST_EMAIL_DELIMITER; ?>123@gmail.com</code> 
will be populated in the email field.</p>

<p class="mm-section-header">Using a Gmail Account: One Email Address, Unlimited Tests</p>
<p>Another important part of testing is using a valid email address so you can receive and verify system-generated 
emails such as welcome emails. In order to accomplish this, when creating accounts MemberMouse replaces the 
<code><?php echo MM_TestDataUtils::$UNIQUE_TEST_EMAIL_DELIMITER; ?></code> in the email address with a <code>+</code>. 
When you use a valid Gmail account this allows you to have unlimited unique email addresses for testing without having 
to use more then one real email account to receive all the emails. This is because with Gmail, if you have a valid email address 
<code>test@gmail.com</code> then sending emails to <code>test+1@gmail.com</code>, <code>test+2@gmail.com</code>, 
<code>test+146@gmail.com</code>, etc will result in delivering the emails to your <code>test@gmail.com</code> account.</p>

<p>NOTE: Usually MemberMouse uses a member's email as their username, but WordPress doesn't allow the <code>+</code> character to be 
used in usernames. As a result, when you're using test data, MemberMouse will end up creating an account with a username of 
<code>test<?php echo MM_TestDataUtils::$UNIQUE_TEST_EMAIL_DELIMITER; ?>123@gmail.com</code> and an email of <code>test+123@gmail.com</code>. 
MemberMouse will only automaically adjust email addresses like this when creating accounts when you've elected to use test data.</p>
</div>

<script>
	function toggleTestDataForm()
	{
		if(jQuery("#mm_use_test_data_cb").is(":checked"))
		{
			jQuery("#mm_use_test_data").val("1");
			jQuery("#mm-test-data-form").show();
		}
		else
		{
			jQuery("#mm_use_test_data").val("0");
			jQuery("#mm-test-data-form").hide();
		}
	}

	function showEmailInfo()
	{
		jQuery("#mm-email-test-data-info").show();
		jQuery("#mm-email-test-data-info").dialog({autoOpen: true, width: "650", height: "450"});
	}

	toggleTestDataForm();
</script>

<?php echo $form->getInitJavascript($testData); ?>

<?php if(isset($_POST["mm_use_test_data"])) { ?>
<script>alert("Test data settings saved successfully");</script>
<?php } ?>