<?php 
// This file demonstrates how to handle different bundle events and access the data passed to the script by MemberMouse.

// ---- GET EVENT TYPE ----
if(!isset($_GET["event_type"])) 
{
	// event type was not found, so exit
	exit;
}
else 
{
	$eventType = $_GET["event_type"];
}


// ---- ACCESS DATA ----
// member data
$memberId = $_GET["member_id"];
$registeredDate = $_GET["registered"];
$lastLoginDate = $_GET["last_logged_in"];
$lastUpdatedDate = $_GET["last_updated"];
$daysAsMember = $_GET["days_as_member"];
$status = $_GET["status"];
$statusName = $_GET["status_name"];
$membershipLevelId = $_GET["membership_level"];
$membershipLevelName = $_GET["membership_level_name"];
$username = $_GET["username"];
$email = $_GET["email"];
$phone = $_GET["phone"];
$firstName = $_GET["first_name"];
$lastName = $_GET["last_name"];
$billingAddress = $_GET["billing_address"];
$billingCity = $_GET["billing_city"];
$billingState = $_GET["billing_state"];
$billingZipCode = $_GET["billing_zip_code"];
$billingCountry = $_GET["billing_country"];
$shippingAddress = $_GET["shipping_address"];
$shippingCity = $_GET["shipping_city"];
$shippingState = $_GET["shipping_state"];
$shippingZipCode = $_GET["shipping_zip_code"];
$shippingCountry = $_GET["shipping_country"];

// custom field data
// You can access custom field data by accessing the get parameter cf_# where # is the
// ID of the custom field
//$exampleCustomData = $_GET["cf_1"];

// bundle data
$bundleId = $_GET["bundle_id"];
$bundleName = $_GET["bundle_name"];
$daysWithBundle = $_GET["days_with_bundle"];
$bundleStatus = $_GET["bundle_status"];
$bundleStatusName = $_GET["bundle_status_name"];
$bundleDateAdded = $_GET["bundle_date_added"];
$bundleLastUpdatedDate = $_GET["bundle_last_updated"];


// ---- EVENT TYPES ----
$BUNDLE_ADD = "mm_bundles_add";
$BUNDLE_STATUS_CHANGE = "mm_bundles_status_change";


// ---- PERFORM ACTION BASED ON EVENT TYPE ----
switch($eventType)
{
	case $BUNDLE_ADD:
		// do something
		break;
		
	case $BUNDLE_STATUS_CHANGE:
		// do something
		break;
}
?>