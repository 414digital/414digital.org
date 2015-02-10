<?php 
// This file demonstrates how to handle different payment events and access the data passed to the script by MemberMouse.

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

// order data
$orderNumber = $_GET["order_number"];
$orderTotal = $_GET["order_total"];
$orderSubtotal = $_GET["order_subtotal"];
$orderDiscount = $_GET["order_discount"];
$orderShipping = $_GET["order_shipping"];
$orderShippingMethod = $_GET["order_shipping_method"];
$orderBillingAddress = $_GET["order_billing_address"];
$orderBillingCity = $_GET["order_billing_city"];
$orderBillingState = $_GET["order_billing_state"];
$orderBillingZipCode = $_GET["order_billing_zipcode"];
$orderBillingCountry = $_GET["order_billing_country"];
$orderShippingAddress = $_GET["order_shipping_address"];
$orderShippingCity = $_GET["order_shipping_city"];
$orderShippingState = $_GET["order_shipping_state"];
$orderShippingZipCode = $_GET["order_shipping_zipcode"];
$orderShippingCountry = $_GET["order_shipping_country"];
$orderAffiliateId = $_GET["order_affiliate_id"];
$orderSubaffiliateId = $_GET["order_subaffiliate_id"];

// access products associated with the order
$products = json_decode(stripslashes($_GET["order_products"]));

foreach($products as $product)
{
	$productId = $product->id;
	$productName = $product->name;
	$productSku = $product->sku;
	$productAmount = $product->amount;
	$productQuantity = $product->quantity;
	$productTotal = $product->total;						// amount * quantity
	$productIsRecurring = $product->is_recurring;			// true, false
	$productRecurringAmount = $product->recurring_amount;	// amount charged every rebill period
	$productRebillPeriod = $product->rebill_period;			// integer - complete rebill period is a combination of rebill period 
															// and frequency i.e. 1 months, 30 days, 2 weeks, etc.
	$productRebillFrequency = $product->rebill_frequency;  	// days, weeks, months, years
}

// ---- EVENT TYPES ----
$PAYMENT_RECEIVED = "mm_payment_received";
$PAYMENT_REBILL = "mm_payment_rebill";


// ---- PERFORM ACTION BASED ON EVENT TYPE ----
switch($eventType)
{
	case $PAYMENT_RECEIVED:
		// do something
		break;
		
	case $PAYMENT_REBILL:
		// do something
		break;
}
?>