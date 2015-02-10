<?php
/**
 *
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */
 
// get data based on search criteria and datagrid settings
$view = new MM_DuplicateSubscriptionsView();
$dataGrid = new MM_DataGrid($_REQUEST, "user_registered", "desc");
$data = $view->search($_REQUEST, $dataGrid);

$dataGrid->setTotalRecords($data);
$dataGrid->recordName = "duplicate";


// define datagrid headers
$headers = array
(	
   	'member_name'			=> array('content' => 'Name', "attr" => "style='width:140px;'"),
   	'user_email'			=> array('content' => 'Email', "attr" => "style='width:200px;'"),
   	'order_number'			=> array('content' => 'Order#', "attr" => "style='width:80px;'"),
   	'product'				=> array('content' => 'Product'),
	'date_added'			=> array('content' => 'Date', "attr" => "style='width:140px;'"),
	'actions'				=> array('content' => 'Actions', "attr" => "style='width:160px;'")
);
$datagridRows = array();

$blankRow = array(
				array('content'=>'', "attr" => "style='height:2px; background-color:#ECECEC'"),
				array('content'=>'', "attr" => "style='background-color:#ECECEC'"),
				array('content'=>'', "attr" => "style='background-color:#ECECEC'"),
				array('content'=>'', "attr" => "style='background-color:#ECECEC'"),
				array('content'=>'', "attr" => "style='background-color:#ECECEC'"),
				array('content'=>'', "attr" => "style='background-color:#ECECEC'"));

$lastUserId = "";
// process data
foreach($data as $key=>$item)
{
	if (!empty($lastUserId) && ($item->user_id != $lastUserId))
	{
		$datagridRows[] = $blankRow;
	}
	$lastUserId = $item->user_id;
	
    // actions
    $actions = "<a onclick='mmjs.cancelSubscription(\"{$item->id}\",\"{$item->order_number}\")' style='margin-left: 5px; cursor:pointer;' class='mm-ui-button'>Cancel Subscription</a>";
	
	// build datagrid row
	$row = array();
	$row[] = array('content' => "<span title='User ID: [{$item->user_id}]' style='line-height:20px;'>{$item->member_name}</span>");
	$row[] = array('content' => "<a href='".MM_ModuleUtils::getUrl(MM_MODULE_MANAGE_MEMBERS, MM_MODULE_MEMBER_DETAILS_TRANSACTION_HISTORY)."&user_id={$item->user_id}' target='_blank'>$item->user_email");
	$row[] = array('content' => "<span style='font-family:courier;'>{$item->order_number}</span>");
	$row[] = array('content' => $item->product);  
    $row[] = array('content' => MM_Utils::dateToLocal($item->date_added));
    $row[] = array('content' => $actions);
    
	$datagridRows[] = $row;
}

$dataGrid->setHeaders($headers);
$dataGrid->setRows($datagridRows);

$dgHtml = $dataGrid->generateHtml();

if(empty($dgHtml)) 
{
	$dgHtml = "<p><i>No duplicate subscriptions found.</i></p>";
}


echo "<div style='width:85%'>{$dgHtml}</div>";
?>