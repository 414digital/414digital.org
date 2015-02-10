/*!
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */
var MM_MemberDetailsViewJS = MM_Core.extend({

	updateMember: function(id)
	{	
		this.id = id;
		
		if(this.validateForm()) 
		{
			var form_obj = new MM_Form('mm-form-container');
		    var values = form_obj.getFields();
		     
		    this.page = values.page;
		    this.mm_module = values.module;
		    
		    values.mm_action = "updateMember";
		    
		    var ajax = new MM_Ajax(false, this.module, this.action, this.method);
		    ajax.send(values, false, 'mmjs', "memberUpdateHandler"); 
		}
	},
	
	memberUpdateHandler: function(data)
	{
		if(data.type == "error")
		{
			if(data.message.length > 0)
			{  
				alert(data.message);
				return false;
			}
		}
		else 
		{
			if(data.message != undefined && data.message.length > 0)
			{
				alert(data.message);
			}
		  
			this.refreshView();
		}
	},
  
	refreshView: function()
	{
		var values = {
			user_id: this.id,
			page: this.page,
			mm_module: this.mm_module,
			mm_action: "refreshView"
		};
    
		var ajax = new MM_Ajax(false, this.module, this.action, this.method);
		ajax.send(values, false, 'mmjs','refreshViewCallback'); 
	},
	
	sendPasswordEmail: function(user_id)
	{
	    var isOk = confirm("Are you sure you want to send this member an email with their current password?");
	    if(isOk){
			var values = {};
		     
		    values.mm_action = "sendPasswordEmail";
		    values.user_id = user_id;
		    
		    var ajax = new MM_Ajax(false, this.module, this.action, this.method);
		    ajax.send(values, false, 'mmjs', "passwordUpdateHandler"); 
	    }
	},
	
	passwordUpdateHandler: function(data)
	{
		if(data.type=='error'){
			alert(data.message);
		}
		else{
			alert("The member's current password was resent to them successfully");
		}
	},
	
	sendWelcomeEmail: function(user_id)
	{
	    var isOk = confirm("Are you sure you want to resend the welcome email to this member?");
	    if(isOk){
			var values = {};
		     
		    values.mm_action = "sendWelcomeEmail";
		    values.user_id = user_id;
		    
		    var ajax = new MM_Ajax(false, this.module, this.action, this.method);
		    ajax.send(values, false, 'mmjs', "sendWelcomeEmailHandler"); 
	    }
	},
	
	sendWelcomeEmailHandler: function(data)
	{
		if(data.type=='error'){
			alert(data.message);
		}
		else{
			alert("The welcome email was resent successfully");
			location.reload(); 
		}
	},
	
	loginAsMember: function(user_id)
	{
	    var isOk = confirm("Are you sure you want to login as this member?\n\nYou will be logged out of your account if you proceed.");
	    if(isOk){
			var values = {};
		     
		    values.mm_action = "loginAsMember";
		    values.user_id = user_id;
		    
		    var ajax = new MM_Ajax(false, this.module, this.action, this.method);
		    ajax.send(values, false, 'mmjs', "loginAsMemberHandler"); 
	    }
	},
	
	loginAsMemberHandler: function(data)
	{
		if(data.type=='error'){
			alert(data.message);
		}
		else{
			document.location = data.message;
		}
	},
	
	deleteMember: function(id, redirectUrl)
	{
		var msg = "Are you sure you want to delete this member?\n\nAll data associated with this member will be deleted. This operation cannot be undone.";
		
		if(confirm(msg))
		{
			this.redirectUrl = redirectUrl;

			var values = {};
		    values.id = id;
		    values.mm_action = "deleteMember";
		        
		    var ajax = new MM_Ajax(false, this.module, this.action, this.method);
		    ajax.send(values, false, 'mmjs', "deleteMemberHandler"); 
		}
	},
	
	deleteMemberHandler: function(data)
	{
		if(data.type == "error")
		{
			if(data.message.length > 0)
			{  
				alert(data.message);
				return false;
			}
		}
		else 
		{
			if(data.message != undefined && data.message.length > 0)
			{
				alert(data.message);
			}
			
			document.location = this.redirectUrl;
		}
	},
	
	validateForm: function()
	{
		if(jQuery('#mm-email').val() == "") 
		{
			alert("Email is required");
			jQuery('#mm-email').focus();
			return false;
		}
	   
		if(!this.validateEmail(jQuery('#mm-email').val())) 
		{
			alert("Please enter a valid email address");
			jQuery('#mm-email').focus();
			return false;
		}
		
		if(jQuery('#mm-username').val() == "") 
		{
			alert("Username is required");
			jQuery('#mm-username').focus();
			return false;
		}
		
		if(jQuery('#mm-new-password').val() != "") 
		{
			if(jQuery('#mm-new-password').val() != jQuery('#mm-confirm-password').val()) 
			{
				alert("The new and confirm passwords don't match");
				jQuery('#mm-confirm-password').focus();
				return false;
			}
		}

		return true;
	}
});

var redirectUrl = "";
var mmjs = new MM_MemberDetailsViewJS("MM_MemberDetailsView", "Member");