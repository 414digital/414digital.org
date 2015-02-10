<?php 
$report = new MM_SalesByMembershipReport();
$startDate = date("m/d/Y",strtotime("-3 months"));
$endDate = date("m/d/Y");
?>
<!---------- Report Injection ---------->
<div class="mm-report-container">
	<h2 class="mm-dashboard-title">Sales By Membership Level <em class="mm-beta">beta</em></h2>
	
	<?php include("dropdown_nav_menu.php"); ?>
	
	<div class="mm-graph-container">
		<form novalidate="">
			<div id="mm-form-container" class="mm-report-params">
				
				<div class="mm-report-filter">
					<div class="mm-report-filter-set">
						<label>Start Date:</label>
						<input type="text" id="start_date" class="mm-reporting-datepicker" name="start_date" value="<?php echo $startDate; ?>" placeholder="mm/dd/yyyy"/>
						<a onClick="jQuery('#start_date').focus();"><i class="fa fa-calendar"></i></a>
					</div>
					<!--/.mm-report-filter-set-->
					<div class="mm-report-filter-set">
						<label>End Date:</label>
						<input type="text" id="end_date" class="mm-reporting-datepicker" name="end_date" value="<?php echo $endDate; ?>" placeholder="mm/dd/yyyy"/>
						<a onClick="jQuery('#end_date').focus();"><i class="fa fa-calendar"></i></a>
					</div>
					<!--/.mm-report-filter-set-->
					<div class="mm-report-filter-set">
						<label>Include Rebills:</label>
						<input type="checkbox" name="include_rebills" CHECKED />
					</div>
					<!--/.mm-report-filter-set-->
					<div class="mm-report-filter-set">
						<input class="mm-report-submitter" type="submit" value="Generate Report" />
						<input class="mm-report-reset" type="reset" value="Reset Form" onClick="event.preventDefault(); mmjs.resetForm();"/>
					</div>
					<!--/.mm-report-filter-set-->
				</div>
				<!--/.mm-report-filter-->
				
			</div>
		</form>
		
		<div id="rendered_report">
			
			<!-- /#customer_value_report_datagrid-->
			<?php echo $report->createVisualElement(MM_SalesByMembershipReport::$SALESBYMEMBERSHIP_REPORT_DATAGRID,array("start_date"=>$startDate,"end_date"=>$endDate,"include_rebills"=>"on"),"100%","100%"); ?>
			
			<div style="margin-top:3em;"></div> <!-- spacer -->
			
			<?php echo $report->createVisualElement(MM_SalesByMembershipReport::$SALESBYMEMBERSHIP_REPORT_BARCHART,array("start_date"=>$startDate,"end_date"=>$endDate,"include_rebills"=>"on"),"100%","456px"); ?>
			<div class="clear"></div>
		</div>
		<!--/#rendered_report-->
	</div>
	<!--/.mm-graph-container-->
	
</div>
<!--/.mm-report-container-->

<div id="mm-report-description" style="display:none;" title="Report Description" style="font-size:11px;">
The Membership Sales report displays how much revenue was generated according to membership level, for the given date range. 
The report also includes the option to include or exclude rebill revenue, otherwise known as continuity or "back-end" revenue. 
Excluding rebill revenue is useful because it allows you to see how much money is being generated without taking recurring revenue 
into account, which can then be compared against monthly advertising spend to get a sense of how quickly the business is making or 
losing money acquiring new customers.
</div>