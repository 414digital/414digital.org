/*!
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */
var MM_CouponViewJS = MM_Core.extend({
	createCoupon: function(){

		this.processForm();
		if(this.validateForm()) 
		{
			var form_obj = new MM_Form('mm-coupons-container');
		    var values = form_obj.getFields();
		    values.mm_action = "save";
		    
		    values.mm_products= this.getProducts();
		    values.mm_recurring_setting = (jQuery("#mm_recurring_setting_first").is(":checked"))?"first":"all";
		    
		    values.mm_status = (jQuery("#mm_status_active").is(":checked"))?"1":"0";
		    var ajax = new MM_Ajax(false, this.module, this.action, this.method);
		    ajax.send(values, false, 'mmjs', this.updateHandler); 
		}
	},
	
	getProducts: function(){
		var products = new Array();
		jQuery("input:checkbox[name=mm_products]:checked").each(function()
				{
				    // add jQuery(this).val() to your array
			products.push(jQuery(this).val());
	    	    });
	   return products;
	},
	
	typeChangeHandler: function(){
		if(jQuery("#mm_coupon_type").val() == "free")
		{
			jQuery("#mm_coupon_value").hide();
			jQuery("#mm_subscription_options_section").hide();
		}
		else
		{
			jQuery("#mm_coupon_value").show();
			jQuery("#mm_subscription_options_section").show();
		}
	},
	
	processForm: function(){
		
	},
	
	validateForm: function()
	{
		var re = new RegExp("^[0-9\.]+$","g");
		if(jQuery("#mm_coupon_name").val()==""){
			return this.throwError("Please enter a name for the coupon");
		}
		else if(jQuery("#mm_coupon_code").val()==""){
			return this.throwError("Please enter a coupon code");
		}
		else if(jQuery("#mm_coupon_type").val() != "free" && (jQuery("#mm_coupon_value").val()=="" || !re.test(jQuery("#mm_coupon_value").val()))){
			return this.throwError("Please enter a numeric discount amount");
		}
		else if(jQuery("#mm_start_date").val()==""){
			return this.throwError("Please provide a coupon start date");
		}
		return true;
	},
	
	throwError: function(msg){
		alert(msg);
		return false;
	},
	
});

var mmjs = new MM_CouponViewJS("MM_CouponView", "Coupon");