<?php
/**
 *
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */

$view = new MM_PushNotificationView();
$dataGrid = new MM_DataGrid($_REQUEST, "event_type", "asc", 10);
$data = $view->getViewData($dataGrid);
$dataGrid->setTotalRecords($data);
$dataGrid->width = "98%";
$dataGrid->recordName = "notification";

$rows = array();

$headers = array
(
   	'event_type'	=> array('content' => '<a onclick="mmjs.sort(\'event_type\');" href="#">Event</a>'),
   	'action_type'	=> array('content' => '<a onclick="mmjs.sort(\'action_type\');" href="#">Action</a>'),
   	'status'		=> array('content' => '<a onclick="mmjs.sort(\'status\');" href="#">Status</a>'),
   	'actions'		=> array('content' => '')
);

foreach($data as $key=>$item)
{	
	$editActionUrl = 'onclick="mmjs.edit(\'mm-push-notification-dialog\', \''.$item->id.'\', 510, 570)"';
	$deleteActionUrl = 'onclick="mmjs.remove(\''.$item->id.'\')"';
	$actions = MM_Utils::getEditIcon("Edit Push Notification", '', $editActionUrl);
	$actions .= MM_Utils::getDeleteIcon("Delete Push Notification", 'margin-left:5px;', $deleteActionUrl);
	
	if($item->status == "1")
	{
		$actions .= '<a title="Send Test Notification" style="margin-left: 5px; cursor:pointer" onclick="mmjs.sendTestNotification(\''.$item->id .'\');">'.MM_Utils::getIcon('flask', 'green', '1.3em', '2px').'</a>';
	}
	else 
	{
		$actions .= '<a title="Activate this push notification in order to send a test" style="margin-left: 5px;">'.MM_Utils::getIcon('flask', 'grey', '1.3em', '2px').'</a>';
	}
	
	$description = "";
	$actionValue = unserialize($item->action_value);
	
	if($item->action_type == MM_Action::$MM_ACTION_SEND_EMAIL)
	{
		$description .= MM_Utils::getIcon('paper-plane-o', 'green', '1.3em', '2px', '', 'margin-right:4px;');
		$description .= " Send an email to ";
		
		if($actionValue["emailToId"] == "-1")
		{
			$description .= "the current member";
		}
		else
		{
			$employee = new MM_Employee($actionValue["emailToId"]);
			$description .= "<span style='font-family:courier;'>".$employee->getEmail()."</span>";
		}
		
		if(!empty($actionValue["emailCC"]))
		{
			$ccEmails = explode(",", $actionValue["emailCC"]);
			
			if(count($ccEmails) > 0)
			{
				if(count($ccEmails) == 1)
				{
					$description .= ", CC <span style='font-family:courier;'>".$ccEmails[0]."</span>";
				}
				else
				{
					$description .= ", CC ".count($ccEmails)." others";
				}
			}
		}
	}
	else if($item->action_type == MM_Action::$MM_ACTION_CALL_SCRIPT)
	{
		$description .= MM_Utils::getIcon('file-code-o', 'turq', '1.3em', '2px', '', 'margin-right:4px;');
		$description .= " Call script <span style='font-family:courier;'>".MM_Utils::abbrevString($actionValue["scriptUrl"], 80)."</span>";
	}
	
	$eventName = MM_Event::getName($item->event_type);
	$eventNameAttributes = "";
	
	$eventAttributes = unserialize($item->event_attributes);
	
	// add event attributes
	switch($item->event_type)
	{
		case MM_Event::$MEMBER_ADD:
			if(is_array($eventAttributes) && isset($eventAttributes["membership_level_id"]))
			{
				$membershipLevelId = $eventAttributes["membership_level_id"];
				
				if(intval($membershipLevelId) > 0)
				{
					$membership = new MM_MembershipLevel($membershipLevelId);
					
					if($membership->isValid())
					{
						$eventNameAttributes = " (<em>".$membership->getName()."</em>)";
					}
				}
			}
			
			if(empty($eventNameAttributes))
			{
				$eventNameAttributes = " (<em>Any Membership Level</em>)";
			}
			break;
			
		case MM_Event::$BUNDLE_ADD:
			if(is_array($eventAttributes) && isset($eventAttributes["bundle_id"]))
			{
				$bundleId = $eventAttributes["bundle_id"];
		
				if(intval($bundleId) > 0)
				{
					$bundle = new MM_Bundle($bundleId);
						
					if($bundle->isValid())
					{
						$eventNameAttributes = " (<em>".$bundle->getName()."</em>)";
					}
				}
			}
				
			if(empty($eventNameAttributes))
			{
				$eventNameAttributes = " (<em>Any Bundle</em>)";
			}
			break;
			
		case MM_Event::$MEMBER_STATUS_CHANGE:
		case MM_Event::$BUNDLE_STATUS_CHANGE:
			if(is_array($eventAttributes) && isset($eventAttributes["status_id"]))
			{
				$statusId = $eventAttributes["status_id"];
				
				if(intval($statusId) > 0)
				{
					$eventNameAttributes = " (<em>".MM_Status::getName($statusId)." Status</em>)";
				}
			}
			
			if(empty($eventNameAttributes))
			{
				$eventNameAttributes = " (<em>Any Status</em>)";
			}
			break;
	}
	
	$rows[] = array
    (
    	array('content' => $eventName.$eventNameAttributes),
    	array('content' => $description),
    	array('content' => MM_Utils::getStatusImage($item->status)),
    	array('content' => $actions),
    );
}

$dataGrid->setHeaders($headers);
$dataGrid->setRows($rows);

$dgHtml = $dataGrid->generateHtml();

if($dgHtml == "") 
{
	$dgHtml = "<p><i>No push notifications configured.</i></p>";
}
$filePath = MM_TEMPLATE_BASE."/push_notification_sample.php";
?>
<div class="mm-wrap">
    
    <?php if(MM_MemberMouseService::hasPermission(MM_MemberMouseService::$FEATURE_PUSH_NOTIFICATIONS)) { ?>
	<div class="mm-button-container">
		<a onclick="mmjs.create('mm-push-notification-dialog', '510', '570')" class="mm-ui-button green"><?php echo MM_Utils::getIcon('plus-circle', '', '1.2em', '1px'); ?> Create Push Notification</a>
	</div>
	
	<div class="clear"></div>
	
	<?php echo $dgHtml; ?>
	<?php } else { ?>
		<?php echo MM_Utils::getIcon('lock', 'yellow', '1.3em', '2px'); ?>
		This feature is not available on your current plan. To get access, <a href="<?php echo MM_MemberMouseService::getUpgradeUrl(MM_MemberMouseService::$FEATURE_PUSH_NOTIFICATIONS); ?>" target="_blank">upgrade your plan now</a>.
	<?php } ?>
</div>