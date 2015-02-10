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
			$view = new MM_SubscriptionsView();
			$dataGrid = new MM_DataGrid($_REQUEST, "date_added", "desc", 10);
			$data = $view->getViewData($user->getId(),$dataGrid);
			$rows = $view->generateRows($data, true);
			$dataGrid->setTotalRecords($data);
			$dataGrid->recordName = "subscription";
			
			$nextRebillDateInfo = "Next rebill date is only available for subscriptions billed with a card-on-file payment service (i.e. Stripe, Braintree, Authorize.net CIM). When non-card-on-file payment services are used (i.e. PayPal, Authorize.net), the billing schedule is managed on their end so MemberMouse doesn't have access to the next rebill date.";
			
			$headers = array
			(
				'date_added'   			=> array('content' => '<a onclick="mmjs.sort(\'date_added\');" href="#">Start Date</a>', "attr" => "style='width:100px;'"),
			   	'order_item_status'		=> array('content' => 'Status', "attr" => "style='width:50px;'"),
			   	'access_type_name'		=> array('content' => 'Associated Access'),
			   	'product_id'			=> array('content' => 'Product Name'),
			   	'billing_description'	=> array('content' => 'Billing Description'),
			   	'rebill_date'			=> array('content' => 'Next Rebill Date'.MM_Utils::getInfoIcon($nextRebillDateInfo), "attr" => "style='width:140px;'"),
			   	'actions'				=> array('content' => 'Actions', "attr" => "style='width:90px;'")
			);
			
			$dataGrid->setHeaders($headers);
			$dataGrid->setRows($rows);
			
			$dgHtml = $dataGrid->generateHtml();
			
			if($dgHtml == "") 
			{
				$dgHtml = "<p><i>No active subscriptions.</i></p>";
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
	
	<div style='width:90%'>
	<?php echo $dgHtml; ?>
	</div>
</div>

<div id='mm-edit-subscription-dialog'></div>
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