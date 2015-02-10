<?php
/**
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */
class MM_Role
{
	public static $ROLE_ADMINISTRATOR = "administrator";
	public static $ROLE_ANALYST = "mm_role_analyst";
	public static $ROLE_SALES = "mm_role_customer_sales";
	public static $ROLE_CUSTOMER_SUPPORT = "mm_role_customer_support";
	public static $ROLE_PRODUCT_MANAGER = "mm_role_product_manager";
	public static $ROLE_SUBSCRIBER = "subscriber";
	public static $ROLE_IGNORE = "mm-ignore-role";
 	
 	public static function getRoleList()
 	{
 		$list = array();
 		$list[self::$ROLE_ADMINISTRATOR] = self::getRoleName(self::$ROLE_ADMINISTRATOR);
 		$list[self::$ROLE_ANALYST] = self::getRoleName(self::$ROLE_ANALYST);
 		$list[self::$ROLE_PRODUCT_MANAGER] = self::getRoleName(self::$ROLE_PRODUCT_MANAGER);
 		$list[self::$ROLE_SALES] = self::getRoleName(self::$ROLE_SALES);
 		$list[self::$ROLE_CUSTOMER_SUPPORT] = self::getRoleName(self::$ROLE_CUSTOMER_SUPPORT);
 		return $list;
 	}
 	
 	public static function getRoleName($roleId)
 	{
 		switch($roleId)
 		{
 			case self::$ROLE_ADMINISTRATOR:
 				return "Administrator";
 				break;

 			case self::$ROLE_ANALYST:
 				return "Analyst";
 				break;

 			case self::$ROLE_CUSTOMER_SUPPORT:
 				return "Support";
 				break;

 			case self::$ROLE_PRODUCT_MANAGER:
 				return "Product Manager";
 				break;

 			case self::$ROLE_SALES:
 				return "Sales";
 				break;
 				
 			default:
 				return "";
 				break;
 		}
 	}
 	
 	public static function getUserMeta($roleId)
 	{
 		$userMeta = array();
 		
 		switch($roleId)
 		{
 			case self::$ROLE_ADMINISTRATOR:
 				$userMeta["user_level"] = "10";
 				$userMeta["role"] = self::$ROLE_ADMINISTRATOR;
 				break;

 			case self::$ROLE_CUSTOMER_SUPPORT:
 				$userMeta["user_level"] = "7";
 				$userMeta["role"] = self::$ROLE_CUSTOMER_SUPPORT;
 				break;

 			case self::$ROLE_SALES:
 				$userMeta["user_level"] = "7";
 				$userMeta["role"] = self::$ROLE_SALES;
 				break;

 			case self::$ROLE_ANALYST:
 				$userMeta["user_level"] = "7";
 				$userMeta["role"] = self::$ROLE_ANALYST;
 				break;

 			case self::$ROLE_PRODUCT_MANAGER:
 				$userMeta["user_level"] = "7";
 				$userMeta["role"] = self::$ROLE_PRODUCT_MANAGER;
 				break;
 				
 			default:
 				$userMeta["user_level"] = "0";
 				$userMeta["role"] = self::$ROLE_SUBSCRIBER;
 				break;
 		}
 		return $userMeta;
 	}
 	
 	public static function addRoles()
 	{	
 		// add customer support role
 		remove_role(self::$ROLE_CUSTOMER_SUPPORT);
 		add_role(self::$ROLE_CUSTOMER_SUPPORT, self::getRoleName(self::$ROLE_CUSTOMER_SUPPORT), array(
 			'read' => true, 
 			'moderate_comments' => true
 		));
 		
 		// add sales role
 		remove_role(self::$ROLE_SALES);
 		add_role(self::$ROLE_SALES, self::getRoleName(self::$ROLE_SALES), array(
 			'read' => true,
 			'moderate_comments' => true
 		));
 		
 		// add analyst role
 		remove_role(self::$ROLE_ANALYST);
 		add_role(self::$ROLE_ANALYST, self::getRoleName(self::$ROLE_ANALYST), array(
 				'read' => true,
 				'moderate_comments' => true
 		));
 		
 		// add product manager role
 		remove_role(self::$ROLE_PRODUCT_MANAGER);
 		add_role(self::$ROLE_PRODUCT_MANAGER, self::getRoleName(self::$ROLE_PRODUCT_MANAGER), array(
 				'read' => true,
 				'moderate_comments' => true
 		));
 	}
}