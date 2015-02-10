/*!
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */
var MM_ProductViewJS = MM_Core.extend({
	enableLimitPayments: function()
	{
		if(jQuery("#mm-limit_payments").is(":checked"))
		{
			jQuery("#mm-number_of_payments").removeAttr("disabled");
			jQuery("#mm-do_limit_payments").val(1);
			jQuery("#mm-number_of_payments").val("");
			jQuery("#mm-number_of_payments").focus();
		}
		else
		{
			jQuery("#mm-number_of_payments").attr("disabled","disabled");
			jQuery("#mm-do_limit_payments").val(0);
			jQuery("#mm-number_of_payments").val(0);
		}
	},
	
	processForm: function()
	{	
		// status
	 	jQuery("#mm-status").attr('value', jQuery('#mm-status-container input:radio:checked').val());
	},
	
	accessChangeHandler: function()
	{	
		jQuery("#mm-associated-access-value").attr('value', jQuery('#mm-access-container input:radio:checked').val());
		
		if(jQuery("#mm-associated-access-value").val() == "membership") 
		{
			jQuery("#mm-membership-access-container").show();
			jQuery("#mm-bundle-access-container").hide();
		}
		else if(jQuery("#mm-associated-access-value").val() == "bundle") 
		{
			jQuery("#mm-membership-access-container").hide();
			jQuery("#mm-bundle-access-container").show();
		}
		else
		{
			jQuery("#mm-membership-access-container").hide();
			jQuery("#mm-bundle-access-container").hide();
		}
	},
	
	saveProduct: function()
	{
		jQuery("#mm-associated-access-value").attr('value', jQuery('#mm-access-container input:radio:checked').val());
		
		var params = {};
		this.save(undefined, params);
	},
	
	validateForm: function()
	{
		if(jQuery("#mm-name").val() == "")
		{
			alert("Please enter a name");
			jQuery("#mm-name").focus();
			return false;
		}
		if(jQuery("#mm-price").val() == "")
		{
			alert("Please enter a price");
			jQuery("#mm-price").focus();
			return false;
		}
		
		// validate trial period
		if(jQuery("#mm-has_trial_val").val() == "1")
		{	
			if(jQuery("#mm-trial_duration").val() == "" || parseInt(jQuery("#mm-trial_duration").val()) == 0)
			{
				alert("The trial period must be greater than 0");
				jQuery("#mm-trial_duration").focus();
				return false;
			}
			
			switch(jQuery("#mm-trial_frequency").val())
			{
				case "days":
					if(parseInt(jQuery("#mm-trial_duration").val()) < 1 || parseInt(jQuery("#mm-trial_duration").val()) > 90)
					{
						alert("The trial period must be between 1 and 90 days");
						jQuery("#mm-trial_duration").focus();
						return false;
					}
					break;
				
				case "weeks":
					if(parseInt(jQuery("#mm-trial_duration").val()) < 1 || parseInt(jQuery("#mm-trial_duration").val()) > 52)
					{
						alert("The trial period must be between 1 and 52 weeks");
						jQuery("#mm-trial_duration").focus();
						return false;
					}
					break;
					
				case "months":
					if(parseInt(jQuery("#mm-trial_duration").val()) < 1 || parseInt(jQuery("#mm-trial_duration").val()) > 12)
					{
						alert("The trial period must be between 1 and 12 months");
						jQuery("#mm-trial_duration").focus();
						return false;
					}
					break;
					
				case "years":
					if(parseInt(jQuery("#mm-trial_duration").val()) != 1)
					{
						alert("The trial period must be 1 year");
						jQuery("#mm-trial_duration").focus();
						return false;
					}
					break;
			}
		}
		
		// validate rebill period
		if(jQuery("#mm-is_recurring_val").val() == "1")
		{	
			if(jQuery("#mm-rebill_period").val() == "" || parseInt(jQuery("#mm-rebill_period").val()) == 0)
			{
				alert("The rebill period must be greater than 0");
				jQuery("#mm-rebill_period").focus();
				return false;
			}
			
			switch(jQuery("#mm-rebill_frequency").val())
			{
				case "days":
					if(parseInt(jQuery("#mm-rebill_period").val()) < 7 || parseInt(jQuery("#mm-rebill_period").val()) > 90)
					{
						alert("The rebill period must be between 7 and 90 days");
						jQuery("#mm-rebill_period").focus();
						return false;
					}
					break;
				
				case "weeks":
					if(parseInt(jQuery("#mm-rebill_period").val()) < 1 || parseInt(jQuery("#mm-rebill_period").val()) > 52)
					{
						alert("The rebill period must be between 1 and 52 weeks");
						jQuery("#mm-rebill_period").focus();
						return false;
					}
					break;
					
				case "months":
					if(parseInt(jQuery("#mm-rebill_period").val()) < 1 || parseInt(jQuery("#mm-rebill_period").val()) > 12)
					{
						alert("The rebill period must be between 1 and 12 months");
						jQuery("#mm-rebill_period").focus();
						return false;
					}
					break;
					
				case "years":
					if(parseInt(jQuery("#mm-rebill_period").val()) != 1)
					{
						alert("The rebill period must be 1 year");
						jQuery("#mm-rebill_period").focus();
						return false;
					}
					break;
			}
		}
		
		return true;
	},
	
	changeOption: function(id)
	{
		if(jQuery("#"+id).is(":checked"))
		{
			jQuery("#"+id+"_val").val("1");
		}
		else
		{
			jQuery("#"+id+"_val").val("0");
		}
	},

	toggleTrial: function()
	{
		if(jQuery("#mm-has_trial").is(":checked"))
		{
			jQuery("#mm_has_trial_row").show();
		}
		else
		{
			jQuery("#mm_has_trial_row").hide();
		}
		
		this.changeOption('mm-has_trial');
	},
	
	toggleRecurring: function()
	{
		if(jQuery("#mm-is_recurring").is(":checked"))
		{
			jQuery("#mm_rebill_row").show();
		}
		else
		{
			jQuery("#mm_rebill_row").hide();
		}
		
		this.changeOption('mm-is_recurring');
	},
	
	showPurchaseLinks: function(productId, productName)
	{	
		var values =  {};
		values.product_id = productId;
		values.product_name = productName;
		values.mm_action = "showPurchaseLinks";
		
		mmdialog_js.showDialog("mm-purchaselinks-dialog", this.module, 515, 375, "Purchase Links", values);
	},
	
	addFieldOption: function(addImage, removeImage)
	{
		var optionId = -1;
		
		jQuery('input.field-option').each(function( index ) {
			crntOptionId = $(this).attr('id').replace("mm-partner-", ""); 
			if(parseInt(crntOptionId) > optionId)
			{
				optionId = crntOptionId;
			}
		});
		
		optionId++;
		
		var html = "<div id=\"mm-partner-container-" + optionId + "\">";
		html += "<input id=\"mm-partner-" + optionId + "\" type=\"text\" class=\"field-option\" size=\"15\" /> ";
		html += "<select id=\"mm-commission-profile-" + optionId + "\">";
		html += jQuery("#mm-commission-profile-options-container").html();
		html += "</select> ";
		html += "<a href=\"javascript:mmjs.addFieldOption('" + String(addImage).replace(/"/g, '&quot;') + "', '" + String(removeImage).replace(/"/g, '&quot;') + "');\">" + addImage + "</a> ";
		html += "<a href=\"javascript:mmjs.removeFieldOption('mm-partner-container-" + optionId + "');\">" + removeImage + "</a>";
		html += "</div>";
		jQuery("#mm-partners").append(html);
		jQuery("#mm-partner-" + optionId).focus();
	},
	
	removeFieldOption: function(id)
	{
		jQuery("#"+id).remove();
	}
});

var mmjs = new MM_ProductViewJS("MM_ProductView", "Product");