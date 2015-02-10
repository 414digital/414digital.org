<?php
class Utils
{	
	public static function isAuthenticated($post)
	{
		if(!isset($post["apikey"]) || !isset($post["apisecret"]))
		{
			return false;
		}
		else
		{
	        if($post["apikey"] == null || $post["apisecret"] == null || !preg_match("/^[a-zA-Z0-9]+$/",$post["apikey"]))
	        {
	        	return false;
	        }
	        
	        global $wpdb;
	      	$sql = "SELECT COUNT(id) as total FROM ".TABLE_ACCESS_KEYS." WHERE "; 
	      	$sql .= "api_key=%s AND api_secret=%s AND status='1';";
	      	$row = $wpdb->get_row($wpdb->prepare($sql,$post["apikey"],$post["apisecret"]));
	      	
	      	if(is_object($row)) 
	      	{
	      		return ($row->total > 0);
	      	}
		}
        return true;
	}
	
	/**
	 * This method takes data from an API request, validates any required params and if all
	 * required parameters are present and valid, returns an object will all the data in it. 
	 * Otherwise, it returns an MM_Response object with an error message
	 * @param array $post API request data
	 * @param stdClass $requiredParams key/value pairs of required fields and a regular expression to validate it's contents
	 * @return stdClass|MM_Response returns an object with API request data or an MM_Response object if there was an error
	 */
	public static function processApiRequestData($post, $requiredParams)
	{
		$data = new stdClass();
		foreach($requiredParams as $key=>$regex)
		{
			if(!isset($post[$key]) || (isset($post[$key]) && !preg_match("/".$regex."/", $post[$key])))
			{
				return new MM_Response(RESPONSE_ERROR_MESSAGE_MISSING_PARAMS." : ".$key, MM_Response::$ERROR);
			}
			else
			{
				$data->$key = stripslashes($post[$key]);
			}
		}
		
		foreach($post as $k=>$v)
		{
			$data->$k = $v;
		}
		return $data;
	}
}