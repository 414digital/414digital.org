<?php
/**
 *
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */

require_once("../../../../wp-load.php");
require_once("../includes/mm-constants.php");
require_once("../includes/init.php");


//functions
function isAuthenticated($post)
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
		$sql = "SELECT COUNT(id) as total FROM ".MM_TABLE_API_KEYS." WHERE ";
		$sql .= "api_key=%s AND api_secret=%s AND status='1';";
		$row = $wpdb->get_row($wpdb->prepare($sql,$post["apikey"],$post["apisecret"]));

		if(is_object($row))
		{
			return ($row->total > 0);
		}
	}
	return true;
}

if (!isAuthenticated($_POST))
{
	error_log("Access Denied to report data generator");
	exit;
}

if (!isset($_POST['cacheId']) || empty($_POST['cacheId']))
{
	echo "Invalid cache id";
	exit;
}

// Send connection close to allow the caller to continue processing
// ----------------------------------------------------------------
MM_ConnectionUtils::closeConnectionAndContinueProcessing();


// Set operating parameters
// ----------------------------------------------------------------
$maxExecutionTime = 300; //in seconds
$cacheId = $_POST['cacheId'];

// Retrieve and cache data
// ----------------------------------------------------------------
set_time_limit($maxExecutionTime);
$lockName = $wpdb->prepare("mm-report-data-cache-lock-%d",$cacheId);

$lockAcquired = $wpdb->get_var("SELECT COALESCE(GET_LOCK('{$lockName}',10),0)");
if ($lockAcquired != "1")
{
	error_log("Report data generation: Could not acquire lock");
	exit;
}

$notAvailable = false;
$cacheRow = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".MM_TABLE_REPORT_DATA_CACHE." WHERE id=%s",$cacheId));
$previousStatus = $cacheRow->status;
if ($previousStatus == MM_ReportDataRetriever::$DATA_PENDING_STATUS)
{
	$wpdb->update(MM_TABLE_REPORT_DATA_CACHE,array("status"=>MM_ReportDataRetriever::$DATA_PROCESSING_STATUS),array("id"=>$cacheId));
}
else 
{
	$notAvailable = true;	
}
//release the lock
$wpdb->query("SELECT RELEASE_LOCK('{$lockName}')");


if ($notAvailable)
{
	error_log("Requested cache entry is already being processed or is not available for processing");
	exit;
}

//now route the query to the correct handler, and retrieve the data
try {
	
	//clean expired from the cache first
	MM_ReportDataRetriever::clearDataCache(true);	

	$queryTarget = $cacheRow->query_target;
	$queryIdentifier = $cacheRow->query_token;
	$params = MM_ReportDataRetriever::decodeParams($cacheRow->query_params);
	
	$dataResponse = MM_ReportDataRetriever::generateData($queryTarget, $queryIdentifier, $params, MM_ReportDataRetriever::$DEFAULT_CACHE_TIMEOUT);
	if (MM_Response::isError($dataResponse))
	{
		$wpdb->update(MM_TABLE_REPORT_DATA_CACHE,array("status"=>MM_ReportDataRetriever::$DATA_ERROR_STATUS),array("id"=>$cacheId));
		error_log("Error generating data for query referenced by cache id {$cacheId}:{$dataResponse->message}");
		exit;
	}
	
	//data has been retrieved, update cache table
	$wpdb->update(MM_TABLE_REPORT_DATA_CACHE,array("status"=>MM_ReportDataRetriever::$DATA_READY_STATUS),array("id"=>$cacheId));
	
	//processing complete, terminate disconnected process
	exit;
}
catch (Exception $e)
{
	$wpdb->update(MM_TABLE_REPORT_DATA_CACHE,array("status"=>MM_ReportDataRetriever::$DATA_ERROR_STATUS),array("id"=>$cacheId));
	$error = $e->getMessage();
	error_log("Error generating data for query referenced by cache id {$cacheId}".(empty($error)?"":": {$error}"));
}
?>