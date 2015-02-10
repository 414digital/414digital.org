/*!
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */
var MM_EmailIntegrationViewJS = MM_Core.extend({
  
	providerOptionsReset: function() {
		jQuery("#provider_token").removeAttr("disabled");
		jQuery("#mm-email-service-provider-options input").removeAttr("readonly");
		jQuery("#mm-email-service-provider-options select").removeAttr("disabled");
		jQuery("#email-service-provider-configure").removeAttr("readonly");
		jQuery("#mm-membertype-to-list").html("");
		jQuery("#mm-email-service-provider-list-mappings").hide("fast");
		jQuery("#mm-email-service-provider-list-bundle-mappings").hide("fast");
		jQuery("#reset").hide("fast");
		
		jQuery("#email-service-provider-configure").show("fast");
	},
	
	showNewProviderOptions: function(form_data) {

		jQuery("#email-service-provider-configure").show();
		jQuery("#mm-email-service-provider-list-mappings").hide();
		jQuery("#mm-email-service-provider-list-bundle-mappings").hide();
		
		var values = {};
		values.mm_action = "showProviderOptions";
		jQuery.each(form_data, function() {
	        if (values[this.name] !== undefined) {
	            if (!values[this.name].push) {
	                values[this.name] = [values[this.name]];
	            }
	            values[this.name].push(this.value || '');
	        } else {
	            values[this.name] = this.value || '';
	        }
	    });
		
		var module = this.module;
	    var method = "performAction";
	    var action = 'module-handle';
	    
	    var ajax = new MM_Ajax(false, module, action, method);
	    ajax.send(values, false, 'mmjs','updateProviderOptionsUI');
	},
	
	showListMappingDialog: function(form_data) {
		var values =  {};		
	    values.mm_action = "showListMappingDialog";
	    jQuery.each(form_data, function() {
	        if (values[this.name] !== undefined) {
	            if (!values[this.name].push) {
	                values[this.name] = [values[this.name]];
	            }
	            values[this.name].push(this.value || '');
	        } else {
	            values[this.name] = this.value || '';
	        }
	    });
	    
	    var module = this.module;
	    var method = "performAction";
	    var action = 'module-handle';
	      
	    var ajax = new MM_Ajax(false, module, action, method);
	    ajax.send(values, false, 'mmjs','updateListMappingUI');
	},
	
	providerOptionsSave: function(form_data) {
		//verify prospect list doesnt have the same member id as any of the lists mapped to member type
		if (jQuery('select[name="prospect_list_id"]').length > 0)
		{
			var prospect_list_id = jQuery('select[name="prospect_list_id"]').val();
			var prospect_not_unique = false;
			
			if (prospect_list_id.toLowerCase() != "none" && prospect_list_id.toLowerCase() != "") {
		        var configured_lists = jQuery('select[name^="member_type_mappings"]');
		        for (i=0; (i<configured_lists.length && !prospect_not_unique); i++)
		        {
		            if (jQuery(configured_lists[i]).val() == prospect_list_id) 
		            {
		                prospect_not_unique = true;
		            }
		        }
	        }
	
			if (prospect_not_unique) {
				alert("Prospect List cannot be the same as a Member Type List");
				return false;
			}
		}
		var values =  {};		
	    values.mm_action = "saveProviderOptions";
	    values['provider_token'] = jQuery("#provider_token").val();
	    jQuery.each(form_data, function() {
	        if (values[this.name] !== undefined) {
	            if (!values[this.name].push) {
	                values[this.name] = [values[this.name]];
	            }
	            values[this.name].push(this.value || '');
	        } else {
	            values[this.name] = this.value || '';
	        }
	    });
	    
	    //special code to deal with iContact client folder
	    if (jQuery("select[name='icontact_client_folder']").length > 0)
	    {
	    	values['icontact_client_folder'] = jQuery("select[name='icontact_client_folder']").val();
	    }
	    
	    var module = this.module;
	    var method = "performAction";
	    var action = 'module-handle';
	      
	    var ajax = new MM_Ajax(false, module, action, method);
	    ajax.send(values, false, 'mmjs','updateStatus');
	},
	
	exportMembers: function(export_url) {
		var random=Math.floor(Math.random()*1024);
		export_url = export_url + '&x=' + random; //prevents IE from caching
		jQuery("#export_frame").attr('src',export_url);
	},
	
	updateStatus: function(data) {
		if(data.type!='error'){
			alert("Provider settings saved successfully");
		}
		else {
			alert(data.message);
		}
	},
	
	updateListMappingUI: function(data) {
		if(data.type!='error'){
			jQuery("#email-service-provider-configure").hide("fast");
			jQuery("#reset").show();
			jQuery("#provider_token").attr("disabled","disabled");
			jQuery("#mm-email-service-provider-options input").attr("readonly","readonly");
			jQuery("#mm-email-service-provider-options select").attr("disabled","disabled");
			jQuery("#email-service-provider-configure").attr("readonly","readonly");
			jQuery("#mm-membertype-to-list").html(data.message);
			jQuery("#mm-email-service-provider-list-mappings").show();
			jQuery("#mm-email-service-provider-list-bundle-mappings").show();
		}
		else {
			if (~data.message.indexOf('Invalid Mailchimp API Key'))
			{
				alert("Invalid MailChimp API Key. Please try again.");
			}
			else
			{
				alert(data.message);
			}
		}
	},
	
	updateProviderOptionsUI: function(data) {
		if ((data.type!='error') && (typeof data.message != 'undefined') && (typeof data.message.dialog != 'undefined')){
			jQuery("#mm-email-service-provider-options").html(data.message.dialog);
			jQuery("#mm-email-service-provider-controls").html(data.message.controls);
		}
		else {
			if (data.type == 'error')
			{
				alert(data.message);
			}
			else 
			{
				alert("There was an error retrieving the provider options");
			}
		}
	}
	
});

var mmjs = new MM_EmailIntegrationViewJS("MM_EmailIntegrationView", "Bundle Mapping");