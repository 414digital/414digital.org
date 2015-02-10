/*!
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */
var MM_MembershipLevelsViewJS = MM_Core.extend({
	setToExpire: function(){
		if(jQuery("#expiry-setting").is(":checked")){
			jQuery("#expires_div").show();
			jQuery("#expiry_chk").val("1");
		}
		else{
			jQuery("#expiry_chk").val("0");
			jQuery("#expires_div").hide();	
		}
	},
	
	setDefault: function(id)
	{
		var doSet = confirm("Are you sure you want to set this membership level as the default?");
	    
	    if(doSet)
	    {
	        var values = {
	            id:id,
	            mm_action: "setDefault"
	        };

	        var ajax = new MM_Ajax(false, this.module, this.action, this.method);
	        ajax.send(values, false, 'mmjs',this.updateHandler); 
	    }
	},
	
	welcomeEmailChanged: function()
	{
		if(!jQuery("#mm-welcome-email-enabled-field").is(":checked"))
		{
			jQuery("#mm-welcome-email-row").hide();
			jQuery("#mm-welcome-email-enabled").val("0");
		}
		else{
			jQuery("#mm-welcome-email-row").show();
			jQuery("#mm-welcome-email-enabled").val("1");
		}
	},
	
	filterRegistrationProducts: function()
	{
	    var selected = jQuery("#mm-default-product-id").val();
		jQuery("#mm-default-product-id").find('option').remove().end();
		
		var options = new Array();
	    jQuery("select[id='mm-products[]'] :selected").each(function()
	    {
		    	jQuery("#mm-default-product-id").append("<option value='"+jQuery(this).val()+"'>"+jQuery(this).text()+"</option>");
	    });
	    
	    
	    jQuery("select[id='mm-products[]'] :disabled").each(function()
	    {
			var val = jQuery(this).val();
			if(!jQuery.inArray(val, options))
			{
				jQuery("#mm-default-product-id").append("<option value='"+jQuery(this).val()+"'>"+jQuery(this).text()+"</option>");
			}
	    });
	    
	    if(jQuery("select#mm-default-product-id option").length > 0)
	    {
	    	if(selected)
	    	{
	    		jQuery("#mm-default-product-id").val(selected);
	    	}
	    }
	    else
	    {
	    	jQuery("#mm-default-product-id").append("<option value=''>Select a product</option>");
	    }
	},
	
  processForm: function()
  {	
 	  // status
 	  jQuery("#mm-status").attr('value', jQuery('#mm-status-container input:radio:checked').val());
 	  
 	  // subscribtion type
 	  var subTypeSelection = jQuery('#mm-subscription-container input:radio:checked').val();

 	  jQuery("#mm-subscription-type").attr('value', subTypeSelection);
 	  
 	  if(subTypeSelection == 'paid' && jQuery("#mm-has-associations").val() == "no") 
 	  {
 		  jQuery("#mm-products\\[\\]").removeAttr("disabled");
 		  jQuery("#mm-default-product-id").removeAttr("disabled");
 	  } 
 	  
 	  if(subTypeSelection == 'paid')
 	  {
		  jQuery("#mm-paid-membership-settings").show();
 		  jQuery("#mm-free-membership-settings").hide();
 	  }
 	  else
 	  {
		  jQuery("#mm-paid-membership-settings").hide();
 		  jQuery("#mm-free-membership-settings").show();
 	  }
 	  
	    jQuery("select[id=mm-products\\[\\]] :disabled").each(function()
	    {
	    	jQuery(this).attr("selected","selected");
	    	jQuery(this).removeAttr("disabled");
	    });
  },
   
  validateForm: function()
  {
	   // display name 
	   if(jQuery('#mm-display-name').val() == "") {
		   this._alert("Please enter a membership level name");
		   return false;
	   }
	   
	   // subscription type
	   if(jQuery("#mm-subscription-type").val() == "paid" && (jQuery("#mm-products\\[\\]").val() == null || jQuery("#mm-products\\[\\]").val() == "")) 
	   {
		   this._alert("Please select one or more products or set the membership type to Free");
		   return false;
	   }
	   
	   if(jQuery("#mm-welcome-email-enabled-field").is(":checked"))
	   {
		   // email subject
		   if(jQuery("#mm-email-subject").val() == "") {
			   this._alert("Please enter a subject for the welcome email");
			   return false;
		   }
		   
		   // email body
		   if(jQuery("#mm-email-body").val() == "") {
			   this._alert("Please enter a body for the welcome email");
			   return false;
		   }
	   }
	   
	   return true;
  },
	
  showPurchaseLinks: function(accessTypeId, accessTypeName, productIds)
  {	
		var values =  {};
		values.access_type_id = accessTypeId;
		values.access_type_name = accessTypeName;
		values.product_ids = productIds;
		values.mm_action = "showPurchaseLinks";
		
		mmdialog_js.showDialog("mm-purchaselinks-dialog", this.module, 515, 430, "Purchase Links", values);
  },
	
  productChangeHandler: function()
  {	
	  $lastProductId = jQuery("#mm-last-selected-product-id").val();
	  $productId = jQuery("#mm-product-selector").val();
	  
	  if($lastProductId != 0)
	  {
		  jQuery("#mm-purchaselinks-"+$lastProductId).hide(); 
	  }
	  
	  if($productId != 0)
	  {
		  jQuery("#mm-purchaselinks-"+$productId).show(); 
	  }
	  
	  jQuery("#mm-last-selected-product-id").val($productId);
  },
});

var mmjs = new MM_MembershipLevelsViewJS("MM_MembershipLevelsView", "Membership Level");
