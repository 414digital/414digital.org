<?php 
$report = new MM_TotalValueReport();
$startDate = date("m/d/Y",strtotime("-3 months"));
$endDate = date("m/d/Y");
?>
<!---------- Report Injection ---------->

<div class="mm-report-container">
	<h2 class="mm-dashboard-title">Channel Sales <em class="mm-beta">beta</em></h2>
	
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
						<input class="mm-report-submitter" type="submit" value="Generate Report" />
						<input class="mm-report-reset" type="reset" value="Reset Form" />
					</div>
					<!--/.mm-report-filter-set-->
				</div>
				<!--/.mm-report-filter-->
				
			</div>
		</form>
		
		<div id="rendered_report">
			<?php echo $report->createVisualElement(MM_TotalValueReport::$TOTAL_VALUE_REPORT_DATAGRID,array("start_date"=>$startDate,"end_date"=>$endDate),"100%","100%"); ?>			
			<div class="clear"></div>
		</div>
		<!--/#rendered_report-->
	</div>
	<!--/.mm-graph-container-->
	
</div>
<!--/.mm-report-container-->

<div id="mm-report-description" style="display:none;" title="Report Description" style="font-size:11px;">
The Channel Sales report allows you to see the total value of each affiliate ID being tracked by MemberMouse, for the given 
date range. Knowing how much revenue has been generated in total by a given channel (also known as an affiliate) is useful 
since it allows you to compare this value against advertising costs or affiliate payments associated with that channel. 
If you are spending $1,000 per month on a given channel, this report will help tell you if that money is well spent.
</div>