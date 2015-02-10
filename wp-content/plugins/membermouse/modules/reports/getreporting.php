<?php 
	$showContent = true;
	$error = "";
	
	if(isset($_GET["update"]) && $_GET["update"] == "true")
	{
		MM_MemberMouseService::authorize();
		$reportingDashboard = MM_ModuleUtils::getUrl(MM_MODULE_REPORTING);
		
		if(MM_MemberMouseService::hasPermission(MM_MemberMouseService::$FEATURE_REPORTING_SUITE) == MM_MemberMouseService::$ACTIVE)
		{
	?>
			<h2 class="mm-dashboard-title">Congratulations! Advanced Reporting has been activated.</h2>
			
			<p style="line-height:30px;"><a href="<?php echo $reportingDashboard; ?>" class="mm-ui-button green">Go to Reporting Dashboard</a></p>
	<?php
			$showContent = false;
		}
		else
		{
			$error = "Your license doesn't have access to the advanced reporting suite. Contact <a href='mailto:support@membermouse.com'>MemberMouse support</a> if you need assistance upgrading.";
		}
	}

	if($showContent)
	{
		$getReportingUrl = MM_ModuleUtils::getUrl(MM_MODULE_GET_REPORTING);
		
		if(!empty($error))
		{
			echo "<div class='error'><p>{$error}</p></div>";
		}
?>
<style>
#mm-upgrade-container { }
#mm-upgrade-content { padding-right:60px; }
#mm-upgrade-col-left { float: left; width: 700px; }
#mm-upgrade-col-right { float: left; width: 470px; }
</style>
<div id="mm-upgrade-container">
<div id="mm-upgrade-col-left">
<div id="mm-upgrade-content">
	<h2 class="mm-dashboard-title">Profit from the Advanced Reporting Suite!</h2>
	
	<iframe src="//fast.wistia.net/embed/iframe/dnjs7qfaic" allowtransparency="true" frameborder="0" scrolling="no" class="wistia_embed" name="wistia_embed" allowfullscreen mozallowfullscreen webkitallowfullscreen oallowfullscreen msallowfullscreen width="640" height="360"></iframe>
	
	<p>The reporting suite from MemberMouse is based on input from our customers who have built multi-million dollar
	membership websites. If you're serious about taking your business to the next level, unlocking this reporting suite will
	give you the metrics and insights you need to grow.</p>

	<h2 class="mm-dashboard-title">Unlock the Power of Lifetime Customer Value</h2>

	<p>Direct response marketers have known for years that accurate measurements of customer value are critical to scaling a
	business profitably. Since the profitability of customers can vary dramatically by source, it's important to understand which
	traffic sources are making you money and which are not.</p>

	<h2 class="mm-dashboard-title">Plus Retention, Churn, Channel Sales &amp; More...</h2>

	<p>Improving retention is one of the highest leverage actions that can be done to increase profitability. So in addition to customer
	value, you'll also be able to measure retention and churn rates, which are key components that drive profitability. Small increases in
	retention = big boosts to profits.</p>

<br><br>
</div><!--/.mm-upgrade-content-->
	</div><!--/.mm-upgrade-col-left-->
<div id="mm-upgrade-col-right">
	<h2 class="mm-dashboard-title"><i class="fa fa-bar-chart-o" style="#00465D"></i> How to Get Advanced Reporting</h2>

	<ol>
		<li><p>Go to your My Account page inside MemberMouse.com</p>
		<p><a href="https://membermouse.com/my-account" target=_blank" class="mm-ui-button blue">Access My Account</a></p></li>
		<li>In the <em>License Management</em> section, select the <em>Advanced</em> plan or higher from the dropdown in the <em>Upgrade</em> column</li>
		<li>Click <em>Upgrade</em></li>
		<li><p>Once your license has been upgraded, return here and click the button below to update your MemberMouse plugin</p>
		<p><a href="<?php echo $getReportingUrl; ?>&update=true" class="mm-ui-button">Update MemberMouse Plugin</a></p></li>
	</ol>

	<p style="border-top:1px solid #ccc; padding-top:10px; margin-top:15px">If you need help upgrading your account, simply send an email to <a href="mailto:support@membermouse.com?subject=Reporting%20Upgrade">support@membermouse.com</a>
	with the subject line <span style="font-family:courier">Reporting Upgrade</span>, include the URL of the site you want upgraded and we'll upgrade
	your account for you. Otherwise, simply follow the instructions above.</p>
</div><!--/.mm-upgrade-col-right-->
</div><!--/.mm-upgrade-container-->
</div>
<?php } ?>