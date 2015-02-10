<?php
define("TABLE_ACCESS_KEYS", "mm_api_keys");

//errors http://www.iana.org/assignments/http-status-codes
define("RESPONSE_ERROR_CODE_AUTH", "401");
define("RESPONSE_ERROR_CODE_BAD_REQUEST", "400");
define("RESPONSE_ERROR_CODE_MISSING_PARAMS", "412");
define("RESPONSE_ERROR_CODE_INTERNAL", "500");
define("RESPONSE_ERROR_CODE_CONFLICT", "409");
define("RESPONSE_ERROR_CODE_NOCHANGE", "308");
define("RESPONSE_SUCCESS_CODE","200");

define("RESPONSE_ERROR_MESSAGE_AUTH", "Invalid Authorization");	
define("RESPONSE_ERROR_MESSAGE_MISSING_PARAMS","Missing, Empty, or Invalid required parameters");
define("RESPONSE_ERROR_MESSAGE_INTERNAL","Internal Server Error");
define("RESPONSE_ERROR_MESSAGE_CONFLICT","Conflict has been detected.");
define("RESPONSE_ERROR_MESSAGE_NOCHANGE","No information has been updated.");

define("RESPONSE_SUCCESS","");

// db definitions
if(!defined("BASE_DIR")){
	define("BASE_DIR", dirname(dirname(__FILE__))."/");
}