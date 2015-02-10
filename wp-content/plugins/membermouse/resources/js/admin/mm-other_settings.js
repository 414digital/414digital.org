/*!
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */
var MM_OtherSettingsViewJS = MM_Core.extend({
	showMbrHomepageOptions: function(){
		if(jQuery("input:radio[name=mm_member_homepage_setting]:checked").val() == "0") 
		{
			jQuery("#mm-mbr-homepage-options-div").show();
		} 
		else 
		{
			jQuery("#mm-mbr-homepage-options-div").hide();
		}
	},
	
	showAccountSharingForm: function()
	{
		if(jQuery("#mm-cb-enable-acct-sharing-prevention").is(":checked")) 
		{
			jQuery("#mm-acct-sharing-prevention").show();
		} 
		else 
		{
			jQuery("#mm-acct-sharing-prevention").hide();
		}
	},
	
	showActivityLogForm: function()
	{
		if(jQuery("#mm-cb-enable-activity-log-cleanup").is(":checked")) 
		{
			jQuery("#mm-activity-log-cleanup").show();
		} 
		else 
		{
			jQuery("#mm-activity-log-cleanup").hide();
		}
	},
	
	updateWPMenuSettingsForm: function()
	{
		if(jQuery("#mm_hide_menu_items_cb").is(":checked")) 
		{
			jQuery("#mm_hide_menu_items").val("1");
		} 
		else 
		{
			jQuery("#mm_hide_menu_items").val("0");
		}
		
		if(jQuery("#mm_show_login_logout_link_cb").is(":checked")) 
		{
			jQuery("#mm_show_login_logout_link").val("1");
		} 
		else 
		{
			jQuery("#mm_show_login_logout_link").val("0");
		}
	}
	
});

var mmjs = new MM_OtherSettingsViewJS("", "");