<?php 
/**
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */

if($user && $user->isValid())
{

$dataGrid = new MM_DataGrid();
$dataGrid->showPagingControls = false;
$dataGrid->recordName = "bundle";

$rows = array();

// applied bundles
$appliedBundles = $user->getAppliedBundles(true);

foreach($appliedBundles as $appliedBundle)
{	
	// Bundle
	$bundle = $appliedBundle->getBundle();
	
	// status
	$status = MM_Status::getImage($appliedBundle->getStatus());
	
	if($appliedBundle->isComplimentary())
	{
		$status .= MM_Utils::getIcon('ticket', 'purple', '1.3em', '2px', "Bundle is complimentary", "margin-left:4px;");
	}
	
	if($appliedBundle->isImported())
	{
		$status .= MM_Utils::getIcon('sign-in', 'blue', '1.3em', '2px', "Bundle applied through import", "margin-left:4px;");
	}
	
	// actions
	$actions = "";
	$showCancel = false;
	$showPause = false;
	$showActivate = false;
	$showEditCalc = false;
	if($appliedBundle->getStatus() == MM_Status::$ACTIVE || $appliedBundle->getStatus() == MM_Status::$EXPIRED || $appliedBundle->getStatus() == MM_Status::$PAUSED)
	{
		$showCancel = true;
		$showEditCalc = true;
		
		if($appliedBundle->getStatus() == MM_Status::$ACTIVE || $appliedBundle->getStatus() == MM_Status::$EXPIRED) 
		{
			$showPause = true;
		}
		else if($appliedBundle->getStatus() == MM_Status::$PAUSED) 
		{
			$showActivate = true;
		}
	}
	else if($appliedBundle->getStatus() == MM_Status::$OVERDUE)
	{
		$showCancel = true;
		$showPause = true;
	}
	else if($appliedBundle->getStatus() == MM_Status::$PENDING_CANCELLATION)
	{
		$showActivate = true;
		$showEditCalc = true;
	}
	else
	{
		$showActivate = true;
	}
	
	if($showActivate)
	{
		$actions .= "<a style=\"cursor: pointer;\" onclick=\"mmjs.changeBundleStatus('{$user->getId()}', '{$bundle->getId()}', '".MM_Status::$ACTIVE."')\" title=\"Activate {$bundle->getName()}\">".MM_Utils::getIcon('play-circle', 'green', '1.3em', '3px', '', 'margin-right:3px;')."</a> ";
	}
	
	if($showCancel)
	{
		$actions .= "<a style=\"cursor: pointer;\" onclick=\"mmjs.changeBundleStatus('{$user->getId()}', '{$bundle->getId()}', '".MM_Status::$CANCELED."')\" title=\"Cancel {$bundle->getName()}\">".MM_Utils::getIcon('stop', 'red', '1.2em', '2px', '', 'margin-right:3px;')."</a> ";
	}
	
	if($showPause)
	{
		$actions .= " <a style=\"cursor: pointer;\" onclick=\"mmjs.changeBundleStatus('{$user->getId()}', '{$bundle->getId()}', '".MM_Status::$PAUSED."')\" title=\"Pause {$bundle->getName()}\">".MM_Utils::getIcon('pause', 'turq', '1.2em', '2px', '', 'margin-right:3px;')."</a> ";
	}
	
	if($showEditCalc)
	{
		$editActionUrl = 'onclick="mmjs.editBundleConfiguration(\''.$user->getId().'\', \''.$bundle->getId().'\')"';
		$actions .= MM_Utils::getEditIcon("Edit Bundle Configuration", '', $editActionUrl);
	}
	
	$expirationDate = "";
	if($appliedBundle->doesExpire() && $appliedBundle->getStatus() == MM_Status::$ACTIVE)
	{
		$expirationDate = date("M j, Y", strtotime($appliedBundle->getExpirationDate()));
	}
	else 
	{
		$expirationDate = MM_NO_DATA;
	}
	
	$cancellationDate = "";
	if($appliedBundle->isPendingCancellation())
	{
		$cancellationDate = date("M j, Y", strtotime($appliedBundle->getCancellationDate()));
	}
	else
	{
		$cancellationDate = MM_NO_DATA;
	}
	
    $rows[] = array
    (
    	array('content' => MM_Utils::abbrevString($bundle->getName())),
    	array('content' => $status),
    	array('content' => "<span style='font-family:courier;'>".number_format($user->getDaysWithBundle($bundle->getId()))."</span>"),
    	array('content' => $appliedBundle->getApplyDate(true)),
    	array('content' => $expirationDate),
    	array('content' => $cancellationDate),
    	array('content' => $actions)
    );
}

// membership level bundles
$membershipBundles = $membership->getBundles();

foreach($membershipBundles as $id=>$name)
{
	$status = MM_Utils::getIcon('user', 'blue', '1.3em', '2px', "Bundle applied through membership");

	$rows[] = array
	(
			array('content' => $name),
			array('content' => $status),
			array('content' => MM_NO_DATA),
			array('content' => MM_NO_DATA),
			array('content' => MM_NO_DATA),
			array('content' => MM_NO_DATA),
			array('content' => MM_NO_DATA)
	);
}

$headers = array
(
	'bundle'		=> array('content' => 'Bundle'),
	'status'		=> array('content' => 'Status'),
	'days'			=> array('content' => '<span title=\'Days with Bundle\'>Days...</span>'),
	'date_added'	=> array('content' => 'First Applied'),
	'date_expires'	=> array('content' => 'Expires On'),
	'date_cancels'	=> array('content' => 'Cancels On'),
	'actions'		=> array('content' => 'Actions')
);

$dataGrid->setHeaders($headers);
$dataGrid->setRows($rows);

$dgHtml = $dataGrid->generateHtml();

if($dgHtml == "")
{
	$dgHtml = "<p><em>No bundles applied.</em></p>";
}
?>
<div id="mm-grid-container">
	<?php echo $dgHtml; ?>
</div>
<div id='mm-edit-bundle-configuration-dialog'></div>
<?php } ?>