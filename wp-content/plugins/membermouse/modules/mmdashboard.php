<?php
/**
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */
global $wpdb;

$startDate = date("Y-m-d");

/* 
 * start calculations
 */

$activeStatus = MM_Status::$ACTIVE;
$pendingCancellationStatus = MM_Status::$ACTIVE;
$canceledStatus = MM_Status::$CANCELED;

$statistics = MM_MemberMouseService::generateStatistics();

$newMembersPaid = 0;
$newMembersFree = 0;

$sql = "SELECT count(1) as members, memberships.is_free FROM ".MM_TABLE_USER_DATA." u, ".MM_TABLE_MEMBERSHIP_LEVELS." memberships ";
$sql .= " WHERE u.membership_level_id=memberships.id AND u.became_active >= '{$startDate}' AND (u.status='{$activeStatus}' OR u.status='{$pendingCancellationStatus}')";
$sql .= " GROUP BY memberships.is_free";
$memberResults = $wpdb->get_results($sql);

if (($memberResults !=null) && is_array($memberResults) && (count($memberResults)>0))
{
	foreach($memberResults as $k=>$memberCount)
	{
		if ($memberCount->is_free == 1)
		{
			$newMembersFree = $memberCount->members;
		}
		else 
		{
			$newMembersPaid = $memberCount->members;
		}
	}
}

$newMbrCancelPaid = 0;
$newMbrCancelFree = 0;

$sql = "SELECT count(1) as members, memberships.is_free FROM ".MM_TABLE_USER_DATA." u, ".MM_TABLE_MEMBERSHIP_LEVELS." memberships ";
$sql .= " WHERE ((u.membership_level_id=memberships.id) and ((u.status='{$canceledStatus}') OR (u.status='".MM_Status::$PAUSED."'))) ";
$sql .= " GROUP BY memberships.is_free";
$cancelResults = $wpdb->get_results($sql);

if (($cancelResults !=null) && is_array($cancelResults) && (count($cancelResults)>0))
{
	foreach($cancelResults as $k=>$memberCount)
	{
		if ($memberCount->is_free == 1)
		{
			$newMbrCancelFree = $memberCount->members;
		}
		else 
		{
			$newMbrCancelPaid = $memberCount->members;
		}
	}
}

$newBundlesPaid = 0;
$newBundlesFree = 0;

$baseSql = "SELECT count(1) as total_bundles FROM ".MM_TABLE_APPLIED_BUNDLES." appBundles, {$wpdb->users} users, ";
$baseSql .= MM_TABLE_BUNDLES." bundles WHERE appBundles.access_type='".MM_AppliedBundle::$ACCESS_TYPE_USER."' AND appBundles.access_type_id = users.id ";
$baseSql .= "AND (appBundles.status='".MM_Status::$ACTIVE."' OR appBundles.status='".MM_Status::$PENDING_CANCELLATION."') AND appBundles.bundle_id = bundles.id AND appBundles.apply_date >= '{$startDate}' ";

// PAID BUNDLES
$result = $wpdb->get_row($baseSql." AND bundles.is_free = '0';");

if($result)
{
	$newBundlesPaid = $result->total_bundles;
}
else
{
	$newBundlesPaid = 0;
}

// FREE BUNDLES
$result = $wpdb->get_row($baseSql." AND bundles.is_free = '1';");

if($result)
{
	$newBundlesFree = $result->total_bundles;
}
else
{
	$newBundlesFree = 0;
}                                                

$newBundleCancelPaid = 0;
$newBundleCancelFree = 0;

$baseSql = "SELECT count(1) as total_bundles FROM ".MM_TABLE_APPLIED_BUNDLES." appBundles, {$wpdb->users} users, ";
$baseSql .= MM_TABLE_BUNDLES." bundles WHERE appBundles.access_type='".MM_AppliedBundle::$ACCESS_TYPE_USER."' AND appBundles.access_type_id = users.id ";
$baseSql .= "AND (appBundles.status='".MM_Status::$CANCELED."' OR appBundles.status='".MM_Status::$PAUSED."') ";
$baseSql .= "AND appBundles.bundle_id = bundles.id ";

// PAID BUNDLES
$result = $wpdb->get_row($baseSql." AND bundles.is_free = '0';");

if($result)
{
	$newBundleCancelPaid = $result->total_bundles;
}
else
{
	$newBundleCancelPaid = 0;
}

// FREE BUNDLES
$result = $wpdb->get_row($baseSql." AND bundles.is_free = '1';");

if($result)
{
	$newBundleCancelFree = $result->total_bundles;
}
else
{
	$newBundleCancelFree = 0;
}                                                                                                                                                                                                                                               

/*
 * end calculations
 */

//reformat start date for display
$startDate = date("M j, Y", strtotime($startDate));
?>

