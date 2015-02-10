<?php 
/**
 *
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */
	// get default selections
	$selectedEventType = "";
	$selectedBundle = "";
	
	if(isset($_REQUEST["eventType"]))
	{ 
		$selectedEventType = $_REQUEST["eventType"];
		$_REQUEST["mm_event_types"] = array($selectedEventType);
	}
	
	if(isset($_REQUEST["bundleId"]))
	{ 
		$selectedBundle = $_REQUEST["bundleId"];
		$_REQUEST["mm_bundles"] = array($selectedBundle);
	}
?>
<div id="mm-form-container" style="background-color: #EAF2FA; padding-top:2px; padding-left:8px; padding-bottom:8px;">
	<table>
		<tr>
			<!-- LEFT COLUMN -->
			<td valign="top">
			<table cellspacing="5">
				<tr>
					<td>From</td>
					<td>
						<?php echo MM_Utils::getCalendarIcon(); ?>
						<input id="mm-from-date" type="text" style="width: 152px" /> 
					</td>
				</tr>
				<tr>
					<td>To</td>
					<td>
						<?php echo MM_Utils::getCalendarIcon(); ?>
						<input id="mm-to-date" type="text" style="width: 152px"  />
					</td>
				</tr>
				<tr>
					<td>IP Address</td>
					<td><input id="mm-ip-address" type="text" size="25" /></td>
				</tr>
				<tr>
					<td>Session</td>
					<td><input id="mm-diagnostic-session-id" type="text" size="25" /></td>
				</tr>
				<tr>
					<td>Location</td>
					<td><input id="mm-event-location" type="text" size="25" /></td>
				</tr>
				<tr>
					<td>Line #</td>
					<td><input id="mm-line-number" type="text" size="25" /></td>
				</tr>
				<tr>
					<td>Event Data</td>
					<td><input id="mm-event-data" type="text" size="25" /></td>
				</tr>
			</table>
			</td>
			
			<!-- CENTER COLUMN -->
			<td valign="top">
			<table cellspacing="5">
				<tr>
					<td>Event Types</td>
					<td>
						<select id="mm-event-types[]" size="6" multiple="multiple" style="width:300px;">
						<?php echo MM_HtmlUtils::generateSelectionsList(MM_DiagnosticLog::getEventTypeLabels(),$selectedEventType); ?>
						</select>
					</td>
				</tr>
			</table>
			</td>
		</tr>
	</table>
	
	<input type="button" class="mm-ui-button blue" value="Apply Filters" onclick="mmjs.search(0);">
	<input type="button" class="mm-ui-button" value="Reset Form" onclick="mmjs.resetForm();">
</div>

<script type='text/javascript'>
	jQuery(document).ready(function(){
		jQuery("#mm-from-date").datepicker();
		jQuery("#mm-to-date").datepicker();
		jQuery("#mm-form-container :input").keypress(function(e) {
	        if(e.which == 13) {
	            jQuery(this).blur();
	            mmjs.search(0);
	        }
	    });
				
	});
</script>