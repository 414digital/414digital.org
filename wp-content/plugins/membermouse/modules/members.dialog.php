<?php 
/**
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */
$memberData = array();
$memberData["mm_field_first_name"] = "";
$memberData["mm_field_last_name"] = "";
$memberData["mm_field_email"] = "";
$memberData["mm_field_phone"] = "";
$memberData["mm_field_password"] = "";

if(MM_OptionUtils::getOption(MM_OptionUtils::$OPTION_KEY_USE_CHECKOUT_FORM_TEST_DATA) == "1")
{
	$testData = MM_TestDataUtils::getCheckoutFormTestData();
	$memberData["mm_field_first_name"] = MM_TestDataUtils::getTestValue($testData, "mm_field_first_name");
	$memberData["mm_field_last_name"] = MM_TestDataUtils::getTestValue($testData, "mm_field_last_name");
	$memberData["mm_field_email"] = MM_TestDataUtils::getTestValue($testData, "mm_field_email", true);
	$memberData["mm_field_password"] = MM_TestDataUtils::getTestValue($testData, "mm_field_password");
	$memberData["mm_field_phone"] = MM_TestDataUtils::getTestValue($testData, "mm_field_phone");
}
?>
<div id="mm-new-member-form-container">
	<table cellspacing="10">
		<tr>
			<td width="120">Membership Level</td>
			<td>
				<select id="mm-new-membership-selector" style="width:300px;">
					<?php echo MM_HtmlUtils::getMemberships(null, true); ?>
				</select>
			</td>
		</tr>
		<tr>
			<td>First Name*</td>
			<td><input id="mm-new-first-name" type="text" size="40" value="<?php echo $memberData["mm_field_first_name"]; ?>" /></td>
		</tr>
		<tr>
			<td>Last Name*</td>
			<td><input id="mm-new-last-name" type="text" size="40" value="<?php echo $memberData["mm_field_last_name"]; ?>" /></td>
		</tr>
		<tr>
			<td>Email*</td>
			<td><input id="mm-new-email" type="text" size="40" value="<?php echo $memberData["mm_field_email"]; ?>" /></td>
		</tr>
		<tr>
			<td>Phone</td>
			<td><input id="mm-new-phone" type="text" size="40" value="<?php echo $memberData["mm_field_phone"]; ?>" /></td>
		</tr>
		<tr>
			<td>Password</td>
			<td><input id="mm-new-password" type="password" size="40" value="<?php echo $memberData["mm_field_password"]; ?>" /></td>
		</tr>
	</table>
</div>

<div class="mm-dialog-footer-container">
<div class="mm-dialog-button-container">
<a href="javascript:mmjs.createMember();" class="mm-ui-button blue">Create Member</a>
<a href="javascript:mmjs.closeDialog();" class="mm-ui-button">Cancel</a>
</div>
</div>