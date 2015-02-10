<?php
/**
 *
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */

define("MM_USE_STREAM_WRAPPERS", "0");
if(!defined("BASE_DIR"))
{
	define("BASE_DIR", dirname(dirname(__FILE__))."/api/");
}

function useStreamWrappers()
{
	return MM_USE_STREAM_WRAPPERS==="1";
}

function loadFileContents($path)
{
	if(file_exists($path))
	{
		$contents = file_get_contents($path);
		if($contents !== false)
		{
			return base64_decode(stripslashes($contents));
		}
	}
	return false;
}

function showLoadedClasses($class, $str="")
{
	if(isset($_GET["debug"]))
	{
		if($_GET["debug"]=="log")
		{
			LogMe::write($class." ".$str);
		}
		else
		{
			echo $class." ".$str."<br />";
		}
	}
}

class MM_ClassLoader
{	
	private static function includeLocalFiles($className,$classFileName)
	{
		if(defined("MM_PLUGIN_ABSPATH"))
		{	
			if(preg_match("/(hooks)/", $className))
			{
				$dir = "hooks";
				$file = MM_PLUGIN_ABSPATH."/".$dir."/class.".strtolower($classFileName).".php";
				$ifile = MM_PLUGIN_ABSPATH."/".$dir."/interface.".strtolower($classFileName).".php";
				
				if(file_exists($file))
				{
					require_once($file);
					return true;
				}
				
				if(file_exists($ifile))
				{
					require_once($ifile);
					return true;
				}
			}
			else
			{
				$dirs = array();
				$dirs[] = "bootstrap";
				$dirs[] = "com/membermouse/service";
				$dirs[] = "com/membermouse/engine";
				$dirs[] = "com/membermouse/entity";
				$dirs[] = "com/membermouse/import";
				$dirs[] = "com/membermouse/view";
				$dirs[] = "com/membermouse/engine";
				$dirs[] = "com/membermouse/util";
				$dirs[] = "com/membermouse/esp";
				$dirs[] = "com/membermouse/esp/util";
				$dirs[] = "com/membermouse/esp/aweber";
				$dirs[] = "com/membermouse/shipping";
				$dirs[] = "com/membermouse/payment";
				$dirs[] = "com/membermouse/forms";
				$dirs[] = "com/membermouse/smarttags";
				$dirs[] = "com/membermouse/widgets";
				$dirs[] = "com/membermouse/orderrequest";
				$dirs[] = "com/membermouse/affiliate";
				$dirs[] = "com/membermouse/payment";
				$dirs[] = "com/membermouse/shipping";
				$dirs[] = "com/membermouse/scheduler";
				$dirs[] = "com/membermouse/extensions";
				$dirs[] = "com/membermouse/reporting";
				$dirs[] = "com/membermouse/reporting/components";
				
				
				foreach($dirs as $dir)
				{
					$file = MM_PLUGIN_ABSPATH."/".$dir."/class.".strtolower($classFileName).".php";
					$ifile = MM_PLUGIN_ABSPATH."/".$dir."/interface.".strtolower($classFileName).".php";
					
					if(file_exists($file))
					{		
						require_once($file);
						return true;
					}
					
					if(file_exists($ifile))
					{
						require_once($ifile);
						return true;
					}
				}
			}
		}
		return false;
	}
	
	private static function includeDBClass($className,$classFileName)
	{
		global $wpdb;
		$sql = "select obj from ".MM_TABLE_CONTAINER." where LOWER(name)='%s'";
		$row = $wpdb->get_row($wpdb->prepare($sql, strtolower($classFileName)));
		if(isset($row->obj) && !empty($row->obj)){
			$classObj = base64_decode(stripslashes($row->obj));
			if(useStreamWrappers()){
				if (!in_array('membermouseStream', stream_get_wrappers())) 
				{
					stream_wrapper_register("membermouseStream", "MM_MemberMouseStream");
	        	}
				include('membermouseStream://classObj');
			}
			if(!class_exists($className, false) && !interface_exists($className,false))
			{
				$classObj = preg_replace("/^(<\?php)/", "", $classObj);
				if(@eval($classObj)!==false)
				{
					if(class_exists($className, false) || interface_exists($className,false))
					{
	        			showLoadedClasses($className, "<b>DB Class</b> Added {$className}");
	        			return true;
					}
				}
			}
		}
		return false;
	}
	
	private static function includeLimitedLocalFiles($className, $classFileName)
	{
		if(defined("MM_PLUGIN_ABSPATH"))
		{	
			if(preg_match("/(hooks)/", $className))
			{
				$dir = "hooks";
				$file = MM_PLUGIN_ABSPATH."/".$dir."/class.".strtolower($classFileName).".php";
				$ifile = MM_PLUGIN_ABSPATH."/".$dir."/interface.".strtolower($classFileName).".php";
				
				if(file_exists($file))
				{
					require_once($file);
					return true;
				}
				
				if(file_exists($ifile))
				{
					require_once($ifile);
					return true;
				}
			}
			else
			{
				$dirs = array("bootstrap","lib", "managers", 'hooks',"com/membermouse/esp/util");
				
				foreach($dirs as $dir)
				{
					$file = MM_PLUGIN_ABSPATH."/".$dir."/class.".strtolower($classFileName).".php";
					$ifile = MM_PLUGIN_ABSPATH."/".$dir."/interface.".strtolower($classFileName).".php";
					
					if(file_exists($file))
					{		
						require_once($file);
						return true;
					}
					
					if(file_exists($ifile))
					{
						require_once($ifile);
						return true;
					}
				}
			}
		}
		return false;
	}
	
