<?php 
$report = new MM_AffiliateReport();
?>
<div id="mm-report-container">
	
	<?php echo $report->renderForm(array(
		array("label"=>"Start Date", "name"=>"start_date", "type"=>"date"),
		array("label"=>"End Date", "name"=>"end_date", "type"=>"date"),
		array("type"=>"blank_row"),
		array("name"=>"generate_report", "value"=>"Generate Report", "class"=>"mm-ui-button blue mm-report-submitter", "type"=>"submit-button"),
		array("name"=>"reset_button", "value"=>"Reset Form", "class"=>"mm-ui-button mm-report-reset", "type"=>"reset-button"),
	),"mm-form-container"); ?>
	<div id="rendered_report">
		<?php echo $report->createVisualElementPlaceholder(MM_AffiliateReport::$AFFILIATE_REPORT_DATAGRID,640,480); ?>
		<div class="clear"></div>
	</div>
</div>