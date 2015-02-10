<?php
/**
 * @package MemberMouse
 * @version 2.2.2
 *
 * Plugin Name: MemberMouse Platform
 * Plugin URI: http://membermouse.com
 * Description: MemberMouse is an enterprise-level membership platform that allows you to quickly and easily manage a membership site or subscription business. MemberMouse is designed to deliver digital content, automate customer self-service and provide you with advanced marketing tools to maximize the profitability of your continuity business.
 * Author: MemberMouse, LLC
 * Version: 2.2.2
 * Author URI: http://membermouse.com
 * Copyright: 2009-2014 MemberMouse LLC. All rights reserved.
 */

require_once("includes/mm-constants.php");
require_once("includes/mm-functions.php");
require_once("lib/class.membermousestream.php");
require_once("includes/init.php");

if(isLocalInstall())
{
	error_reporting(E_ALL);
	ini_set("display_errors","On");
}

if (class_exists("MM_DiagnosticLog") && MM_DiagnosticLog::isEnabled())
{	
	function membermouseDiagnosticCapture()
	{
		$error = @error_get_last();
	    if (($error != null) && ($error['type'] & (E_ERROR | E_USER_ERROR | E_PARSE | E_CORE_ERROR | E_COMPILE_ERROR | E_RECOVERABLE_ERROR)))
	    {
	    	@MM_DiagnosticLog::LogItem($error['file'],MM_DiagnosticLog::$PHP_ERROR, $error['message'], $error['line']);   
	    } 
	}
	register_shutdown_function('membermouseDiagnosticCapture');
	set_error_handler(array("MM_DiagnosticLog","logPHPErrors"),E_ALL);
}

