<?php 
$report = new MM_SimpleAverageReport();
$startDate = date("m/d/Y",strtotime("-3 months"));
$endDate = date("m/d/Y");
?>
<!---------- Report Injection ---------->
<div class="mm-report-container">
	<h2 class="mm-dashboard-title">Quick Average <em class="mm-beta">beta</em></h2>
	
	<?php include("dropdown_nav_menu.php"); ?>
	
	
	<div class="mm-graph-container">
		<form novalidate="">
			<div id="mm-form-container" class="mm-report-params">
				
				<div class="mm-report-filter">
					<div class="mm-report-filter-set">
						<label>Start Date:</label>
						<input type="text" id="start_date" name="start_date" class="mm-reporting-datepicker" value="<?php echo $startDate; ?>" placeholder="mm/dd/yyyy"/>
						<a onClick="jQuery('#start_date').focus();"><i class="fa fa-calendar"></i></a>
					</div>
					<!--/.mm-report-filter-set-->
					<div class="mm-report-filter-set">
						<label>End Date:</label>
						<input type="text" id="end_date" name="end_date" class="mm-reporting-datepicker" value="<?php echo $endDate; ?>" placeholder="mm/dd/yyyy"/>
						<a onClick="jQuery('#end_date').focus();"><i class="fa fa-calendar"></i></a>
					</div>
					<!--/.mm-report-filter-set-->
					<div class="mm-report-filter-set">
						<label>Analyze By:</label>
						<select name="type">
							<option selected="selected" value="affiliate">Affiliate</option>
							<option value="subaffiliate">Subaffiliate</option>
							<!-- <option value="product">Product</option> -->
						</select>
					</div>
					<!--/.mm-report-filter-set-->
					<div class="mm-report-filter-set">
						<input class="mm-report-submitter" type="submit" value="Generate Report" />
						<input class="mm-report-reset" type="reset" value="Reset Form" />
					</div>
					<!--/.mm-report-filter-set-->
				</div>
				<!--/.mm-report-filter-->
				
			</div>
		</form>
		
		<div id="rendered_report">
			
			<!-- /#customer_value_report_datagrid-->
			<?php echo $report->createVisualElement(MM_SimpleAverageReport::$SIMPLE_AVERAGE_DATAGRID,array("start_date"=>$startDate,"end_date"=>$endDate,"type"=>"affiliate"),"100%","100%"); ?>
			<?php echo $report->createVisualElement(MM_SimpleAverageReport::$SIMPLE_AVERAGE_COLUMN_CHART,array("start_date"=>$startDate,"end_date"=>$endDate,"type"=>"affiliate"),"100%","456"); ?>
			<div class="clear"></div>
		</div>
		<!--/#rendered_report-->
	</div>
	<!--/.mm-graph-container-->
	
</div>
<!--/.mm-report-container-->

<div id="mm-report-description" style="display:none;" title="Report Description" style="font-size:11px;">
The Quick Average report is similar to the Customer Value report, but here, revenue for the given date range is simply 
divided by the number of customers for the period. This report is useful if you would like to get a sense of what you 
Customer Value is, but do not yet have enough data to look back in time, which is the primary utility of the Customer 
Value report. This report will generally provide conservative estimates, since it does not take future payments into 
account that would occur after the end date. Regardless, it will help you get an idea of how much each source of customers 
is worth relative to other sources (also known as affiliates or channels).
</div>