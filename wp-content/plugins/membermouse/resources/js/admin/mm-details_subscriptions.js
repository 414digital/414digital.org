/*!
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */
var MM_SubscriptionViewJS = MM_Core.extend({
	editSubscription: function(orderItemId)
	{
		var dialogId = "mm-edit-subscription-dialog";
		jQuery("#"+dialogId).dialog({autoOpen: false});
		
		var values = {};
		values.order_item_id = orderItemId;
		mmdialog_js.method = 'showEditSubscriptionDialog';
		mmdialog_js.showDialog(dialogId, this.module, 400, 250, "Edit Subscription", values);
	},
	
	saveSubscription: function()
	{
		var form_obj = new MM_Form('mm-edit-subscription-div');
	    var values = form_obj.getFields();
		values.mm_action = "saveSubscription";
	    
	    var ajax = new MM_Ajax(false, this.module, this.action, this.method);
	    ajax.send(values, false, 'mmjs', "saveSubscriptionCallback"); 
	},
	
	saveSubscriptionCallback: function(response)
	{
		mmjs.closeDialog();
		alert(response.message);
		document.location.href = document.location.href;
	},
	
	cancelSubscriptionAndAccess: function(orderItemId){
		mmjs.closeDialog();
		var isOk = confirm("Are you sure you want to cancel this subscription and remove the member's associated access rights?");
		if(isOk){
		    var values = {
		    	mm_order_item_id:orderItemId,
		        mm_action: "cancelSubscriptionAndAccess"
		    };
		    var ajax = new MM_Ajax(false, this.module, this.action, this.method);
		    ajax.send(values, false, 'mmjs', "cancelSubscriptionCallback");
		}
	},
	
	cancelSubscriptionOnly: function(orderItemId){
		mmjs.closeDialog();
		var isOk = confirm("Are you sure you want to cancel this subscription and keep the member's associated access rights?");
		if(isOk){
		    var values = {
		    	mm_order_item_id:orderItemId,
		        mm_action: "cancelSubscriptionOnly"
		    };
		    var ajax = new MM_Ajax(false, this.module, this.action, this.method);
		    ajax.send(values, false, 'mmjs', "cancelSubscriptionCallback");
		}
	},
	
	cancelSubscriptionCallback: function(response)
	{
		if(response.type == "success")
		{
			alert(response.message);
			this.search();
		}
		else
		{
			alert(response.message);
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
	
	dgSetResultSize: function(pageControl){
		  if(jQuery(pageControl).val() != undefined)
		  {
			  this.crntPage = 0;
			  this.resultSize = jQuery(pageControl).val();
			  this.search();
		  }
	  },
	  
	  search: function(crntPage) 
	  {
		  var form_obj = new MM_Form('mm-form-container');
		  var values = form_obj.getFormContents();
		  
		  if(crntPage != undefined) {
			  this.crntPage = crntPage;
		  }
      
		  values.sortBy = this.sortBy;
		  values.sortDir = this.sortDir;
		  values.crntPage = this.crntPage;
		  values.resultSize = this.resultSize;
		  values.mm_action = "refreshView";
		  var ajax = new MM_Ajax(false, this.module, this.action, this.method);
		  ajax.send(values, false, "mmjs", "refreshViewCallback"); 
	  },
	
});

var mmjs = new MM_SubscriptionViewJS("MM_SubscriptionsView", "Subscriptions");