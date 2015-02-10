<?php 
/**
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */

MM_MemberMouseService::validateLicense(new MM_License());

$crntPage = MM_ModuleUtils::getPage();
$primaryTab = MM_ModuleUtils::getPrimaryTab();
$module = MM_ModuleUtils::getModule();

if(isset($_REQUEST[MM_Session::$PARAM_USER_ID]))
{
	$user = new MM_User($_REQUEST[MM_Session::$PARAM_USER_ID]);
}
else
{
	$user = new MM_User();
}

$resourceUrl = MM_RESOURCES_URL;

if(MM_Utils::isSSL())
{
	$resourceUrl = preg_replace("/(http\:)/", "https:", MM_RESOURCES_URL);
}

if(version_compare(get_bloginfo('version'), "3.8", ">="))
{
?>
<!-- override WordPress 3.8 styles -->
<style>
#wpwrap
{
	background-color: #fff;
}
.ui-widget 
{
	font-size:1em;
}
textarea, input, select
{
	font-size:11px;
}
</style>
<?php } ?>

<?php if ($primaryTab == MM_MODULE_MEMBER_DETAILS) { ?>
<div class="mm-navbar" style="margin-bottom:10px;">	
<ul>
	<li> 
		<a href="<?php echo MM_ModuleUtils::getUrl($crntPage, MM_MODULE_BROWSE_MEMBERS); ?>">
			<i class="fa fa-chevron-left"></i> 
		</a>
	</li>
	
	<?php if($user->isValid()) { ?>
	<li> 
		<a href="<?php echo MM_ModuleUtils::getUrl($crntPage, MM_MODULE_MEMBER_DETAILS_GENERAL); ?>&user_id=<?php echo $user->getId(); ?>" class='<?php echo ($module == MM_MODULE_MEMBER_DETAILS_GENERAL ? "active":""); ?>'>
			<i class="fa fa-user"></i>
			General
		</a>
	</li>
	<li> 
		<a href="<?php echo MM_ModuleUtils::getUrl($crntPage, MM_MODULE_MEMBER_DETAILS_ACCESS_RIGHTS); ?>&user_id=<?php echo $user->getId(); ?>" class='<?php echo ($module == MM_MODULE_MEMBER_DETAILS_ACCESS_RIGHTS ? "active":""); ?>'>
			<i class="fa fa-key"></i>
			Access Rights
		</a>
	</li>
	<li>
		<a href="<?php echo MM_ModuleUtils::getUrl($crntPage, MM_MODULE_MEMBER_DETAILS_SUBSCRIPTIONS); ?>&user_id=<?php echo $user->getId(); ?>" class='<?php echo ($module == MM_MODULE_MEMBER_DETAILS_SUBSCRIPTIONS ? "active":""); ?>'>
			<i class="fa fa-refresh"></i>
			Subscriptions
		</a>
	</li>
	<li>
		<a href="<?php echo MM_ModuleUtils::getUrl($crntPage, MM_MODULE_MEMBER_DETAILS_TRANSACTION_HISTORY); ?>&user_id=<?php echo $user->getId(); ?>" class='<?php echo ($module == MM_MODULE_MEMBER_DETAILS_TRANSACTION_HISTORY ? "active":""); ?>'>
			<i class="fa fa-credit-card"></i>
			Transactions
		</a>
	</li>
	<?php if($user->hasGifts()) { ?>
	<li>
		<a href="<?php echo MM_ModuleUtils::getUrl($crntPage, MM_MODULE_MEMBER_DETAILS_GIFT_HISTORY); ?>&user_id=<?php echo $user->getId(); ?>" class='<?php echo ($module == MM_MODULE_MEMBER_DETAILS_GIFT_HISTORY ? "active":""); ?>'>
			<i class="fa fa-gift"></i>
			Gifts
		</a>
	</li>
	<?php } ?>
	<li>
		<a href="<?php echo MM_ModuleUtils::getUrl($crntPage, MM_MODULE_MEMBER_DETAILS_ACTIVITY_LOG); ?>&user_id=<?php echo $user->getId(); ?>" class='<?php echo ($module == MM_MODULE_MEMBER_DETAILS_ACTIVITY_LOG ? "active":""); ?>'>
			<i class="fa fa-list"></i>
			Activity Log
		</a>
	</li>
	<?php if(MM_CustomField::hasCustomFields()) { ?>
	<li>
		<a href="<?php echo MM_ModuleUtils::getUrl($crntPage, MM_MODULE_MEMBER_DETAILS_CUSTOM_FIELDS); ?>&user_id=<?php echo $user->getId(); ?>" class='<?php echo ($module == MM_MODULE_MEMBER_DETAILS_CUSTOM_FIELDS ? "active":""); ?>'>
			<i class="fa fa-edit"></i>
			Custom Fields
		</a>
	</li>
	<?php } ?>
	<?php } ?>
	
	<?php echo MM_SupportUtils::supportMenuItem($module); ?>
</ul>
</div>
<?php } ?>

