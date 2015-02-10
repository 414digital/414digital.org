<?php 
$report = new MM_NewMembersComparisonReport();
$startDate = date("m/d/Y",strtotime("-3 months"));
$endDate = date("m/d/Y");

$membershipLevels = MM_MembershipLevel::getMembershipLevelsList();
$firstInList = (is_array($membershipLevels))?current(array_keys($membershipLevels)):"";
$membershipLevelsOptionsHtml = MM_HtmlUtils::generateSelectionsList($membershipLevels);

?>
<!---------- Report Injection ---------->
<div class="mm-report-container">
	<h2 class="mm-dashboard-title">Compare Membership Levels <em class="mm-beta">beta</em></h2>
	
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
							<option selected="selected" value="weekly">Weekly</option>
							<option value="monthly">Monthly</option>
							<option value="yearly">Yearly</option>
						</select>
					</div>
					<!--/.mm-report-filter-set-->
					<br/>
					<div class="mm-report-filter-set">
						<label>Membership Levels</label>
						<select name="membership_levels">
							<?php echo $membershipLevelsOptionsHtml; ?>
						</select>
					
						<select name="membership_levels">
							<option selected="selected" value="none">None</option>
							<?php echo $membershipLevelsOptionsHtml; ?>
						</select>
					
						<select name="membership_levels">
							<option selected="selected" value="none">None</option>
							<?php echo $membershipLevelsOptionsHtml; ?>
						</select>
					
						<select name="membership_levels">
							<option selected="selected" value="none">None</option>
							<?php echo $membershipLevelsOptionsHtml; ?>
						</select>
					</div>
					<br/>
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
			<?php echo $report->createVisualElement(MM_NewMembersComparisonReport::$NEW_MEMBERS_COMPARISON_DATAGRID,array("start_date"=>$startDate,"end_date"=>$endDate,"type"=>"weekly","membership_levels"=>array($firstInList,"none","none","none")),"100%","100%"); ?>
			<?php echo $report->createVisualElement(MM_NewMembersComparisonReport::$NEW_MEMBERS_COMPARISON_LINECHART,array("start_date"=>$startDate,"end_date"=>$endDate,"type"=>"weekly","membership_levels"=>array($firstInList,"none","none","none")),"100%","456"); ?>
			<div class="clear"></div>
		</div>
		<!--/#rendered_report-->
	</div>
	<!--/.mm-graph-container-->
	
</div>
<!--/.mm-report-container-->

<div id="mm-report-description" style="display:none;" title="Report Description" style="font-size:11px;">
The Compare Levels report allows you to compare the performance of membership levels against each other. This report is 
useful to help you understand which membership levels are popular, and how they are performing relative to each other.
</div>