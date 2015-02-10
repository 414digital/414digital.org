<?php
/**
 *
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */
class MM_Status
{
	public static $ACTIVE = 1;
	public static $CANCELED = 2;
	public static $LOCKED = 3;
	public static $PAUSED = 4;
	public static $OVERDUE = 5;
	public static $PENDING_ACTIVATION = 6;
	public static $ERROR = 7;
	public static $EXPIRED = 8;
	public static $PENDING_CANCELLATION = 9;
	
	public static function getName($statusId, $doLowercase=false)
	{
		$statusName = "";
		
		switch($statusId) 
		{
			case self::$ACTIVE:
				$statusName =  "Active";
				break;
			
			case self::$CANCELED:
				$statusName =  "Canceled";
				break;
				
			case self::$LOCKED:
				$statusName =  "Locked";
				break;
				
			case self::$PAUSED:
				$statusName =  "Paused";
				break;
				
			case self::$OVERDUE:
				$statusName =  "Overdue";
				break;
				
			case self::$PENDING_ACTIVATION:
				$statusName =  "Pending Activation";
				break;
				
			case self::$PENDING_CANCELLATION:
				$statusName = "Pending Cancellation";
				break;
				
			case self::$ERROR:
				$statusName =  "Error";
				break;
				
			case self::$EXPIRED:
				$statusName = "Expired";
				break;
		}
		
		return ($doLowercase) ? strtolower($statusName) : $statusName;
	}
	
	public static function getSmartTagValue($statusId)
	{
		$statusName = "";
	
		switch($statusId)
		{
			case self::$ACTIVE:
				$statusName =  "active";
				break;
					
			case self::$CANCELED:
				$statusName =  "canceled";
				break;
	
			case self::$LOCKED:
				$statusName =  "locked";
				break;
	
			case self::$PAUSED:
				$statusName =  "paused";
				break;
	
			case self::$OVERDUE:
				$statusName =  "overdue";
				break;
	
			case self::$PENDING_ACTIVATION:
				$statusName =  "pending";
				break;
	
			case self::$PENDING_CANCELLATION:
				$statusName = "pending_cancel";
				break;
	
			case self::$ERROR:
				$statusName =  "error";
				break;
	
			case self::$EXPIRED:
				$statusName = "expired";
				break;
		}
	
		return $statusName;
	}
	
	public static function isValidStatus($statusId)
	{
		switch($statusId)
		{
			case self::$ACTIVE:
			case self::$CANCELED:
			case self::$LOCKED:
			case self::$PAUSED:
			case self::$OVERDUE:
			case self::$ERROR:
			case self::$EXPIRED:
			case self::$PENDING_ACTIVATION:
			case self::$PENDING_CANCELLATION:
				return true;
				break;
		}
	
		return false;
	}
	
	public static function getImage($statusId, $showTitle=true)
	{
		$title = ($showTitle) ? self::getName($statusId) : "";
		
		switch($statusId) 
		{
			case self::$ACTIVE:
				return MM_Utils::getIcon('play-circle', 'green', '1.3em', '2px', $title);
			
			case self::$CANCELED:
				return MM_Utils::getIcon('stop', 'red', '1.2em', '1px', $title);
				
			case self::$LOCKED:
				return MM_Utils::getIcon('lock', 'yellow', '1.4em', '2px', $title);
				
			case self::$PAUSED:
				return MM_Utils::getIcon('pause', 'turq', '1.2em', '1px', $title);
				
			case self::$OVERDUE:
				return MM_Utils::getIcon('credit-card', 'orange', '1.3em', '1px', $title);
				
			case self::$ERROR:
				return MM_Utils::getIcon('warning', 'red', '1.2em', '1px', $title);
				
			case self::$EXPIRED:
				return MM_Utils::getIcon('times-circle', 'yellow', '1.3em', '2px', $title);
				
			case self::$PENDING_ACTIVATION:
				return MM_Utils::getIcon('clock-o', 'blue', '1.3em', '1px', $title);
				
			case self::$PENDING_CANCELLATION:
				return MM_Utils::getIcon('clock-o', 'red', '1.3em', '1px', $title);
		}
		
		return "";
	}
	
	public static function getStatusTypesList()
	{
		$list = array();
		$list[MM_Status::$ACTIVE] = MM_Status::getName(MM_Status::$ACTIVE);
		$list[MM_Status::$CANCELED] = MM_Status::getName(MM_Status::$CANCELED);
		$list[MM_Status::$PAUSED] = MM_Status::getName(MM_Status::$PAUSED);
		$list[MM_Status::$OVERDUE] = MM_Status::getName(MM_Status::$OVERDUE);
		$list[MM_Status::$EXPIRED] = MM_Status::getName(MM_Status::$EXPIRED);
		$list[MM_Status::$PENDING_ACTIVATION] = MM_Status::getName(MM_Status::$PENDING_ACTIVATION);
		$list[MM_Status::$PENDING_CANCELLATION] = MM_Status::getName(MM_Status::$PENDING_CANCELLATION);
		$list[MM_Status::$LOCKED] = MM_Status::getName(MM_Status::$LOCKED);
		$list[MM_Status::$ERROR] = MM_Status::getName(MM_Status::$ERROR);
		return $list;
	}
}
?>
