<?php
/**
 *
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */
 class MM_Utils
 {	
 	public static function isMemberMouseActive()
 	{
	 	$plugins = get_option('active_plugins');
	 	$required_plugin = MM_PLUGIN_NAME.'/index.php';
	 	$mmActive = false;
	 	
	 	if(in_array($required_plugin, $plugins))
	 	{
	 		$mmActive = true;
	 	}
	 	
	 	return $mmActive;
 	}
	
	public static function sendRequest($url, $params, $post=1)
	{
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_POST, $post);
		if($post==1) 
		{
			curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
		}
		curl_setopt($ch, CURLOPT_HEADER, 0);  // DO NOT RETURN HTTP HEADERS
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  // RETURN THE CONTENTS OF THE CALL
		$contents = curl_exec($ch);
		curl_close($ch);
		return $contents;
	}
 	
	public static function getJsFiles($directory, $recursive = false, $includeDirs = false, $pattern = '/.*/'){
		$items = array();
		
		if($handle = opendir($directory)) {
			while (($file = readdir($handle)) !== false) {
				if ($file != '.' && $file != '..') {
					$path = "$directory/$file";
					$path = preg_replace('#//#si', '/', $path);
					if (is_dir($path)) {
						if ($includeDirs) {
							$items[] = $path;
						}
						if ($recursive) {
							$items = array_merge($items, self::getJsFiles($path, true, $includeDirs, $pattern));
						}
					}
					else {
						if (preg_match($pattern, $file)) {
							$items[] = $path;
						}
					}
				}
			}
			
			closedir($handle);
		}
		sort($items);
		
		return $items;
	}
	
	public static function startExecutionTimer() 
	{
		$mtime = microtime();
		$mtime = explode(" ",$mtime);
		$mtime = $mtime[1] + $mtime[0];
		$starttime = $mtime; 
		return $starttime;
	}
	
	public static function getTotalExecutionTime($starttime) 
	{
		$mtime = microtime();
	   	$mtime = explode(" ",$mtime);
	   	$mtime = $mtime[1] + $mtime[0];
	   	$endtime = $mtime;
	   	$totaltime = ($endtime - $starttime);
	   	return $totaltime;
	}
	
	public static function getCustomPostTypes()
	{
		$args = array(
				'public'   => true,
				'_builtin' => false
		);
		
		return get_post_types($args, 'names', 'and');
	}
	
	public static function isCustomPostType($slug)
	{
		$postTypes = self::getCustomPostTypes();
		
		return in_array($slug, $postTypes);
	}
	
	/**
	 * This funciton returns the current UTC/GMT time in a format appropriate for
	 * storing in the database.
	 * 
	 * @return String returns a date-time string representing the current UTC/GMT time
	 */
	public static function getCurrentTime($format="mysql")
	{
		return current_time($format, 1);
	}
	
	/**
	 * This function uses the current WordPress timezone configuration to translate a UTC/GMT
	 * date to the local timezone.
	 * 
	 * @param utcDateStr String|Timestamp this is the UTC/GMT date to translate to the local timezone. It can be a unix timestamp or a date-time string
	 * @param format String this is the format to use when formatting the date
	 * 
	 * @return String a formatted date in the local timezone configured in WordPress
	 */
	public static function dateToLocal($utcDateStr, $format="M j, Y g:i a")
	{
		$timezoneStr = get_option('timezone_string');
		if(!empty($timezoneStr))
		{
			date_default_timezone_set($timezoneStr);
		}
		
		// check if date string passed is already a unix timestamp
		if(((string) (int) $utcDateStr === $utcDateStr) && ($utcDateStr <= PHP_INT_MAX) && ($utcDateStr >= ~PHP_INT_MAX))
		{
			$unixtimestamp = $utcDateStr;
		}
		else
		{
			$unixtimestamp = strtotime($utcDateStr);
		}
		
		$offset = date('Z', $unixtimestamp);
		$localDate = date($format, $unixtimestamp + $offset);
		
		// reset default timezone
		date_default_timezone_set("UTC");
		
		return $localDate;
	}
	
	public static function dateToUTC($localDateStr, $format="M j, Y g:i a")
	{
		$timezoneStr = get_option('timezone_string');
		if(!empty($timezoneStr))
		{
			date_default_timezone_set($timezoneStr);
		}
	
		// check if date string passed is already a unix timestamp
		if(((string) (int) $localDateStr === $localDateStr) && ($localDateStr <= PHP_INT_MAX) && ($localDateStr >= ~PHP_INT_MAX))
		{
			$unixtimestamp = $localDateStr;
		}
		else
		{
			$unixtimestamp = strtotime($localDateStr);
		}
	
		$offset = date('Z', $unixtimestamp);
		$utcDate = date($format, $unixtimestamp - $offset);
	
		// reset default timezone
		date_default_timezone_set("UTC");
	
		return $utcDate;
	}
	
	public static function isSSL()
	{
		$plugins = get_option('active_plugins');
		$required_plugin = "wordpress-https/wordpress-https.php";
		$wpHttpsActive = false;
		 
		if(in_array($required_plugin, $plugins))
		{
			$wpHttpsActive = true;
		}
		
		if($wpHttpsActive)
		{
			// user is_ssl function from WordPress HTTPS plugin if it's activated
			$wphttps = new WordPressHTTPS();
			return $wphttps->is_ssl();
		}
		else 
		{
			// use WordPress is_ssl function
			return is_ssl();
		}
	}
	
 	public static function isLoggedIn()
 	{
 		return is_user_logged_in();
 	}
 	
 	public static function abbrevString($str, $maxLength=40)
 	{
 		$origStr = $str;
 	
 		if(strlen($str) >= $maxLength)
 		{
 			$str = substr($str, 0, $maxLength)."...";
 		}
 	
 		return "<span title='".$origStr."'>".$str."</span>";
 	}
 	
 	public static function isGetParamAllowed($getParam)
 	{
 		global $reservedGetParams;
 		
 		$key = strtolower($getParam);
 		  
 		if(isset($reservedGetParams[$key]))
 		{
 			return false;
 		}
	 	
	 	return true;
 	}
 	
 	public static function validateEmail($email)
 	{
 		$isValid = true;
 		$atIndex = strrpos($email, "@");
 		
 		if(is_bool($atIndex) && !$atIndex)
 		{
 			$isValid = false;
 		}
 		else
 		{
 			$domain = substr($email, $atIndex+1);
 			$local = substr($email, 0, $atIndex);
 			$localLen = strlen($local);
 			$domainLen = strlen($domain);
 			
 			if ($localLen < 1 || $localLen > 64)
 			{
 				// local part length exceeded
 				$isValid = false;
 			}
 			else if ($domainLen < 1 || $domainLen > 255)
 			{
 				// domain part length exceeded
 				$isValid = false;
 			}
 			else if ($local[0] == '.' || $local[$localLen-1] == '.')
 			{
 				// local part starts or ends with '.'
 				$isValid = false;
 			}
 			else if (preg_match('/\\.\\./', $local))
 			{
 				// local part has two consecutive dots
 				$isValid = false;
 			}
 			else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain))
 			{
 				// character not valid in domain part
 				$isValid = false;
 			}
 			else if (preg_match('/\\.\\./', $domain))
 			{
 				// domain part has two consecutive dots
 				$isValid = false;
 			}
 			else if(!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/', str_replace("\\\\","",$local)))
 			{
 				// character not valid in local part unless
 				// local part is quoted
 				if (!preg_match('/^"(\\\\"|[^"])+"$/',
 						str_replace("\\\\","",$local)))
 				{
 					$isValid = false;
 				}
 			}
 			
 			if ($isValid && function_exists("checkdnsrr") && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A")))
 			{
 				// domain not found in DNS
 				$isValid = false;
 			}
 		}
 		
 		return $isValid;
 	}
 	
	private static function countDays( $a, $b )
	{
	    // First we need to break these dates into their constituent parts:
	    $gd_a = getdate( $a );
	    $gd_b = getdate( $b );
	
	    // Now recreate these timestamps, based upon noon on each day
	    // The specific time doesn't matter but it must be the same each day
	    $a_new = mktime( 12, 0, 0, $gd_a['mon'], $gd_a['mday'], $gd_a['year'] );
	    $b_new = mktime( 12, 0, 0, $gd_b['mon'], $gd_b['mday'], $gd_b['year'] );
	
	    // Subtract these two numbers and divide by the number of seconds in a
	    //  day. Round the result since crossing over a daylight savings time
	    //  barrier will cause this time to be off by an hour or two.
	    return round( abs( $a_new - $b_new ) / 86400 );
	}
	
 	public static function getNextPaymentDate($startDate, $productId){
 		$product = new MM_Product($productId);
 		$start  = strtotime($startDate);
 		
 		if($product->isRecurring(false)){
			$period = $product->getRebillPeriod();
			$freq = $product->getRebillFrequency();
			$newdate = null;
			switch($freq){
				case "months":
					$months = floor((mktime()-$start)/2628000);
					$newStart = strtotime( $months.' month' , strtotime ( $start ) ) ;
					$newdate = strtotime( $period.' month' , strtotime ( $newStart ) ) ;
					break;
				case "days":
					$days = self::countDays(Date("Y-m-d h:i:s"), $startDate);
					$diff = ($days>$period)?intval($days/$period):1;
					$newStart = strtotime( $diff.' day' , strtotime ( $start ) ) ;
					$newdate = strtotime( $period.' day' , strtotime ( $newStart ) ) ;
					break;
				case "weeks":
					$days = self::countDays(Date("Y-m-d h:i:s"), $startDate);
					$weeks = ($days>7)?intval($days/7):1;
					$diff = ($weeks>$period)?intval($weeks/$period):1;
					
					$newStart = strtotime( $diff.' week' , strtotime ( $start ) ) ;
					$newdate = strtotime( $period.' week' , strtotime ( $newStart ) ) ;
					break;
				case "years":
					$days = self::countDays(Date("Y-m-d h:i:s"), $startDate);
					$years = ($days>365)?intval($days/365):1;
					$diff = ($years>$period)?intval($years/$period):1;
					$newStart = strtotime( $diff.' year' , strtotime ( $start ) ) ;
					$newdate = strtotime( $period.' year' , strtotime ( $newStart ) ) ;
					break;
			}
			
			if(!is_null($newdate)){
				return $newdate;
			}
 		}
 		return false;
 	}
 	
 	public static function convertArrayToObject($arr){
 		if(is_array($arr)){
 			$info = new stdClass();
 			foreach($arr as $k=>$v){
 				$info->$k = $v;
 			}
 			return $info;
 		}
 		return new stdClass();
 	}
 	
 	public static function getFileContents($files){
 		$contents = "";
 		if(is_array($files)){
 			foreach($files as $file){
 				$contents .= self::loadFile($file);
 			}
 		}
 		return $contents;
 	}
 	
 	public static function loadFile($file)
 	{
		if(file_exists($file))
		{
			return file_get_contents($file);
		}
		return "";
	}
	
	public static function getReferrer()
	{
		if(isset($_SERVER["HTTP_REFERER"]))
		{
			return $_SERVER["HTTP_REFERER"];
		}
		return "";
	}
	
	public static function explode($needle, $haystack)
	{
		$arr = explode($needle, $haystack);
		if(is_array($arr)){
			foreach($arr as &$value){
				$value = urldecode($value);
			}
		}
		return $arr;
	}
	
	 public static function isURL($url = null) 
	 {
	        if(is_null($url))
	        {
	        	return false;
	        }
	
	        $protocol = '(http://|https://)';
	        $allowed = '([a-z0-9]([-a-z0-9]*[a-z0-9]+)?)';
	
	        $regex = "^". $protocol . // must include the protocol
	                         '(' . $allowed . '{1,63}\.)+'. // 1 or several sub domains with a max of 63 chars
	                         '[a-z]' . '{2,6}'; // followed by a TLD
	        if(eregi($regex, $url)===true)
	        {
	        	return true;
	        }
	        else
	        {
	        	return false;
	        }
	}
	
 	public static function appendUrlParam($url, $paramKey, $paramVal, $urlencode=true)
 	{
 		if($urlencode)
 		{
 			$paramVal = urlencode($paramVal);
 		}
 		
 		if(preg_match("/(\?)/", $url))
 		{
 			return $url."&".$paramKey."=".$paramVal;	
 		}
 		return $url."?".$paramKey."=".$paramVal;
 	}
 	
 	public static function chooseRandomAssocOption($options)
 	{
 		if(is_array($options)){
 			$key = array_rand($options,1);
 			return $options[$key];
 		}
 		return "";
 	}
 	
 	public static function chooseRandomOption($options)
 	{
 		if(is_array($options)){
 			$index = rand(0, count($options)-1);
 			return $options[$index];
 		}
 		return "";
 	}
 	
 	public static function createRandomString($length=7, $onlyAlpha=false, $onlyDigits=false) { 
 		$chars = "abcdefghijkmnopqrstuvwxyz023456789"; 
 		if($onlyAlpha){
 			$chars = "abcdefghijkmnopqrstuvwxyz";
 		}
 		else if($onlyDigits){
 			$start = str_pad("1", $length, "0", STR_PAD_RIGHT);
 			$end = str_pad("9", $length, "9", STR_PAD_RIGHT);
 			return rand($start, $end);
 		}
	    srand((double)microtime()*1000000); 
	    $i = 0; 
	    $pass = '' ; 
	
	    while ($i <= $length) { 
	        $num = rand() % 33; 
	        $tmp = substr($chars, $num, 1); 
	        $pass = $pass . $tmp; 
	        $i++; 
	    } 
	
	    return $pass; 
	} 
 	
 	public static function calculateDaysDiff($startDate, $endDate)
 	{
 		$day = 86400; 
		$start_time = strtotime($startDate);
		$end_time = strtotime($endDate); 
		
		return (round($end_time - $start_time) / $day) + 1;
 	}
 	
 	public static function getFilesFromDir($directory, $recursive = false, $includeDirs = false, $pattern = '/.*/')
	{
		$items = array();
		
		if($handle = opendir($directory)) {
			while (($file = readdir($handle)) !== false) {
				if ($file != '.' && $file != '..') {
					$path = "$directory/$file";
					$path = preg_replace('#//#si', '/', $path);
					if (is_dir($path)) {
						if ($includeDirs) {
							$items[] = $path;
						}
						if ($recursive) {
							$items = array_merge($items, self::getFilesFromDir($path, true, $includeDirs, $pattern));
						}
					}
					else {
						if (preg_match($pattern, $file)) {
							$items[] = $path;
						}
					}
				}
			}
			
			closedir($handle);
		}
		
		sort($items);
		
		return $items;
	}
	
	public static function getClientIPAddress()
	{
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) 
	    {
	      $ip = $_SERVER['HTTP_CLIENT_IP'];
	    }
	    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
	    {
	      $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	    }
	    else
	    {
	      $ip = $_SERVER['REMOTE_ADDR'];
	    }
	    
	    //don't use ipv6 for local installs
	    if (preg_match("/::/",$ip) && isLocalInstall())
	    {
	    	$ip = "127.0.0.1";
	    }
	    return $ip;
	}
	
	public static function getIcon($iconName, $color="", $fontSize="", $offset="", $title="", $addlStyles="")
	{
		$iconStr = "<i class=\"fa fa-{$iconName} mm-icon {$color}\" style=\"";
		
		if(!empty($fontSize))
		{
			$iconStr .= " font-size:{$fontSize};";
		}
		
		if(!empty($offset))
		{
			$iconStr .= " position:relative; top:{$offset};";
		}
		
		if(!empty($addlStyles))
		{
			$iconStr .= " {$addlStyles}";
		}
		
		$iconStr .= "\"></i>";
		
		if(!empty($title))
		{
			$iconStr = "<a title=\"{$title}\">{$iconStr}</a>";
		}
		
		return $iconStr;
	}
	
	public static function getInfoIcon($description="", $addlStyles='margin-left:4px;', $onClickHandler="")
	{
		if(empty($onClickHandler))
		{
			return MM_Utils::getIcon('info-circle', 'blue', '1.3em', '2px', $description, $addlStyles);
		}
		else
		{
			return "<a onclick='{$onClickHandler}' style='cursor:pointer;' title='{$description}'>".MM_Utils::getIcon('info-circle', 'blue', '1.3em', '2px', '', $addlStyles)."</a>";
		}
	}
	
	public static function getEditIcon($description="", $addlStyles='', $actionString="", $isDisabled=false)
	{
		$color = ($isDisabled) ? "grey" : "yellow";
		
		if(empty($actionString) || $isDisabled)
		{
			return MM_Utils::getIcon('pencil', $color, '1.3em', '2px', $description, $addlStyles);
		}
		else
		{
			return "<a {$actionString} style='cursor:pointer; {$addlStyles}' title='{$description}'>".MM_Utils::getIcon('pencil', $color, '1.3em', '2px', '', '')."</a>";
		}
	}
	
	public static function getDeleteIcon($description="", $addlStyles='', $actionString="", $isDisabled=false)
	{
		$color = ($isDisabled) ? "grey" : "red";
		
		if(empty($actionString) || $isDisabled)
		{
			return MM_Utils::getIcon('trash-o', $color, '1.3em', '2px', $description, $addlStyles);
		}
		else
		{
			return "<a {$actionString} style='cursor:pointer; {$addlStyles}' title='{$description}'>".MM_Utils::getIcon('trash-o', $color, '1.3em', '2px', '', '')."</a>";
		}
	}
	
	public static function getAccessIcon($accessType, $description='', $addlStyles='')
	{
		switch($accessType)
		{
			case MM_OrderItemAccess::$ACCESS_TYPE_BUNDLE:
				$description = (empty($description)) ? "Bundle" : $description;
				return MM_Utils::getIcon('cube', 'yellow', '1.3em', '2px', $description, $addlStyles);
				break;
				
			case MM_OrderItemAccess::$ACCESS_TYPE_MEMBERSHIP:
				$description = (empty($description)) ? "Membership Level" : $description;
				return MM_Utils::getIcon('user', 'blue', '1.3em', '2px', $description, $addlStyles);
				break;
		}
	}
	
	public static function getDefaultFlag($description="", $actionString="", $isDefault=false, $addlStyles="")
	{
		if($isDefault)
		{
			return MM_Utils::getIcon('flag', 'orange', '1.3em', '2px', $description, $addlStyles);
		}
		else
		{
			return "<a {$actionString} style='cursor:pointer; {$addlStyles}' title='{$description}'>".MM_Utils::getIcon('flag-o', 'grey', '1.3em', '2px', '', '')."</a>";
		}
	}
	
	public static function getCalendarIcon()
	{
		return MM_Utils::getIcon('calendar', 'blue', '1.2em', '1px');
	}
	
	public static function getCheckIcon($description="")
	{
		return MM_Utils::getIcon('check', 'green', '1.3em', '1px', $description);
	}
	
	public static function getCrossIcon($description="")
	{
		return MM_Utils::getIcon('times', 'red', '1.3em', '1px', $description);
	}
	
	public static function getAffiliateIcon($description="", $addlStyles="")
	{
		return MM_Utils::getIcon('bullhorn', 'orange', '1.4em', '2px', $description, $addlStyles);
	}
	
	public static function getDiscountIcon($description="", $addlStyles="")
	{
		return MM_Utils::getIcon('ticket', 'purple', '1.4em', '2px', $description, $addlStyles);
	}
	
 	public static function getImageUrl($imageName)
 	{
 		$imageUrl = MM_IMAGES_URL;
 		
 		if(MM_Utils::isSSL())
 		{
 			$imageUrl = preg_replace("/(http\:)/", "https:", $imageUrl);
 		}
 		
 		$imageType = "";
 		if (strpos($imageName,"/") !== false)
 		{
 			$split = explode("/", $imageName);
 			if (count($split) >1)
 			{
 				$imageType = strtolower($split[0]);
 			}
 		}
 		
 		switch ($imageType)
 		{
 			case 'dashboard':
 				$central = preg_replace("/^(http:|https:)/","",MM_PRETTY_CENTRAL_SERVER_URL); //remove the scheme so the browser can adjust based on secure/non-secure (rfc 1808)
 				$imageUrl = $central."/images/{$imageName}.png";
 				break;	
 			default:
 				if(file_exists(MM_IMAGES_PATH."/".$imageName.".png")) 
 				{
 					$imageUrl .= $imageName.".png";
 				}
 				else if(file_exists(MM_IMAGES_PATH."/".$imageName.".jpg"))
 				{
 					$imageUrl .= $imageName.".jpg";
 				}
 				else if(file_exists(MM_IMAGES_PATH."/".$imageName.".gif"))
 				{
 					$imageUrl .= $imageName.".gif";
 				}
 				else if(file_exists(MM_IMAGES_PATH."/".$imageName.".svg"))
 				{
 					$imageUrl .= $imageName.".svg";
 				} 
 				break;
 		}
 		
 		return $imageUrl;
 	}
 	
 	public static function createOptionsArray($obj, $idLabel, $valueLabel)
 	{
 		$retArr = array();
 		
 		if(is_array($obj))
 		{
 			foreach($obj as $row)
 			{
 				if(isset($row->$idLabel) && isset($row->$valueLabel))
 				{
 					$retArr[$row->$idLabel] = $row->$valueLabel;
 				}
 			}
 		}
 		return $retArr;
 	}
 	
 	public static function constructPageUrl() 
 	{
		$pageURL = (isset($_SERVER["HTTPS"]) && ($_SERVER["HTTPS"] == "on")) ? "https://" : "http://";
		
		if ($_SERVER["SERVER_PORT"] != "80" && $_SERVER["SERVER_PORT"] != "443") {
			$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		} else {
			$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		}
		
		return $pageURL;
	}
 	
 	public static function getStatusImage($status) 
 	{
	 	if($status == '1') {
	 		return MM_Utils::getIcon('circle', 'green', '1em', '2px', 'Active');
	    }
	    else if($status == '0') {
	 		return MM_Utils::getIcon('circle-o', 'red', '1em', '2px', 'Inactive');
	    }
	    else
	    {
	    	return MM_NO_DATA;
	    }
 	}
	
	/**
	 * This function returns a user object based on if a member is logged in or an admin. If an 
	 * admin is logged in, a user object will be returned based on the current preview bar settings
	 */
	public static function getCurrentUser()
	{
		global $user, $current_user;
		 
		$user_obj = null;
		 
		if(MM_Employee::isEmployee())
		{
			$previewObj = MM_Preview::getData();
			
			if($previewObj !== false)
			{
				return $previewObj->getUser();
			}
		}
		 
		if(isset($user->ID) && intval($user->ID) > 0)
		{
			$user_obj = new MM_User($user->ID);
		}
		else if(isset($user->data->ID) && intval($user->data->ID)>0)
		{
			$user_obj = new MM_User($user->data->ID);
		}
		else if(isset($current_user->ID) && intval($current_user->ID) > 0)
		{
			$user_obj = new MM_User($current_user->ID);
		}
		 
		return $user_obj;
	}
	
	public static function cacheIsWriteable()
	{
		$cacheDir = self::getCacheDir();
		if (!is_writeable($cacheDir))
		{
			@chmod($cacheDir,0777);	//first see if we can make it writeable
		}
		return is_writable($cacheDir);
	}
	
	public static function getCacheDir()
	{
		$cacheDir = MM_PLUGIN_ABSPATH."/com/membermouse/cache";
		return $cacheDir;
	}
	
	public static function getPluginWarnings()
	{
		$problemPlugins = array();
		$problemPlugins["W3 Total Cache"] = "w3-total-cache/w3-total-cache.php";
		$problemPlugins["WP Super Cache"] = "wp-super-cache/wp-cache.php";
		
		$plugins = get_option('active_plugins');
		
		foreach($problemPlugins as $name=>$location)
		{
			if(in_array($location, $plugins))
			{
				if(!MM_Messages::isMessageHidden($location))
				{
					$hideWarningUrl = MM_Messages::getHideMessageUrl(self::constructPageUrl(), $location);
					MM_Messages::addError("<strong>MemberMouse Warning</strong>: The <em>{$name}</em> plugin is known to cause issues with MemberMouse. <a href='https://membermouse.uservoice.com/knowledgebase/articles/319203-plugins-that-cause-problems' target='_blank'>Learn more</a> | <a href='{$hideWarningUrl}'>Hide this warning</a>");
				}
			}
		}
		
		// check if client is using WP Engine
		if(class_exists("WPE_API", false) || defined("WPE_APIKEY") || defined("WPE_ISP")) 
		{
			if(!MM_Messages::isMessageHidden("wp-engine-warning"))
			{
				$hideWarningUrl = MM_Messages::getHideMessageUrl(self::constructPageUrl(), "wp-engine-warning");
				MM_Messages::addError("<strong>MemberMouse Warning</strong>: You're using WP Engine. Follow the instructions in this article to <a href='http://support.membermouse.com/knowledgebase/articles/356866-configuring-wp-engine-hosting' target='_blank'>work with WP Engine to ensure your server is configured to allow MemberMouse to run properly</a>. | <a href='{$hideWarningUrl}'>Hide this warning</a>");
			}
		}
	}
 }