<?php

class ReleaseController implements RestController 
{
    function execute(RestServer $rest) {}
    
    public function deployRelease($rest)
    {
    	$post = $rest->getRequest()->getPost();
    	
        MM_LogApi::logRequest(json_encode($post), "/deployRelease");
    
        if(!isset($post["version"]))
        {
        	return new Response($rest,  "Major version number is required", RESPONSE_ERROR_MESSAGE_MISSING_PARAMS." : version", RESPONSE_ERROR_CODE_MISSING_PARAMS, RESPONSE_ERROR_MESSAGE_MISSING_PARAMS);
        }
        
        $majorVersion = $post["version"];
        $minorVersion = (isset($post["minor_version"])) ? $post["minor_version"] : MM_MemberMouseService::$DEFAULT_MINOR_VERSION;
        
        $crntVersion = MM_MemberMouseService::getPluginVersion();
        
        if($crntVersion != $majorVersion)
        {
        	MM_OptionUtils::setOption(MM_OptionUtils::$OPTION_KEY_UPGRADE_NOTICE, $majorVersion);
        	return new Response($rest, "Major version do not match. A notification will be displayed to the customer informing them an update is available.", "Major version do not match. A notification will be displayed to the customer informing them an update is available.", RESPONSE_ERROR_CODE_BAD_REQUEST, RESPONSE_ERROR_MESSAGE_MISSING_PARAMS);
        }
        
        // if major versions match, update cache with the latest files from central
        $writeableDir = MM_Utils::getCacheDir();
        
        // delete existing cache
        if(is_dir($writeableDir))
        {
        	if(is_writeable($writeableDir))
        	{
	        	if ($handle = opendir($writeableDir)) 
	        	{
				    while(false !== ($file = readdir($handle))) 
				    {
				        if(!is_dir($file))
				        {
				        	@unlink($writeableDir."/".$file);
				        }
				    }
				    
				    closedir($handle);
				}
        	}
        }
        
        // get updated classes from central
        MM_OptionUtils::setOption(MM_OptionUtils::$OPTION_KEY_MINOR_VERSION, $minorVersion);
		$ret = MM_MemberMouseService::authorize(true);
		
		if(MM_Response::isError($ret))
		{
			return new Response($rest, "Could not find classes associated with version {$majorVersion}.{$minorVersion}", "Invalid major/minor version combination", RESPONSE_ERROR_CODE_BAD_REQUEST, RESPONSE_ERROR_MESSAGE_MISSING_PARAMS);
		}
        
        if(defined("DB_NAME"))
        {
        	global $wpdb;
        	
        	if(file_exists($writeableDir."/membermouse_schema.sql"))
        	{	
				$phpObj = new MM_PhpObj($wpdb, DB_NAME);
				if(!$phpObj->importFile($writeableDir."/membermouse_schema.sql", true))
				{
		        	return new Response($rest, "Could not update MemberMouse database", "Could not update MemberMouse database", RESPONSE_ERROR_CODE_BAD_REQUEST, RESPONSE_ERROR_MESSAGE_MISSING_PARAMS);
				}
        	}
        }
   		else
   		{
   			return new Response($rest,  "DB_NAME not defined", "DB_NAME not defined", RESPONSE_ERROR_CODE_BAD_REQUEST, RESPONSE_ERROR_MESSAGE_MISSING_PARAMS);
   		}
		        
   		$version = $majorVersion;
   		
   		if(!empty($minorVersion))
   		{
   			$version .= "-".$minorVersion;
   		}
   		
   		$versionRelease = MM_VersionRelease::findByVersion($version);
   		$versionRelease->setVersion($version);
   		$versionRelease->commitData();
   		
   		return new Response($rest);
    } 
    
    /**
     * Diagnostic method that responds if MemberMouse is active and working correctly
     * 
     * @param RestServer $rest An instance of the RestServer
     * @return Response object with status 200 containing if MemberMouse is installed, receives the request, and is able to respond
     */
    public function ping($rest)
    {
    	$result = MM_MemberMouseService::ping();
    	
    	if($result instanceof MM_Response)
    	{
    		return new Response($rest, $result->message, $result->message, RESPONSE_ERROR_CODE_INTERNAL, RESPONSE_ERROR_CODE_INTERNAL);
    	}
    	
    	if($result)
    	{
    		return new Response($rest, "PONG", "PONG");
    	}
    	else 
    	{
    		return new Response($rest, "Plugin authorization failed", "Plugin authorization failed", RESPONSE_ERROR_CODE_INTERNAL, RESPONSE_ERROR_MESSAGE_INTERNAL);
    	}
    }
}