<?php if($crntPage == MM_MODULE_PRODUCT_SETTINGS) { ?>
<div class="mm-navbar">	
<ul>
	<li> 
		<a href="<?php echo MM_ModuleUtils::getUrl($crntPage, MM_MODULE_PRODUCTS); ?>" class='<?php echo ($module == MM_MODULE_PRODUCTS ? "active":""); ?>'>
			<i class="fa fa-shopping-cart"></i> 
			Products
		</a>
	</li>
	<?php 
		$activePymtProvider = MM_PaymentServiceFactory::getOnsitePaymentService();
		if(!is_null($activePymtProvider) && $activePymtProvider->isActive() && $activePymtProvider->getToken() == MM_PaymentService::$LIMELIGHT_SERVICE_TOKEN) { 
	?>
	<li class="dropdown">
		<a href=""><i class="fa fa-exchange"></i> Lime Light Mappings</a>
		<ul>
			<li> 
				<a href="<?php echo MM_ModuleUtils::getUrl($crntPage, MM_MODULE_LIMELIGHT_PRODUCTS); ?>" class='<?php echo ($module == MM_MODULE_LIMELIGHT_PRODUCTS ? "active":""); ?>'>
					<i class="fa fa-shopping-cart"></i> 
					Product Mappings
				</a>
			</li>
			<li> 
				<a href="<?php echo MM_ModuleUtils::getUrl($crntPage, MM_MODULE_LIMELIGHT_SHIPPING_METHODS); ?>" class='<?php echo ($module == MM_MODULE_LIMELIGHT_SHIPPING_METHODS ? "active":""); ?>'>
					<i class="fa fa-truck"></i>
					Shipping Method Mappings
				</a>
			</li>
		</ul>
	</li>
	<?php } ?>
	<li>
		<a href="<?php echo MM_ModuleUtils::getUrl($crntPage, MM_MODULE_MEMBERSHIP_LEVELS); ?>" class='<?php echo ($module == MM_MODULE_MEMBERSHIP_LEVELS ? "active":""); ?>'>
			<i class="fa fa-users"></i>
			Membership Levels
		</a>
	</li>
	<li>
		<a href="<?php echo MM_ModuleUtils::getUrl($crntPage, MM_MODULE_BUNDLES); ?>" class='<?php echo ($module == MM_MODULE_BUNDLES ? "active":""); ?>'>
			<i class="fa fa-cubes"></i>
			Bundles
		</a>
	</li>
	<li>
		<a href="<?php echo MM_ModuleUtils::getUrl($crntPage, MM_MODULE_COUPONS); ?>" class='<?php echo ($module == MM_MODULE_COUPONS ? "active":""); ?>'>
			<i class="fa fa-ticket" style="font-size:1.1em"></i>
			Coupons
		</a>
	</li>
	<li>
		<a href="<?php echo MM_ModuleUtils::getUrl($crntPage, MM_MODULE_DRIP_CONTENT_SCHEDULE); ?>" class='<?php echo ($module == MM_MODULE_DRIP_CONTENT_SCHEDULE ? "active":""); ?>'>
			<i class="fa fa-calendar"></i>
			Drip Content Schedule
		</a>
	</li>
	
	<?php echo MM_SupportUtils::supportMenuItem($module); ?>
</ul>
</div>
<?php } ?>


