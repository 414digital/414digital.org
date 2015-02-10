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
			$view = new MM_TransactionHistoryView();
			$dataGrid = new MM_DataGrid($_REQUEST, "date", "desc", 10);
			$data = $view->getViewData($user->getId(),$dataGrid);
			$rows = $view->generateRows($data, true);
			$dataGrid->setTotalRecords($data);
			$dataGrid->recordName = "transaction";
			
			$headers = array
			(
				'orderNumber'   => array('content' => '<a onclick="mmjs.sort(\'orderNumber\');" href="#">Order#</a>', "attr" => "style='width:65px;'"),
			   	'date'			=> array('content' => '<a onclick="mmjs.sort(\'date\');" href="#">Date</a>', "attr" => "style='width:180px;'"),
			   	'productName'	=> array('content' => '<a onclick="mmjs.sort(\'productName\');" href="#">Product Name</a>'),
			   	'amount'		=> array('content' => '<a onclick="mmjs.sort(\'amount\');" href="#">Amount</a>', "attr" => "style='width:120px;'"),
			   	'transType'		=> array('content' => '<a onclick="mmjs.sort(\'transType\');" href="#">Type</a>', "attr" => "style='width:50px;'"),
			   	'affiliate'		=> array('content' => 'Affiliate', "attr" => "style='width:60px;'"),
			   	'actions'		=> array('content' => 'Actions', "attr" => "style='width:140px;'")
			);
			
			$dataGrid->setHeaders($headers);
			$dataGrid->setRows($rows);
			
			$dgHtml = $dataGrid->generateHtml();
			
			if($dgHtml == "") 
			{
				$dgHtml = "<p><i>No transactions.</i></p>";
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

<div id="mm-issue-refund-dialog" style="display:none;" title="Refund Request" style="font-size:11px;">
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