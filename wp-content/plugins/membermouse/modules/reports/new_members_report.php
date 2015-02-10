<?php 
$report = new MM_NewMembersReport();
$startDate = date("m/d/Y",strtotime("-3 months"));
$endDate = date("m/d/Y");
?>
<!---------- Report Injection ---------->
<div class="mm-report-container">
	<h2 class="mm-dashboard-title">Member Count <em class="mm-beta">beta</em></h2>
	
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
						<label>Group By:</label>
						<select name="type">
							<option value="daily">Daily</option>
							<option value="weekly">Weekly</option>
							<option selected="selected" value="monthly">Monthly</option>
							<option value="yearly">Yearly</option>
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
			<?php echo $report->createVisualElement(MM_NewMembersReport::$NEW_MEMBERS_DATAGRID,array("start_date"=>$startDate,"end_date"=>$endDate,"type"=>"monthly"),"100%","100%"); ?>
			<!-- #customer_value_report_datagrid-->
			
			<div class="clear"></div>
		</div>
		<!--/#rendered_report-->
	</div>
	<!--/.mm-graph-container-->
	
</div>
<!--/.mm-report-container-->

<div id="mm-report-description" style="display:none;" title="Report Description" style="font-size:11px;">
The Member Count report displays how many members have joined according to membership level, for the given date range. 
Counts can be grouped by day, week, month, or year. This report is useful to see overal membership trends.
</div>