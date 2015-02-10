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
			include_once MM_MODULES."/activity_log.php";
		}
		else
		{
			echo "<div style=\"margin-top:10px;\"><em>You do not have permission to manage this member.</em></div>";
		}
	}
	else 
	{
		echo "<div style=\"margin-top:10px;\"><i>Invalid Member ID</i></div>";
	}
}
?>