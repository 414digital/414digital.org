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
			
			// prepare data grid
			$view = new MM_GiftHistoryView();
			$dataGrid = new MM_DataGrid($_REQUEST, "date_gifted", "desc", 10);
			$data = $view->getViewData($user->getId(), $dataGrid);
			$rows = $view->generateRows($data, true);
			$dataGrid->setTotalRecords($data);
			$dataGrid->recordName = "gift";
			
			$headers = array
			(
				'date_gifted'   => array('content' => '<a onclick="mmjs.sort(\'date_gifted\');" href="#">Date Purchased</a>', "attr" => "style='width:170px;'"),
			   	'name'			=> array('content' => '<a onclick="mmjs.sort(\'name\');" href="#">Description</a>'),
			   	'status'		=> array('content' => 'Status')
			);
			
			$dataGrid->setHeaders($headers);
			$dataGrid->setRows($rows);
			
			$dgHtml = $dataGrid->generateHtml();
			
			if($dgHtml == "") 
			{
				$dgHtml = "<p><i>No gifts purchased.</i></p>";
			}
?>
<div class="mm-wrap">
	<div id="mm-form-container">
		<input type="hidden" name="user_id" value="<?php echo $user->getId();?>"/>
		
		<?php if(isset($_GET["page"])) { ?>
		<input type="hidden" name="page" value="<?php echo $_GET["page"];?>"/>
		<?php } ?>
		
		<?php if(isset($_GET["module"])) { ?>
		<input type="hidden" name="module" value="<?php echo $_GET["module"];?>"/>
		<?php } ?>
	</div>
	
	<div style='width:85%'>
	<?php echo $dgHtml; ?>
	</div>
</div>

</div>
<?php 
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