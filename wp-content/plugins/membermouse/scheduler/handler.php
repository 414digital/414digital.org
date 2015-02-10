<?php
/**
 *
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */

require_once("../../../../wp-load.php");
require_once("../includes/mm-constants.php");
require_once("../includes/init.php");

function returnStatus($status, $message)
{
	echo json_encode(array('status'=>$status,'message'=>$message));
	exit(0);
}

$postdata = file_get_contents("php://input");
$request = json_decode($postdata,true);

if (($request === false) || empty($request['reference_id']))
{
	returnStatus('error','Invalid Request');
}

$eventId = $request['reference_id'];
$eventType = $wpdb->get_var("SELECT event_type from ".MM_TABLE_SCHEDULED_EVENTS." where id='{$eventId}'");

switch ($eventType)
{	
	case MM_ScheduledEvent::$PAYMENT_SERVICE_EVENT:
		$paymentEvent = new MM_ScheduledPaymentEvent($eventId);
		$billingStatus = $request['status'];
		$paymentEvent->setBillingStatus($billingStatus);
		if ($paymentEvent->getStatus() == MM_ScheduledEvent::$EVENT_PROCESSED)
		{
			returnStatus("ok","Event {$eventId} already processed");
		}
		$paymentService = MM_PaymentServiceFactory::getPaymentServiceById($paymentEvent->getPaymentServiceId());
		if (is_null($paymentService))
		{
			returnStatus("error","Improper event configuration: Payment service with id {$paymentService->getPaymentServiceId()} not found");
		}
		$response = $paymentService->processScheduledPaymentEvent($paymentEvent);
		if (MM_PaymentServiceResponse::isError($response) || MM_PaymentServiceResponse::isFailed($response))
		{
			returnStatus("error", $response->message);
		}
		returnStatus("ok","");
		break;
	default:
		//TODO: logging
		returnStatus('error','Invalid Event Type');
		break;
}

?>