<?php 
/**
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */

$view = new MM_SmartTagLibraryView();
$dataGrid = new MM_DataGrid($_REQUEST, "name", "asc", 10);
$data = $view->getLookupData($p->objectType, $dataGrid);
$dataGrid->showPagingControls = false;

switch($p->objectType)
{
	case MM_TYPE_POST:
		$dataGrid->recordName = "protected post";
		break;
		
	case MM_TYPE_BUNDLE:
		$dataGrid->recordName = "bundle";
		break;
		
	case MM_TYPE_MEMBERSHIP_LEVEL:
		$dataGrid->recordName = "membership level";
		break;
		
	case MM_TYPE_EMPLOYEE_ACCOUNT:
		$dataGrid->recordName = "employee";
		break;
		
	case MM_TYPE_PRODUCT:
		$dataGrid->recordName = "product";
		break;
		
	case MM_TYPE_CUSTOM_FIELD:
		$dataGrid->recordName = "custom field";
		break;
}

$rows = array();

$headers = array
(	    
	'action' 	=> array('content' => '', 'attr'=>'style="width:20px;"'),
	'id'		=> array('content' => 'ID', 'attr'=>'style="width:20px;"'),
   	'name'		=> array('content' => 'Name'),
);

if($p->objectType == MM_TYPE_PRODUCT)
{	 
	$headers['access'] = array( 'content' => 'Associated Access');
}

foreach($data as $key => $item)
{   	
    $action = '<a title="Insert ID \''.$item->id.'\'" onclick="stl_js.insertContent(\''.$item->id.'\')" style="cursor:pointer;">'.MM_Utils::getIcon('plus-circle', 'green', '1.4em', '1px').'</a>';
   
    $row_array = array
    (
    	array( 'content' => $action),
    	array( 'content' => $item->id),
    	array( 'content' => MM_Utils::abbrevString($item->name, 25)),
    );
    
    if($p->objectType == MM_TYPE_PRODUCT)
    {
    	$product = new MM_Product($item->id);
    	
    	// Associated Access
    	$accessGranted = "";
    	
    	$membership = $product->getAssociatedMembership();
    	
    	if($membership->isValid())
    	{
    		$accessGranted .= MM_Utils::getAccessIcon(MM_OrderItemAccess::$ACCESS_TYPE_MEMBERSHIP);
    		$accessGranted .= " ".MM_Utils::abbrevString($membership->getName(), 25)."</a>";
    	}
    	
    	if(empty($accessGranted))
    	{
    		$bundle = $product->getAssociatedBundle();
    	
    		if($bundle->isValid())
    		{
    			$accessGranted .= MM_Utils::getAccessIcon(MM_OrderItemAccess::$ACCESS_TYPE_BUNDLE);
    			$accessGranted .= " ".MM_Utils::abbrevString($bundle->getName(), 25)."</a>";
    		}
    	}
    	
    	if(empty($accessGranted))
    	{
    		$accessGranted = MM_NO_DATA;
    	}
    	
    	$row_array[] = array( 'content' => $accessGranted);
    }
    
    $rows[] = $row_array;
}

$dataGrid->setHeaders($headers);
$dataGrid->setRows($rows);

$dgHtml = $dataGrid->generateHtml();

if($dgHtml == "") {
	$dgHtml = "<p><i>No ".$dataGrid->recordName."s found.</i></p>";
}

echo $dgHtml; 
?>