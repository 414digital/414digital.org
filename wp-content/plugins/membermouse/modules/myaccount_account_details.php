<?php 
/**
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */

global $current_user;

$user = new MM_User($current_user->ID);
$enableUsernameChange = (MM_OptionUtils::getOption(MM_OptionUtils::$OPTION_KEY_ENABLE_USERNAME_CHANGE) == "1") ? true : false;
?>

<div id="mm-form-container">
<p class="mm-myaccount-dialog-section-header">Account Information</p>
<table>
	<tr>
		<td><span class="mm-myaccount-dialog-label">First Name</span></td>
		<td><input id="mm_first_name" name="mm_first_name" type="text" class="mm-myaccount-form-field" value="<?php echo $user->getFirstName(); ?>"/></td>
	</tr>
	<tr>
		<td><span class="mm-myaccount-dialog-label">Last Name</span></td>
		<td><input id="mm_last_name" name="mm_last_name" type="text" class="mm-myaccount-form-field" value="<?php echo $user->getLastName(); ?>"/></td>
	</tr>
	<tr>
		<td><span class="mm-myaccount-dialog-label">Phone</span></td>
		<td><input id="mm_phone" name="mm_phone" type="text" class="mm-myaccount-form-field" value="<?php echo $user->getPhone(); ?>"/></td>
	</tr>
	<tr>
		<td><span class="mm-myaccount-dialog-label">Email*</span></td>
		<td>
			<input id="mm_email" name="mm_email" type="text" class="mm-myaccount-form-field" value="<?php echo $user->getEmail(); ?>"/>
		</td>
	</tr>
	<?php if($enableUsernameChange) { ?>
	<tr>
		<td><span class="mm-myaccount-dialog-label">Username*</span></td>
		<td>
			<input id="mm_username" name="mm_username" type="text" class="mm-myaccount-form-field"  value="<?php echo $user->getUsername(); ?>"/>
			<input id="mm_original_username" name="mm_original_username" type="hidden" value="<?php echo $user->getUsername(); ?>"/>
		</td>
	</tr>
	<?php } ?>
</table>

<p class="mm-myaccount-dialog-section-header">Change Password</p>
<table>
	<tr>
		<td><span class="mm-myaccount-dialog-label">New Password</span></td>
		<td><input name="mm_new_password" id="mm_new_password" type="password" class="mm-myaccount-form-field" value=""/></td>
	</tr>
	<tr>
		<td><span class="mm-myaccount-dialog-label">Confirm Password</span></td>
		<td><input name="mm_new_password_confirm" id="mm_new_password_confirm" type="password" class="mm-myaccount-form-field" value=""/></td>
	</tr>
</table>

<?php 
	$fields = MM_CustomField::getCustomFieldsList(true);
	
	if(count($fields) > 0)
	{
?>
<p class="mm-myaccount-dialog-section-header">Additional Information</p>
<table>
<?php
	foreach($fields as $id=>$displayName)
	{
		$customField = new MM_CustomField($id);
		$value = $user->getCustomDataByFieldId($id)->getValue();
		
		if($customField->isValid())
		{
?>
	<tr>
		<td>
			<span class="mm-myaccount-dialog-label"><?php echo $customField->getDisplayName(); ?></span>
		</td>
		<td>
		<?php
			$class = "mm-myaccount-field-".$customField->getType();
			echo $customField->draw($value, $class, "mm_custom_");
		?>
		</td>
	</tr>
<?php
 		}
	} 
?>
</table> 
<?php } ?>
</div>

<div class="mm-dialog-footer-container">
<div class="mm-dialog-button-container">
<a href="javascript:myaccount_js.updateMemberData(<?php echo $user->getId(); ?>, 'account-details');" class="mm-ui-button blue">Update</a>
<a href="javascript:myaccount_js.closeDialog();" class="mm-ui-button">Cancel</a>
</div>
</div>