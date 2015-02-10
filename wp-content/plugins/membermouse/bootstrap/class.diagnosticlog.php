<?php
/**
 *
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */
class MM_DiagnosticLog
{
	//event types
	public static $MM_SUCCESS = "mm-success";
	public static $MM_ERROR   = "mm-error";
	public static $PHP_ERROR  = "php-error";
	public static $PHP_WARNING  = "php-warning";
	
	public static $DIAGNOSTIC_MODE = "";
	
	//diagnostic modes
	public static $MODE_OFF    = "off";
	public static $MODE_ERRORS = "error";
	public static $MODE_FULL   = "full";
	
	//In the context of the diagnostic log, "session" means a single page load by one user.
	protected static $SESSION = "unknown";
	protected static $IP_ADDRESS="unknown";
	
	private static $initialized = false;
	
	
	/**
	 * This method should be called at class load, and stores the mode, ip address, and creates a session identifier, so that these things only need to 
	 * happen once within the life of the interpreter process
	 */
	public static function init()
	{
		if (!self::$initialized)
		{
			$diagnosticMode = (class_exists("MM_OptionUtils"))?MM_OptionUtils::getOption(MM_OptionUtils::$OPTION_KEY_DIAGNOSTIC_MODE):"";
			self::$DIAGNOSTIC_MODE = empty($diagnosticMode)?self::$MODE_OFF:$diagnosticMode;
			if (self::$DIAGNOSTIC_MODE !== self::$MODE_OFF)
			{
				self::$IP_ADDRESS = (class_exists("MM_Utils"))?MM_Utils::getClientIPAddress():"unknown";
				//the transaction key class logic for generating random identifiers is reused here, for convenience
				self::$SESSION = (class_exists("MM_TransactionKey"))?MM_TransactionKey::createRandomIdentifier(8):"unknown";
			}
			self::$initialized = true;
		}
	}
	
	
	/**
	 * Returns whether the diagnostic log is off, or is configured to log in any mode
	 * 
	 * @return boolean true or false if logging is turned on
	 */
	public static function isEnabled()
	{
		return (!empty(self::$DIAGNOSTIC_MODE) && (self::$DIAGNOSTIC_MODE != self::$MODE_OFF));
	}
	
	
	/**
	 * User-friendly labels for the mode constants
	 * @return string see the modes listed above
	 */
	public static function getModeLabels()
	{
		$modeLabels = array(self::$MODE_OFF    => "Off",
							self::$MODE_ERRORS => "Log MemberMouse and PHP errors only",
							self::$MODE_FULL   => "Log all events");
		return $modeLabels;
	}
	
	
	/**
	 * User-friendly labels for the event type constants
	 * @return string see the event types listed above
	 */
	public static function getEventTypeLabels()
	{
		$eventTypeLabels = array(self::$PHP_ERROR   => "PHP Error",
								 self::$PHP_WARNING => "PHP Warning",
								 self::$MM_SUCCESS  => "MemberMouse Success Response",
								 self::$MM_ERROR    => "MemberMouse Error Response"
		);
		return $eventTypeLabels;
	}
	
	
	/**
	 * Sets the configured mode
	 * 
	 * @param String $mode One of the diagnostic modes defined above
	 */
	public static function setMode($mode)
	{
		$modeList = self::getModeLabels();
		if (!in_array($mode,array_keys($modeList)))
		{
			$mode = self::$MODE_OFF;
		}
		MM_OptionUtils::setOption(MM_OptionUtils::$OPTION_KEY_DIAGNOSTIC_MODE,$mode);
	}
	
	
	/**
	 * Returns the configured mode of the diagnostic log. It the option has never been set, it is set to $MODE_OFF initially
	 * 
	 * @return string One of MM_DiagnosticLog::$MODE_OFF, MM_DiagnosticLog::$MODE_ERRORS, or MM_DiagnosticLog::$MODE_FULL
	 */
	public static function getMode()
	{
		$mode = MM_OptionUtils::getOption(MM_OptionUtils::$OPTION_KEY_DIAGNOSTIC_MODE);
		$modeList = self::getModeLabels();
		if (!in_array($mode,array_keys($modeList)))
		{
			$mode = self::$MODE_OFF;
			MM_OptionUtils::setOption(MM_OptionUtils::$OPTION_KEY_DIAGNOSTIC_MODE,$mode);
		}
		return $mode;
	}
	
	
	/**
	 * Logs a generic item to the diagnostic log
	 * 
	 * @param string $location The location of the error (can be a class/method, function, or file)
	 * @param string $type Predefined string representing the type of item being logged. See event types above
	 * @param string $event The item being logged. This can be the payload of a response object, a warning/error message, etc
	 * @param int $line The line number where this event was generated, if available
	 * @return boolean
	 */
	public static function LogItem($location, $type, $event, $line="")
	{
		global $wpdb;
		
		switch (self::$DIAGNOSTIC_MODE)
		{
			case self::$MODE_FULL:
				$acceptedModes = array(self::$MM_ERROR,self::$MM_SUCCESS,self::$PHP_ERROR,self::$PHP_WARNING);
				break;
			case self::$MODE_ERRORS:
				$acceptedModes = array(self::$MM_ERROR, self::$PHP_ERROR);
				break;
			case self::$MODE_OFF;
			default:
				$acceptedModes = array();
		}
		
		if (!in_array($type,$acceptedModes))
		{
			return true; //no error, type is just not applicable to this logging mode, so return true
		}
		
		$diagData = array(
			"type"       => $type,
			"ip_address" => self::$IP_ADDRESS,
			"session"    => self::$SESSION,
			"location"   => $location,
			"event"      => $event
		);
		if (!empty($line))
		{
			$diagData['line'] = $line;
		}
		return @$wpdb->insert(MM_TABLE_DIAGNOSTIC_LOG, $diagData);	
	}
	
	
	/**
	 * Convenience method for logging an MM_Response object
	 * 
	 * @param string $type Predefined string representing the type of response
	 * @param string $event The response data
	 * 
	 * @return boolean true if captured successfully, false otherwise
	 */
	public static function LogResponse($type, $event)
	{
		if (self::$DIAGNOSTIC_MODE == self::$MODE_OFF)
		{
			return true;
		}
		$backtrace = debug_backtrace();
		$location = "unknown";
		$line = 0;
		if (isset($backtrace[2]) && isset($backtrace[2]['function']))
		{
			$location  = isset($backtrace[2]['class'])?"{$backtrace[2]['class']}::":"";
			$location .= $backtrace[2]['function'];
			$line = isset($backtrace[2]['line'])?intval($backtrace[2]['line']):"unknown";
		}
		self::LogItem($location, $type, $event, $line);
	}
	
	
	/**
	 * Logs PHP errors and warnings. This method returns false to indicate that the error should pass-thru to PHP's error handling
	 * 
	 * @param int $errorLevel The PHP error level defined in the error constants section on php.net
	 * @param string $errorMessage The message associated with the error/warning
	 * @param string $errorFile The file (if available) where the error/warning occurred
	 * @param number $errorLine The line number (if available) where the error/warning occurred
	 * @param string $symbolTable Necessary to match the method signature of a PHP error handler, but not used
	 * 
	 * @return boolean should always return false
	 */
	public static function logPHPErrors($errorLevel, $errorMessage, $errorFile="unknown", $errorLine=0, $symbolTable="")
	{
		if (self::$DIAGNOSTIC_MODE == self::$MODE_OFF)
		{
			return false;
		}
		if (($errorLevel != null) && ($errorLevel & (E_WARNING| E_NOTICE|E_CORE_WARNING| E_COMPILE_WARNING| E_USER_WARNING| E_USER_NOTICE)))
		{
			$errorType = self::$PHP_WARNING;
		}
		else 
		{
			$errorType = self::$PHP_ERROR;
		}
		self::LogItem($errorFile, $errorType, "{$errorMessage} ({$errorLevel})",$errorLine);
		return false; //allow the error to be handled by PHP by returning false
	}
	
	
	/**
	 * Clears the diagnostic log in the database
	 * 
	 * @return boolean true on success, false on error
	 */
	public static function clearLog()
	{
		global $wpdb;
		
		return ($wpdb->query("DELETE FROM ".MM_TABLE_DIAGNOSTIC_LOG) !== false);
	}
}
MM_DiagnosticLog::init(); //static initializer
?>