<?php if($crntPage == MM_MODULE_CHECKOUT_SETTINGS) { ?>
<div class="mm-navbar">	
<ul>
	<li> 
		<a href="<?php echo MM_ModuleUtils::getUrl($crntPage, MM_MODULE_CUSTOM_FIELDS); ?>" class='<?php echo ($module == MM_MODULE_CUSTOM_FIELDS ? "active":""); ?>'>
			<i class="fa fa-edit"></i>
			Custom Fields
		</a>
	</li>
	<li>
		<a href="<?php echo MM_ModuleUtils::getUrl($crntPage, MM_MODULE_COUNTRIES); ?>" class='<?php echo ($module == MM_MODULE_COUNTRIES ? "active":""); ?>'>
			<i class="fa fa-globe"></i>
			Countries
		</a>
	</li>
	<li>
		<a href="<?php echo MM_ModuleUtils::getUrl($crntPage, MM_MODULE_SHIPPING); ?>" class='<?php echo ($module == MM_MODULE_SHIPPING ? "active":""); ?>'>
			<i class="fa fa-truck"></i>
			Shipping Methods
		</a>
	</li>
	<li>
		<a href="<?php echo MM_ModuleUtils::getUrl($crntPage, MM_MODULE_CHECKOUT_OTHER_SETTINGS); ?>" class='<?php echo ($module == MM_MODULE_CHECKOUT_OTHER_SETTINGS ? "active":""); ?>'>
			<i class="fa fa-cogs"></i>
			Other Settings
		</a>
	</li>
	
	<?php echo MM_SupportUtils::supportMenuItem($module); ?>
</ul>
</div>
<?php } ?>


<?php if($crntPage == MM_MODULE_PAYMENT_SETTINGS) { ?>
<div class="mm-navbar">	
<ul>
	<li> 
		<a href="<?php echo MM_ModuleUtils::getUrl($crntPage, MM_MODULE_PAYMENT_METHODS); ?>" class='<?php echo ($module == MM_MODULE_PAYMENT_METHODS ? "active":""); ?>'>
			<i class="fa fa-credit-card"></i> 
			Payment Methods
		</a>
	</li>
	<li> 
		<a href="<?php echo MM_ModuleUtils::getUrl($crntPage, MM_MODULE_TEST_DATA); ?>" class='<?php echo ($module == MM_MODULE_TEST_DATA ? "active":""); ?>'>
			<i class="fa fa-flask"></i>
			Test Data
		</a>
	</li>
	<li>
		<a href="<?php echo MM_ModuleUtils::getUrl($crntPage, MM_MODULE_CANCELLATION_METHOD); ?>" class='<?php echo ($module == MM_MODULE_CANCELLATION_METHOD ? "active":""); ?>'>
			<i class="fa fa-ban"></i>
			Cancellation Method
		</a>
	</li>
	
	<?php echo MM_SupportUtils::supportMenuItem($module); ?>
</ul>
</div>
<?php } ?>


<?php if($crntPage == MM_MODULE_EMAIL_SETTINGS) { ?>
<div class="mm-navbar">	
<ul>
	<li> 
		<a href="<?php echo MM_ModuleUtils::getUrl($crntPage, MM_MODULE_EMAIL_INTEGRATION); ?>" class='<?php echo ($module == MM_MODULE_EMAIL_INTEGRATION ? "active":""); ?>'>
			<i class="fa fa-envelope"></i>
			Email Integration
		</a>
	</li>
	<li>
		<a href="<?php echo MM_ModuleUtils::getUrl($crntPage, MM_EMAIL_TEMPLATES); ?>" class='<?php echo ($module == MM_EMAIL_TEMPLATES ? "active":""); ?>'>
			<i class="fa fa-file-text-o"></i>
			Email Templates
		</a>
	</li>
	
	<?php echo MM_SupportUtils::supportMenuItem($module); ?>
</ul>
</div>
<?php } ?>


