<?php

class MemberController implements RestController 
{
	private static $REGEX_INTEGER_ONLY = "^[0-9]+$";
	private static $REGEX_FLOAT_ONLY = "^[0-9\.]+$";
	private static $REGEX_CONTAINS_NUMBERS = "[0-9]+";
	private static $REGEX_CONTAINS_SOMETHING = "[\w\W\-\.]+";
	private static $REGEX_CONTAINS_ALPHA = "[a-zA-Z]+";
	private static $REGEX_BOOLEAN_ONLY = "^(0|1)$";
	private static $REGEX_CONTAINS_EMAIL = "(\@)";
	private static $REGEX_ALPHANUMERIC_ONLY = "^[a-zA-Z0-9\_\-\.\s]+$";
	
    function execute(RestServer $rest) {}
    
    public function getMember($rest)
    {
    	$post = $rest->getRequest()->getPost();
        MM_LogApi::logRequest(json_encode($post), "/getMember");
        
        if(!Utils::isAuthenticated($post))
        {
	    	return new Response($rest, null, RESPONSE_ERROR_MESSAGE_AUTH, RESPONSE_ERROR_CODE_AUTH, RESPONSE_ERROR_MESSAGE_AUTH);
        }
        
		$req = new stdClass();
		if(!empty($post["member_id"]))
		{
			$req->member_id = self::$REGEX_INTEGER_ONLY;
		}
		else
		{
			$req->email = self::$REGEX_CONTAINS_EMAIL;
		}
		$data = Utils::processApiRequestData($post, $req);
        
		if(MM_Response::isError($data))
		{
			return new Response($rest, null, $data->message, RESPONSE_ERROR_CODE_MISSING_PARAMS, RESPONSE_ERROR_MESSAGE_MISSING_PARAMS);
		}
		
		$result = MM_APIService::getMember($data);
		if(MM_Response::isError($result))
		{
	    	return new Response($rest, null, $result->message, RESPONSE_ERROR_CODE_CONFLICT, RESPONSE_ERROR_MESSAGE_CONFLICT);
		}
		
	    return new Response($rest, $result->message);
    }
    
    public function updateMember($rest)
    {	
    	$post = $rest->getRequest()->getPost();
        MM_LogApi::logRequest(json_encode($post), "/updateMember");
        
        if(!Utils::isAuthenticated($post))
        {
	    	return new Response($rest, null, RESPONSE_ERROR_MESSAGE_AUTH, RESPONSE_ERROR_CODE_AUTH, RESPONSE_ERROR_MESSAGE_AUTH);
        }
        
    	$req = new stdClass();
		if(!empty($post["member_id"]))
		{
			$req->member_id = self::$REGEX_INTEGER_ONLY;
		}
		else
		{
			$req->email = self::$REGEX_CONTAINS_EMAIL;
		}
		$data = Utils::processApiRequestData($post, $req);
		
		if(MM_Response::isError($data))
		{
			return new Response($rest, null, $data->message, RESPONSE_ERROR_CODE_MISSING_PARAMS, RESPONSE_ERROR_MESSAGE_MISSING_PARAMS);
		}
        
		$result = MM_APIService::updateMember($data);
		if(MM_Response::isError($result))
		{
			return new Response($rest, null, $result->message, RESPONSE_ERROR_CODE_CONFLICT, RESPONSE_ERROR_MESSAGE_CONFLICT);
		}
		
	    return new Response($rest, $result->message);
    }
    
    public function createMember($rest)
    {
    	$post = $rest->getRequest()->getPost();
        MM_LogApi::logRequest(json_encode($post), "/createMember");
        
        if(!Utils::isAuthenticated($post))
        {
	    	return new Response($rest, null, RESPONSE_ERROR_MESSAGE_AUTH, RESPONSE_ERROR_CODE_AUTH, RESPONSE_ERROR_MESSAGE_AUTH);
        }
        
    	$req = new stdClass();
    	$req->membership_level_id = self::$REGEX_INTEGER_ONLY;
		$req->email = self::$REGEX_CONTAINS_EMAIL;
		$data = Utils::processApiRequestData($post, $req);
		
		if(MM_Response::isError($data))
		{
			return new Response($rest, null, $data->message, RESPONSE_ERROR_CODE_MISSING_PARAMS, RESPONSE_ERROR_MESSAGE_MISSING_PARAMS);
		}
        
		$result = MM_APIService::createMember($data);
		
    	if(MM_Response::isSuccess($result))
		{
			$user = MM_User::findByEmail($data->email);
			
			if(!$user->isValid())
			{
				return new Response($rest, null, "Failed to create user with email address {$data->email}", RESPONSE_ERROR_CODE_CONFLICT, RESPONSE_ERROR_MESSAGE_CONFLICT);
			}
			
			$userData = array(
				'member_id'			=> $user->getId(),
				'username'			=> $user->getUsername(),
				'email' 			=> $user->getEmail(),
				'password'			=> $user->getPassword(),
				'confirmationUrl'	=> $result->getData(MM_Response::$DATA_KEY_URL)
			);

	   		return new Response($rest, $userData, $userData);
		}
		else
		{
	    	return new Response($rest, null, $result->message, RESPONSE_ERROR_CODE_CONFLICT, RESPONSE_ERROR_MESSAGE_CONFLICT);
		}
    }
    
    public function purchaseBundle($rest)
    {
    	$post = $rest->getRequest()->getPost();
    	MM_LogApi::logRequest(json_encode($post), "/purchaseBundle");
    
    	if(!Utils::isAuthenticated($post))
    	{
    		return new Response($rest, null, RESPONSE_ERROR_MESSAGE_AUTH, RESPONSE_ERROR_CODE_AUTH, RESPONSE_ERROR_MESSAGE_AUTH);
    	}
  
    	$req = new stdClass();
    	$req->email = self::$REGEX_CONTAINS_ALPHA;
		$req->product_id = self::$REGEX_INTEGER_ONLY;
		$data = Utils::processApiRequestData($post, $req);
		
		if(MM_Response::isError($data))
		{
			return new Response($rest, null, $data->message, RESPONSE_ERROR_CODE_MISSING_PARAMS, RESPONSE_ERROR_MESSAGE_MISSING_PARAMS);
		}
    
    	$result = MM_APIService::purchaseBundle($data);
    	
    	if(MM_Response::isSuccess($result))
    	{
    		$url = $result->getData(MM_Response::$DATA_KEY_URL);
    		if(!empty($url))
    		{	
	    		$userData = array(
	    			'confirmationUrl' => $result->getData(MM_Response::$DATA_KEY_URL)
	    		);
    	
    			return new Response($rest, $userData, $userData);
    		}
    		else
    		{
    			return new Response($rest, $result->message);
    		}
    	}
    	else
    	{
    		return new Response($rest, null, $result->message, RESPONSE_ERROR_CODE_CONFLICT, RESPONSE_ERROR_MESSAGE_CONFLICT);
    	}
    }
}