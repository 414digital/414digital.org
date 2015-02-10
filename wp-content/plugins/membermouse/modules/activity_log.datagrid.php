<?php
/**
 *
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */

$view = new MM_ActivityLogView();

if(isset($_REQUEST["isMemberDetailsView"]))
{
	$isMemberDetailsArea = ($_REQUEST["isMemberDetailsView"] == "true") ? true : false;
}
else 
{
	$module = MM_ModuleUtils::getModule();
	$isMemberDetailsArea = ($module == MM_MODULE_MEMBER_DETAILS_ACTIVITY_LOG) ? true : false;
}

if(!empty($_REQUEST["sortby"]))
{
	$dataGrid = new MM_DataGrid($_REQUEST, $_REQUEST["sortby"], "desc", 20);
}
else
{
	$dataGrid = new MM_DataGrid($_REQUEST, "date_added", "desc", 20);
}
$data = $view->getViewData($_REQUEST, $dataGrid);
$dataGrid->setTotalRecords($data);
$dataGrid->recordName = "event";

$rows = array();
$headers = array();

$headers['event_type'] = array('content' => '<a onclick="mmjs.sort(\'event_type\');" href="#">Type</a>', "attr" => "style='width:50px;'");

if(!$isMemberDetailsArea)
{
   	$headers['user_id'] = array('content' => '<a onclick="mmjs.sort(\'user_id\');" href="#">Member</a>', "attr" => "style='width:250px;'");
}

$headers['details'] = array('content' => 'Details');
$headers['ip'] = array('content' => '<a onclick="mmjs.sort(\'ip\');" href="#">IP Address</a>', "attr" => "style='width:100px;'");
$headers['date_added'] = array('content' => '<a onclick="mmjs.sort(\'date_added\');" href="#">Date</a>', "attr" => "style='width:150px;'");

