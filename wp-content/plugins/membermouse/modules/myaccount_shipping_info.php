<?php 
/**
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */

global $current_user;

$user = new MM_User($current_user->ID);
?>

<div id="mm-form-container">
<table>
	<tr>
		<td><span class="mm-myaccount-dialog-label">Address</span></td>
		<td><input id="mm-shipping-address" type="text" class="mm-myaccount-form-field" value="<?php echo $user->getShippingAddress(); ?>"/></td>
	</tr>
	<tr>
		<td><span class="mm-myaccount-dialog-label">City</span></td>
		<td><input id="mm-shipping-city" type="text" class="mm-myaccount-form-field" value="<?php echo $user->getShippingCity(); ?>"/></td>
	</tr>
	<tr>
		<td><span class="mm-myaccount-dialog-label">State</span></td>
		<td><input id="mm-shipping-state" type="text" class="mm-myaccount-form-field" value="<?php echo $user->getShippingState(); ?>"/></td>
	</tr>
	<tr>
		<td><span class="mm-myaccount-dialog-label">Zip Code</span></td>
		<td><input id="mm-shipping-zip-code" type="text" class="mm-myaccount-form-field" value="<?php echo $user->getShippingZipCode(); ?>"/></td>
	</tr>
	<tr>
		<td><span class="mm-myaccount-dialog-label">Country</span></td>
		<td><select id="mm-shipping-country"><?php echo MM_HtmlUtils::getCountryList($user->getShippingCountry()); ?></select></td>
	</tr>
</table>
</div>

<div class="mm-dialog-footer-container">
<div class="mm-dialog-button-container">
<a href="javascript:myaccount_js.updateMemberData(<?php echo $user->getId(); ?>, 'shipping-info');" class="mm-ui-button blue">Update</a>
<a href="javascript:myaccount_js.closeDialog();" class="mm-ui-button">Cancel</a>
</div>
</div>