/*!
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */
var MM_DiagnosticsViewJS = MM_Core.extend({
  
	setDiagnosticsMode: function(currentModeVal, newModeVal)
	{
		var doSet = true;
		
		if (currentModeVal == newModeVal)
		{
			alert('The diagnostics mode has not been changed');
			return;
		}
		
		if (newModeVal != "off")
		{
			doSet = confirm("Are you sure you want to enable diagnostics mode? \nThere will be a performance " +
					        "hit due to the overhead needed to gather diagnostic and trace data.\n" +
					        "Click OK to confirm, or Cancel to abort");
		}
			
	    if(doSet)
	    {
	        var values = {
	            newMode: newModeVal,
	            mm_action: "setDiagnosticsMode"
	        };

	        var ajax = new MM_Ajax(false, this.module, this.action, this.method);
	        ajax.send(values, false, 'mmjs',this.updateHandler); 
	    }
	},
	
	clearLog: function()
	{	
		var values = { mm_action:"clearLog" };
		var ajax = new MM_Ajax(false, this.module, this.action, this.method);
        ajax.send(values, false, 'mmjs',this.updateHandler); 		
	},
	
	showFilters: function()
	{	
		jQuery("#mm-show-filters-btn").hide();
		jQuery("#mm-hide-filters-btn").show();
		jQuery("#mm-filter-criteria").slideDown(300, this.storeFilterState);
	},
	
	hideFilters: function()
	{	
		jQuery("#mm-show-filters-btn").show();
		jQuery("#mm-hide-filters-btn").hide();
		jQuery("#mm-filter-criteria").slideUp(300, this.storeFilterState);
	},
	
	storeFilterState: function()
	{
		var values = {
			mm_action: "storeFilterState",
			mm_admin_id: jQuery("#mm-admin-id").val(),
			mm_show_filters: (jQuery('#mm-filter-criteria').is(':visible'))?"1":"0"
		}
		
		 var ajax = new MM_Ajax(false, mmjs.module, mmjs.action, mmjs.method);
		 ajax.useLoader = false;
		 ajax.send(values, false, 'mmjs','storeFilterStateCallback'); 
	},
	
	storeFilterStateCallback: function(data) 
	{ 
		//do nothing
	},
	
	resetForm: function()
	  {
		  var values = {
				  mm_action: "resetForm"
		  };
	  
		  var ajax = new MM_Ajax(false, this.module, this.action, this.method);
		  ajax.send(values, false, "mmjs", "resetFormHandler");
	  },
	  
	  resetFormHandler: function(data)
	  {
		  if(data) {
			  jQuery("#mm-filter-criteria-container").html(data);
		  }
	  },
	  
	  search: function(crntPage) 
	  {
		  var form_obj = new MM_Form('mm-form-container');
		  var values = form_obj.getFields();
		  
		  if(crntPage != undefined) {
			  this.crntPage = crntPage;
		  }
      
		  values.sortBy = this.sortBy;
		  values.sortDir = this.sortDir;
		  values.crntPage = this.crntPage;
		  values.resultSize = this.resultSize;
		  values.mm_action = "filter";
	  
		  var ajax = new MM_Ajax(false, this.module, this.action, this.method);
		  ajax.send(values, false, "mmjs", "resetGridHandler"); 
	  },
	  
	  resetGridHandler: function(data)
	  {
		  if(data) {
			  jQuery("#mm-grid-container").html(data);
		  }
	  }
});

var mmjs = new MM_DiagnosticsViewJS("MM_DiagnosticsView", "Diagnostic Items");