foreach($data as $key=>$item)
{	
	// member link
	$user = new MM_User($item->user_id);
	
	$memberLink = MM_NO_DATA;
	
	if($user->isValid())
	{
		$memberLink = $user->getUsername();
		$memberLink = "<a href='?page=".MM_MODULE_MANAGE_MEMBERS."&module=details_general&user_id=".$item->user_id."'>".$user->getUsername()."</a>";
	}
	
	$params = null;
	$details = MM_NO_DATA;
	$eventType = MM_NO_DATA;
	
	if(!empty($item->additional_params))
	{
		$params = unserialize($item->additional_params);
	}
	
	// IP Address
	$ipAddress = MM_NO_DATA;
	
	if(!empty($item->ip))
	{
		$ipAddress = "<span style='font-family:courier;'><a href='http://www.infosniper.net/index.php?ip_address={$item->ip}' target='_blank'>".$item->ip."</a></span>";
	}
	
	if(!empty($item->event_type))
	{
		switch($item->event_type)
		{
			case MM_ActivityLog::$EVENT_TYPE_PAGE_ACCESS:
				$details = "Member accessed ";
				$eventType = MM_Utils::getIcon('file-o', 'turq', '1.2em', '1px', "Page Accessed");
				$urlParts = explode("?", $item->url);
				$urlParams = "";
				if(count($urlParts) > 1)
				{
					$urlParams = $urlParts[1];
				}
				
				// construct URL
				if(!is_null($params))
				{	
					if(isset($params[MM_ActivityLog::$PARAM_PAGE_ID]))
					{
						$pageInfo = get_page($params[MM_ActivityLog::$PARAM_PAGE_ID]);
						
						if(isset($pageInfo->ID))
						{
							$permalink = get_permalink($pageInfo->ID);
							$details .= "<span style='font-family:courier;'><a href=\"{$permalink}\" target=\"_blank\">{$pageInfo->post_title}</a></span>";
						}
					}
					else
					{
						$details .= $urlParts[0];
					}
				}
				else
				{
					$details .= $urlParts[0];
				}
				
				$details .= " page";
				
				if(!empty($urlParams))
				{
					$paramString = "Parameters:\n";
					$pairs = explode("&", $urlParams);
					
					if(!empty($pairs) && count($pairs) > 0)
					{
						foreach($pairs as $pair)
						{
							$paramString .= urldecode(str_replace("=", ": ", $pair))."\n";
						}
						
						$details .= MM_Utils::getIcon('link', 'grey', '1.2em', '2px', $paramString, "margin-left:5px;");
					}
				}
				
				// add affiliate info
				if(!is_null($params))
				{
					if(!empty($params[MM_ActivityLog::$PARAM_AFFILIATE_ID]) || !empty($params[MM_ActivityLog::$PARAM_SUBAFFILIATE_ID]))
					{
						$affiliateInfo = "";
						if(!empty($params[MM_ActivityLog::$PARAM_AFFILIATE_ID]))
						{
							$affiliateInfo .= "Affiliate ID: {$params[MM_ActivityLog::$PARAM_AFFILIATE_ID]}\n";
						}
						if(!empty($params[MM_ActivityLog::$PARAM_SUBAFFILIATE_ID]))
						{
							$affiliateInfo .= "Sub-affiliate ID: {$params[MM_ActivityLog::$PARAM_SUBAFFILIATE_ID]}";
						}
						$details .= MM_Utils::getAffiliateIcon($affiliateInfo, "margin-left:5px;");
					}
				}
				
				break;
				
			case MM_ActivityLog::$EVENT_TYPE_AFFILIATE_TRACKING:
				$eventType = MM_Utils::getAffiliateIcon("Affiliate Tracking");
				$details = "An unknown affiliate tracking event occurred";
				$affiliateProviderName = "Unknown Provider";
				$orderNumber = MM_NO_DATA;
				
				if(!empty($params[MM_ActivityLog::$PARAM_AFFILIATE_PROVIDER_NAME]))
				{
					$affiliateProviderName = $params[MM_ActivityLog::$PARAM_AFFILIATE_PROVIDER_NAME];
				}
				
				if(!empty($params[MM_ActivityLog::$PARAM_AFFILIATE_ORDER_NUMBER]))
				{
					$orderNumber = $params[MM_ActivityLog::$PARAM_AFFILIATE_ORDER_NUMBER];
				}
				
				if(!empty($params[MM_ActivityLog::$PARAM_AFFILIATE_TRACKING_EVENT]))
				{	
					switch($params[MM_ActivityLog::$PARAM_AFFILIATE_TRACKING_EVENT])
					{
						case MM_AffiliateController::$AFFILIATE_EVENT_TRACK_INITIAL_COMMISSION:
						case MM_AffiliateController::$AFFILIATE_EVENT_TRACK_REBILL_COMMISSION:
							if($params[MM_ActivityLog::$PARAM_AFFILIATE_TRACKING_EVENT] == MM_AffiliateController::$AFFILIATE_EVENT_TRACK_INITIAL_COMMISSION)
							{
								$details = "Initial ";
							}
							else
							{
								$details = "Rebill ";
							}
							
							$details .= "commission request sent to <em>{$affiliateProviderName}</em> for <span style='font-family:courier;'>Order# {$orderNumber}</span>";
							break;
						
						case MM_AffiliateController::$AFFILIATE_EVENT_REVERSE_COMMISSION:
							$details = "Terminate commission request sent to <em>{$affiliateProviderName}</em> for <span style='font-family:courier;'>Order# {$orderNumber}</span>";
							break;
							
						case MM_AffiliateController::$AFFILIATE_EVENT_CREATE_ACCOUNT:
							$details = "Affiliate account creation request sent to <em>{$affiliateProviderName}</em>";
							break;
					}
				}
				
				if(($params[MM_ActivityLog::$PARAM_AFFILIATE_TRACKING_EVENT] == MM_AffiliateController::$AFFILIATE_EVENT_TRACK_INITIAL_COMMISSION) ||
					($params[MM_ActivityLog::$PARAM_AFFILIATE_TRACKING_EVENT] == MM_AffiliateController::$AFFILIATE_EVENT_TRACK_REBILL_COMMISSION))
				{
				?>
					<div id="mm-view-info-<?php echo $item->id; ?>" style="display:none;" title="Commission Request Details" style="font-size:11px;">
						<p><strong>Commission Request Data</strong></p>
						
						<p>
				<?php 
					if(!empty($params[MM_ActivityLog::$PARAM_AFFILIATE_ID]))
					{
				?>
							Affiliate ID: <span style="font-family:courier;"><?php echo $params[MM_ActivityLog::$PARAM_AFFILIATE_ID]; ?></span><br/>
				<?php } ?>
							Order Number: <span style="font-family:courier;"><?php echo $params[MM_ActivityLog::$PARAM_AFFILIATE_ORDER_NUMBER]; ?></span><br/>
							Order Total: <span style="font-family:courier;"><?php echo _mmf($params[MM_ActivityLog::$PARAM_AFFILIATE_ORDER_TOTAL]); ?></span><br/>
							IP Address: <span style="font-family:courier;"><?php echo $params[MM_ActivityLog::$PARAM_AFFILIATE_ORDER_IP_ADDRESS]; ?></span><br/>
				<?php 
					if(!empty($params[MM_ActivityLog::$PARAM_AFFILIATE_COUPON_CODE]))
					{
				?>
							Coupon Code: <span style="font-family:courier;"><?php echo $params[MM_ActivityLog::$PARAM_AFFILIATE_COUPON_CODE]; ?></span>
				<?php } ?>
						</p>
						
				<?php 
					if(!empty($params[MM_ActivityLog::$PARAM_AFFILIATE_TRACKING_URL]))
					{
				?>
						<p><strong>Request Sent to <?php echo $affiliateProviderName ?></strong></p>
						
						<p style="font-size:12px;">This is the exact request that was sent to <?php echo $affiliateProviderName ?>. Note that this is a request only which means 
						that a commission will not necessarily be recorded in <?php echo $affiliateProviderName ?>. Once the request is received, <?php echo $affiliateProviderName ?> 
						will determine if a commission should be recorded based on the information provided. To know definitively which commissions
						are being recorded log into <?php echo $affiliateProviderName ?>.</p>
						
						<p><span style="font-family:courier;"><?php echo $params[MM_ActivityLog::$PARAM_AFFILIATE_TRACKING_URL]; ?></span></p>
				<?php } ?>
					</div>
				<?php 
					$details .= MM_Utils::getInfoIcon("View commission request details", "margin-left:4px;", "viewInfo({$item->id})");
				}
				
				break;
				
			case MM_ActivityLog::$EVENT_TYPE_LOGIN:
				$details = "Member logged in from <span style='font-family:courier;'>{$ipAddress}</span>";
				$eventType = MM_Utils::getIcon('key', 'yellow', '1.3em', '2px', "Login");
				break;
				
			case MM_ActivityLog::$EVENT_TYPE_ACCESS_RIGHTS:
				if(!is_null($params) && !empty($params[MM_ActivityLog::$PARAM_ACCESS_EVENT]))
				{	
					// get access name
					switch($params[MM_ActivityLog::$PARAM_ACCESS_EVENT])
					{
						case MM_Event::$MEMBER_ADD:
						case MM_Event::$MEMBER_STATUS_CHANGE:
						case MM_Event::$MEMBER_MEMBERSHIP_CHANGE:
							$membership = new MM_MembershipLevel($params[MM_ActivityLog::$PARAM_ACCESS_ID]);
								
							if($membership->isValid())
							{
								$accessName = $membership->getName();
							}
							else
							{
								$accessName = "Unknown Membership";
							}
							
							if(!empty($params[MM_ActivityLog::$PARAM_ACCESS_STATUS]))
							{
								$accessStatusInfo = MM_Status::getImage($params[MM_ActivityLog::$PARAM_ACCESS_STATUS]);
								$accessStatusInfo .= " <em>".MM_Status::getName($params[MM_ActivityLog::$PARAM_ACCESS_STATUS], true)."</em>";
								
								if($params[MM_ActivityLog::$PARAM_ACCESS_STATUS] == MM_Status::$ERROR && !empty($params[MM_ActivityLog::$PARAM_ACCESS_STATUS_MESSAGE]))
								{
									$accessStatusInfo .= MM_Utils::getInfoIcon(htmlentities($params[MM_ActivityLog::$PARAM_ACCESS_STATUS_MESSAGE], ENT_QUOTES), "margin-left:4px;");
								}
							}
							break;

						case MM_Event::$BUNDLE_ADD:
						case MM_Event::$BUNDLE_STATUS_CHANGE:
							$bundle = new MM_Bundle($params[MM_ActivityLog::$PARAM_ACCESS_ID]);
							
							if($bundle->isValid())
							{
								$accessName = $bundle->getName();
							}
							else
							{
								$accessName = "Unknown Bundle";
							}
							
							if(!empty($params[MM_ActivityLog::$PARAM_ACCESS_STATUS]))
							{
								$accessStatusInfo = MM_Status::getImage($params[MM_ActivityLog::$PARAM_ACCESS_STATUS]);
								$accessStatusInfo .= " <em>".MM_Status::getName($params[MM_ActivityLog::$PARAM_ACCESS_STATUS], true)."</em>";
							}
							break;
					}
					
					// generate details and event type icon
					switch($params[MM_ActivityLog::$PARAM_ACCESS_EVENT])
					{
						case MM_Event::$MEMBER_ADD:
							$eventType = MM_Utils::getAccessIcon(MM_OrderItemAccess::$ACCESS_TYPE_MEMBERSHIP, "Member created");
							$details = "Account created with <em>{$accessName}</em> membership";
							break;
							
						case MM_Event::$MEMBER_STATUS_CHANGE:
							$eventType = MM_Utils::getAccessIcon(MM_OrderItemAccess::$ACCESS_TYPE_MEMBERSHIP, "Membership status changed");
							$details = "<em>{$accessName}</em> membership status changed to {$accessStatusInfo}";
							break;
							
						case MM_Event::$MEMBER_MEMBERSHIP_CHANGE:
							$eventType = MM_Utils::getAccessIcon(MM_OrderItemAccess::$ACCESS_TYPE_MEMBERSHIP, "Membership level changed");
							$details = "Membership changed to <em>{$accessName}</em>";
							break;
							
						case MM_Event::$BUNDLE_ADD:
							$eventType = MM_Utils::getAccessIcon(MM_OrderItemAccess::$ACCESS_TYPE_BUNDLE, "Bundle added");
							$details = "<em>{$accessName}</em> bundle added to account";
							break;

						case MM_Event::$BUNDLE_STATUS_CHANGE:
							$eventType = MM_Utils::getAccessIcon(MM_OrderItemAccess::$ACCESS_TYPE_BUNDLE, "Bundle status changed");
							$details = "<em>{$accessName}</em> bundle status changed to {$accessStatusInfo}";
							break;
					}
					
					// add complimentary indicator
					if(isset($params[MM_ActivityLog::$PARAM_ACCESS_IS_COMPLIMENTARY]) && $params[MM_ActivityLog::$PARAM_ACCESS_IS_COMPLIMENTARY] == "true")
					{
						switch($params[MM_ActivityLog::$PARAM_ACCESS_EVENT])
						{
							case MM_Event::$MEMBER_ADD:
							case MM_Event::$MEMBER_MEMBERSHIP_CHANGE:
							case MM_Event::$BUNDLE_ADD:
								$compDescription = ($params[MM_ActivityLog::$PARAM_ACCESS_EVENT] == MM_Event::$BUNDLE_ADD) ? "Bundle" : "Membership";
								$compDescription .= " is complimentary";
								$details .= MM_Utils::getDiscountIcon($compDescription, "margin-left:5px;");
								break;
								
							case MM_Event::$MEMBER_STATUS_CHANGE:
							case MM_Event::$BUNDLE_STATUS_CHANGE:
								if($params[MM_ActivityLog::$PARAM_ACCESS_STATUS] == MM_Status::$ACTIVE)
								{
									$compDescription = ($params[MM_ActivityLog::$PARAM_ACCESS_EVENT] == MM_Event::$BUNDLE_ADD) ? "Bundle" : "Membership";
									$compDescription .= " is complimentary";
									$details .= MM_Utils::getDiscountIcon($compDescription, "margin-left:5px;");
								}
								break;
						}
					}
				}
				break;
				
			case MM_ActivityLog::$EVENT_TYPE_BILLING:
				if(!is_null($params) && !empty($params[MM_ActivityLog::$PARAM_BILLING_EVENT]))
				{
					// get product and coupon names
					$productName = "Unknown Product";
					$isSubscription = false;
					$hasCoupon = false;
					$hasProration = false;
					$orderTotal = "";
					switch($params[MM_ActivityLog::$PARAM_BILLING_EVENT])
					{
						case MM_Event::$PAYMENT_RECEIVED:
						case MM_Event::$PAYMENT_REBILL:
						case MM_Event::$PAYMENT_REBILL_DECLINED:
						case MM_Event::$BILLING_SUBSCRIPTION_UPDATED:
						case MM_Event::$BILLING_SUBSCRIPTION_REBILL_DATE_CHANGED:
						case MM_Event::$BILLING_SUBSCRIPTION_CANCELED:
						case MM_Event::$REFUND_ISSUED:
							$products = json_decode(stripslashes($params[MM_ActivityLog::$PARAM_BILLING_ORDER_PRODUCTS]));
							
							foreach($products as $product)
							{
								$productName = $product->name;
								$isSubscription = $product->is_recurring;
								break; // only support one product per order
							}
							
							$coupons = json_decode(stripslashes($params[MM_ActivityLog::$PARAM_BILLING_ORDER_COUPONS]));
							
							foreach($coupons as $coupon)
							{
								$hasCoupon = true;
								$couponName = $coupon->name;
								break; // only support one coupon per order
							}
							
							if (isset($params[MM_ActivityLog::$PARAM_BILLING_ORDER_PRORATIONS]))
							{
								$prorations = json_decode(stripslashes($params[MM_ActivityLog::$PARAM_BILLING_ORDER_PRORATIONS]));
									
								foreach($prorations as $proration)
								{
									$hasProration = true;
									$prorationAmount = abs($proration->amount);
									$prorationDescription = $proration->description;
									break; // only support one proration per order
								}
							}
							
							$details = "<span style='font-family:courier;'>Order# {$params[MM_ActivityLog::$PARAM_BILLING_ORDER_NUMBER]}</span>: ";
							
							$orderTotal = _mmf($params[MM_ActivityLog::$PARAM_BILLING_ORDER_TOTAL]);
							$orderTotal = "<span style='font-family:courier;'>{$orderTotal}</span>";
							break;
					}
						
					// generate details and event type icon
					switch($params[MM_ActivityLog::$PARAM_BILLING_EVENT])
					{
						case MM_Event::$PAYMENT_RECEIVED:
							$eventType = MM_Utils::getIcon('money', 'green', '1.4em', '2px', 'Initial Payment Received');
							
							if($isSubscription)
							{
								$details .= "Initial payment of {$orderTotal} received for subscription product <em>{$productName}</em>";
							}
							else 
							{
								$details .= "Payment of {$orderTotal} received for product <em>{$productName}</em>";
							}
							break;
								
						case MM_Event::$PAYMENT_REBILL:
							$eventType = MM_Utils::getIcon('money', 'green', '1.4em', '2px', 'Rebill Payment Received');
							$details .= "Rebill payment of {$orderTotal} received for subscription product <em>{$productName}</em>";
							break;
								
						case MM_Event::$PAYMENT_REBILL_DECLINED:
							$eventType = MM_Utils::getIcon('money', 'red', '1.4em', '2px', 'Rebill Payment Failed');
							$details .= "Rebill payment failed for subscription product <em>{$productName}</em>";
							break;
								
						case MM_Event::$BILLING_SUBSCRIPTION_UPDATED:
							$eventType = MM_Utils::getIcon('credit-card', 'green', '1.4em', '2px', 'Credit Card Updated');
							$details .= "Member updated their credit card information for subscription product <em>{$productName}</em>";
							break;
								
						case MM_Event::$BILLING_SUBSCRIPTION_REBILL_DATE_CHANGED:
							$eventType = MM_Utils::getIcon('refresh', 'green', '1.3em', '2px', 'Subscription Updated');
							
							if(!empty($params[MM_ActivityLog::$PARAM_BILLING_NEXT_REBILL_DATE]))
							{
								$details .= "Next rebill date was changed to <span style='font-family:courier;'>{$params[MM_ActivityLog::$PARAM_BILLING_NEXT_REBILL_DATE]}</span> for subscription product <em>{$productName}</em>";
							}
							else
							{
								$details .= "Next rebill date was changed for subscription product <em>{$productName}</em>";
							}
							break;
								
						case MM_Event::$BILLING_SUBSCRIPTION_CANCELED:
							$eventType = MM_Utils::getIcon('refresh', 'red', '1.3em', '2px', 'Subscription Canceled');
							$details .= "Subscription was canceled for product <em>{$productName}</em>";
							break;
								
						case MM_Event::$REFUND_ISSUED:
							$eventType = MM_Utils::getIcon('money', 'red', '1.4em', '2px', 'Refund Issued');
							if($isSubscription)
							{
								$details .= "Refund of {$orderTotal} issued for subscription product <em>{$productName}</em>";
							}
							else
							{
								$details .= "Refund of {$orderTotal} issued for product <em>{$productName}</em>";
							}
							break;
					}
						
					// add affiliate information
					if(!empty($params[MM_ActivityLog::$PARAM_BILLING_ORDER_AFFFILIATE_ID]) || !empty($params[MM_ActivityLog::$PARAM_BILLING_ORDER_SUBAFFFILIATE_ID]))
					{
						switch($params[MM_ActivityLog::$PARAM_BILLING_EVENT])
						{
							case MM_Event::$PAYMENT_RECEIVED:
							case MM_Event::$PAYMENT_REBILL:
							case MM_Event::$REFUND_ISSUED:
								
								$affiliateDescription = "";
								if(!empty($params[MM_ActivityLog::$PARAM_BILLING_ORDER_AFFFILIATE_ID]))
								{
									$affiliateDescription .= "Affiliate ID: {$params[MM_ActivityLog::$PARAM_BILLING_ORDER_AFFFILIATE_ID]}\n";
								}
								
								if(!empty($params[MM_ActivityLog::$PARAM_BILLING_ORDER_SUBAFFFILIATE_ID]))
								{
									$affiliateDescription .= "Sub-affiliate ID: {$params[MM_ActivityLog::$PARAM_BILLING_ORDER_SUBAFFFILIATE_ID]}\n";
								}
								
								$details .= MM_Utils::getAffiliateIcon($affiliateDescription, "margin-left:5px;");
								break;
						}
					}
					
					// add discount information
					if($hasCoupon || $hasProration)
					{
						switch($params[MM_ActivityLog::$PARAM_BILLING_EVENT])
						{
							case MM_Event::$PAYMENT_RECEIVED:
							case MM_Event::$PAYMENT_REBILL:
								$discountDescription = "DISCOUNTS APPLIED\n";
								
								if($hasCoupon)
								{
									$couponAmount = abs($params[MM_ActivityLog::$PARAM_BILLING_ORDER_DISCOUNT]);
									
									if($hasProration)
									{
										$couponAmount -= $prorationAmount;
									}
									
									$discountDescription .= "Coupon: "._mmf($couponAmount)." ({$couponName})\n";
								}
								
								if($hasProration)
								{
									$discountDescription .= "Proration: "._mmf($prorationAmount)."\n";
								}
								
								if($hasCoupon && $hasProration)
								{
									$discountDescription .= "---------------------\n";
									$discountDescription .= "Total Discount: "._mmf($params[MM_ActivityLog::$PARAM_BILLING_ORDER_DISCOUNT]);
								}

								$details .= MM_Utils::getDiscountIcon($discountDescription, "margin-left:5px;");
								break;
						}
					}
				}
				break;
				
			case MM_ActivityLog::$EVENT_TYPE_EMAIL:
				$eventType = MM_Utils::getIcon('paper-plane-o', 'green', '1.2em', '1px', 'Email sent');
				
				if(!is_null($params))
				{
					if(isset($params[MM_ActivityLog::$PARAM_EMAIL_SUBJECT]) && isset($params[MM_ActivityLog::$PARAM_EMAIL_TO_ADDRESS]))
					{
						$details = "Email sent to <span style='font-family:courier;'>{$params[MM_ActivityLog::$PARAM_EMAIL_TO_ADDRESS]}</span>";
						
						if(isset($params[MM_ActivityLog::$PARAM_EMAIL_BODY]) && isset($params[MM_ActivityLog::$PARAM_EMAIL_FROM_ADDRESS]))
						{
						?>
	<div id="mm-view-info-<?php echo $item->id; ?>" style="display:none;" title="Email Viewer" style="font-size:11px;">
		<p>To: <span style="font-family:courier;"><?php echo $params[MM_ActivityLog::$PARAM_EMAIL_TO_ADDRESS]; ?></span><br/>
		<?php if(!empty($params[MM_ActivityLog::$PARAM_EMAIL_CC_ADDRESSES])) { ?>
		Cc: <span style="font-family:courier;"><?php echo $params[MM_ActivityLog::$PARAM_EMAIL_CC_ADDRESSES]; ?></span><br/>
		<?php } ?>
		From: <span style="font-family:courier;"><?php echo $params[MM_ActivityLog::$PARAM_EMAIL_FROM_ADDRESS]; ?></span><br/>
		Subject: <span style="font-family:courier;"><?php echo $params[MM_ActivityLog::$PARAM_EMAIL_SUBJECT]; ?></span></p>
		<p><span style="font-family:courier;"><?php echo nl2br($params[MM_ActivityLog::$PARAM_EMAIL_BODY]); ?></span></p>
	</div>
						<?php 
							$details .= " &mdash; ";
							$details .= " <a onclick=\"viewInfo({$item->id})\" style='cursor: pointer;' title='View Email'>".MM_Utils::getIcon('file-text', 'blue', '1.2em', '1px', '', 'margin-right:4px;')."<span style='font-family:courier;'>".MM_Utils::abbrevString($params[MM_ActivityLog::$PARAM_EMAIL_SUBJECT])."</span></a>";
						}
					}
					else
					{
						$details .= $urlParts[0];
					}
				}
				
				break;
		}
	}
	
	$row = array();
	$row[] = array('content' => $eventType);
	
	if(!$isMemberDetailsArea)
	{
		$row[] = array('content' => $memberLink);
	}
	
	$row[] = array('content' => $details);
	$row[] = array('content' => $ipAddress);
	$row[] = array('content' => MM_utils::dateToLocal($item->date_added));
	
	$rows[] = $row;
}

$dataGrid->setHeaders($headers);
$dataGrid->setRows($rows);

$dgHtml = $dataGrid->generateHtml();

if($dgHtml == "") 
{
	$dgHtml = "<p><i>No activity found.</i></p>";
}

echo $dgHtml;
?>