/*!
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */
var MM_ActivityLogViewJS = MM_Core.extend({
	setIsMemberDetailsArea: function(isMemberDetailsArea)
	{
		this.isMemberDetailsArea = isMemberDetailsArea;
	},
	
	resetForm: function()
	{
		jQuery("#from_date").val("");
		jQuery("#to_date").val("");
		
		if(this.isMemberDetailsArea == "false")
		{
			jQuery("#member_id").val("");
		}
		
		jQuery("#event_type").val("");
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
	  
	  resetAndSearch: function()
	  {
		  this.crntPage = 0;
		  this.search();
	  },
  
	  search: function() 
	  {
		  var form_obj = new MM_Form('mm-form-container');
		  var values = form_obj.getFields();
		  
		  values.isMemberDetailsView = this.isMemberDetailsArea;
		  values.sortBy = this.sortBy;
		  values.sortDir = this.sortDir;
		  values.crntPage = this.crntPage;
		  values.resultSize = this.resultSize;
		  values.mm_action = "search";
	  
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

var isMemberDetailsArea = false;
var mmjs = new MM_ActivityLogViewJS("MM_ActivityLogView", "Activity Log");