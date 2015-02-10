/*!
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */
var MM_CustomFieldViewJS = MM_Core.extend({
    
	validateForm: function()
	{
		var name = jQuery("#mm-display-name").val();
		
		if(name.length <= 0)
		{
			alert("Please enter a name");
			return false;
		}
		return true;
	},
	
	showOnMyAccountChanged: function()
	{
		if(!jQuery("#mm-show-on-my-account-cb").is(":checked"))
		{
			jQuery("#mm-show-on-my-account").val("0");
		}
		else
		{
			jQuery("#mm-show-on-my-account").val("1");
		}
	},
	
	addFieldOption: function(addImage, removeImage)
	{
		var optionId = -1;
		
		jQuery('input.field-option').each(function( index ) {
			crntOptionId = jQuery(this).attr('id').replace("mm-field-option-", ""); 
			if(parseInt(crntOptionId) > optionId)
			{
				optionId = crntOptionId;
			}
		});
		
		optionId++;
		
		var html = "<div id=\"mm-field-option-container-" + optionId + "\">";
		html += "<input id=\"mm-field-option-" + optionId + "\" type=\"text\" class=\"field-option\" size=\"30\" /> ";
		html += "<a href=\"javascript:mmjs.addFieldOption('" + String(addImage).replace(/"/g, '&quot;') + "', '" + String(removeImage).replace(/"/g, '&quot;') + "');\">" + addImage + "</a> ";
		html += "<a href=\"javascript:mmjs.removeFieldOption('mm-field-option-container-" + optionId + "');\">" + removeImage + "</a>";
		html += "</div>";
		jQuery("#mm-field-options").append(html);
		jQuery("#mm-field-option-" + optionId).focus();
	},
	
	removeFieldOption: function(id)
	{
		jQuery("#"+id).remove();
	},
	
	typeChangeHandler: function()
	{
		if(jQuery("#mm-field-type").val() == "radio" || jQuery("#mm-field-type").val() == "dropdown")
		{
			jQuery("#field-options-container").show();
		}
		else
		{
			jQuery("#field-options-container").hide();
		}
	},
	
	cancelCreation: function(fieldId) {
		var values = {
            mm_action: "cancelCreation",
            id: fieldId,
        };

        var ajax = new MM_Ajax(false, this.module, this.action, this.method);
        ajax.send(values, false, "mmjs", "cancelCallbackHandler");
	},
	
	cancelCallbackHandler: function()
	{
		mmjs.closeDialog();
	},
	
	showCheckoutFormSmartTags: function(customFieldId, customFieldName)
	{	
		var values =  {};
		values.custom_field_id = customFieldId;
		values.custom_field_name = customFieldName;
		values.mm_action = "showCheckoutFormSmartTags";
		
		mmdialog_js.showDialog("mm-smarttags-dialog", this.module, 490, 340, "Form SmartTag", values);
	},
});

var mmjs = new MM_CustomFieldViewJS("MM_CustomFieldView", "Custom Field");