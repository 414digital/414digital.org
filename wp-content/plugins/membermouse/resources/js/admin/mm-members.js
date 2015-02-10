/*!
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */
var MM_MembersViewJS = MM_Core.extend({
  
	showSearch: function()
	{	
		jQuery("#mm-show-search-btn").hide();
		jQuery("#mm-hide-search-btn").show();
		jQuery("#mm-advanced-search").slideDown(300, this.storeSearchState);
	},
	
	hideSearch: function()
	{	
		jQuery("#mm-show-search-btn").show();
		jQuery("#mm-hide-search-btn").hide();
		jQuery("#mm-advanced-search").slideUp(300, this.storeSearchState);
	},
	
	storeSearchState: function()
	{
		this.module = "MM_MembersView";;
		this.method = "performAction";
		this.action = "module-handle";
		  
		var values = {
			mm_action: "storeSearchState",
			mm_admin_id: jQuery("#mm-admin-id").val(),
			mm_show_search: "0"
		}
		
		if(jQuery('#mm-advanced-search').is(':visible'))
		{
			values.mm_show_search = "1";
		}
		 
		 var ajax = new MM_Ajax(false, this.module, this.action, this.method);
		 ajax.useLoader = false;
		 ajax.send(values, false, 'mmjs', "storeSearchStateCallback"); 
	},
	
	storeSearchStateCallback: function(data)
	{
		if(data == undefined)
		{
			alert("No response received");
		}
		else if(data.type == "error")
		{
			alert(data.message);
		}
		else
		{
			// do nothing
		}
	},
	
	createMember: function()
	{	
		if(this.validateForm()) 
		{
			var form_obj = new MM_Form('mm-new-member-form-container');
		    lastActionValues = form_obj.getFields();
		    lastActionValues.mm_id = -1;
		    lastActionValues.mm_new_membership = jQuery("#mm-new-membership-selector").val();
		    lastActionValues.mm_action = "createMember";
		    lastActionValues.mm_jshandle = "mmjs";
		    lastActionValues.mm_compfunction = "compAccess";
			
			pymtutils_js.checkIfPaymentRequired('membership', lastActionValues.mm_new_membership, 'paymentRequirementHandler', 'mmjs');
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
			if(lastActionValues.mm_action == "createMember")
			{
				pymtutils_js.showPaymentOptions(lastActionValues.mm_id, 'membership', lastActionValues.mm_new_membership, JSON.stringify(lastActionValues));
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
		var doContinue = true;
		
		if(actionValues.mm_action == "createMember" && doComp)
		{
			msg = "Are you sure you want to create a '" + jQuery("#mm-new-membership-selector :selected").text() + "' member for free?";
			doContinue = confirm(msg);
		}
		
		if(doContinue)
		{
			pymtutils_js.closeDialog(mm_pymtdialog);
			mmjs.closeDialog();
			var ajax = new MM_Ajax(false, this.module, this.action, this.method);
		    ajax.send(actionValues, false, 'mmjs', "createMemberCallbackHandler"); 
		}
	},
	
	createMemberCallbackHandler: function(data)
	{
		lastActionValues = {};
		
		if(data.type == "error")
		{
			if(data.message.length > 0)
			{  
				alert(data.message);
				return false;
			}
			return false;
		}
		else 
		{
			this.search();
			alert("Member created successfully");
		}
	},
	
	validateForm: function()
	{	
		if(jQuery('#mm-new-first-name').val() == "") 
		{
			alert("Please enter the member's first name");
			return false;
		}
		
		if(jQuery('#mm-new-last-name').val() == "") 
		{
			alert("Please enter the member's last name");
			return false;
		}
		
		if(jQuery('#mm-new-email').val() == "") 
		{
			alert("Please enter the member's email address");
			return false;
		}
	   
		if(!this.validateEmail(jQuery('#mm-new-email').val())) 
		{
			alert("Please enter a valid email address");
			return false;
		}

		return true;
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
			  jQuery("#mm-advanced-search-container").html(data);
		  }
	  },
  
	  sort: function(columnName) 
	  {
		  var newSortDir = "asc";
		  
		  if(columnName == this.sortBy)
		  {
			  if(this.sortDir=="asc") {
				  newSortDir = "desc";
			  }
		  }
		  
		  this.sortBy = columnName;
		  this.sortDir = newSortDir;
		  
		  this.search();
	  },
	  
	  dgPreviousPage: function(crntPage)
	  {	
		  if(parseInt(crntPage) != 0) {
			  this.crntPage = parseInt(crntPage) - 1;
			  this.search();
		  }
	  },
	  
	  dgNextPage: function(crntPage, totalPages)
	  {
		  if(crntPage != (parseInt(totalPages) - 1)) {
			  this.crntPage = parseInt(crntPage) + 1;
			  this.search();
		  }
	  },
	  
	  dgSetResultSize: function(pageControl)
	  {
		  if(jQuery(pageControl).val() != undefined)
		  {
			  this.crntPage = 0;
			  this.resultSize = jQuery(pageControl).val();
			  this.search();
		  }
	  },
	  
	  changeCustomField: function(field){
		var customField = jQuery("#"+field).val();
		if(customField==''){
			jQuery("#"+field+"-value").hide();
		}
		else{
			jQuery("#"+field+"-value").show();
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
		  values.mm_action = "search";
	  
		  var ajax = new MM_Ajax(false, this.module, this.action, this.method);
		  ajax.send(values, false, "mmjs", "resetGridHandler"); 
	  },
	  
	  remove: function(id, memberEmail)
	  { 
	    var doRemove = confirm("Are you sure you want to delete the member '" + memberEmail + "'?");
	    
	    if(doRemove)
	    {
	        var values = {
	            id: id,
	            mm_action: "remove"
	        };
	        
	        var ajax = new MM_Ajax(false, this.module, this.action, this.method);
	        ajax.send(values, false, 'mmjs', this.updateHandler); 
	    }
	  },
	  
	  csvExport:function(crntPage)
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
		  values.csv = 1;
		  values.mm_action = "search";
	  
		  var ajax = new MM_Ajax(false, this.module, this.action, this.method);
		  ajax.send(values, false, "mmjs", "csvExportCallback"); 
	  },
  
	  csvExportCallback: function(data)
	  {
	    jQuery("#mm_members_csv").append('<form id="mm_exportform" method="post" target="_blank"><input type="hidden" id="mm_exportdata" name="exportdata" /></form>');
	    jQuery("#mm_exportform").submit().remove();
	    
	    this.search();
	    return true; 
	  },
	  
	  resetGridHandler: function(data)
	  {
		  if(data) {
			  jQuery("#mm-grid-container").html(data);
		  }
	  }
});

var lastActionValues = {};
var mmjs = new MM_MembersViewJS("MM_MembersView", "Member");