<?php 
/**
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */

	$user = new MM_User($p->userId);
	
	if(!$user->isValid())
	{
		// a new user is being so there create a pending account for them
		if(intval($p->userId) == MM_User::$NEW_USER_ID)
		{
			// the following fields in lastActionParams are defined in members.dialog.php/mm_members.js
			$lastParams = json_decode($p->lastActionParams);
			
			$user = new MM_User();
			$user->setStatus(MM_Status::$PENDING_ACTIVATION);
			$user->setStatusMessage("Customer account created by administrator using the Create Member tool but not completed.");
			$user->setMembershipId($lastParams->mm_new_membership);
			$user->setEmail($lastParams->mm_new_email);
			$user->setFirstName($lastParams->mm_new_first_name);
			$user->setLastName($lastParams->mm_new_last_name);
			
			if(isset($lastParams->mm_new_phone))
			{
				$user->setPhone($lastParams->mm_new_phone);
			}
				
			if(isset($lastParams->mm_new_password))
			{
				$user->setPassword($lastParams->mm_new_password);
			}
			
			$result = $user->commitData();
				
			if(MM_Response::isError($result))
			{
				echo "Payment Options Dialog:  Error creating pending account for new member: {$result->message}";
				exit;
			}
		}
		
		if(!$user->isValid())
		{
			echo "Payment Options Dialog: Invalid user ID '{$p->userId}'.";
			exit;
		}
	}
	
	$membership = null;
	$bundle = null;
	$products = array();
	
	if($p->accessType == MM_AccessControlEngine::$ACCESS_TYPE_MEMBERSHIP)
	{
		$membership = new MM_MembershipLevel($p->accessTypeId);
		if($membership->isValid())
		{
			$accessName = $membership->getName();
			$products = $membership->getProductIds();
		}
		else 
		{
			echo "Payment Options Dialog: Invalid membership level ID '{$p->accessTypeId}'";
			exit;
		}
	}
	else if($p->accessType == MM_AccessControlEngine::$ACCESS_TYPE_BUNDLE)
	{
		$bundle = new MM_Bundle($p->accessTypeId);
		if($bundle->isValid())
		{
			$accessName = $bundle->getName();
			$products = $bundle->getAssociatedProducts();
		}
		else
		{
			echo "Payment Options Dialog: Invalid bundle ID '{$p->accessTypeId}'";
			exit;
		}
	}
	
	$actionParams = json_decode($p->lastActionParams);
	
	$compFunction = $actionParams->mm_jshandle.".";
	$compFunction .= $actionParams->mm_compfunction."(";
	$compFunction .= htmlspecialchars(json_encode($p->lastActionParams), ENT_NOQUOTES);
	$compFunction .= ");";
?>

<script>
function appendAffiliateParams(url)
{
	if(jQuery("#mm-affiliate").val() != "")
	{
		url += "&<?php echo MM_OptionUtils::getOption(MM_OptionUtils::$OPTION_KEY_AFFILIATE); ?>=";
		url += jQuery("#mm-affiliate").val();
	}

	if(jQuery("#mm-subaffiliate").val() != "")
	{
		url += "&<?php echo MM_OptionUtils::getOption(MM_OptionUtils::$OPTION_KEY_SUB_AFFILIATE); ?>=";
		url += jQuery("#mm-subaffiliate").val();
	}

	return url;
}

function sendEmail(checkoutUrl)
{
	var subject = "Purchase '<?php echo addslashes($accessName); ?>'";
	var body = "You can purchase '<?php echo addslashes($accessName); ?>' using the following link: \n" + appendAffiliateParams(checkoutUrl);
	var mailToLink = "mailto:<?php echo $user->getEmail(); ?>?subject=" + subject + "&body=" + encodeURIComponent(body);
	window.location.href = mailToLink;
}

function goToCheckoutPage(checkoutUrl)
{
	window.location.href = appendAffiliateParams(checkoutUrl);
}
</script>

<div id="mm-main-container">
<div style="margin-top:10px; margin-bottom:15px;">
<a href=""></a>
<a href='javascript:<?php echo $compFunction; ?>' class="mm-ui-button">
<?php echo MM_Utils::getIcon('ticket', '', '1.2em'); ?>
<?php echo "Comp {$accessName}"; ?>
</a>
</div>

<div style="width: 425px; margin-bottom: 15px;" class="mm-divider"></div>

<div style="margin-bottom:10px;">
<span class="mm-section-header">Charge for <?php echo $accessName; ?></span>
</div>

<div style="margin-bottom:10px;">
	<select id="mm-product-selector" onchange="pymtutils_js.getPaymentOptionsList();">
	<option value='0'>Select a product to purchase</option>
	<?php
		foreach($products as $productId=>$productName) 
		{
			$product = new MM_Product($productId);
			if($product->isValid())
			{
				echo "<option value='{$product->getId()}'>{$product->getName()}</option>";
			}
		}
	?>
	</select>
</div>

<input id="mm-user-id" type="hidden" value="<?php echo $user->getId(); ?>" />

<div id="mm-payment-options-list" style="display:none;">
</div>
</div>