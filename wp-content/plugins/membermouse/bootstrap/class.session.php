<?php
/**
 * 
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */
 class MM_Session
 {
 	public static $KEY_IMPORT_MEMBERS = "import-members";
 	public static $KEY_LAST_USER_ID = "last-user-id";
 	public static $KEY_LAST_COUPON_VALUE = "last-coupon";
 	public static $KEY_UPDATE_USER_ID = "update-user-id";
	public static $KEY_LAST_ORDER_ID = "last-order-id";
 	public static $KEY_LAST_FORM = "last-form";
	public static $KEY_LOGIN_FORM_USER_ID = "login-form-user-id";
	public static $KEY_LOGIN_FORM_USERNAME = "login-form-username";
	public static $KEY_ERRORS = "errors";
	public static $KEY_MESSAGES = "messages";
	public static $KEY_CHECKOUT_FORM = "checkout-form";
 	public static $KEY_SMARTTAGS = "smarttags";
 	public static $KEY_MM_LICENSE = "license";
	public static $KEY_CSV = "csv";
	public static $KEY_PREVIEW_MODE = "preview";
	public static $KEY_USING_DB_CACHE = "using_db_cache";
	public static $KEY_TRANSACTION_KEY = "transaction_key";
	public static $PARAM_LOGIN_TOKEN = "reftok";
	public static $PARAM_USER_ID = "user_id";
	public static $PARAM_MESSAGE_KEY = "message";
	public static $PARAM_COMMAND_DEACTIVATE = "mm-deactivate";
	public static $PARAM_SUBMODULE = "submodule";
 	
 	public static function value($name, $val=null)
 	{
 		if(self::sessionExists())
 		{
	 		if(!is_null($val)) 
	 		{
	 			$_SESSION[MM_PREFIX.$name] = $val;
	 		}
	 		
	 		if(isset($_SESSION[MM_PREFIX.$name])) 
	 		{
	 			return $_SESSION[MM_PREFIX.$name];
	 		}
 		}
 		
 		return false;
 	}
 	
 	public static function clear($name)
 	{
 		if(self::sessionExists())
 		{
	 		$_SESSION[MM_PREFIX.$name] = null;
	 		unset($_SESSION[MM_PREFIX.$name]);
 		}
 	}
 	
 	private static function sessionExists()
 	{
 		$sessionId = session_id();
 		return !empty($sessionId);
 	}
 }
?>
