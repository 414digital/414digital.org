/*!
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */
var MM_RepairMemberMouseViewJS = MM_Core.extend({		
	
	fetchSourceArchive: function() {
		jQuery('#mm-pre-repair-membermouse').hide('fast');
		jQuery('#mm-repair-membermouse').show('fast');
		mmjs.restoreView();
		jQuery.ajaxSetup({ timeout: 0}); //disable jquery ajax timeout, this operation might take awhile
		var module = this.module;
	    var method = "performAction";
	    var action = 'module-handle';
	    var values = {};
		values.mm_action = "fetchSourceArchive";
	    
	    var ajax = new MM_Ajax(false, module, action, method);
	    ajax.send(values, false, 'mmjs','repairStepOneCallback');
	},
	
	repairStepOneCallback: function(data) {
		if (data.type == 'error')
		{
			if ((data.message.status != 'undefined') && (data.message.status == 'cache_not_writeable'))
			{
				alert('Unable to retrieve repair archive due to the cache directory not being writeable. Please make the cache directory writeable and try again.');
				mmjs.restoreView();
				return false;
			}
			else if ((data.message.status != 'undefined') && (data.message.status == 'remote_archive_doesnt_exist'))
			{
				alert('Remote archive does not exist. Please contact MemberMouse Support');
				mmjs.restoreView();
				return false;
			}
			else 
			{
				alert('There was an error performing the repair operation. Please contact MemberMouse Support');
				mmjs.restoreView();
				return false;
			}
		}
		//assume success if we reach this point
		jQuery.ajaxSetup({ timeout: 0}); //disable jquery ajax timeout, this operation might take awhile
		var module = this.module;
	    var method = "performAction";
	    var action = 'module-handle';
	    var values = {};
		values.mm_action = "repairFromArchive";
	    
	    var ajax = new MM_Ajax(false, module, action, method);
	    ajax.send(values, false, 'mmjs','repairStepTwoCallback');
	},
	
	repairStepTwoCallback: function(data) {
		if (data.type != 'success')
		{
			
		}
		else 
		{
			//success
			alert('Repair completed successfully');
		}
	},
	
	restoreView: function() {
		jQuery('#mm-repair-membermouse').hide('fast');
		jQuery('#mm-pre-repair-membermouse').show('fast');
	}
	
});

var mmjs = new MM_RepairMemberMouseViewJS("MM_RepairMemberMouseView", "Repair MemberMouse");