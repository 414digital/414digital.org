<?php
/**
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */

// Attempt to correct any invalid employees. Otherwise, delete invalid accounts 
global $wpdb;
$sql = "SELECT ea.* FROM ".MM_TABLE_EMPLOYEE_ACCOUNTS." ea LEFT JOIN {$wpdb->users} u ON (ea.user_id = u.ID) ".
	   "where ((ea.user_id IS NULL) OR (u.ID IS NULL))";
$results = $wpdb->get_results($sql);
$invalid_account_ids = array();
if ($wpdb->num_rows > 0)
{
	//either these accounts are not linked or the user_id is not valid (user with that ID does not exist in WP)
	foreach ($results as $nullAccount)
	{
		$currentAccount = new MM_Employee($nullAccount->id);
		if ($currentAccount->isValid())
		{
			$currentAccount->commitData(); //the commit logic will clean up any invalid links
			
			if (($currentAccount->getUserId() instanceof WP_Error) || ($currentAccount->getUserId() <= 0))
			{
				$invalid_account_ids[] = $currentAccount->getId();
			}
		}
	}
}

if (count($invalid_account_ids) > 0)
{
	$successfully_deleted_accounts = array();
	
	// attempt to delete all invalid accounts
	for($i = 0; $i < count($invalid_account_ids); $i++) 
	{
		$account = new MM_Employee($invalid_account_ids[$i]);
		
		if($account->isValid()) 
		{
			$userEmail = $account->getEmail();
			$response = $account->delete();
			
			// if account was deleted successfully, store it in $successfully_deleted_accounts
			if($response) 
			{
				$successfully_deleted_accounts[] = $userEmail;
			}
		}
	}
	
	// if one of more accounts were deleted successfully, display a message
	if(count($successfully_deleted_accounts) > 0) 
	{
		$error = "The following invalid accounts were detected and have been deleted: <strong>".implode(", ",$successfully_deleted_accounts)."</strong>";
		echo "<div class='updated'>";
		echo "<p>".$error."</p>";
		echo "</div>";
	}
}

// prepare data grid
$view = new MM_EmployeesView();
$dataGrid = new MM_DataGrid($_REQUEST, "display_name", "asc", 10);
$data = $view->getViewData($dataGrid);
$dataGrid->setTotalRecords($data);
$dataGrid->recordName = "employee";

$rows = array();

foreach($data as $key => $item)
{
	// Default
	$defaultDescription = "The default employee email address is used when MemberMouse sends a customer an email (i.e. the forgot password email)";
    
	if($item->is_default == '1') 
	{
		$defaultFlag = MM_Utils::getDefaultFlag("Default Employee\n\n{$defaultDescription}", "", true, 'margin-right:5px;');
	}
	else 
	{
		$defaultFlag = MM_Utils::getDefaultFlag("Set as Default Employee\n\n{$defaultDescription}", "onclick='mmjs.setDefault(\"".$item->id."\")'", false, 'margin-right:5px;');
	}
	
	// Full Name
	$realName = MM_NO_DATA;
	
	if($item->first_name != "")
	{
		$realName = $item->first_name;
		
		if($item->last_name != "")
		{
			$realName .= " ".$item->last_name." ";
		}
	}
	else if($item->last_name != "") 
	{
		$realName = $item->last_name." ";
	}
	
	// Phone
	if($item->phone != "")
	{
		$phone = $item->phone;
	}
	else
	{
		$phone = MM_NO_DATA;
	}
	
	// Role Name
 	$item->role_name = MM_Role::getRoleName($item->role_id);
	if(empty($item->role_name))
	{
		$item->role_name = MM_NO_DATA;
	}
	
	// Access Restrictions
	if(MM_MemberMouseService::hasPermission(MM_MemberMouseService::$FEATURE_EMPLOYEE_ACCOUNTS))
	{
		$employee = new MM_Employee($item->id);
		$hasRestrictions = false;
		
		if($employee->isValid())
		{
			$membershipRestrictions = $employee->getAccessRescrictions(MM_Employee::$ACCESS_TYPE_MEMBERSHIP);
			
			if(count($membershipRestrictions) > 0)
			{
				$hasRestrictions = true;
			}
		}
		
		if($hasRestrictions)
		{
			$item->role_name .= MM_Utils::getIcon('lock', 'yellow', '1.3em', '2px', 'This employee has additional access restrictions', 'margin-left:5px;');
		}
	}
    	
    // Actions
    $editActionUrl = 'onclick="mmjs.edit(\'mm-employee-accounts-dialog\', \''.$item->id.'\', 540, 450)"';
   	$deleteActionUrl = 'onclick="mmjs.removeAccount(\''.$item->id.'\')"';
   	$actions = MM_Utils::getEditIcon("Edit Employee", '', $editActionUrl);
    
    if(!MM_Employee::isBeingUsed($item->id))
    {
  		$actions .= MM_Utils::getDeleteIcon("Delete Employee", 'margin-left:5px;', $deleteActionUrl);
    }
    else 
    {
  		$actions .= MM_Utils::getDeleteIcon("This employee is currently being used and cannot be deleted", 'margin-left:5px;', '', true);
    }
    
    $rows[] = array
    (
    	array( 'content' => $defaultFlag." <span title='ID [".$item->id."]'>".$item->display_name."</span>"),
    	array( 'content' => $realName),
    	array( 'content' => $item->email),
    	array( 'content' => $phone),
    	array( 'content' => $item->role_name),
    	array( 'content' => $actions),
    );
}

$headers = array
(
   	'display_name'	=> array('content' => '<a onclick="mmjs.sort(\'display_name\');" href="#" style="margin-left:22px;">Display Name</a>'),
   	'first_name'	=> array('content' => '<a onclick="mmjs.sort(\'first_name\');" href="#">Real Name</a>'),
   	'email'			=> array('content' => '<a onclick="mmjs.sort(\'email\');" href="#">Email</a>'),
   	'phone'			=> array('content' => '<a onclick="mmjs.sort(\'phone\');" href="#">Phone</a>'),
   	'role_id'		=> array('content' => '<a onclick="mmjs.sort(\'role_id\');" href="#">Role</a>'),
   	'actions'		=> array('content' => 'Actions')
);

$dataGrid->setHeaders($headers);
$dataGrid->setRows($rows);

$dgHtml = $dataGrid->generateHtml();

if($dgHtml == "") {
	$dgHtml = "<p><i>No employees.</i></p>";
}
?>
<div class="mm-wrap">
	<div class="mm-button-container">
		<a onclick="mmjs.create('mm-employee-accounts-dialog', 540, 475)" class="mm-ui-button green"><?php echo MM_Utils::getIcon('plus-circle', '', '1.2em', '1px'); ?> Create Employee</a>
	</div>

	<div class="clear"></div>
	
	<?php echo $dgHtml; ?>
</div>