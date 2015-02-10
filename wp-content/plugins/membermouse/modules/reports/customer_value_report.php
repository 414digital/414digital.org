<?php 
$report = new MM_CustomerValueReport();
$startDate = date("m/d/Y",strtotime("-3 months"));
$endDate = date("m/d/Y");
?>

<div class="mm-report-container">
	<h2 class="mm-dashboard-title">Customer Value <em class="mm-beta">beta</em></h2>
	
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
						<select id="customer_value_group" name="group_by">
							<option selected="selected" value="affiliate_id">Affiliate</option>
							<option value="membership_level">Membership Level</option>
						</select>
					</div>
					<!--/.mm-report-filter-set-->
					<div class="mm-report-filter-set">
						<input class="mm-report-submitter" type="submit" value="Generate Report" onClick="jQuery('#customer_value_units').html(jQuery('#customer_value_group option:selected').text() + 's');"/>
						<input class="mm-report-reset" type="reset" value="Reset Form" onClick="event.preventDefault(); mmjs.resetForm();"/>
					</div>
					<!--/.mm-report-filter-set-->
				</div>
				<!--/.mm-report-filter-->
				
			</div>
		</form>
		
		<div id="rendered_report">
			
			<!-- /#customer_value_report_datagrid-->
			<?php echo $report->createVisualElement(MM_CustomerValueReport::$CUSTOMER_VALUE_REPORT_DATAGRID,array("start_date"=>$startDate,"end_date"=>$endDate,"group_by"=>"affiliate_id"),"100%","100%"); ?>
			<div style="height:1rem;">&nbsp</div>
			<div><h2 class="mm-dashboard-title" style="text-align:center;">Top Ten <span id="customer_value_units">Affiliates</span></h2></div>
			<?php echo $report->createVisualElement(MM_CustomerValueReport::$CUSTOMER_VALUE_REPORT_LINECHART,array("start_date"=>$startDate,"end_date"=>$endDate,"group_by"=>"affiliate_id"),"100%","456"); ?>
			<div class="clear"></div>
		</div>
		<!--/#rendered_report-->
	</div>
	<!--/.mm-graph-container-->
	
</div>
<!--/.mm-report-container-->

<div id="mm-report-description" style="display:none;" title="Report Description" style="font-size:11px;">
The Customer Value report displays the average customer value by affiliate or by membership level, for the given date range. 
Each affiliate can be further analyzed by clicking on the affiliate ID, which will then show a list of sub-affiliate IDs associated with 
that affiliate, if any exist. This report shows how much money is spent by customers according to several arbitrary milestones. The first being 
Day 0, and the last being Day 720, in a customer's lifetime. Traditional direct response analysis instructs us to look at Days 30, 
60, and 90, to asses how much a customer is worth after several months of retention. This report will help you judge which sources 
of customers are the most valueable, and how much money you should spend acquiring a customer.  This report should be used after 
several months of data has been generated, ideally, a long enough period of time to approximate the retention duration of an average 
customer.
</div>