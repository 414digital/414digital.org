<?php
/**
 *
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */

require_once("../../../../wp-load.php");
require_once("../includes/mm-constants.php");
require_once("../includes/init.php");

//TODO: test redirect_url parameters

function processLogin($request,$provider)
{
	//don't attempt to login already logged in users
	if (MM_Utils::isLoggedIn())
	{
		$loggedInUser = MM_Utils::getCurrentUser();
		$redirectUrl = isset($request['redirect_url'])?$request['redirect_url']:MM_CorePageEngine::getUrl(MM_CorePageType::$MEMBER_HOME_PAGE,null,$loggedInUser);
		wp_redirect($redirectUrl);
		exit;
	}
	
	//either login using a linked account, or using the email (if provided) by the social media account, with that order of precedence
	$authResponse = $provider->authenticate();
	if (!MM_Response::isSuccess($authResponse))
	{
		//error authenticating
		throw new Exception("Error authenticating with social network","1001015");
	}
	
	$profileResponse = $provider->getUserProfile();
	if (!MM_Response::isSuccess($profileResponse))
	{
		//error retrieving profile
		throw new Exception("Error retrieving social network profile","1001005");
	}
	$profile = $profileResponse->message;
	if (!isset($profile->identifier) || empty($profile->identifier))
	{
		//invalid profile identifier
		throw new Exception("Error retrieving social network profile identier or identifier was invalid","1001006");
	}
	$socialNetworkUniqueIdentifier = $profile->identifier;
	$userAccountResponse = $provider->findLinkedUserByIdentifier($socialNetworkUniqueIdentifier);
	if (MM_Response::isSuccess($userAccountResponse))
	{
		$loginUser = $userAccountResponse->message;
	}
	else if ($provider->getEmailHandlingStrategy() == MM_AbstractSocialLoginExtension::$EMAIL_PROVIDED)
	{
		//couldnt locate a linked account, either because it doesnt exist or there was an error, try using profile email
		$email = isset($profile->emailVerified)?$profile->emailVerified:(isset($profile->email)?$profile->email:"");
		if (empty($email))
		{
			throw new Exception("Unable to login: account not linked and no user account found with the supplied email","1001002");
		}
		$loginUser = MM_User::findByEmail($email);
		if (!$loginUser->isValid())
		{
			throw new Exception("Unable to login: account not linked and no valid user account found with the supplied email","1001003");
		}
	}
	else
	{
		throw new Exception("Unable to login: account not linked and provider doesn't supply email","1001001");
	}
	
	//we have the user now
	$userHooks = new MM_UserHooks();
	$redirectUrl = isset($request['redirect_url'])?$request['redirect_url']:MM_CorePageEngine::getUrl(MM_CorePageType::$MEMBER_HOME_PAGE,null,$loginUser);
	$userHooks->doAutoLogin($loginUser->getId(),$redirectUrl);
	
	//end login block
	exit;
}


