/*!
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */
var MM_MemberDetailsViewJS = MM_Core.extend({
	
	changeMembership: function(memberId, crntMembershipId)
	{
		if(crntMembershipId == jQuery("#mm-new-membership-selection").val()) 
		{
		    alert("Please select a different membership level to change to");
		}
		else
		{
			this.id = memberId; 
			
			lastActionValues = {
				mm_id: this.id,
				mm_new_membership: jQuery("#mm-new-membership-selection").val(),
				mm_action: "changeMembership",
				mm_jshandle: "mmjs",
				mm_compfunction: "compAccess"
		    };
			
			pymtutils_js.checkIfPaymentRequired('membership', lastActionValues.mm_new_membership, 'paymentRequirementHandler', 'mmjs');
		}
	},

	setCalcMethod: function(method)
	{
		jQuery("#mm-membership-calc-method").val(method);
		jQuery("#mm-membership-custom-date").val("");
		jQuery("#mm-membership-fixed").val("");
	},
	
	changeMembershipStatus: function(memberId, membership_id, newStatus, doUnlock)
	{	
		var checkPymtRequirements = false;
		var msg = "";
		
		switch(parseInt(newStatus))
		{
			case 1:
				if(!doUnlock)
				{
					checkPymtRequirements = true;
				}
				else
				{
					msg = "Are you sure you want to unlock this account?";
					checkPymtRequirements = false;
				}
				break;
				
			case 2:
				msg = "Are you sure you want to cancel this account?";
				checkPymtRequirements = false;
				break;
				
			case 3:
				msg = "Are you sure you want to lock this account?";
				checkPymtRequirements = false;
				break;
				
			case 4:
				msg = "Are you sure you want to pause this account?";
				checkPymtRequirements = false;
				break;
				
			default:
				msg = "Invalid status '" + newStatus + "'";
				checkPymtRequirements = false;
				break;
		}
		
		this.id = memberId; 
		
		lastActionValues = {
			mm_id: this.id,
			mm_membership_id: membership_id,
			mm_new_status: newStatus,
			mm_action: "changeMembershipStatus",
			mm_jshandle: "mmjs",
			mm_compfunction: "compAccess"
	    };
		
		if(checkPymtRequirements == true)
		{
			pymtutils_js.checkIfPaymentRequired('membership', lastActionValues.mm_membership_id, 'paymentRequirementHandler', 'mmjs'); 
		}
		else
		{
			var doContinue = confirm(msg);
			if(doContinue)
			{
				var ajax = new MM_Ajax(false, this.module, this.action, this.method);
				ajax.send(lastActionValues, false, 'mmjs', "accessRightsUpdateHandler"); 
			}
		}
	},
	
	applyBundle: function(memberId, newStatus)
	{
		mmjs.changeBundleStatus(memberId, jQuery("#bundle-selector").val(), newStatus);
	},
	
	changeBundleStatus: function(memberId, bundleId, newStatus)
	{	
		var checkPymtRequirements = false;
		var msg = "";
		
		switch(parseInt(newStatus))
		{
			case 1:
				checkPymtRequirements = true;
				break;
				
			case 2:
				msg = "Are you sure you want to cancel this bundle?";
				checkPymtRequirements = false;
				break;
				
			case 4:
				msg = "Are you sure you want to pause this bundle?";
				checkPymtRequirements = false;
				break;
				
			default:
				msg = "Invalid status '" + newStatus + "'";
				break;
		}
		
		this.id = memberId; 
			
		lastActionValues = {
			mm_id: this.id,
			mm_bundle_id: bundleId,
			mm_new_status: newStatus,
			mm_action: "changeBundleStatus",
			mm_jshandle: "mmjs",
			mm_compfunction: "compAccess"
	    };
	    
		if(checkPymtRequirements == true)
		{
			pymtutils_js.checkIfPaymentRequired('bundle', lastActionValues.mm_bundle_id, 'paymentRequirementHandler', 'mmjs'); 
		}
		else
		{
			var doContinue = confirm(msg);
			if(doContinue)
			{
				var ajax = new MM_Ajax(false, this.module, this.action, this.method);
				ajax.send(lastActionValues, false, 'mmjs', "accessRightsUpdateHandler"); 
			}
		}
	},
	
	paymentRequirementHandler: function(result)
	{
		if(typeof result === 'object')
		{
			alert("Error checking payment requirements: " + result.message);
		}
		else if(result == true)
		{
			if(lastActionValues.mm_action == "changeMembership")
			{
				pymtutils_js.showPaymentOptions(lastActionValues.mm_id, 'membership', lastActionValues.mm_new_membership, JSON.stringify(lastActionValues));
			}
			else if(lastActionValues.mm_action == "changeMembershipStatus")
			{
				pymtutils_js.showPaymentOptions(lastActionValues.mm_id, 'membership', lastActionValues.mm_membership_id, JSON.stringify(lastActionValues));
			}
			else if(lastActionValues.mm_action == "changeBundleStatus")
			{
				pymtutils_js.showPaymentOptions(lastActionValues.mm_id, 'bundle', lastActionValues.mm_bundle_id, JSON.stringify(lastActionValues));
			}
		}
		else
		{
			mmjs.executeAction(lastActionValues, false);
		}
	},
	
	compAccess: function(actionValues)
	{
		mmjs.executeAction(JSON.parse(actionValues), true);
	},
	
	executeAction: function(actionValues, doComp)
	{
		var msg = "";
		
		if(actionValues.mm_action == "changeMembership")
		{
			msg = "Are you sure you want to change this member's membership to '" + jQuery("#mm-new-membership-selection :selected").text() + "'";
		}
		else if(actionValues.mm_action == "changeMembershipStatus")
		{
			if(parseInt(actionValues.mm_new_status) == 1)
			{
				msg = "Are you sure you want to activate this account";
			}
		}
		else if(actionValues.mm_action == "changeBundleStatus")
		{
			if(parseInt(actionValues.mm_new_status) == 1)
			{
				msg = "Are you sure you want to activate this bundle";
			}
		}
		
		if(doComp)
		{
			msg += " for free";
		}
		
		msg += "?";
		
		var doContinue = confirm(msg);
		if(doContinue)
		{
			pymtutils_js.closeDialog(mm_pymtdialog);
			var ajax = new MM_Ajax(false, this.module, this.action, this.method);
		    ajax.send(actionValues, false, 'mmjs', "accessRightsUpdateHandler"); 
		}
	},
	
	accessRightsUpdateHandler: function(data)
	{
		
		if(data.type == "error")
		{
			if ((lastActionValues.mm_action == "changeMembership") && (data.data != undefined) && 
				(data.data.payment_service_cancel_failed != undefined) && (data.data.payment_service_cancel_failed == "true"))
			{
				var confirmMsg = "MemberMouse was unable to cancel the billing associated with the current membership. \n" + 
				                 "Click OK to change membership anyway (not recommended)";
				if (confirm(confirmMsg))
				{
					lastActionValues.mm_ignore_payment_service_errors = "true";
					var ajax = new MM_Ajax(false, this.module, this.action, this.method);
				    ajax.send(lastActionValues, false, 'mmjs', "accessRightsUpdateHandler");
				}
				return false;
			}
			lastActionValues = {};
			if(data.message.length > 0)
			{  
				alert(data.message);
				return false;
			}
			return false;
		}
		else 
		{
			lastActionValues = {};
			mmjs.closeDialog();
		  
			if(data.message.indexOf("http") >= 0)
			{
				document.location.href = data.message;
				return false;
			}
		  
			if(data.message != undefined && data.message.length > 0)
			{
				alert(data.message);
			}
			
			var url = document.location.href;
			var index = url.indexOf("message");
			// remove any message from the URL before refreshing the page
			if(index != -1)
			{
				url = url.substring(0, index-1);
			}
			document.location.href = url;
		}
	},
	
	editBundleConfiguration: function(memberId, bundleId)
	{
		var dialogId = "mm-edit-bundle-configuration-dialog";
		jQuery("#"+dialogId).dialog({autoOpen: false});
		
		var values = {};
		values.member_id = memberId;
		values.bundle_id = bundleId;
		mmdialog_js.method = 'editBundleConfiguration';
		mmdialog_js.showDialog(dialogId, this.module, 400, 250, "Edit Bundle Configuration", values);
	},

	changeCalcMethodHandler: function(method)
	{
		jQuery("#mm-calc-method").val(method);
		jQuery("#mm-custom-date").val("");
		jQuery("#mm-fixed").val("");
	},
	
	saveBundleConfiguration: function()
	{
		var form_obj = new MM_Form('mm-calc-method-div');
	    var values = form_obj.getFields();
		values.mm_action = "saveBundleConfiguration";
	    
	    var ajax = new MM_Ajax(false, this.module, this.action, this.method);
	    ajax.send(values, false, 'mmjs', "saveBundleConfigurationCallback"); 
	},
	
	saveBundleConfigurationCallback: function(response)
	{
		mmjs.closeDialog();
		alert(response.message);
		document.location.href = document.location.href;
	}
});

var lastActionValues = {};
var mmjs = new MM_MemberDetailsViewJS("MM_MemberDetailsView", "Access Rights");