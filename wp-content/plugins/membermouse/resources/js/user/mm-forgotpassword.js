/*!
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */
var MM_ForgotPassword = Class.extend({
	checkFields: function()
	{
		var email = jQuery("#email").val();
		
		if(email.length <= 0)
		{
			alert("Please enter your email address");
			jQuery("#email").focus();
			return false;
		}
		return true;
	} 
});

var forgotpassword_js = new MM_ForgotPassword();