function processSignup($request,$provider)
{
	//don't attempt to signup already logged in users
	if (MM_Utils::isLoggedIn())
	{
		$loggedInUser = MM_Utils::getCurrentUser();
		$redirectUrl = isset($request['redirect_url'])?$request['redirect_url']:MM_CorePageEngine::getUrl(MM_CorePageType::$MEMBER_HOME_PAGE,null,$loggedInUser);
		wp_redirect($redirectUrl);
		exit;
	}
	
	if (!$provider->allowsSignups())
	{
		//configuration does not allow signups
		throw new Exception("Signups not allowed","1001007");
	}
	
	if (isset($request['membership_level']))
	{
		$membershipLevel = trim($request['membership_level']);
		$membershipLevel = htmlentities($membershipLevel);
		if (!is_numeric($membershipLevel))
		{
			//membership level was not passed as a valid id
			throw new Exception("Invalid Membership Level","1001008");
		}
	}
	else
	{
		$membershipLevel = $provider->getSignupMembershipLevel();
		if (!is_numeric($membershipLevel) || ($membershipLevel == 0))
		{
			//should never happen - default signup membership level is invalid
			throw new Exception("Invalid Default Membership Level","1001009");
		}
	}
	
	//ensure that if the chosen provider doesnt allow access to the email, and the provider is configured not to generate one, that one was supplied
	if (($provider->getEmailHandlingStrategy() == MM_AbstractSocialLoginExtension::$EMAIL_RETRIEVED_BY_POPUP) && empty($request['email']))
	{
		//email required but not supplied
		throw new Exception("No email supplied","1001010");
	}
	
	//Authenticate with the provider, and retrieve the remote user profile
	$authResponse = $provider->authenticate();
	if (!MM_Response::isSuccess($authResponse))
	{
		//error authenticating
		throw new Exception("Error authenticating with social network","1001016");
	}
	$profileResponse = $provider->getUserProfile();
	
	if (!MM_Response::isSuccess($profileResponse))
	{
		//retrievng profile failed
		throw new Exception("Unable to retrieve profile from social network","1001011");
	}
	
	$profile = $profileResponse->message;
	
	if (!isset($profile->identifier) || empty($profile->identifier))
	{
		//invalid social network identifier returned
		throw new Exception("Invalid social network identifier","1001012");
	}
	
	//Populate memberinfo with the necessary member information, in the expected format
	$memberInfo = array();
	$memberInfo["membership_level"] = $membershipLevel;
	
	if (isset($profile->firstName) && !empty($profile->firstName))
	{
		$memberInfo['first_name'] = $profile->firstName;
	}
	
	if (isset($profile->lastName) && !empty($profile->lastName))
	{
		$memberInfo['last_name'] = $profile->lastName;
	}
	
	$emailHandlingStrategy = $provider->getEmailHandlingStrategy();
	
	if ($emailHandlingStrategy == MM_AbstractSocialLoginExtension::$EMAIL_RETRIEVED_BY_POPUP)
	{
		//TODO: sanitize email
		$memberInfo['email'] = $request['email'];
	}
	else if ($emailHandlingStrategy == MM_AbstractSocialLoginExtension::$EMAIL_PROVIDED)
	{
		if (isset($profile->emailVerified) && !empty($profile->emailVerified))
		{
			$memberInfo['email'] = $profile->emailVerified;
		}
		else if (isset($profile->email) && !empty($profile->email))
		{
			$memberInfo['email'] = $profile->email;
		}
		else
		{
			throw new Exception("Social Network provider was supposed to supply user email, but did not","1001013");
		}
	}
	else if ($emailHandlingStrategy == MM_AbstractSocialLoginExtension::$EMAIL_GENERATE_BOGUS_EMAIL)
	{
		$bogusUser = MM_Utils::createRandomString(8,true).MM_Utils::createRandomString(24);
		//TODO: tag user portion of email with social network identifier
		$bogusDomain = "example.com";
		$memberInfo['email'] = "{$bogusUser}@{$bogusDomain}";
	}
	
	$socialSignupRequest = new MM_SocialLoginRequest($memberInfo);
	$response = $socialSignupRequest->submitRequest();
	if (MM_Response::isSuccess($response))
	{
		$newUser = $socialSignupRequest->getNewUser();
		$provider->linkUserToSocialMediaAccount($newUser, $profile->identifier);
		$socialSignupRequest->completeSignup();
		exit;
	}
	else
	{
		if (strpos($response->message,"already exists") !== false)
		{
			//the member signing up already exists, send them to login instead
			processLogin($request,$provider);
			exit;
		}
		throw new Exception($response->message,"1001014");
	}
	
	//end signup block
	exit;
}

try 
{
	$request = $_GET + $_POST;
	if (!isset($request['cmd']) || !isset($request['provider']))
	{
		//script called incorrectly
		throw new Exception("Social login authenticator called incorrectly","1001003");
	}
	$providerToken = ucfirst(strtolower(htmlentities($request['provider']))); //hybridauth requires providers be all lowercase with the first letter capitalized
	
	$provider = MM_ExtensionsFactory::getExtension($providerToken);
	if (is_null($provider) || !($provider instanceof MM_AbstractSocialLoginExtension) || (!$provider->isActive()))
	{
		//can't access requested provider
		throw new Exception("Requested Social Login Provider not found","1001004");
	}
	
	
	if ($request['cmd'] == "login")
	{
		processLogin($request,$provider);
		exit;
	}
	else if ($request['cmd'] == "signup")
	{
		processSignup($request,$provider);
		exit;	
	}
}
catch (Exception $e)
{
	//redirect to error page
	$url = MM_CorePageEngine::getUrl(MM_CorePageType::$ERROR, MM_Error::$ACCESS_DENIED)."&slcode={$e->getCode()}";
	LogMe::write($e->getCode().":".$e->getMessage());
	wp_redirect($url);
	exit;
}
?>