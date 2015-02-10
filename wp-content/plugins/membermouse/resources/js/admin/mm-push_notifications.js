/*!
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */
var MM_PushNotificationViewJS = MM_Core.extend({
    
	eventChangeHandler: function() 
	{
		// hide all second tier options
		jQuery("#mm_member_add_attributes").hide();
		jQuery("#mm_member_status_change_attributes").hide();
		jQuery("#mm_bundles_add_attributes").hide();
		jQuery("#mm_bundles_status_change_attributes").hide();
		
		// show second tier options based on selected status
		if(jQuery("#mm-event-type").val() == "mm_member_status_change" ||
				jQuery("#mm-event-type").val() == "mm_member_add" ||
				jQuery("#mm-event-type").val() == "mm_bundles_add" ||
				jQuery("#mm-event-type").val() == "mm_bundles_status_change")
		{
			jQuery("#" + jQuery("#mm-event-type").val() + "_attributes").show();
		}
	},
	
	actionChangeHandler: function() 
	{
		if(jQuery("#mm-action-type").val() == "action_send_email")
		{
			jQuery("#mm-action-send-email").show();
			jQuery("#mm-action-call-script").hide();
		}
		else if(jQuery("#mm-action-type").val() == "action_call_script")
		{
			jQuery("#mm-action-send-email").hide();
			jQuery("#mm-action-call-script").show();
		}
	},
	
	statusChangeHandler: function()
	{
		jQuery("#mm-action-status").attr('value', jQuery('#mm-status-container input:radio:checked').val());
	},
	
	validateForm: function()
	{
		if(jQuery("#mm-action-type").val() == "action_send_email")
		{
			if(jQuery('#mm-send-email-subject').val() == "") 
			{
				alert("Please enter an email subject");
				jQuery('#mm-send-email-subject').focus();
				return false;
			}
			
			if(jQuery('#mm-send-email-body').val() == "") 
			{
				alert("Please enter an email body");
				jQuery('#mm-send-email-body').focus();
				return false;
			}
		}
		else if(jQuery("#mm-action-type").val() == "action_call_script")
		{
			if(jQuery("#mm-script-url").val() == "") 
			{
				alert("Please enter a script URL");
				jQuery('#mm-script-url').focus();
				return false;
			}

			var url = jQuery("#mm-script-url").val();
			if(url.length > 0)
			{
				if(!this.isValidURL(url))
				{
					alert("Please enter a valid script URL");
					jQuery('#mm-script-url').focus();
					return false;
				}
			}
		}
		
		return true;
	},
	
	sendTestNotification: function(actionId)
	{
		var values = {
            action_id:actionId,
            mm_action: "sendTestNotification"
        };

        var ajax = new MM_Ajax(false, this.module, this.action, this.method);
        ajax.send(values, false, 'mmjs','testNotificationCallback'); 
	},
	
	testNotificationCallback: function(data)
	{
		if(data.type == 'error')
		{
			alert(data.message);
		}
		else
		{
			alert("Test notification sent");
		}	
	}
});

var mmjs = new MM_PushNotificationViewJS("MM_PushNotificationView", "Push Notification");