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
			$message = "";
			
			if(!empty($_POST["membership_update_submit"]))
			{
				$_POST["membership_update_submit"] = "";
				$calcMethod = MM_DaysCalculationTypes::$JOIN_DATE;
				
				if(isset($_POST["mm-membership-calc-method"]))
				{
					$calcMethod = $_POST["mm-membership-calc-method"];
				}
				
				$calcValue = "";
				switch($calcMethod)
				{
					case MM_DaysCalculationTypes::$CUSTOM_DATE:
						$calcValue = Date("Y-m-d", strtotime($_POST["mm-membership-custom-date"]));
						break;
				
					case MM_DaysCalculationTypes::$FIXED:
						$calcValue = preg_replace("/[^0-9]+/", "", $_POST["mm-membership-fixed"]);
						break;
				}
				
				$user->setDaysCalcMethod($calcMethod);
				$user->setDaysCalcValue($calcValue);
				
				if($user->doesExpire() && isset($_POST["mm-membership-expiration-date"]))
				{	
					$user->setExpirationDate(Date("Y-m-d", strtotime($_POST["mm-membership-expiration-date"])));
				}
				
				if($user->isPendingCancellation() && isset($_POST["mm-membership-cancellation-date"]))
				{
					$user->setCancellationDate(Date("Y-m-d", strtotime($_POST["mm-membership-cancellation-date"])));
				}
				
				$result = $user->commitData();
				
				if(MM_Response::isSuccess($result))
				{
					$message = "Membership properties updated successfully";
				}
				else
				{
					$message = "Error updating membership properties";
				}
			}
			
			$canChangeDaysCalc = true;
			if($user->getStatus() == MM_Status::$PAUSED)
			{
				$canChangeDaysCalc = false;
			}
			
			$customDateSelected = "";
			$fixedSelected = "";
			$joinDateSelected = "";
			$customDateValue = "";
			$fixedValue = "";
			switch($user->getDaysCalcMethod())
			{
				case MM_DaysCalculationTypes::$CUSTOM_DATE:
					$calcMethod = MM_DaysCalculationTypes::$CUSTOM_DATE;
					$customDateValue  = date("m/d/Y", strtotime($user->getDaysCalcValue()));
					$customDateSelected = "checked";
					break;
			
				case MM_DaysCalculationTypes::$FIXED:
					$calcMethod = MM_DaysCalculationTypes::$FIXED;
					$fixedValue = $user->getDaysCalcValue();
					$fixedSelected = "checked";
					break;
			
				default:
					$calcMethod = MM_DaysCalculationTypes::$JOIN_DATE;
					$joinDateSelected = "checked";
					break;
			}
			
			include_once MM_MODULES."/details.header.php";
			
			$membership = $user->getMembershipLevel();
