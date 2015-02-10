<?php
/**
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */

if(isset($_REQUEST[MM_Session::$PARAM_USER_ID])) 
{
	$user = new MM_User($_REQUEST[MM_Session::$PARAM_USER_ID]);

	if($user->isValid()) 
	{
		// check to make sure current employee has access to manage this member
		global $current_user;
		$employee = MM_Employee::findByUserId($current_user->ID);
		$allowAccess = true;
		
		if($employee->isValid())
		{
			$allowAccess = $employee->canManageMember($user);
		}
		
		if($allowAccess) 
		{
			include_once MM_MODULES."/details.header.php";
			
			$columnWidth = 125;
			$enableUsernameChange = (MM_OptionUtils::getOption(MM_OptionUtils::$OPTION_KEY_ENABLE_USERNAME_CHANGE) == "1") ? true : false;
?>
<div id="mm-form-container">
	<input type="hidden" id="page" value="<?php echo $_REQUEST["page"];?>"/>
	<input type="hidden" id="module" value="<?php echo $_REQUEST["module"];?>"/>
	<table cellspacing="8">
		<tr>
			<td width="<?php echo $columnWidth; ?>px;">Membership Status</td>
			<td>
				<?php 
				$statusDesc = MM_Status::getImage($user->getStatus())." ";
				
				switch($user->getStatus())
				{
					case MM_Status::$ACTIVE:
					case MM_Status::$OVERDUE:
						$statusDesc .= "Account became ".MM_Status::getName($user->getStatus(), true)." on ".$user->getStatusUpdatedDate(true);
						break;
					
					case MM_Status::$PENDING_CANCELLATION:
						$statusDesc .= "Account is ".MM_Status::getName($user->getStatus(), true)." as of ".$user->getStatusUpdatedDate(true);
						break;
						
					case MM_Status::$CANCELED:
					case MM_Status::$PAUSED:
					case MM_Status::$LOCKED:
						$statusDesc .= "Account was ".MM_Status::getName($user->getStatus(), true)." on ".$user->getStatusUpdatedDate(true);
						break;
						
					case MM_Status::$ERROR:
						$statusDesc .= "An error was encountered when creating this account.";
						break;
						
					case MM_Status::$PENDING_ACTIVATION:
						$statusDesc .= "Account is pending activation";
						break;
						
					case MM_Status::$EXPIRED:
						$statusDesc .= "Account ".MM_Status::getName($user->getStatus(), true)." on ".$user->getStatusUpdatedDate(true);
						break;
				}
				?>
				
				<div style="margin-bottom:5px;">
					<?php echo $statusDesc; ?>
					<a href="?page=<?php echo MM_MODULE_MANAGE_MEMBERS."&module=".MM_MODULE_MEMBER_DETAILS_ACTIVITY_LOG."&event_type=".MM_ActivityLog::$EVENT_TYPE_ACCESS_RIGHTS."&user_id=".$user->getId(); ?>" style="font-size:11px;">view details</a>
				</div>
				<?php if($user->isComplimentary()) { ?>
				<div style="margin-bottom:5px;">
					<?php echo MM_Utils::getIcon('ticket', 'purple', '1.4em', '1px', "Membership is complimentary"); ?>
					This account is complimentary
				</div>
				<?php } ?>
			</td>
		</tr>
		
		<?php if(($user->getStatus() == MM_Status::$ERROR || $user->getStatus() == MM_Status::$PENDING_ACTIVATION) && $user->getStatusMessage() != "") { ?>
		<tr>
			<td></td>
			<td><div style="width:500px; font-size:11px; color:#555;"><em><?php echo $user->getStatusMessage(); ?></em></div></td>
		</tr>
		<?php } ?>
		
		<tr><td colspan="2"><input type="hidden" id="mm-id" value="<?php echo $user->getId(); ?>" /></td></tr>
		
		<tr>
			<td>Account Stats</td>
			<td>
				<div style="margin-bottom:5px;">
					<?php echo MM_Utils::getIcon('info', 'black', '1.2em', '1px', '', 'padding-left:4px; margin-right:4px;'); ?> Member ID is <span style='font-family:courier'><?php echo $user->getId(); ?></span>
				</div>
				<div style="margin-bottom:5px;">
					<?php echo MM_Utils::getIcon('calendar-o', 'purple', '1.2em', '1px'); ?> Account created on <?php echo $user->getRegistrationDate(true); ?>
				</div>
				
				<?php 
				if($user->isImported())
				{
				?>
				<div style="margin-bottom:5px;">
					<?php echo MM_Utils::getIcon('sign-in', 'blue', '1.3em', '1px'); ?> Account imported on <?php echo $user->getRegistrationDate(true); ?>
				</div>
				<?php		
				}
				?>
				
				<?php
				$welcomeEmailSent = $user->getWelcomeEmailSentDate();
				
				if(!empty($welcomeEmailSent))
				{
				?>
					<div style="margin-bottom:5px;">
						<?php echo MM_Utils::getIcon('paper-plane-o', 'green', '1.2em', '1px'); ?> Welcome email sent on <?php echo $user->getWelcomeEmailSentDate(true); ?>
					</div>
				<?php 
				}
				
				$emailCount = $user->getEmailCount();
				if($emailCount > 0)
				{
				?>
					<div style="margin-bottom:5px;">
						<?php echo MM_Utils::getIcon('envelope-o', 'beige', '1.2em', '1px'); ?> Emails sent: 
						<span style='font-family:courier; font-size:14px;'><?php echo $emailCount; ?></span>
						<a href="?page=<?php echo MM_MODULE_MANAGE_MEMBERS."&module=".MM_MODULE_MEMBER_DETAILS_ACTIVITY_LOG."&event_type=".MM_ActivityLog::$EVENT_TYPE_EMAIL."&user_id=".$user->getId(); ?>" style="font-size:11px;">view details</a>
					</div>
				<?php 
				} else {
				?>
					<div style="margin-bottom:5px;">
						<?php echo MM_Utils::getIcon('envelope-o', 'beige', '1.2em', '1px'); ?>
						<em>No email activity available</em>
					</div>	
				<?php 
				}
				?>
			</td>
		</tr>
		
		<tr><td colspan="2"></td></tr>
		
		<tr>
			<td>Engagement Stats</td>
			<td>
				<?php
				$loginCount = $user->getLoginCount();
				$pageAccessCount = $user->getPageAccessCount();
				
				if($loginCount > 0)
				{
				?>
					<div style="margin-bottom:5px;">
						<?php echo MM_Utils::getIcon('calendar-o', 'purple', '1.2em', '1px'); ?> Last Login Date: <?php echo $user->getLastLoginDate(true); ?>
					</div>
					<div style="margin-bottom:5px;">
						<?php echo MM_Utils::getIcon('globe', 'purple', '1.3em', '1px'); ?> Last Login IP: <span style='font-family:courier; font-size:12px;'><a href="http://www.infosniper.net/index.php?ip_address=<?php echo $user->getLastLoginIpAddress() ?>" target="_blank"><?php echo $user->getLastLoginIpAddress() ?></a></span>
					</div>
					<div style="margin-bottom:5px;">
						<?php echo MM_Utils::getIcon('key', 'yellow', '1.2em', '1px'); ?> Logins: 
						<span style='font-family:courier; font-size:14px;'><?php echo $loginCount; ?></span>
						<a href="?page=<?php echo MM_MODULE_MANAGE_MEMBERS."&module=".MM_MODULE_MEMBER_DETAILS_ACTIVITY_LOG."&event_type=".MM_ActivityLog::$EVENT_TYPE_LOGIN."&user_id=".$user->getId(); ?>" style="font-size:11px;">view details</a>
					</div>
				<?php 
				} else {
				?>
					<div style="margin-bottom:5px;">
						<?php echo MM_Utils::getIcon('key', 'yellow', '1.2em', '1px'); ?>
						<em>No login activity available</em>
					</div>	
				<?php 
				}
				
				if($pageAccessCount > 0)
				{
				?>
					<div style="margin-bottom:5px;">
						<?php echo MM_Utils::getIcon('file-o', 'turq', '1.2em', '1px'); ?> Pages Accessed: 
						<span style='font-family:courier; font-size:14px;'><?php echo $pageAccessCount; ?></span>
						<a href="?page=<?php echo MM_MODULE_MANAGE_MEMBERS."&module=".MM_MODULE_MEMBER_DETAILS_ACTIVITY_LOG."&event_type=".MM_ActivityLog::$EVENT_TYPE_PAGE_ACCESS."&user_id=".$user->getId()."&sortby=url"; ?>" style="font-size:11px;">view details</a>
					</div>
				<?php 
				} else {
				?>
					<div style="margin-bottom:5px;">
						<?php echo MM_Utils::getIcon('file-o', 'turq', '1.2em', '1px'); ?>
						<em>No page access activity available</em>
					</div>	
				<?php 
				}
				?>
			</td>
		</tr>
		
		<tr><td colspan="2"></td></tr>
		
		<tr>
			<td>Tools</td>
			<td>
				<div style="margin-bottom:5px;">
					<?php echo MM_Utils::getIcon('key', 'yellow', '1.2em', '1px'); ?> 
					<a style='cursor: pointer;' onclick="mmjs.loginAsMember('<?php echo $user->getId(); ?>');">Login as this member</a>
				</div>
				<?php if(!empty($welcomeEmailSent)) { ?>
				<div>
					<?php echo MM_Utils::getIcon('paper-plane-o', 'green', '1.2em', '1px'); ?>
					<a style='cursor: pointer;' onclick="mmjs.sendWelcomeEmail('<?php echo $user->getId(); ?>');">Resend welcome email to member</a>
				</div>
				<?php } ?>
			</td>
		</tr>
		
		<tr><td colspan="2"><div style="width: 600px; margin-top: 8px; margin-bottom: 8px;" class="mm-divider"></div></td></tr>
		
		<tr>
			<td>First Name</td>
			<td><input id="mm-first-name" type="text" style="width:200px;" value="<?php echo $user->getFirstName() ?>"></td>
		</tr>
		<tr>
			<td>Last Name</td>
			<td><input id="mm-last-name" type="text" style="width:200px;" value="<?php echo $user->getLastName() ?>"></td>
		</tr>
		<tr>
			<td>Email</td>
			<td><input id="mm-email" type="text" style="width:200px;" value="<?php echo $user->getEmail() ?>"></td>
		</tr>
		<tr>
			<td>Username</td>
			<td>
				<input id="mm-username" type="text" style="width:200px;" value="<?php echo $user->getUsername() ?>" <?php echo ($enableUsernameChange == true) ? "":"disabled"; ?>>
				<?php if(!$enableUsernameChange) { ?>
				<span class="description">Username cannot be changed.</span> <a href="https://membermouse.uservoice.com/knowledgebase/articles/319089-configure-if-member-can-change-their-username" target="_blank"><em>Learn more</em></a>
				<?php } ?>
			</td>
		</tr>
		<tr>
			<td style="padding-top:10px;">Password</td>
			<td>
				<div style="margin-top:10px; margin-bottom:15px;">
					<?php echo MM_Utils::getIcon('paper-plane-o', 'green', '1.2em', '1px'); ?>
					<a style='cursor: pointer;' onclick="mmjs.sendPasswordEmail('<?php echo $user->getId(); ?>');">Email current password to member</a>
				</div>
				
				<div style="margin-bottom:5px;">
					<span style="margin-right:18px;">New Password</span> <input id="mm-new-password" type="password">
				</div>
				<div style="margin-bottom:10px;">
					Confirm Password <input id="mm-confirm-password" type="password">
				</div>
			</td>
		</tr>
		<tr>
			<td>Phone</td>
			<td><input id="mm-phone" type="text" style="width:200px;" value="<?php echo $user->getPhone() ?>"></td>
		</tr>
		<tr>
			<td>Notes</td>
			<td>
				<textarea id="mm-notes" rows="6" style="font-family:courier; width:450px;"><?php echo $user->getNotes() ?></textarea>
			</td>
		</tr>
	</table>
	
	<div style="width: 600px; margin-top: 8px; margin-bottom: 8px;" class="mm-divider"></div> 
	
	<table cellspacing="8">
		<tr>
		<td width="<?php echo $columnWidth; ?>px;">Billing Address</td>
		<td>
			<table cellspacing="8">
			<tr>
				<td width='75'>Address</td>
				<td><input id="mm-billing-address" type="text" class="medium-text"  value="<?php echo $user->getBillingAddress(); ?>"/></td>
			</tr>
			<tr>
				<td>City</td>
				<td><input id="mm-billing-city" type="text" class="medium-text"  value="<?php echo $user->getBillingCity(); ?>"/></td>
			</tr>
			<tr>
				<td>State</td>
				<td><input id="mm-billing-state" type="text" class="medium-text"  value="<?php echo $user->getBillingState(); ?>"/></td>
			</tr>
			<tr>
				<td>Zip Code</td>
				<td><input id="mm-billing-zip-code" type="text" class="medium-text"  value="<?php echo $user->getBillingZipCode(); ?>"/></td>
			</tr>
			<tr>
				<td>Country</td>
				<td><select id="mm-billing-country"><?php echo MM_HtmlUtils::getCountryList($user->getBillingCountry()); ?></select></td>
			</tr>
			</table>
		</td>
		</tr>
	</table>
	
	<div style="width: 600px; margin-top: 8px; margin-bottom: 8px;" class="mm-divider"></div> 
	
	<table cellspacing="8">
		<tr>
		<td width="<?php echo $columnWidth; ?>px;">Shipping Address</td>
		<td>
			<table cellspacing="8">
			<tr>
				<td width='75'>Address</td>
				<td><input id="mm-shipping-address" type="text" class="medium-text"  value="<?php echo $user->getShippingAddress(); ?>"/></td>
			</tr>
			<tr>
				<td>City</td>
				<td><input id="mm-shipping-city" type="text" class="medium-text"  value="<?php echo $user->getShippingCity(); ?>"/></td>
			</tr>
			<tr>
				<td>State</td>
				<td><input id="mm-shipping-state" type="text" class="medium-text"  value="<?php echo $user->getShippingState(); ?>"/></td>
			</tr>
			<tr>
				<td>Zip Code</td>
				<td><input id="mm-shipping-zip-code" type="text" class="medium-text"  value="<?php echo $user->getShippingZipCode(); ?>"/></td>
			</tr>
			<tr>
				<td>Country</td>
				<td><select id="mm-shipping-country"><?php echo MM_HtmlUtils::getCountryList($user->getShippingCountry()); ?></select></td>
			</tr>
			</table>
		</td>
		</tr>
	</table>
	
	<div style='clear: both; height:10px;'></div>
	
	<div style="width:600px">
		<input type="button" class="mm-ui-button blue" value="Update Member" onclick="mmjs.updateMember(<?php echo $user->getId(); ?>);">
		
		<?php if(($user->getStatus() == MM_Status::$ERROR) || ($user->getStatus() == MM_Status::$PENDING_ACTIVATION) || !$user->hasActiveSubscriptions()) { ?>
		<span style="float:right;">
			<input type="button" class="mm-ui-button red" value="Delete Member" onclick="mmjs.deleteMember(<?php echo $user->getId(); ?>, '<?php echo MM_ModuleUtils::getUrl($crntPage, MM_MODULE_BROWSE_MEMBERS); ?>');">
		</span>
		<?php } ?>
	</div>
</div>

<div style='clear: both; height:20px;'></div>

<?php 
	}
	else
	{
		echo "<div style=\"margin-top:10px;\"><em>You do not have permission to manage this member.</em></div>";
	}
}
else 
{
	echo "<div style=\"margin-top:10px;\"><em>Invalid Member ID</em></div>";
}
}
?>