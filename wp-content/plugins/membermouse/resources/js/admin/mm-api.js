/*!
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */
var MM_APIViewJS = MM_Core.extend({
    
	toggleVisibility: function(fieldId)
	{
		jQuery("#"+fieldId+"-show").toggle();
		jQuery("#"+fieldId+"-hide").toggle();
	},
	
	setStatusField: function()
	{
		var status = jQuery("#mm-status-container input:radio:checked").val();
		var statusVal = '0';
		if(status=='active')
		{
			statusVal = '1';
		}
		jQuery("#mm-status").val(statusVal);
	},
	
	validateForm: function()
	{
		var key = jQuery("#mm-api-key").val();
		var secret = jQuery("#mm-api-secret").val();
		var name = jQuery("#mm-name").val();
		
		this.setStatusField();
		
		if(key.length<=6){
			alert("API Key must be great than 5 alpha-numeric characters.");
			return false;
		}
		
		if(secret.length<=6){
			alert("API Secret must be great than 5 alpha-numeric characters.");
			return false;
		}
		
		if(name.length<=0){
			alert("You must provide an API name.");
			return false;
		}
		return true;
	},
	
	createRandomString: function(len)
	{	
		if(len==undefined){
			len = 7;
		}
		
		var text = "";
		var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
		
		for( var i=0; i < len; i++ )
		    text += possible.charAt(Math.floor(Math.random() * possible.length));
		
		return text;
	},
	
	generateKey: function(key)
	{	
		jQuery("#"+key).val(this.createRandomString());
	},
	
});

var mmjs = new MM_APIViewJS("MM_ApiView", "API Credentials");