?>
<div id="mm-form-container">
	<!-- MANAGE MEMBERSHIP -->
	<div style="margin-bottom:15px;"><span class="mm-section-header">Membership</span></div>
	
	<!-- CHANGE MEMBERSHIP LEVEL -->
	<div>
		<select id="mm-new-membership-selection">
			<?php echo MM_HtmlUtils::getMemberships($user->getMembershipId(), true); ?>
		</select>
		<a onclick="mmjs.changeMembership('<?php echo $user->getId(); ?>', '<?php echo $user->getMembershipId(); ?>')" class="mm-ui-button"><?php echo MM_Utils::getIcon('pencil', '', '1.2em', '1px'); ?> Change Membership</a>
	</div>
	
	<!-- CHANGE MEMBERSHIP STATUS -->
	<div style="margin-top:15px;">
	<?php if($user->getStatus() == MM_Status::$ACTIVE || $user->getStatus() == MM_Status::$LOCKED) { ?>

		<a onclick="mmjs.changeMembershipStatus('<?php echo $user->getId(); ?>', '<?php echo $membership->getId(); ?>', '<?php echo MM_Status::$CANCELED; ?>', false)" class="mm-ui-button"><?php echo MM_Status::getImage(MM_Status::$CANCELED, false); ?> Cancel Membership</a>
		<a onclick="mmjs.changeMembershipStatus('<?php echo $user->getId(); ?>', '<?php echo $membership->getId(); ?>', '<?php echo MM_Status::$PAUSED; ?>', false)" class="mm-ui-button"><?php echo MM_Status::getImage(MM_Status::$PAUSED, false); ?> Pause Membership</a>		

		<?php if($user->getStatus() == MM_Status::$ACTIVE) { ?>
		
			<a onclick="mmjs.changeMembershipStatus('<?php echo $user->getId(); ?>', '<?php echo $membership->getId(); ?>', '<?php echo MM_Status::$LOCKED; ?>', false)" class="mm-ui-button"><?php echo MM_Status::getImage(MM_Status::$LOCKED, false); ?> Lock Account</a>
		
		<?php } else { ?>
			
			<a onclick="mmjs.changeMembershipStatus('<?php echo $user->getId(); ?>', '<?php echo $membership->getId(); ?>', '<?php echo MM_Status::$ACTIVE; ?>', true)" class="mm-ui-button"><?php echo MM_Utils::getIcon('unlock-alt', 'yellow', '1.4em', '2px'); ?> Unlock Account</a>
			
		<?php } ?>
		
	<?php } else if($user->getStatus() == MM_Status::$CANCELED || $user->getStatus() == MM_Status::$PAUSED || $user->getStatus() == MM_Status::$OVERDUE || $user->getStatus() == MM_Status::$EXPIRED || $user->getStatus() == MM_Status::$PENDING_ACTIVATION || $user->getStatus() == MM_Status::$ERROR) { ?>
	
		<a onclick="mmjs.changeMembershipStatus('<?php echo $user->getId(); ?>', '<?php echo $membership->getId(); ?>', '<?php echo MM_Status::$ACTIVE; ?>', false)" class="mm-ui-button"><?php echo MM_Status::getImage(MM_Status::$ACTIVE, false); ?> Activate Membership</a>
		
		<?php if($user->getStatus() == MM_Status::$OVERDUE) { ?>
		
			<a onclick="mmjs.changeMembershipStatus('<?php echo $user->getId(); ?>', '<?php echo $membership->getId(); ?>', '<?php echo MM_Status::$CANCELED; ?>', false)" class="mm-ui-button"><?php echo MM_Status::getImage(MM_Status::$CANCELED, false); ?> Cancel Membership</a>
		
		<?php } ?>
		
	<?php } else if($user->getStatus() == MM_Status::$PENDING_CANCELLATION) { ?>
		
		<a onclick="mmjs.changeMembershipStatus('<?php echo $user->getId(); ?>', '<?php echo $membership->getId(); ?>', '<?php echo MM_Status::$ACTIVE; ?>', false)" class="mm-ui-button"><?php echo MM_Status::getImage(MM_Status::$ACTIVE, false); ?> Activate Membership</a>
		
	<?php } ?>
	</div>
	
	<?php if($user->getStatus() != MM_Status::$CANCELED) { ?>
	<div style="width: 800px; margin-top: 15px;" class="mm-divider"></div>
	<?php } ?>
	
	<form name='mm_membership_update_form' method='post'>
	
		<?php if($user->getStatus() != MM_Status::$CANCELED) { ?>
			<!-- CHANGE DAYS AS MEMBER CALC METHOD -->
			<?php
			$calcMethodDesc = "This determines how MemberMouse will calculate the number of days someone has been a member. This is used primarily in determining where a member is in a drip content schedule and therefore what content they get access to. By default, the calculation is done based on a member's registration date, but you can choose to have the calculation done based on a custom date or fix the number of days to a specific number.";
			?>
			<div style="margin-top:15px; margin-bottom:10px;">
				<span class="mm-section-header" style="font-size:14px;">'Days as Member' Calculation Method</span> 
				<?php echo MM_Utils::getInfoIcon($calcMethodDesc); ?>
			</div>
		
			<?php if(!$canChangeDaysCalc) { ?>
			<div style="margin-bottom:5px; width:480px;">
				<?php echo MM_Utils::getIcon('warning', 'orange', '1.2em', '1px'); ?> You can modify the number of days this member is fixed at, 
				but to change the calculation method you must change the member's status to Active.
			</div>
			<?php } ?> 
			<div style="margin-bottom:5px;">
				<input type='radio' <?php echo ((!$canChangeDaysCalc)?"disabled='disabled'":""); ?> onchange="mmjs.setCalcMethod('<?php echo MM_DaysCalculationTypes::$JOIN_DATE; ?>');" <?php echo $joinDateSelected; ?> name='mm-membership-calc-method-option' style='margin-right:5px;' /> 
				By join date
			</div>
			
			<div style="margin-bottom:5px;">
				<input type='radio' <?php echo ((!$canChangeDaysCalc)?"disabled='disabled'":""); ?> onchange="mmjs.setCalcMethod('<?php echo MM_DaysCalculationTypes::$CUSTOM_DATE; ?>');" <?php echo $customDateSelected; ?> name='mm-membership-calc-method-option' style='margin-right:5px;' /> 
				By custom date 
			
				<?php echo MM_Utils::getCalendarIcon(); ?>
				<input <?php echo ((!$canChangeDaysCalc)?"disabled='disabled'":""); ?> id="mm-membership-custom-date" name="mm-membership-custom-date" type="text" style="width: 152px" value="<?php echo $customDateValue; ?>" /> 
			</div>
			
			<div>
				<input type='radio' onchange="mmjs.setCalcMethod('<?php echo MM_DaysCalculationTypes::$FIXED; ?>');" <?php echo $fixedSelected; ?>  name='mm-membership-calc-method-option' style='margin-right:5px;' /> 
				Fixed at <input id="mm-membership-fixed" name="mm-membership-fixed" type="text" value="<?php echo $fixedValue; ?>"  style="width: 52px" /> days <br />
			</div>
			 	
			<input type='hidden' id='mm-membership-calc-method' name='mm-membership-calc-method' value="<?php echo $calcMethod; ?>" /> 
			
			<?php 
			if($user->doesExpire()) 
			{ 
				$expirationDate = date("m/d/Y", strtotime($user->getExpirationDate(false)));
			?>
			<div style="margin-top:20px; margin-bottom:10px;">
				<span class="mm-section-header" style="font-size:14px;">Membership Expires</span> 
			</div>
			
			<div style="margin-bottom:5px;">
				<?php echo MM_Utils::getCalendarIcon(); ?>
				<input id="mm-membership-expiration-date" name="mm-membership-expiration-date" type="text" style="width: 152px" value="<?php echo $expirationDate; ?>" /> 
			</div>
			<?php } ?>
		<?php } ?>
		
		<?php 
		if($user->isPendingCancellation()) 
		{ 
			$cancellationDate = date("m/d/Y", strtotime($user->getCancellationDate(false)));
		?>
		<div style="margin-top:20px; margin-bottom:10px;">
			<span class="mm-section-header" style="font-size:14px;">Membership Pending <?php echo ($user->getPendingStatus() == MM_Status::$CANCELED) ? "Cancellation":"Pause"; ?></span> 
		</div>
		
		<div style="margin-bottom:5px;">
			<?php echo MM_Utils::getInfoIcon(); ?> 
			<em>You can force <?php echo ($user->getPendingStatus() == MM_Status::$CANCELED) ? "cancel":"pause"; ?> the membership by setting the cancellation date to any date in the past.</em>
			
			<br/><br/>
		
			Membership will be <?php echo ($user->getPendingStatus() == MM_Status::$CANCELED) ? "canceled":"paused"; ?> on 
			<?php echo MM_Utils::getCalendarIcon(); ?>
			<input id="mm-membership-cancellation-date" name="mm-membership-cancellation-date" type="text" style="width: 152px" value="<?php echo $cancellationDate; ?>" /> 
		</div>
		<?php } ?>
		
		<?php if($user->getStatus() != MM_Status::$CANCELED) { ?>
		<div style='margin-top:15px;'>
			<input type="submit" name='membership_update_submit' class="mm-ui-button blue" value="Update Membership Properties" >	
		</div>
		<?php } ?>
	</form>
	
	<!-- MANAGE BUNDLES -->
	<?php 
		$bundleOptions = MM_HtmlUtils::getBundles(null, true);
		
		if(!empty($bundleOptions))
		{
	?>
	<div style="width: 800px; margin-top: 15px;" class="mm-divider"></div>
	
	<p><span class="mm-section-header">Bundles</span></p>
			
	<div style="margin-bottom:20px;">
		<select id="bundle-selector" name="bundle-seletor">
		<?php echo $bundleOptions; ?>
		</select>
		<a onclick="mmjs.applyBundle('<?php echo $user->getId(); ?>', '<?php echo MM_Status::$ACTIVE; ?>')" class="mm-ui-button"><?php echo MM_Utils::getIcon('plus-circle', '', '1.2em', '1px'); ?> Apply Bundle</a>
	</div>
	
	<div id="mm-grid-container" style="width:800px;">
		<?php include_once MM_MODULES."/details_access_rights.appliedbundles.php"; ?>
	</div>
	<?php } ?>
</div>

<script type='text/javascript'>
jQuery(document).ready(function()
{
	jQuery("#mm-membership-custom-date").datepicker();	
	jQuery("#mm-membership-expiration-date").datepicker();	
	jQuery("#mm-membership-cancellation-date").datepicker();	
});
</script>
<?php 
	if(isset($_GET["message"]))
	{
?>
<script>
alert("<?php echo $_GET["message"]; ?>");
</script>
<?php
	}
	
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
<div style="clear:both; height: 10px;" ></div>
<?php if(!empty($message)) { ?>
<script type='text/javascript'>
alert('<?php echo $message; ?>');
</script>
<?php } ?>