<?php if($crntPage == MM_MODULE_AFFILIATE_SETTINGS) { ?>
<div class="mm-navbar">	
<ul>
	<li> 
		<a href="<?php echo MM_ModuleUtils::getUrl($crntPage, MM_MODULE_AFFILIATE_INTEGRATION); ?>" class='<?php echo ($module == MM_MODULE_AFFILIATE_INTEGRATION ? "active":""); ?>'>
			<i class="fa fa-bullhorn"></i> 
			Affiliate Integration
		</a>
	</li>
	<li>
		<a href="<?php echo MM_ModuleUtils::getUrl($crntPage, MM_MODULE_COMMISSION_PROFILES); ?>" class='<?php echo ($module == MM_MODULE_COMMISSION_PROFILES ? "active":""); ?>'>
			<i class="fa fa-money"></i>
			Commission Profiles
		</a>
	</li>
	<li>
		<a href="<?php echo MM_ModuleUtils::getUrl($crntPage, MM_MODULE_AFFILIATE_TRACKING); ?>" class='<?php echo ($module == MM_MODULE_AFFILIATE_TRACKING ? "active":""); ?>'>
			<i class="fa fa-cogs"></i>
			Tracking Settings
		</a>
	</li>
	
	<?php echo MM_SupportUtils::supportMenuItem($module); ?>
</ul>
</div>
<?php } ?>


<?php if($crntPage == MM_MODULE_DEVELOPER_TOOLS) { ?>
<div class="mm-navbar">	
<ul>
	<li>
		<a href="<?php echo MM_ModuleUtils::getUrl($crntPage, MM_MODULE_PUSH_NOTIFICATIONS); ?>" class='<?php echo ($module == MM_MODULE_PUSH_NOTIFICATIONS ? "active":""); ?>'>
			<i class="fa fa-send"></i>
			Push Notifications
		</a>
	</li>
	<li> 
		<a href="<?php echo MM_ModuleUtils::getUrl($crntPage, MM_MODULE_API); ?>" class='<?php echo ($module == MM_MODULE_API ? "active":""); ?>'>
			<i class="fa fa-key"></i> 
			API Credentials
		</a>
	</li>
	<li>
		<a href="<?php echo MM_ModuleUtils::getUrl($crntPage, MM_MODULE_WORDPRESS_HOOKS); ?>" class='<?php echo ($module == MM_MODULE_WORDPRESS_HOOKS ? "active":""); ?>'>
			<i class="fa fa-wordpress"></i>
			WordPress Hooks/Filters
		</a>
	</li>
	<li>
		<a href="<?php echo MM_ModuleUtils::getUrl($crntPage, MM_MODULE_PHP_INTERFACE); ?>" class='<?php echo ($module == MM_MODULE_PHP_INTERFACE ? "active":""); ?>'>
			<i class="fa fa-code"></i>
			PHP Interface
		</a>
	</li>
	
	<?php echo MM_SupportUtils::supportMenuItem($module); ?>
</ul>
</div>
<?php } ?>


<?php if($crntPage == MM_MODULE_LOGS) { ?>
<div class="mm-navbar">	
<ul>
	<li> 
		<a href="<?php echo MM_ModuleUtils::getUrl($crntPage, MM_MODULE_ACTIVITY_LOG); ?>" class='<?php echo ($module == MM_MODULE_ACTIVITY_LOG ? "active":""); ?>'>
			<i class="fa fa-list"></i>
			Activity Log
		</a>
	</li>
	
	<?php echo MM_SupportUtils::supportMenuItem($module); ?>
</ul>
</div>
<?php } ?>	


<?php if($crntPage == MM_MODULE_WEBFORMS) { ?>
<div class="mm-navbar">	
<ul>
	<li>
		<a href="<?php echo MM_ModuleUtils::getUrl($crntPage, MM_MODULE_FREE_MEMBER_FORM); ?>" class='<?php echo ($module == MM_MODULE_FREE_MEMBER_FORM ? "active":""); ?>'>
			<i class="fa fa-list-alt"></i>
			Free Member Webform
		</a>
	</li>
	<li>
		<a href="<?php echo MM_ModuleUtils::getUrl($crntPage, MM_MODULE_LOGIN_FORM); ?>" class='<?php echo ($module == MM_MODULE_LOGIN_FORM ? "active":""); ?>'>
			<i class="fa fa-list-alt"></i>
			Login Webform
		</a>
	</li>
	
	<?php echo MM_SupportUtils::supportMenuItem($module); ?>
</ul>
</div>
<?php } ?>	


