<?php
/**
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */
?>

<style>
.mm-import-wizard-step {
	font: 21px/1.3 'PT Sans','Myriad Pro',Myriad,Arial,Helvetica,sans-serif;
    margin-bottom: 20px;
    color: #004D66;
    margin-top: 20px;
}
.mm-import-wizard-notice {
    color: #F90;
	font: 16px/0.4em 'PT Sans','Myriad Pro',Myriad,Arial,Helvetica,sans-serif;
    margin-bottom: 20px;
    margin-top: 20px;
}
#mm-form-container td {
    font-size: 14px;
    vertical-align: middle;
}
.ui-progressbar-value { 
	background-image: url('<?php echo MM_IMAGES_URL."pbar-animated.gif" ?>'); 
}
</style>

<div class="mm-wrap" style="font-size:14px;">
    
<?php
if(isset($_POST["mm-membership-selector"]))
{	
?>
<div id="import-running">
<p id="mm-import-progress-message" class="mm-import-wizard-notice">IMPORT RUNNING... PLEASE DO NOT REFRESH THIS PAGE</p>

<div id="mm-results-container"></div>
<div id="mm-progressbar-container">
	<div id="mm-progressbar" style="width:400px; height:22px;"></div>
	<script>
	jQuery(function() {
		jQuery("#mm-progressbar").progressbar({value: 100});
	});
	</script>
</div>
</div>
<?php 
	$view = new MM_ImportWizardView();
	$result = $view->import($_POST);
}
else 
{
?>
	<p class="mm-import-wizard-step">Step 1: Download Import Template</p>

	<p style="margin-left:12px;">
		<a class="mm-ui-button" onclick="mmjs.downloadTemplate('<?php echo MM_MODULES_URL; ?>','<?php echo urlencode(MM_PLUGIN_ABSPATH."/templates/mm_import_template.csv"); ?>');"><?php echo MM_Utils::getIcon('download', '', '1.3em', '2px'); ?> Download Import Template</a>
		<a class="mm-ui-button" onclick="stl_js.showIdLookup('');"><?php echo MM_Utils::getIcon('search', '', '1.3em', '2px'); ?> Lookup IDs</a>
		<a class="mm-ui-button" href="http://en.wikipedia.org/wiki/ISO_3166-1_alpha-2#Officially_assigned_code_elements" target="_blank"><?php echo MM_Utils::getIcon('globe', '', '1.3em', '2px'); ?> ISO Country Codes</a>
	</p>
	
	<p style="margin-left:12px; margin-top:20px;">
		Member Status IDs:
		<span style="margin-left:10px; font-size:14px;">
			<?php echo MM_Status::getImage(MM_Status::$ACTIVE); ?> 1
		</span>
		<span style="margin-left:10px; font-size:14px;">
			<?php echo MM_Status::getImage(MM_Status::$CANCELED); ?> 2
		</span>
		<span style="margin-left:10px; font-size:14px;">
			<?php echo MM_Status::getImage(MM_Status::$LOCKED); ?> 3
		</span>
		<span style="margin-left:10px; font-size:14px;">
			<?php echo MM_Status::getImage(MM_Status::$PAUSED); ?> 4
		</span>
		<span style="margin-left:10px; font-size:14px;">
			<?php echo MM_Status::getImage(MM_Status::$OVERDUE); ?> 5
		</span>
		<span style="margin-left:10px; font-size:14px;">
			<?php echo MM_Status::getImage(MM_Status::$PENDING_ACTIVATION); ?> 6
		</span>
		<span style="margin-left:10px; font-size:14px;">
			<?php echo MM_Status::getImage(MM_Status::$ERROR); ?> 7
		</span>
		<span style="margin-left:10px; font-size:14px;">
			<?php echo MM_Status::getImage(MM_Status::$EXPIRED); ?> 8
		</span>
	</p>
	
	<p class="mm-import-wizard-step" style="margin-bottom:10px;">Step 2: Upload Import File</p>

	<div id="mm-upload-import-file-form">
	<table cellspacing="12">
	<tr>
		<td width="120px;">
			<input id="mm-import-file-from-computer-radio" type="radio" checked value="computer" name="import-file-location">
			From Computer
		</td>
		<td>
			<div id="mm-uploaded-file-details" style='display:none;'>
				<div id="mm-uploaded-file" style='float:left; font-family:courier;'></div>
				<div id="mm-uploaded-file-hidden" style='display:none;float:left;'></div>
				<a onclick="mmjs.clearUploadedFile()" class="mm-ui-button" style='margin-left: 10px; float:left;'>Clear</a>
				<div style='clear:both;'></div>
			</div>
			<div id="mm-file-upload-container">
				<form action="admin-ajax.php" name='file-upload' method="post" enctype="multipart/form-data" target="upload_target" onsubmit="mmjs.startUpload();" >
		        	<input id="fileToUpload" name="fileToUpload" type="file" size="30" />
		            <input type="submit" name="submitBtn" class="mm-ui-button" value="Upload" />
	                <input type='hidden' name='method' value='uploadFile' />
	                <input type='hidden' name='module' value='MM_ImportWizardView' />
	                <input type='hidden' name='action' value='module-handle' />
		            <iframe id="upload_target" name="upload_target" style="width:0;height:0;border:0px solid #fff;"></iframe>
		      	</form>
		   	</div>
		</td>
	</tr>
	<tr>
		<td>
			<input id="mm-import-file-from-url-radio" type="radio" value="url" name="import-file-location">
			From URL
		</td>
		<td>
			<span style="font-family: courier; font-size: 12px;">
				<input type="text" id="mm-import-file-from-url-source" style="width:430px;" />
			</span>
		</td>
	</tr>
   	</table>
	</div>
	
	<form method="post" onSubmit="return mmjs.validateForm();">
	<p class="mm-import-wizard-step" style="margin-bottom:10px;">Step 3: Configure Import Settings</p>
	
	<table cellspacing="12">
		<tr>
			<td width="140">Import members as</td>
			<td>
				<select id="mm-membership-selector" name="mm-membership-selector">
					<?php echo MM_HtmlUtils::getMemberships(null, true); ?>
				</select>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<input type='checkbox' id='mm-send-welcome-email' name='mm-send-welcome-email' /> Send welcome email to new members
			</td>
		</tr>
	</table>
	
	<p class="mm-import-wizard-step" style="margin-top:10px;">Step 4: Import Members</p>
	
	<p style="margin-left:12px;">
		<input type="hidden" id="mm-import-file-source" name="mm-import-file-source" />
		<input type="hidden" id="mm-import-file-from-computer" name="mm-import-file-from-computer" />
		<input type="hidden" id="mm-import-file-from-url" name="mm-import-file-from-url" />
		<input type='submit' class="mm-ui-button green" value='Import Members' />
	</p>
	</form>
<?php } ?>
</div>