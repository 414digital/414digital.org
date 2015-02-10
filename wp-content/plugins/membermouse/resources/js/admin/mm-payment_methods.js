/*!
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */
var MM_PaymentViewJS = MM_Core.extend({
	
	toggleOnsitePaymentOption: function(token)
	{
		jQuery("input[name='onsite_payment_service']").each(
				function() {
					if (!jQuery(this).is(":checked"))
					{
						jQuery("#payment_service_"+jQuery(this).val()).hide('fast');
					}
					else
					{
						jQuery("#payment_service_"+jQuery(this).val()).show('slow');
					}
				}
		);
		
		pymtSettings_js.toggleHttpsNotice();
	},
	
	toggleHttpsNotice: function(token)
	{
		if(jQuery("#onsite_payment_service_none").is(":checked"))
		{
			jQuery("#mm-https-notice").hide('fast');
		}
		else
		{
			jQuery("#mm-https-notice").show('slow');
		}
	},
	
	toggleOffsitePaymentOption: function(token)
	{
		if (jQuery("#offsite_payment_service_"+token).is(":checked"))
		{
			jQuery("#payment_service_"+token).show('fast');
		}
		else
		{
			jQuery("#payment_service_"+token).hide('fast');
		}
	},
	
	paymentOptionsSave: function(form_data) {
		
		var values =  {};		
	    values.mm_action = "savePaymentOptions";
	    
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
	    ajax.send(values, false, 'pymtSettings_js','updateStatus');
	},
	
	performIntermediateAction: function(data,token,callback,callback_receiver) {
		callback_receiver = (typeof callback_receiver === "undefined")?"window":callback_receiver;
		if (!callback)
		{
			return;
		}
		
		var values =  {};		
	    values.mm_action = "processIntermediateAction";
	    values.token = token;
	    
	    jQuery.each(data, function() {
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
	    ajax.send(values, false, callback_receiver,callback);
	},
	
	updateStatus: function(data) {
		if(data.type!='error')
		{
			alert("Payment method settings saved successfully");
		}
		else {
			alert(data.message);
		}
	}
	
});

var pymtSettings_js = new MM_PaymentViewJS("MM_PaymentSettingsView", "Payment Method");