<?php  if($crntPage == MM_MODULE_GENERAL_SETTINGS) {  ?>
<div class="mm-navbar">	
<ul>
	<?php if(empty($_GET[MM_Session::$PARAM_SUBMODULE])) { ?>
	<li>
		<a href="<?php echo MM_ModuleUtils::getUrl($crntPage, MM_MODULE_EMPLOYEES); ?>" class='<?php echo ($module == MM_MODULE_EMPLOYEES ? "active":""); ?>'>
			<i class="fa fa-users"></i>
			Employees
		</a>
	</li>
	<li>
		<a href="<?php echo MM_ModuleUtils::getUrl($crntPage, MM_MODULE_EXTENSIONS); ?>" class='<?php echo ($module == MM_MODULE_EXTENSIONS ? "active":""); ?>'>
			<i class="fa fa-puzzle-piece"></i>
			Extensions
		</a>
	</li>
	<li>
		<a href="<?php echo MM_ModuleUtils::getUrl($crntPage, MM_MODULE_DUPLICATE_SUBSCRIPTION_TOOL); ?>" class='<?php echo ($module == MM_MODULE_DUPLICATE_SUBSCRIPTION_TOOL ? "active":""); ?>'>
			<i class="fa fa-copy"></i>
			Duplicate Subscriptions
		</a>
	</li>
	<li>
		<a href="<?php echo MM_ModuleUtils::getUrl($crntPage, MM_MODULE_OTHER_SETTINGS); ?>" class='<?php echo ($module == MM_MODULE_OTHER_SETTINGS ? "active":""); ?>'>
			<i class="fa fa-cogs"></i>
			Other Settings
		</a>
	</li>
	<li class="dropdown">
		<a href=""><i class="fa fa-tasks"></i> Manage Install</a>
		<ul>
			<li><a href="<?php echo MM_ModuleUtils::getUrl($crntPage, MM_MODULE_VERSION_HISTORY); ?>"><i class="fa fa-history"></i> Version History</a></li>
			<li><a href="<?php echo MM_ModuleUtils::getUrl($crntPage, MM_MODULE_REPAIR_INSTALL); ?>"><i class="fa fa-wrench"></i> Repair Install</a></li>
			<?php if (isset($_GET['enable_diagnostic']) && ($_GET['enable_diagnostic'] == "true")) { ?>
			<li><a href="<?php echo MM_ModuleUtils::getUrl($crntPage, MM_MODULE_DIAGNOSTICS); ?>"><i class="fa fa-wrench"></i> Diagnostic Log</a></li>
			<?php } ?>
			<?php if (isLocalInstall()) { ?>
			<li><a href="<?php echo MM_ModuleUtils::getUrl($crntPage, MM_MODULE_REPAIR_MEMBERMOUSE); ?>"><i class="fa fa-wrench"></i> Repair MemberMouse (dev)</a></li>
			<?php } ?>
		</ul>
	</li>
	<?php } else { ?>
	<li> 
		<a href="<?php echo MM_ModuleUtils::getUrl($crntPage, MM_MODULE_EXTENSIONS); ?>">
			<i class="fa fa-chevron-left"></i>
		</a>
	</li>
	<?php } ?>
	
	<?php echo MM_SupportUtils::supportMenuItem($module); ?>
</ul>
</div>
<?php } ?>		

<div style="clear: both"></div>

<div id="mm-view-container">
	<?php 
		if ($module == MM_MODULE_REPORTING)
		{
			echo MM_TEMPLATE::generate(MM_MODULES."/reports/{$crntPage}.php");
		}
		else 
		{
			echo MM_TEMPLATE::generate(MM_MODULES."/{$module}.php"); 
		}
	?>
</div>
	
<?php 
	if (($module != MM_MODULE_REPORTING) &&  file_exists(MM_MODULES."/{$module}.firstrun.php")) 
	{
		echo MM_TEMPLATE::generate(MM_MODULES."/{$module}.firstrun.php"); 
	}
?>