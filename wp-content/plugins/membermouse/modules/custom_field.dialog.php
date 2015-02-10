<?php 
function renderFieldOption($optionId, $value)
{
?>
	<div id="mm-field-option-container-<?php echo $optionId; ?>">
	<input id="mm-field-option-<?php echo $optionId; ?>" type="text" size="30" class="field-option" value="<?php echo $value; ?>" />
	<a href="javascript:mmjs.addFieldOption('<?php echo htmlentities(MM_Utils::getIcon('plus-circle', 'green', '1.2em', '1px'), ENT_QUOTES); ?>', '<?php echo htmlentities(MM_Utils::getIcon('trash-o', 'red', '1.2em', '1px'), ENT_QUOTES); ?>');"><?php echo MM_Utils::getIcon('plus-circle', 'green', '1.2em', '1px'); ?></a>
		
	<?php if($optionId > 1) { ?>
	<a href="javascript:mmjs.removeFieldOption('mm-field-option-container-<?php echo $optionId; ?>');"><?php echo MM_Utils::getIcon('trash-o', 'red', '1.2em', '1px'); ?></a>
	<?php } ?>
	</div>
<?php
}

$doRenderForm = true;
$initialCreation = true;

$customField = new MM_CustomField($p->id);

// create custom field if it doesn't exist
if(!$customField->isValid())
{
	$customField = new MM_CustomField();
	$customField->setDisplayName("Untitled");
	$result = $customField->commitData(); 
	
	if(MM_Response::isSuccess($result))
	{
		$customField = new MM_CustomField($customField->getId());
	}
	else
	{
		$doRenderForm = false;
	}
}
else
{
	$initialCreation = false;
}

if($doRenderForm)
{
	$showOnMyAccountChecked = ($customField->showOnMyAccount() == true) ? "checked" : "";
?>
	<div id="mm-form-container">
	<input type='hidden' id='mm-id' value='<?php echo $customField->getId(); ?>' />
	<table width='95%' cellpadding="6">
	<tr>
		<td style="vertical-align:middle;">
			Name
		</td>
		<td>
			<input type='text' id='mm-display-name' value='<?php echo $customField->getDisplayName(); ?>'  style='width: 225px;'  />
		</td>
	</tr>
	<tr>
		<td></td>
		<td>
			<label>
				<input type='checkbox' id='mm-show-on-my-account-cb' <?php echo $showOnMyAccountChecked; ?> onchange="mmjs.showOnMyAccountChanged()" />
				Show on My Account Page
			</label>
			
			<input type='hidden' id='mm-show-on-my-account' value='<?php echo ($customField->showOnMyAccount() ? "1":"0"); ?>' />
		</td>
	</tr>
	<tr>
		<td>Type</td>
		<td>
			<select id="mm-field-type" onchange="mmjs.typeChangeHandler()">
			<?php echo MM_HtmlUtils::getCustomFieldTypeList($customField->getType()); ?>
			</select>
		</td>
	</tr>
	<tr>
		<td></td>
		<td>
			<div id="field-options-container" style="display:none;">
				Options
				
				<div id="mm-field-options" style="margin-top:5px;">
					<?php 
						$options = $customField->getOptions();
						
						if(count($options) > 0)
						{
							$crntOption = 1;
							foreach($options as $option)
							{
								renderFieldOption($crntOption, $option);
								$crntOption++;
							}
						} 
						else 
						{ 
							renderFieldOption(1, "Option 1");
							renderFieldOption(2, "Option 2");
						} 
					?>
				</div>
			</div>
		</td>
	</tr>
	</table>
	</div>
	
	<div class="mm-dialog-footer-container">
	<div class="mm-dialog-button-container">
	
	<a href="javascript:mmjs.save();" class="mm-ui-button blue">Save Custom Field</a>
	
	<?php if($initialCreation) { ?>
	<a href="javascript:mmjs.cancelCreation(<?php echo $customField->getId(); ?>);" class="mm-ui-button">Cancel</a>
	<?php } else { ?>
	<a href="javascript:mmjs.closeDialog();" class="mm-ui-button">Cancel</a>
	<?php } ?>
	
	</div>
	</div>

<?php } else { ?>

	<div id="mm-form-container">
	Unable to create custom field.
	</div>

<?php } ?>

<script>mmjs.typeChangeHandler();</script>