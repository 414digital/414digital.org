<?php
$now = date("m/d/Y",MM_Utils::getCurrentTime("timestamp"));
$threeMonthsAgo = date("m/d/Y",strtotime("-3 months",MM_Utils::getCurrentTime("timestamp")));
$dashboard = new MM_ReportingDashboard();

$totalMbrsStyle = "width:25%;";
$retentionStyle = "width:25%;";
$avgCustValueStyle = "width:25%;";
$engagementStyle = "width:25%;";
$totalRevenueStyle = "width:25%;";
$retentionRateStyle = "width:15%;";
$churnStyle = "width:15%;";
$avgDailyRevenueStyle = "width:20%;";
$avgMonthlyRevenueStyle = "width:25%;";

$hasAdvancedReporting = (MM_MemberMouseService::hasPermission(MM_MemberMouseService::$FEATURE_REPORTING_SUITE) == MM_MemberMouseService::$ACTIVE) ? true : false;

if(!$hasAdvancedReporting)
{
	$totalMbrsStyle = "width:33%;";
	$retentionStyle = "width:33%;";
	$engagementStyle = "width:33%;";
	$totalRevenueStyle = "width:33%;";
	$avgDailyRevenueStyle = "width:33%;";
	$avgMonthlyRevenueStyle = "width:33%;";
}
?>
<div class="mm-report-container">
	<h2 class="mm-dashboard-title">Dashboard <em class="mm-beta">beta</em></h2>
	<?php include("dropdown_nav_menu.php"); ?>
	<!--/.reporting-dropdown-nav-->

	<h3 class="mm-report-title">Member Profile</h3>
	<div class="mm-metric-wrapper">
		<div id="special_infobox1" class="mm-metric-container mm-metric-container-25 blue" style="<?php echo $totalMbrsStyle; ?>">
			<?php echo $dashboard->createStaticVisualElement(MM_ReportingDashboard::$TOTAL_MEMBERS_VALUE,array(),"","",array("class"=>"metric-value")); ?>
			<h4>
				Total Members <a class="mm-info-tip" title="This field represents the total number of members in the system, both free and paid"><i class="fa fa-info-circle"></i></a>
			</h4>
		</div>
		<!--/.mm-metric-container-->
		<div id="special_infobox2" class="mm-metric-container mm-metric-container-25 turq" style="<?php echo $retentionStyle; ?>">
			<?php echo $dashboard->createStaticVisualElement(MM_ReportingDashboard::$TOTAL_RENTENTION_DURATION_VALUE,array(),"","",array("class"=>"metric-value"));?>
			<h4>
				Retention <a class="mm-info-tip" title="This field represents the average length of time that someone remains an active member"><i class="fa fa-info-circle"></i></a>
			</h4>
		</div>
		<?php if($hasAdvancedReporting) { ?>
		<!--/.mm-metric-container-->
		<div id="special_infobox3" class="mm-metric-container mm-metric-container-25 green" style="<?php echo $avgCustValueStyle; ?>">
			<?php echo $dashboard->createStaticVisualElement(MM_ReportingDashboard::$TOTAL_CUSTOMER_VALUE,array(),"","",array("class"=>"metric-value"));?>
			<h4>
				Avg. Customer Value <a class="mm-info-tip" title="This field represents the average value of a member across all membership levels and time"><i class="fa fa-info-circle"></i></a>
			</h4>
		</div>
		<?php } ?>
		<!--/.mm-metric-container-->
		<div id="special_infobox4" class="mm-metric-container mm-metric-container-25 orange" style="<?php echo $engagementStyle; ?>">
			<?php echo $dashboard->createStaticVisualElement(MM_ReportingDashboard::$TOTAL_ENGAGEMENT_VALUE,array(),"","",array("class"=>"metric-value"));?>
			<h4>
				Engagement <a class="mm-info-tip" title="This field represents the average number of pages a member visits per day"><i class="fa fa-info-circle"></i></a>
			</h4>
		</div>
		<!--/.mm-metric-container-->
	</div>
	<!--/.mm-metric-wrapper-->

	<h3 class="mm-report-title">New Members</h3>
	<div id="middle_left_dashboard_container" class="mm-graph-container">
		<div class="mm-report-filter mm-report-params">
			<div class="mm-report-filter-set">
				<label>Start Date:</label> <input type="text" id="start_date" name="start_date" value="<?php echo $threeMonthsAgo; ?>" class="mm-reporting-datepicker" placeholder="mm/dd/yyyy"/> 
				<a onClick="jQuery('#start_date').focus();"><i class="fa fa-calendar"></i></a>
			</div>
			<!--/.mm-report-filter-set-->
			<div class="mm-report-filter-set">
				<label>End Date:</label> <input type="text" id="end_date" name="end_date" value="<?php echo $now; ?>" class="mm-reporting-datepicker" placeholder="mm/dd/yyyy"/> 
				<a onClick="jQuery('#end_date').focus();"><i class="fa fa-calendar"></i></a>
			</div>
			<!--/.mm-report-filter-set-->
			<div class="mm-report-filter-set">
				<label>Graph Interval</label> 
				<select name="graph_interval">
					<option value="daily">Daily</option>
					<option value="weekly" selected="selected">Weekly</option>
					<option value="monthly">Monthly</option>
				</select> 
			</div>
			<!--/.mm-report-filter-set-->
			<div class="mm-report-filter-set">
				<input type="submit" class="mm-report-submitter" value="Apply" />
			</div>
			<!--/.mm-report-filter-set-->
		</div>
		<!--/.mm-report-filter-->
		<?php echo $dashboard->createVisualElement(MM_ReportingDashboard::$NEW_MEMBERS_LINECHART,array("start_date"=>$threeMonthsAgo,"end_date"=>$now,"graph_interval"=>"weekly"),"95%","456");?>
	</div>
	<!--/.graph-container-->

	<h3 class="mm-report-title">Revenue</h3>
	<div class="mm-metric-wrapper">
		<div id="special_infobox4" class="mm-metric-container mm-metric-container-25 blue" style="<?php echo $totalRevenueStyle; ?>">
			<?php echo $dashboard->createVisualElement(MM_ReportingDashboard::$TOTAL_REVENUE,array("start_date"=>$threeMonthsAgo,"end_date"=>$now),"","",array("class"=>"metric-value"));?>
			<h4>
				Total Revenue <a class="mm-info-tip" title="This field represents the total monthly revenue generated for the given timeframe"><i class="fa fa-info-circle"></i></a>
			</h4>
		</div>
		<!--/.mm-metric-container-->
		<?php if($hasAdvancedReporting) { ?>
		<div id="special_infobox8" class="mm-metric-container mm-metric-container-25 turq" style="<?php echo $retentionRateStyle; ?>">
			<?php echo $dashboard->createVisualElement(MM_ReportingDashboard::$RANGE_ADJUSTED_RETENTION_RATE,array("start_date"=>$threeMonthsAgo,"end_date"=>$now),"","",array("class"=>"metric-value"));?>
			<h4>
				Retention Rate <a class="mm-info-tip" title="Retention rate is calculated by MemberMouse as the inverse of churn. This figure represents the ratio of members who are staying active on a month to month basis. The figure is normalized to a 30 day frame in order to help you think about retention in approximate calendar months, which is the normal way of discussing and analyzing retention. For profit seeking membership businesses, it's important to understand that acquiring a new customer can cost several times more than retaining an existing customer and that small increases in retention can lead to large gains in profitability. Survey members who are canceling to discover ways to increase retention."><i class="fa fa-info-circle"></i></a>
			</h4>
		</div>
		<!--/.mm-metric-container-->
		<?php } ?>
		<div id="special_infobox6" class="mm-metric-container green" style="<?php echo $avgDailyRevenueStyle; ?>">
			<?php echo $dashboard->createVisualElement(MM_ReportingDashboard::$AVG_DAILY_REVENUE,array("start_date"=>$threeMonthsAgo,"end_date"=>$now),"","",array("class"=>"metric-value"));?>
			<h4>
				Avg. Daily Revenue <a class="mm-info-tip" title="This field represents the average daily revenue generated for the given timeframe"><i
					class="fa fa-info-circle"></i></a>
			</h4>
		</div>
		<!--/.mm-metric-container-->
		<div id="special_infobox7" class="mm-metric-container green-2" style="<?php echo $avgMonthlyRevenueStyle; ?>">
			<?php echo $dashboard->createVisualElement(MM_ReportingDashboard::$AVG_MONTHLY_REVENUE,array("start_date"=>$threeMonthsAgo,"end_date"=>$now),"","",array("class"=>"metric-value"));?>
			<h4>
				Avg. Monthly Revenue <a class="mm-info-tip" title="This field represents the average monthly revenue generated for the given timeframe"><i class="fa fa-info-circle"></i></a>
			</h4>
		</div>
		<!--/.mm-metric-container-->
		<?php if($hasAdvancedReporting) { 
			$churnDescription = "Membership churn is the rate of which customers are cancelling their accounts. Click to learn more about churn and how it's calculated.";
		?>
		<div id="special_infobox5" class="mm-metric-container mm-metric-container-25 orange" style="<?php echo $churnStyle; ?>">
			<?php echo $dashboard->createVisualElement(MM_ReportingDashboard::$RANGE_ADJUSTED_CHURN,array("start_date"=>$threeMonthsAgo,"end_date"=>$now),"","",array("class"=>"metric-value"));?>
			<h4>
				Churn <a class="mm-info-tip" onclick="showDescription('mm-churn-description')" title="<?php echo $churnDescription; ?>"><i class="fa fa-info-circle"></i></a>
			</h4>
		</div>
		<!--/.mm-metric-container-->
		<?php } ?>
	</div>
	<!--/.mm-metric-wrapper-->

	<div id="middle_right_dashboard_container" class="mm-graph-container">
		<?php echo $dashboard->createVisualElement(MM_ReportingDashboard::$DASHBOARD_REVENUE_LINECHART,array("start_date"=>$threeMonthsAgo,"end_date"=>$now,"graph_interval"=>"weekly"),"95%","456");?>
	</div>
	<!--/.graph-container-->
</div>
<!--/.mm-report-container-->

<?php if($hasAdvancedReporting) { ?>
<div id="mm-churn-description" style="display:none;" title="Churn Description" style="font-size:11px;">
<p>Churn is defined in MemberMouse as follows:</p>

<p><em>For each day in the given date range, the number of active members and the number 
of not active members are counted. Then, these daily figures are summed together. The summed figures are then divided by each 
other, and multiplied by 30 and then again by 100, to arrive at the displayed churn figure.</em></p>

<p><img src="https://dl.dropboxusercontent.com/u/265387542/plugin_images/churn_calculation.png" /></p>

<p>Members are considered <em>active</em> if they have an account status of <code>active</code>, <code>pending cancellation</code>, <code>locked</code> 
or <code>overdue</code> at the start of the day. Members are considered <em>not active</em> if they have an account status of <code>cancelled</code>
or <code>paused</code>.</p>

<p>What's important to understand is that this figure is normalized into a 30 day form. This means, for the given date range (even if it's 
6 months) the figure you see will represent the approximate 30 day churn rate. This 30 day normalization is useful, because it helps you 
to think about your attrition in calendar months, and is the conventional way of discussing churn.</p>
</div>
<?php } ?>