<div class="mm-view-container">
	<div class="mm-dashboard-header">       
        <div class="logo"><a href="http://membermouse.com/" target="_blank"><img src="https://dl.dropboxusercontent.com/u/265387542/plugin_images/membermouse-logo.png" alt="MemberMouse" style="width:340px; padding-top:12px;"></a></div> 
		        <ul class="mm-quick-nav" style="top:-15px;">
	   				<li><a href="<?php echo MM_ModuleUtils::getUrl(MM_MODULE_MANAGE_MEMBERS); ?>"><i class="fa fa-users"></i> Manage Members</a></li>
	   				<li><a href="<?php echo MM_ModuleUtils::getUrl(MM_MODULE_REPORTING); ?>"><i class="fa fa-bar-chart-o"></i> Reporting Suite</a></li>
	   				<li><a href="<?php echo MM_ModuleUtils::getUrl(MM_MODULE_PRODUCT_SETTINGS); ?>"><i class="fa fa-shopping-cart"></i> Product Settings</a></li>
	   			</ul>       
		    </div>
		    
   			<div class="mm-metric-wrapper">
   				<div class="mm-metric-container mm-metric-container-25 blue">
   					<h3 class="metric-header">
   						<i class="fa fa-users"></i> Membership Snapshot
   					</h3>
   					<div class="metric-row">
	   					Free Members Today
	   					<span class="row-value"><?php echo number_format($newMembersFree); ?></span>
	   				</div>
	   				<div class="metric-row">
	   					Total Free Members
	   					<span class="row-value"><?php echo number_format($statistics[MM_MemberMouseService::$USAGE_FREE_MEMBERS]); ?></span>
	   				</div>
	   				<div class="metric-row">
	   					Paid Members Today
	   					<span class="row-value"><?php echo number_format($newMembersPaid); ?></span>
	   				</div>
	   				<div class="metric-row">
	   					Total Paid Members
	   					<span class="row-value"><?php echo number_format($statistics[MM_MemberMouseService::$USAGE_PAID_MEMBERS]); ?></span>
	   				</div>
   				</div>
   				<!--/.mm-metric-container-->
   				
   				<div class="mm-metric-container mm-metric-container-25 green">
   					<h3 class="metric-header">
   						<i class="fa fa-cubes"></i> Bundle Snapshot
   					</h3>
   					<div class="metric-row">
	   					Free Bundles Today
	   					<span class="row-value"><?php echo number_format($newBundlesFree); ?></span>
	   				</div>
	   				<div class="metric-row">
	   					Total Free Bundles
	   					<span class="row-value"><?php echo number_format($statistics[MM_MemberMouseService::$USAGE_FREE_BUNDLES]); ?></span>
	   				</div>
	   				<div class="metric-row">
	   					Paid Bundles Today
	   					<span class="row-value"><?php echo number_format($newBundlesPaid); ?></span>
	   				</div>
	   				<div class="metric-row">
	   					Total Paid Bundles
	   					<span class="row-value"><?php echo number_format($statistics[MM_MemberMouseService::$USAGE_PAID_BUNDLES]); ?></span>
	   				</div>
   				</div>
   				<!--/.mm-metric-container-->
   				
   				<div class="mm-metric-container mm-metric-container-25 purple">
   					<h3 class="metric-header">
   						<i class="fa fa-shopping-cart"></i> Product Snapshot
   					</h3>
   					<div class="metric-row">
	   					Products
	   					<span class="row-value"><?php echo number_format($statistics[MM_MemberMouseService::$CONFIG_PRODUCTS]); ?></span>
	   				</div>
	   				<div class="metric-row">
	   					Membership Levels
	   					<span class="row-value"><?php echo (number_format($statistics[MM_MemberMouseService::$CONFIG_MEMBERSHIPS_FREE]) + number_format($statistics[MM_MemberMouseService::$CONFIG_MEMBERSHIPS_PAID])) ; ?></span>
	   				</div>
	   				<div class="metric-row">
	   					Bundles
	   					<span class="row-value"><?php echo (number_format($statistics[MM_MemberMouseService::$CONFIG_BUNDLES_FREE]) + number_format($statistics[MM_MemberMouseService::$CONFIG_BUNDLES_PAID])) ; ?></span>
	   				</div>
	   				<div class="metric-row">
	   					&nbsp;
	   				</div>
   				</div>
   				<!--/.mm-metric-container-->
   				
   				<div class="mm-metric-container mm-metric-container-25 turq">
   					<h3 class="metric-header">
   						<i class="fa fa-times"></i> Retention Snapshot
   					</h3>
   					<div class="metric-row">
	   					Canceled Free Memberships
	   					<span class="row-value"><?php echo number_format($newMbrCancelFree); ?></span>
	   				</div>
	   				<div class="metric-row">
	   					Canceled Paid Memberships
	   					<span class="row-value"><?php echo number_format($newMbrCancelPaid); ?></span>
	   				</div>
	   				<div class="metric-row">
	   					Canceled Free Bundles
	   					<span class="row-value"><?php echo number_format($newBundleCancelFree); ?></span>
	   				</div>
	   				<div class="metric-row">
	   					Canceled Paid Bundles
	   					<span class="row-value"><?php echo number_format($newBundleCancelPaid); ?></span>
	   				</div>
   				</div>
   				<!--/.mm-metric-container-->
   				
   			</div>
   			<!--/.mm-metric-wrapper-->
   			
   			<div class="mm-content-wrapper">
   				<div class="mm-content-container">
   					<h3><i class="fa fa-support" style="top:0px;"></i> Support</h3>
   					<ul class="spaced-list">
   						<li><a href="http://support.membermouse.com" target="_blank">Support Center</a></li>
   						<?php if(MM_SupportUtils::hasEmailSupport()) { ?>
   						<li><a href="http://support.membermouse.com/knowledgebase/articles/417246" target="_blank"><i class="fa fa-envelope"></i> Email us</a></li>
   						<?php } ?>
   						<?php if(MM_SupportUtils::hasPhoneSupport()) { ?>
		   				<li><i class="fa fa-phone"></i> (512) 630-2219</li>
		   				<?php } ?>
   					</ul>
   					
   					<h3><i class="fa fa-money" style="top:1px;"></i> Profit Alerts</h3>
   					<ul class="tips">
   					<?php
   						if(MM_MemberMouseService::hasPermission(MM_MemberMouseService::$FEATURE_REPORTING_SUITE) != MM_MemberMouseService::$ACTIVE)
   						{
   					?>
   						<li><i class="fa fa-check-square"></i> Learn more about the <a href="<?php echo MM_ModuleUtils::getUrl(MM_MODULE_GET_REPORTING); ?>">Advanced Reporting Suite</a></li>
   					<?php } ?>
   						<li><i class="fa fa-check-square"></i> Learn how million dollar membership sites operate in <a href="http://membermouse.com/7-steps-to-seven-figures" target="_blank">7 Steps to Seven Figures</a></li>
   						<li><i class="fa fa-check-square"></i> Earn 20% recurring commission with our <a href="http://membermouse.com/affiliate-program/" target="_blank">Affiliate Program</a></li>
   					</ul>
   				</div>
   				<!--/.mm-content-container-->
   				
   				<div class="mm-content-container w50">
   					<h3><i class="fa fa-comments"></i> News</h3>
   					<div class="mm-news-feed">
   						<?php 
	   			function changeCachePeriod($secs)
	   			{
	   				return 7200;
	   			}
	   			if(function_exists('fetch_feed')) 
	   			{  
        			include_once(ABSPATH . WPINC . '/feed.php'); // the file to rss feed generator  
        			add_filter('wp_feed_cache_transient_lifetime' , 'changeCachePeriod');
        			$feed = fetch_feed('http://membermouse.com/?cat=34&feed=rss2'); // specify the rss feed  
        			remove_filter('wp_feed_cache_transient_lifetime' , 'changeCachePeriod');
        			
        			if (!is_wp_error($feed))
        			{
        				$limit = $feed->get_item_quantity(7); // specify number of items  
        				$items = $feed->get_items(0, $limit); // create an array of items  
        			}
        			else
        			{
        				$limit = 0;
        			}
    			}  
    			
    			if ($limit == 0) 
    			{
    				echo "<p class=\"news-body\">No news at the moment.</p>";  
    			}
    			else 
    			{
    				foreach ($items as $item) 
    				{ 
    			?>  
    					<h4 class="news-header"><a href="<?php echo esc_url($item->get_permalink()); ?>" target="_blank" alt="<?php echo esc_html($item->get_title()); ?>"><?php echo esc_html($item->get_title()); ?></a></h1>  
					    <p class="news-date"><?php echo $item->get_date('j F Y @ g:i a'); ?></p>  
					    <p class="news-body"><?php echo substr(esc_html($item->get_description()), 0, 200); ?></p>
    			<?php 
    				}
    			} 
    			?>  
	   				</div>
	   				<!--/.mm-news-feed-->
   				</div>
   				<!--/.mm-content-container-->
   			</div>
   			<!--/.mm-content-wrapper-->
   	
   	<?php 
   		$minorVersion = MM_OptionUtils::getOption(MM_OptionUtils::$OPTION_KEY_MINOR_VERSION);
   		if(empty($minorVersion)) { $minorVersion = MM_MemberMouseService::$DEFAULT_MINOR_VERSION; }
   	?>
   	<div class="mm-version">MemberMouse Version <span><?php echo MM_MemberMouseService::getPluginVersion()."-".$minorVersion; ?></span></div>
   		
</div>
<!--/.mm-view-container-->