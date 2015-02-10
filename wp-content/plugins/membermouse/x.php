<?php
/**
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 * 
 * This script processes payment notifications/callbacks from payment service providers, and routes these messages to the appropriate services
 * A url parameter "service" is expected, with the value containing the token identifier for the service the message is intended for
 * For example, a PayPal IPN notification is expected to be received at http://<sitename>/wp-content/plugins/membermouse/x.php?service=paypal
 * 
 * The placement and name of this script are intended to keep the url as small as possible, under the circumstances
 * 
 */
require_once("../../../wp-load.php");
require_once("includes/mm-constants.php");
require_once("includes/init.php");

$request = isset($_POST)?$_POST:array();
if (isset($_GET['service']) || isset($_POST['service']))
{
	$serviceToken = isset($_GET['service'])?strtoupper($_GET['service']):strtoupper($_POST['service']);
	$service = MM_PaymentServiceFactory::getPaymentService($serviceToken);
	$response = (!is_null($service))?$service->processNotification($request):new MM_PaymentServiceResponse("Improper notification format received",MM_PaymentServiceResponse::$ERROR);
}
else
{
	//unspecified service (which means improper remote setup), see if there is a default payment service that can/will handle it
	$serviceArray = MM_PaymentServiceFactory::getPaymentServicesArray();
	if (count($serviceArray) == 1)
	{
		$service = array_pop($serviceArray);
		$response = $service->processNotification($request);
	}
	else
	{
		$response = new MM_PaymentServiceResponse("Improper notification format received",MM_PaymentServiceResponse::$ERROR);
	}
}

//echo the response to caller
$responseMsg = $response->getMessage();
if (!empty($responseMsg))
{
	echo $responseMsg;
}
?>