if(!class_exists('MemberMouse')) 
{
	class MemberMouse 
	{
 		private static $menus=""; 
		private $option_name = 'membermouse-settings';
		private $defaults = array('count'=>10, 'append'=>1);
		private $metaname = '_associated_membermouse';

		public function __construct() 
		{
			// check if the plugin has been upgraded to a new major version and if so, run the installer
			if(class_exists("MM_MemberMouseService"))
			{
				if(!preg_match("/(plugins\.php)/", $_SERVER["PHP_SELF"]))
				{
					MM_MemberMouseService::validateLicense(new MM_License());
				}
				
				if(!isset($_GET["release"]))
				{
					$crntMajorVersion = MM_MemberMouseService::getPluginVersion();
	 				$lastMajorVersion = MM_OptionUtils::getOption(MM_OptionUtils::$OPTION_KEY_MAJOR_VERSION);
	 				if (!empty($lastMajorVersion) && (version_compare($lastMajorVersion, $crntMajorVersion) < 0))
	 				{	
						$this->install();
					}
				}
			}
			
			$this->addActions();
			$this->addFilters();
		}

		public function addFilters() 
		{
			global $wpdb;
			
			$user_hooks = new MM_UserHooks();
			add_filter('login_redirect', array($user_hooks, 'loginRedirect'), 1, 3);
			add_filter('template_redirect', array($user_hooks, 'handlePageAccess'));
			add_filter('login_url',  array($user_hooks, "loginUrl"),  1, 2);
			add_filter('logout_url',  array($user_hooks, "logoutUrl"),  1, 2);

			// add WP menu filters
			$showLoginLogoutLink = MM_OptionUtils::getOption(MM_OptionUtils::$OPTION_KEY_SHOW_LOGIN_LOGOUT_LINK);
			if($showLoginLogoutLink == "1")
			{
				add_filter('wp_nav_menu_items', array($user_hooks, 'showLoginLogoutLinkInMenu'), 10, 2);
			}
			
			$hideMenuItems = MM_OptionUtils::getOption(MM_OptionUtils::$OPTION_KEY_HIDE_PROTECTED_MENU_ITEMS);
			if($hideMenuItems == "1")
			{
				add_filter('wp_setup_nav_menu_item', array($user_hooks, 'hideProtectedMenuItems'));
			}
			
			add_filter('the_title', 'do_shortcode', 9);
			add_filter('wp_title', 'do_shortcode', 9);
			
			// add MM link footer
			if(class_exists("MM_MemberMouseService") && MM_MemberMouseService::hasPermission(MM_MemberMouseService::$SHOW_MM_FOOTER) == MM_MemberMouseService::$ACTIVE)
			{
				add_filter('wp_footer', array($this,'addMMFooter'), 9);
			}
			
			$post_hook = new MM_PostHooks();
			add_filter('manage_posts_columns', array($post_hook,'postsColumns'), 5);
			add_filter('manage_pages_columns', array($post_hook,'pagesColumns'), 5);
			add_filter('posts_where', array($post_hook, 'handlePostWhere'), 1, 1);
			add_filter('wp_head', array($post_hook, "checkPosts"));
			
			add_filter('the_content', array($this, "filterContent"), 10);
			
			//prevent members with pending status from logging in
			add_filter('authenticate', array($user_hooks,'checkLogin'), 100, 3);
		}
		
		public function addActions() 
		{
			if(function_exists("add_action"))
			{
				add_filter("plugin_action_links", array($this, "updateVersion"), 5, 3);
				
				$user_hooks = new MM_UserHooks();
				add_action('wp_login_failed', array($user_hooks,'loginFailed'), 9999);
				add_action( 'authenticate', array($user_hooks,'authenticateLogin'));
				add_action('init', array($user_hooks, "doAutoLogin"));
				add_action('init', array($user_hooks, "doAutoLogout"));
				add_action('init', array($user_hooks, "removeWPAutoPOnCorePages"));
				add_action("init", array($user_hooks, "setupDefinitions"));
				add_action("wp_footer", array($user_hooks, "pageBasedActions"));
				add_action( 'delete_user', array($user_hooks, "deleteUser"));
				add_action( 'profile_update', array($user_hooks, "handleProfileUpdate"));
				add_action('admin_init', array($user_hooks, "checkEmployeeAccess"));
				
				add_action("wp_head", array($this, "loadPreviewBar"));
				add_action("admin_bar_menu", array($this, "customizeAdminBar"));
				
				add_action("admin_enqueue_scripts", array($this, 'loadResources'));
				add_action("wp_enqueue_scripts", array($this, 'loadResources'));
				
				add_action('admin_menu', array($this, 'buildAdminMenu'));
				add_action("admin_notices", array($this, "showNotices"));
				add_action("admin_notices", array($this, "activationFailed"));
				add_action("admin_init", array($this, 'configurePostMeta'));
				
				if (class_exists("MM_SmartTagLibraryView"))
				{
					$smartTagLibrary = new MM_SmartTagLibraryView();
					add_action("admin_footer", array($smartTagLibrary, "addDialogContainers"));
					add_action('media_buttons_context', array($smartTagLibrary, "customMediaButtons"));
				}
				
				if (class_exists("MM_PaymentUtilsView"))
				{
					$paymentUtils = new MM_PaymentUtilsView();
					add_action("admin_footer", array($paymentUtils, "addDialogContainers"));
					add_action("wp_footer", array($paymentUtils, "addDialogContainers"));
				}
				
				$post_hook = new MM_PostHooks();
				add_action("trashed_post", array($post_hook, "trashPostHandler"));
				add_action("deleted_post", array($post_hook, "deletePostHandler"));
				add_action('manage_posts_custom_column', array($post_hook,'postCustomColumns'), 5, 2);
				add_action('manage_pages_custom_column', array($post_hook,'postCustomColumns'), 5, 2);
				add_action('restrict_manage_posts', array($post_hook,'editPostsFilter'));
				
				/// saveCorePages
				if(class_exists("MM_CorePageEngine"))
				{
					$corePageEngine = new MM_CorePageEngine();
					add_action('save_post', array($corePageEngine, 'saveCorePages'),10,2);
				}
				
				if(class_exists("MM_ProtectedContentEngine"))
				{
					$protectedContent = new MM_ProtectedContentEngine();
					add_action('save_post', array($protectedContent, 'saveSmartContent'),10,2);
				}
				
				add_action('wp_ajax_module-handle', array($this, 'handleAjaxCallback'));
				add_action('wp_ajax_nopriv_module-handle', array($this, 'handleAjaxCallback'));
				add_action('wp_ajax_member-types', array($this, 'handleAjaxCallback'));	
				
				// load MemberMouse widgets
				if(class_exists("MM_SmartWidget"))
				{
					add_action('widgets_init', create_function('', 'return register_widget("MM_SmartWidget");'));
				}
				
				if(class_exists("MM_DripContentWidget"))
				{
					add_action('widgets_init', create_function('', 'return register_widget("MM_DripContentWidget");'));
				}
				
				// add event listeners
				if(class_exists("MM_Event"))
				{
					if(class_exists("MM_PushNotificationEngine"))
					{
						$pne = new MM_PushNotificationEngine();
						add_action(MM_Event::$MEMBER_ADD, array($pne, 'memberAdded'), 10, 2);
						add_action(MM_Event::$MEMBER_STATUS_CHANGE, array($pne, 'memberStatusChanged'), 10, 2);
						add_action(MM_Event::$MEMBER_MEMBERSHIP_CHANGE, array($pne, 'memberMembershipChanged'), 10, 2);
						add_action(MM_Event::$MEMBER_ACCOUNT_UPDATE, array($pne, 'memberAccountUpdated'), 10, 2);
						add_action(MM_Event::$MEMBER_DELETE, array($pne, 'memberDeleted'), 10, 2);
						add_action(MM_Event::$BUNDLE_ADD, array($pne, 'bundleAdded'), 10, 2);
						add_action(MM_Event::$BUNDLE_STATUS_CHANGE, array($pne, 'bundleStatusChanged'), 10, 2);
						add_action(MM_Event::$PAYMENT_RECEIVED, array($pne, 'paymentReceived'), 10, 2);
						add_action(MM_Event::$PAYMENT_REBILL, array($pne, 'rebillPaymentReceived'), 10, 2);
						add_action(MM_Event::$PAYMENT_REBILL_DECLINED, array($pne, 'rebillPaymentDeclined'), 10, 2);
						add_action(MM_Event::$REFUND_ISSUED, array($pne, 'refundIssued'), 10, 2);
						add_action(MM_Event::$BILLING_SUBSCRIPTION_UPDATED, array($pne, 'billingSubscriptionUpdated'), 10, 2);
						add_action(MM_Event::$COMMISSION_INITIAL, array($pne, 'initialCommission'), 10, 2);
						add_action(MM_Event::$COMMISSION_REBILL, array($pne, 'rebillCommission'), 10, 2);
						add_action(MM_Event::$CANCEL_COMMISSION, array($pne, 'cancelCommission'), 10, 2);
						
						// isset methods are only necessary when customers are upgrading from MM 2.1.1 to 2.1.2. This can safely be removed
						// once all customers are on 2.1.2 or above
						if(isset(MM_Event::$BILLING_SUBSCRIPTION_REBILL_DATE_CHANGED))
						{
							add_action(MM_Event::$BILLING_SUBSCRIPTION_REBILL_DATE_CHANGED, array($pne, 'billingSubscriptionRebillDateChanged'), 10, 2);
						}
						
						if(isset(MM_Event::$BILLING_SUBSCRIPTION_CANCELED))
						{
							add_action(MM_Event::$BILLING_SUBSCRIPTION_CANCELED, array($pne, 'billingSubscriptionCanceled'), 10, 2);
						}
					}
					
					if(class_exists("MM_CronEngine"))
					{
						//add a special filter for the scheduled event queue check
						add_filter('cron_schedules', array('MM_CronEngine','addQueueCheckIntervalRecurrenceOption'));
						MM_CronEngine::setup();
					}
					
					if(class_exists("MM_AffiliateController"))
					{
						MM_AffiliateController::setup();
					}
					
					if(class_exists("MM_EmailServiceProviderController"))
					{
						MM_EmailServiceProviderController::setup();
					}
					
					if(class_exists("MM_MemberDetailsView"))
					{
						$mdv = new MM_MemberDetailsView();
						add_action(MM_Event::$PAYMENT_REBILL, array($mdv, 'handleRebillPaymentReceivedEvent'), 10, 2);
						add_action(MM_Event::$PAYMENT_REBILL_DECLINED, array($mdv, 'handleRebillPaymentDeclinedEvent'), 10, 2);
					}
				}
				
				//add payment service hooks
				if (class_exists("MM_PaymentService"))
				{
					add_action('init', array('MM_PaymentService', "performInitActions"), 9);
				}
				
				//add extension hooks
				if(class_exists("MM_Extension"))
				{
					add_action("init", array('MM_Extension', "performInitActions"), 9);
				}
				
				register_activation_hook( __FILE__, array($this, 'install'));
				register_deactivation_hook( __FILE__, array($this, 'onDeactivate'));				
			}
		}
		
		public function addMMFooter($content)
		{
			echo "<div style='width: 100%; padding-top: 10px; text-align:right; height: 50px; font-size: 12px;'>
	<a href=\"http://www.membermouse.com?ref=".urlencode(get_option("siteurl"))."&src=badge\">Membership Software</a> Provided by MemberMouse
	&nbsp;&nbsp;
</div>";
		}
		
		public function filterContent($content)
		{
			global $wp_query; 
			
			if(!is_feed() && !is_search() && !is_archive())
			{
				return $content;
			}
			
			$protectedContent = new MM_ProtectedContentEngine();
			$postId = $wp_query->query_vars["page_id"];
			if ($protectedContent->protectContent($postId))
			{
				$wpPost = get_post($postId);
				setup_postdata($wpPost);
				
				if($wpPost && ($wpPost->post_status == "publish" || $wpPost->post_status == "inherit")
						&& ($wpPost->post_type == "post" || $wpPost->post_type == "page" || MM_Utils::isCustomPostType($wpPost->post_type)))
				{
					$hasExcerpt = strpos($wpPost->post_content, "<!--more-->");
					if($hasExcerpt)
					{
						return substr($wpPost->post_content, 0, $hasExcerpt)." <a href=\"".get_permalink($postId)."\">Read More</a>";
					}
					
					return $content;
				}
				else 
				{
					return $content;	
				}
			}
			
			$post = get_post($postId);
			setup_postdata($post);
			$hasExcerpt = strpos($post->post_content, "<!--more-->");
			if($hasExcerpt)
			{
				return substr($post->post_content, 0, $hasExcerpt)." <a href=\"".get_permalink($postId)."\">Read More</a>";
			}
			
			return "This content is for members only";
		}
		
		public function updateVersion($a,$b,$c)
		{
			if ($b==plugin_basename(__FILE__))
			{
				$crntMajorVersion = MM_MemberMouseService::getPluginVersion();
				$upgradeVersion = MM_OptionUtils::getOption(MM_OptionUtils::$OPTION_KEY_UPGRADE_NOTICE);
				if(!empty($upgradeVersion) && $crntMajorVersion != $upgradeVersion)
				{
					$current = get_site_transient( 'update_plugins' );
					$current->response["membermouse/index.php"]->slug = "membermouse";
					$current->response["membermouse/index.php"]->package = MM_CENTRAL_SERVER_URL."/major-versions/".$upgradeVersion.".zip";
					$current->response["membermouse/index.php"]->new_version = $upgradeVersion;
					set_site_transient('update_plugins', $current);
				}
			}
			return $a;
		}
		
		public function checkVersion()
		{
			if(class_exists("MM_MemberMouseService", false))
			{
				$crntMajorVersion = MM_MemberMouseService::getPluginVersion();
				$upgradeVersion = MM_OptionUtils::getOption(MM_OptionUtils::$OPTION_KEY_UPGRADE_NOTICE);
				if(!empty($upgradeVersion) && $crntMajorVersion != $upgradeVersion)
				{
					if($upgradeVersion !== false)
					{
						$current = get_site_transient( 'update_plugins' );
						$current->response["membermouse/index.php"]->package = MM_PRETTY_CENTRAL_SERVER_URL."/major-versions/".$upgradeVersion.".zip";
						$current->response["membermouse/index.php"]->new_version = $upgradeVersion;
						set_site_transient('update_plugins',$current);
					}
				}
			}
		}
		
		public function loadPreviewBar()
		{
			if(class_exists("MM_PreviewView"))
			{
				if((MM_Employee::isEmployee() == true || current_user_can('manage_options')) && !is_admin()) 
				{
					MM_PreviewView::show();
				}
			}
		}
		
		public function customizeAdminBar()
		{
			if(MM_Employee::isEmployee())
			{
				global $wp_admin_bar;
				
				$wp_admin_bar->add_menu( array(
					'id'    => 'mm-menu',
					'title' => '<img src="'.MM_Utils::getImageUrl('mm-logo-svg').'" style="width:22px; margin-bottom:2px;" />',
					'href'  => MM_ModuleUtils::getUrl(MM_MODULE_DASHBOARD),
					'meta'  => array('title' => __('MemberMouse')),
				));
				
				$wp_admin_bar->add_menu(array(
					"id" => "mm-manage-members",
					"title" => "Manage Members",
					"href" => MM_ModuleUtils::getUrl(MM_MODULE_MANAGE_MEMBERS),
					"parent" => "mm-menu"
				));
				
				$wp_admin_bar->add_menu(array(
					"id" => "mm-reporting-suite",
					"title" => "Reporting Suite",
					"href" => MM_ModuleUtils::getUrl(MM_MODULE_REPORTING),
					"parent" => "mm-menu"
				));
				
				$wp_admin_bar->add_menu(array(
					"id" => "mm-product-settings",
					"title" => "Product Settings",
					"href" => MM_ModuleUtils::getUrl(MM_MODULE_PRODUCT_SETTINGS),
					"parent" => "mm-menu"
				));
				
				$wp_admin_bar->add_menu(array(
					"id" => "mm-support-center",
					"title" => "Support Center",
					"href" => "http://support.membermouse.com",
					"parent" => "mm-menu",
					"meta" => array("target" => "blank")
				));
			}
			
			if(!is_admin() && MM_OptionUtils::getOption(MM_OptionUtils::$OPTION_KEY_SHOW_PREVIEW_BAR) == "1")
			{
			?>
			<style>
			#wpadminbar {
				background: linear-gradient(to top, #373737 0px, #464646 0px) repeat scroll 0 0 #464646;
				background-image: -webkit-linear-gradient(bottom,#373737 0,#464646 1px);
				border-bottom: 1px #555555 solid;
			}
			
			#wpadminbar .ab-top-secondary {
				background: linear-gradient(to top, #373737 0px, #464646 0px) repeat scroll 0 0 #464646;
				background-image: -webkit-linear-gradient(bottom,#373737 0,#464646 1px);
				border-bottom: 1px #555555 solid;
			}
			
			body {
				margin-top: 34px;
			}
			</style>
			<script type='text/javascript'>
			jQuery(document).ready(function() {
				mmPreviewJs.hideNonMemberItems();
			});
			</script>
			<?php
			}
		}
		
		public function loadResources()
		{
			$customCssFiles = array();
			$customCssFiles["main"] = 'resources/css/common/mm-main.css';
			$customCssFiles["buttons"] = 'resources/css/common/mm-buttons.css';
			
			$module = MM_ModuleUtils::getModule();
			if ($module == MM_MODULE_REPORTING)
			{
				$customCssFiles["reporting"] = 'resources/css/admin/reporting/mm-reporting.css';
			}
			
			$useJQueryUI = MM_OptionUtils::getOption(MM_OptionUtils::$OPTION_KEY_USE_JQUERY_UI);
			
			if($useJQueryUI == "1" || is_admin())
			{
				$customCssFiles["jquery-css"] = 'resources/css/jquery-theme/jquery-ui-'.MM_JQUERY_UI_VERSION.'.custom.css';
			}
			$version = MM_MemberMouseService::getPluginVersion();
			
			foreach ($customCssFiles as $cssId=>$cssFile)
			{
				wp_enqueue_style("membermouse-".$cssId, plugins_url($cssFile, __FILE__), array(), $version);
			}
			
			$subfolder = ($module == MM_MODULE_REPORTING)?"reporting/":"";
			if(file_exists(MM_PLUGIN_ABSPATH."/resources/css/admin/{$subfolder}mm-".$module.".css")) 
			{
				wp_enqueue_style('membermouse-'.$module, plugins_url("resources/css/admin/{$subfolder}mm-".$module.'.css', __FILE__), array());	
			}
			
			wp_enqueue_style('membermouse-font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css', array());
			
			$this->loadJavascript($module);
		}
		
		public function loadJavascript($module="") 
		{
			$isAdminArea = is_admin();
			
			$url = MM_OptionUtils::getOption("siteurl");
			$adminUrl = admin_url();
			
			if(isset($_SERVER["HTTP_HOST"]))
			{
				$thisUrl = $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
				if(!preg_match("/(http)/", $thisUrl))
				{
					$thisUrl = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on")?"https://{$thisUrl}":"http://{$thisUrl}";
				}
			}
			
			if ((isset($_SERVER["HTTPS"]) && ($_SERVER["HTTPS"] == "on")) || preg_match("/(https)/", $thisUrl))
			{
				$url = preg_replace("/(http\:)/", "https:", $url);
				$adminUrl = preg_replace("/(http\:)/", "https:", $adminUrl);
			}
			
			//first include global script
			$version = MM_MemberMouseService::getPluginVersion(); //use plugin major version to control caching
			wp_enqueue_script("membermouse-global", plugins_url("/resources/js/global.js",__FILE__), array('jquery'),$version);
			$javascriptData = array("jsIsAdmin"=>$isAdminArea,
					  "adminUrl" =>$adminUrl,
					  "globalurl"=>MM_PLUGIN_URL,
					  "checkoutProcessingPaidMessage" => MM_OptionUtils::getOption(MM_OptionUtils::$OPTION_KEY_CHECKOUT_PAID_MESSAGE),
					  "checkoutProcessingFreeMessage" => MM_OptionUtils::getOption(MM_OptionUtils::$OPTION_KEY_CHECKOUT_FREE_MESSAGE),
					  "checkoutProcessingMessageCSS" => MM_OptionUtils::getOption(MM_OptionUtils::$OPTION_KEY_CHECKOUT_MESSAGE_CSS)
					);
			if (class_exists("MM_CurrencyUtil"))
			{
				$javascriptData["currencyInfo"] = MM_CurrencyUtil::getActiveCurrencyMetadata();
			}
			wp_localize_script("membermouse-global","MemberMouseGlobal",$javascriptData);
			
			$jsFiles = array();
			$userJSDir = "/resources/js/user/";
			$adminJSDir = "/resources/js/admin/";
			$commonJSDir = "/resources/js/common/";
			
			if ((isLocalInstall() && file_exists(MM_PLUGIN_ABSPATH."{$commonJSDir}class.js")) || !file_exists(MM_PLUGIN_ABSPATH."{$commonJSDir}mm-common-core.js"))
			{
				$commonJsFiles = array(
					'jquery.bgiframe-3.0.1.js',
					'jquery.ajaxfileupload.js',
					'class.js',
					'mm-cache.js',
					'mm-main.js',
					'class.ajax.js',
					'mm-dialog.js',
					'mm-core.js',
					'class.form.js',
					'mm-smarttag_library.js',
					'mm-payment_utils.js',
				);
			}
			else 
			{
				$commonJsFiles[] = 'mm-common-core.js';	
			}

			$useJQueryUI = MM_OptionUtils::getOption(MM_OptionUtils::$OPTION_KEY_USE_JQUERY_UI);
				
			if($useJQueryUI == "1" || is_admin())
			{
				$commonJsFiles[] = 'jquery-ui-'.MM_JQUERY_UI_VERSION.'.custom.min.js';
			}
			
			
			
			foreach($commonJsFiles as $file)
			{
				if(file_exists(MM_PLUGIN_ABSPATH."{$commonJSDir}{$file}"))
				{
					$jsFiles[] = plugins_url("{$commonJSDir}{$file}", __FILE__);
				}
			}
			
			if(!$isAdminArea)
			{
				$userFiles = array(
					'mm-preview.js',
				);
				
				foreach ($userFiles as $file)
				{
					if (file_exists(MM_PLUGIN_ABSPATH."{$userJSDir}{$file}"))
					{
						$jsFiles[] = plugins_url("{$userJSDir}{$file}", __FILE__);
					}
				}
			}
			else
			{
				// load UserVoice SDK
				$jsFiles[] = "//widget.uservoice.com/xCTpbo1WnzFyomHpkrBryQ.js";
				$jsFiles[] = plugins_url("{$adminJSDir}mm-corepages.js", __FILE__);
				$jsFiles[] = plugins_url("{$adminJSDir}mm-accessrights.js", __FILE__);
				
				if (!empty($module))
				{
					// load JavaScript classes dynamically based on the module
					$moduleJSDir = $adminJSDir;
					$jsFileName = $module;
					if ($module == MM_MODULE_REPORTING)
					{
						$jsFiles[] = plugins_url("{$adminJSDir}mm-reportjsbase.js", __FILE__);
						$moduleJSDir.="/reports/";
						$jsFileName = MM_ModuleUtils::getPage();
					}
					
					if (file_exists(MM_PLUGIN_ABSPATH."{$moduleJSDir}mm-{$jsFileName}.js"))
					{
						$jsFiles[] = plugins_url("{$moduleJSDir}mm-{$jsFileName}.js", __FILE__);
					}
				}
			}
			
			foreach ($jsFiles as $file_to_include)
			{
				$scriptname = basename($file_to_include);
				wp_enqueue_script($scriptname, $file_to_include, array('membermouse-global'),$version);
			}
		}
		
		public function onDeactivate()
		{	
	 		if(class_exists("MM_MemberMouseService"))
	 		{
		 		if(class_exists("MM_MemberMouseService"))
		 		{
					MM_MemberMouseService::deactivatePlugin();
		 		}
	 		}
	 		
	 		// clean up MM crons
	 		MM_CronEngine::cleanup();
		}
		
		public function activationFailed()
		{
			if(isset($_GET[MM_Session::$PARAM_COMMAND_DEACTIVATE]) && isset($_GET[MM_Session::$PARAM_MESSAGE_KEY]))
			{
				echo "<div class='updated'>";
				echo "<p>".urldecode($_GET[MM_Session::$PARAM_MESSAGE_KEY])."</p>";
				echo "</div>";
				@deactivate_plugins(MM_PLUGIN_ABSPATH."/index.php", false);
			}
		}
		
		public function showNotices()
		{
			$this->checkVersion();
		
			// check to see if cache is being used
			$writeableDir = MM_PLUGIN_ABSPATH."/com/membermouse/cache";
        	$usingDbCache = false;
        	if (class_exists("MM_Session"))
        	{
        		$usingDbCache = MM_Session::value(MM_Session::$KEY_USING_DB_CACHE);
        		if (empty($usingDbCache))
        		{
        			$usingDbCache = false;
        		}
        	}
        	
        	if(!isset($_GET['module']) || ($_GET['module'] != MM_MODULE_REPAIR_INSTALL))
        	{
        		$cacheRepairUrl = MM_ModuleUtils::getUrl(MM_MODULE_GENERAL_SETTINGS, MM_MODULE_REPAIR_INSTALL);
	        	if(!file_exists($writeableDir) || (is_dir($writeableDir) && !is_writeable($writeableDir)))
	        	{
	        		MM_Messages::addMessage("Currently MemberMouse can't utilize the cache. <a href='{$cacheRepairUrl}'>Click here to correct this.</a>");
	        		if (!file_exists($writeableDir))
	        		{
	        			@mkdir($writeableDir);	//if the cache directory is missing, attempt to create it silently if possible
	        		}
	        	}
	        	else if($usingDbCache)
	        	{
	        		//this means the dbcache is in use, but the cache is now writeable, show banner and see if refresh is available
	        		MM_Messages::addMessage("Currently MemberMouse can't utilize the cache. <a href='{$cacheRepairUrl}'>Click here to correct this.</a>");
	        		
	        		$lastAuth = MM_OptionUtils::getOption(MM_OptionUtils::$OPTION_KEY_LAST_CODE_REFRESH);
					$minInterval = time() - 60; //(1 min)
					
					if (class_exists("MM_MemberMouseService") && (empty($lastAuth) || ($lastAuth <= $minInterval)))
					{
						$refreshSuccess = MM_MemberMouseService::authorize();
						MM_OptionUtils::setOption(MM_OptionUtils::$OPTION_KEY_LAST_CODE_REFRESH, time());
					}
					
					MM_Session::clear(MM_Session::$KEY_USING_DB_CACHE);
	        	}
        	}
			
        	// check to see if there's a new version of MM available
			if(class_exists("MM_MemberMouseService"))
			{
				// check if there's an upgrade available
				$crntMajorVersion = MM_MemberMouseService::getPluginVersion();
				$upgradeVersion = MM_OptionUtils::getOption(MM_OptionUtils::$OPTION_KEY_UPGRADE_NOTICE);
				if(!empty($upgradeVersion) && (version_compare($upgradeVersion, $crntMajorVersion, ">")))
				{
					if(!preg_match("/(plugins\.php)/", $_SERVER["PHP_SELF"]) && !(isset($_GET["action"]) && $_GET["action"] == "upgrade-plugin"))
					{
						MM_Messages::addMessage("A new version of MemberMouse is available (<a href='plugins.php?plugin_update=membermouse&version={$upgradeVersion}'>upgrade now</a> | <a href='https://membermouse.uservoice.com/knowledgebase/articles/319210-membermouse-versions' target='_blank'>view release notes</a>)");
					}
				}
				
				// check if plugin needs to be upgraded
				global $wpdb;
				
				$sql = "SELECT count(u.wp_user_id) as total FROM ".MM_TABLE_USER_DATA." u, ".MM_TABLE_MEMBERSHIP_LEVELS." m WHERE ";
				$sql .= "u.membership_level_id = m.id AND (u.status = '".MM_Status::$ACTIVE."' OR u.status = '".MM_Status::$PENDING_CANCELLATION."') ";
				
				$result = $wpdb->get_row($sql);
				
				if($result)
				{
					$activeMembers = intval($result->total);
					$memberLimit = intval(MM_MemberMouseService::getMemberLimit());
					$upgradeUrl = MM_MemberMouseService::getUpgradeUrl();
					if($memberLimit != -1 && $activeMembers > $memberLimit)
					{
						MM_Messages::addMessage("MemberMouse is currently over the limit of ".number_format($memberLimit)." members and will be deactivated within a week of going over the limit. Please <a href='{$upgradeUrl}' target='_blank'>upgrade your account</a> to avoid any service interruptions.");
					}
				}
			}
			
			// check to see if any trouble plugins are activated
			MM_Utils::getPluginWarnings();
			
			// get error messages
			$errors = MM_Messages::get(MM_Session::$KEY_ERRORS);
			
			$output = "";
			
			if(is_array($errors) && count($errors) > 0)
			{	
				$output .= "<div class=\"error\">";
				foreach($errors as $msg)
				{
					$output .= "<p>{$msg}</p>";
				}
				$output .= "</div>";
			}
			
			// get notices
			$messages = MM_Messages::get(MM_Session::$KEY_MESSAGES);
			
			if(is_array($messages) && count($messages) > 0)
			{
				$output .= "<div class=\"updated\">";
				foreach($messages as $msg)
				{
					$output .= "<p>{$msg}</p>";
				}
				$output .= "</div>";
			}
			
			echo $output;
			
			MM_Messages::clear();
		}
		
		public function configurePostMeta()
		{	
			if(is_admin() && class_exists("MM_ProtectedContentView"))
			{
				$protectedContentView = new MM_ProtectedContentView();
				add_meta_box('membermouse_post_access', __('MemberMouse Options'), array($protectedContentView, 'postPublishingBox'), 'post', 'side', 'high');
				add_meta_box('membermouse_post_access', __('MemberMouse Options'), array($protectedContentView, 'postPublishingBox'), 'page', 'side', 'high');
				
				// add meta box to custom post types
				$args = array(
					'public'   => true,
					'_builtin' => false
				);

				$post_types = get_post_types($args, 'names', 'and');
				
				foreach ($post_types as $post_type) 
				{
					add_meta_box('membermouse_post_access', __('MemberMouse Options'), array($protectedContentView, 'postPublishingBox'), $post_type, 'side', 'high');
				}
				
				wp_enqueue_script('membermouse-postmeta', plugins_url('resources/js/admin/mm-accessrights.js', __FILE__), array('membermouse-smarttag'));
				wp_enqueue_script('membermouse-corepages', plugins_url('resources/js/admin/mm-corepages.js', __FILE__), array('membermouse-smarttag'));	
			}
		}
		
		public function install()
		{
			if(class_exists("MM_Install"))
			{
				$installer = new MM_Install();
				$installer->activate();
			}
		}

		public function handleAjaxCallback()
		{
			$response = array();
			$data = stripslashes_deep($_REQUEST);
				
			if(isset($data["module"]))
			{
				if(isset($data["type"]) && $data["type"]=="displayonly")
				{
					$obj = new $data["module"]();
					$ret = $obj->callMethod($data);
					
					if($ret instanceof MM_Response)
					{
						echo $ret->message;
					}
					else 
					{
						echo $ret;
					}
						
					exit();
				}
				else if(class_exists($data["module"]))
				{
					$obj = new $data["module"]();
					$response = $obj->callMethod($data);
				}
			}
			echo json_encode($response);
			exit();
		}
		
		public function buildAdminMenu() 
		{
			if (!isset($_GET[MM_Session::$PARAM_COMMAND_DEACTIVATE]))
			{
				global $current_user;

				$crntModule = MM_ModuleUtils::getPage();
				
				if($crntModule == MM_MODULE_GENERAL_SETTINGS)
				{
					add_thickbox();
				}
				
				if (class_exists("MM_Employee"))
				{
					$employee = MM_Employee::findByUserId($current_user->ID);
					$employee->buildMenu();
				}
			}
		}
	}
	
	if(class_exists("MM_License") && class_exists("MM_MemberMouseService")) 
	{
		if(!session_id() || session_id() == "") 
		{
			session_start();
		}
	 	
		$license = new MM_License();
		
		if($license->isValid())
		{
			if(MM_MemberMouseService::hasPermission(MM_MemberMouseService::$FEATURE_PHP_INTERFACE))
			{
				require_once("includes/php_interface.php");
			}
		}
	}
	
	$mmplugin = new MemberMouse();
}
?>