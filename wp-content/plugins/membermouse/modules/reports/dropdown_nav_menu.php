
<script>
function showDescription(id)
{
	jQuery("#"+id).show();

	if(id == "mm-churn-description")
	{
		jQuery("#"+id).dialog({autoOpen: true, width: "575", height: "480"});
	}
	else
	{
		jQuery("#"+id).dialog({autoOpen: true, width: "575", height: "300"});
	}
}
</script>

<?php 
$hasAdvancedReporting = (MM_MemberMouseService::hasPermission(MM_MemberMouseService::$FEATURE_REPORTING_SUITE) == MM_MemberMouseService::$ACTIVE) ? true : false;
$crntPage = MM_ModuleUtils::getPage();

if($hasAdvancedReporting)
{
	
	if($crntPage != MM_MODULE_REPORTING)
	{
	?>
		<a class="mm-ui-button" onclick="showDescription('mm-report-description')" style='position: absolute; right: 150px; top: -4px;'>About</a>
	<?php } else { ?>
		<a class="mm-ui-button" href="http://support.membermouse.com/knowledgebase/articles/404652" target="_blank" style="position: absolute; right: 150px; top: -4px;">How to Use Reporting</a>
	<?php } ?>
	
	
	
	<!-- .reporting-dropdown-nav-->
		<ul class="mm-reporting-dropdown-nav">
			<li class="nav-title"><a href="<?php echo admin_url('admin.php?page=reporting');?>"><i class="fa fa-list"></i> Reports <i class="fa fa-caret-down"></i></a>
				<ul>
					<li><a href="<?php echo admin_url('admin.php?page=reporting');?>">Dashboard</a></li>
					<li><a href="<?php echo admin_url('admin.php?page=new_members_report');?>">Member Count</a></li>
					<li><a href="<?php echo admin_url('admin.php?page=sales_by_membership_report');?>">Membership Sales</a></li>
					<li><a href="<?php echo admin_url('admin.php?page=sales_by_product_report');?>">Product Sales</a></li>
					<li><a href="<?php echo admin_url('admin.php?page=customer_value_report');?>">Customer Value</a></li>
					<li><a href="<?php echo admin_url('admin.php?page=simple_average_report');?>">Quick Average</a></li>
					<li><a href="<?php echo admin_url('admin.php?page=new_members_comparison_report');?>">Compare Levels</a></li>
					<li><a href="<?php echo admin_url('admin.php?page=sales_by_payment_service_report');?>">Payment Service</a></li>
					<li><a href="<?php echo admin_url('admin.php?page=total_value_report');?>">Channel Sales</a></li>
					<li></li>
					<li><a style="cursor:pointer;" onClick="mmjs.clearDataCache();">Clear Data Cache</a></li>
				</ul>
			</li>
		</ul>
	<!--/.reporting-dropdown-nav-->
<?php } else { 
	$advancedReportingUrl = MM_ModuleUtils::getUrl(MM_MODULE_GET_REPORTING);
	if($crntPage == MM_MODULE_REPORTING)
	{ ?>
	<a class="mm-ui-button" href="http://support.membermouse.com/knowledgebase/articles/404652" target="_blank" style="position: absolute; right: 250px; top: 0px;">How to Use Reporting</a>
	<?php } ?>
	<a href="<?php echo $advancedReportingUrl; ?>" class="mm-report-upgrade-link">Upgrade to Advanced Reporting</a>
<?php } ?>