	public static function includeCacheFiles($className, $classFileName)
	{
        $writeableDir = MM_PLUGIN_ABSPATH."/com/membermouse/cache";
		$tmpClassName = strtolower($classFileName);
		$cacheFileName= $writeableDir."/".base64_encode($tmpClassName).".cache";
		if(!preg_match("/(membermouseservice)/", strtolower($className)))
		{
			if(is_writeable($writeableDir))
			{
				if(is_dir($writeableDir))
				{
					$classObj = loadFileContents($cacheFileName);
					if(is_writeable($cacheFileName))
					{
						
						if(useStreamWrappers())
						{
							$streamObj = "<?php ".$classObj;
							if (!in_array('membermouseStream', stream_get_wrappers())) 
							{
								stream_wrapper_register("membermouseStream", "MM_MemberMouseStream");
					        }
					        
					        if($classObj !== false && !empty($classObj))
					        {
								include('membermouseStream://streamObj');
					        }
						}
					}
					
		        	if(!class_exists($className,false) && !interface_exists($className))
		        	{
						$classObj = preg_replace("/^(<\?php)/", "", $classObj);
		        		
						if(@eval($classObj) !==false)
						{
				        	if(!class_exists($className,false) && !interface_exists($className,false))
				        	{
								$tmpClassName = strtolower($classFileName);
				        		$cacheFileName= $writeableDir."/".base64_encode($tmpClassName).".cache_1";
								if(is_writeable($cacheFileName))
								{
									if($classObj!==false && @eval($classObj) !==false)
									{
					        			if(class_exists($className,false) || interface_exists($className,false))
					        			{
					        				showLoadedClasses($className, "Eval Added {$className} from cache");
											return true;
					        			}
									}
								}
				        	}
				        	else
				        	{
				        		showLoadedClasses($className, "Eval Added {$className} from cache");
				        		return true;
				        	}
					    }
		        	}
		        	else
		        	{
				        showLoadedClasses($className, "<b>Stream</b> Added {$className} from cache");
		        		return true;
		        	}
			    }
			}
		}
		
		return false;
	}
	
	public static function load($className, $canRecurse=true) 
	{
		if(!$canRecurse)
		{
			LogMe::write("Missing {$className} from cache");
			showLoadedClasses($className, "Eval Missing {$className} from cache");
		}
		
		/** only try to load MemberMouse classes **/
		if ((strpos($className, "MM_") === false) && !preg_match("/LogMe/",$className))
		{
			return false;
		}
		$exclusions = array("MemberMouse");
		if (in_array($className,$exclusions))
		{
			return false;
		}
		/** end exclusions **/
		
		$classFileName = preg_replace("/(MM_)/","",$className);
		
		if(self::includeLimitedLocalFiles($className, $classFileName))
		{
			return true;
		}
		
		$forceUseDBCache = (MM_OptionUtils::getOption(MM_OptionUtils::$OPTION_KEY_FORCE_USE_DB_CACHE) == "1") ? true : false;
		
		if (isLocalInstall("localhost") && !$forceUseDBCache)
		{
			// look locally
			if(self::includeLocalFiles($className, $classFileName))
			{
				return true;
			}
			
			if(class_exists($className,false) || interface_exists($className,false))
			{
				return true;
			}
			else
			{
				if(self::includeCacheFiles($className, $classFileName))
				{
					return true;
				}
			
				/// get db class
				if(self::includeDBClass($className, $classFileName))
				{
					if ($classFileName != "MemberMouseService")
					{
						MM_Session::value(MM_Session::$KEY_USING_DB_CACHE, true);
					}
					return true;
				}
			}
		}
		else 
		{
	        if(class_exists($className,false) || interface_exists($className,false))
	        {
	        	return true;	
	        }
	        else
	        {
				if(self::includeCacheFiles($className, $classFileName))
				{
					return true;
				}
				
	        	// get class from DB
				if(self::includeDBClass($className, $classFileName))
				{
					if ($classFileName != "MemberMouseService")
					{
						MM_Session::value(MM_Session::$KEY_USING_DB_CACHE, true);
					}
					return true;
				}
        	}
        	
        	// look locally
        	if(self::includeLocalFiles($className, $classFileName))
        	{
        		return true;
        	}
		}
		
		//if execution gets here, then a needed class is unloadable, meaning its not in the cache or in the dbcache
		//reauth if we haven't already done so in the last 10 mins, and attempt to populate both
		$lastAuth = MM_OptionUtils::getOption(MM_OptionUtils::$OPTION_KEY_LAST_CODE_REFRESH);
		$minInterval = time() - 600; //(600 secs = 10 min)
		if ($canRecurse && class_exists("MM_MemberMouseService") && (empty($lastAuth) || ($lastAuth <= $minInterval)))
		{			
			$authSuccess = MM_MemberMouseService::authorize();
			MM_OptionUtils::setOption(MM_OptionUtils::$OPTION_KEY_LAST_CODE_REFRESH, time());
			if ($authSuccess)
			{
				return MM_ClassLoader::load($className,false); //this will break if the session doesnt work.. but then you have bigger problems...
			}
		}
		return false;
	}
}
spl_autoload_register(array('MM_ClassLoader', 'load'));

?>