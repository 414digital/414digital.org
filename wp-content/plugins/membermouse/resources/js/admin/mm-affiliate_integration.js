/*!
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */
var MM_AffiliateIntegrationViewJS = MM_Core.extend({
  
	providerOptionsReset: function() {
		jQuery("#provider_token").removeAttr("disabled");
		jQuery("#mm-affiliate-provider-options input").removeAttr("readonly");
		jQuery("#mm-affiliate-provider-options select").removeAttr("disabled");
		jQuery("#affiliate-provider-configure").removeAttr("readonly");
		jQuery("#mm-membership-to-profile").html("");
		jQuery("#mm-affiliate-provider-additional-options").hide("fast");
		jQuery("#reset").hide("fast");
		
		jQuery("#affiliate-provider-configure").show("fast");
	},
	
	showNewProviderOptions: function(form_data) {

		jQuery("#affiliate-provider-configure").show();
		jQuery("#mm-affiliate-provider-additional-options").hide();
		
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
	
	showAdditionalOptionsDialog: function(form_data) {
		var values =  {};		
	    values.mm_action = "showAdditionalOptionsDialog";
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
	    ajax.send(values, false, 'mmjs','updateAdditionalOptionsUI');
	},
	
	providerOptionsSave: function(form_data) {
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

	    var module = this.module;
	    var method = "performAction";
	    var action = 'module-handle';
	      
	    var ajax = new MM_Ajax(false, module, action, method);
	    ajax.send(values, false, 'mmjs','updateStatus');
	},
	
	updateStatus: function(data) {
		if(data.type!='error'){
			alert("Provider settings saved successfully");
		}
		else {
			alert(data.message);
		}
	},
	
	updateAdditionalOptionsUI: function(data) {
		if(data.type!='error'){
			jQuery("#affiliate-provider-configure").hide("fast");
			jQuery("#reset").show("fast");
			jQuery("#provider_token").attr("disabled","disabled");
			jQuery("#mm-affiliate-provider-options input").attr("readonly","readonly");
			jQuery("#mm-affiliate-provider-options select").attr("disabled","disabled");
			jQuery("#affiliate-provider-configure").attr("readonly","readonly");
			jQuery("#mm-membership-to-profile").html(data.message);
			jQuery("#mm-affiliate-provider-additional-options").show("slow");
		}
		else {
			alert(data.message);
		}
	},
	
	updateProviderOptionsUI: function(data) {
		if ((data.type!='error') && (typeof data.message != 'undefined') && (typeof data.message.dialog != 'undefined'))
		{
			jQuery("#mm-affiliate-provider-options").html(data.message.dialog);
			jQuery("#mm-affiliate-provider-controls").html(data.message.controls);
		}
		else 
		{
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

var mmjs = new MM_AffiliateIntegrationViewJS("MM_AffiliateIntegrationView", "Affiliate Integration");