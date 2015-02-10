<?php
// bootstrap wp
require_once("../../../../wp-load.php");

require_once("../includes/mm-constants.php");
require_once("../includes/init.php");
require_once("classes/class.response.php");
require_once("classes/class.utils.php");
require_once("include/loadLibrary.php");
require_once('controllers/class.webcontroller.php');
require_once('controllers/class.membercontroller.php');
require_once('controllers/class.releasecontroller.php');
require_once('include/constants.php');

$_GET["q"] = (isset($_GET["q"]) && $_GET["q"] != null)?$_GET["q"]:"";

$rest = new RestServer($_GET["q"]);

$ref = (isset($_SERVER["HTTP_REFERER"]))?$_SERVER["HTTP_REFERER"]:"";
$ip = (isset($_SERVER["REMOTE_ADDR"]))?$_SERVER["REMOTE_ADDR"]:"";

$rest->addMap("GET","/?","WebController");

if(MM_Utils::isMemberMouseActive() && MM_MemberMouseService::hasPermission(MM_MemberMouseService::$FEATURE_API)) 
{
	$rest->addMap("GET","/createMember","MemberController::createMember"); 
	$rest->addMap("POST","/createMember","MemberController::createMember"); 
	$rest->addMap("GET","/purchaseBundle","MemberController::purchaseBundle"); 
	$rest->addMap("POST","/purchaseBundle","MemberController::purchaseBundle");
	$rest->addMap("GET","/updateMember","MemberController::updateMember"); 
	$rest->addMap("POST","/updateMember","MemberController::updateMember"); 
	$rest->addMap("GET","/getMember","MemberController::getMember"); 
	$rest->addMap("POST","/getMember","MemberController::getMember");  
}

$rest->addMap("GET","/deployRelease","ReleaseController::deployRelease");
$rest->addMap("POST","/deployRelease","ReleaseController::deployRelease"); 
$rest->addMap("GET","/ping","ReleaseController::ping");
$rest->addMap("POST","/ping","ReleaseController::ping");

echo $rest->execute();

?>
