/*!
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */
var MM_BundlesViewJS = MM_Core.extend({
	setToExpire: function(){
		if(jQuery("#expiry-setting").is(":checked")){
			jQuery("#expires_div").show();
			jQuery("#should-expire").val("1");
		}
		else{
			jQuery("#should-expire").val("0");
			jQuery("#expires_div").hide();	
		}
	},
  
	processForm: function()
	{
		// status
		jQuery("#mm-status").attr('value', jQuery('#mm-status-container input:radio:checked').val());
 	  
		// subscribtion type
 	    var subTypeSelection = "free";
 	    
 	    if(jQuery("#subscription-type-paid").is(":checked"))
 	    {
 	    	subTypeSelection = "paid";
 	    }
		
		if(subTypeSelection == 'paid')
 	 	{
		  jQuery("#mm-paid-bundle-settings").show();
 	 	}
		else
		{
		  jQuery("#mm-paid-bundle-settings").hide();
		}
 	    
		jQuery("#mm-subscription-type").attr('value', subTypeSelection);
 	  
		if(subTypeSelection == 'paid') 
		{
			jQuery("#mm-products\\[\\]").removeAttr("disabled");
			jQuery("#mm-products\\[\\]").show();
		} else 
		{
			jQuery("#mm-products\\[\\]").attr("disabled","disabled");
			jQuery("#mm-products\\[\\]").hide();
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
			alert("Please enter a bundle name");
			return false;
		}
	   
		// subscription type
		if(jQuery("#mm-subscription-type").val() == "paid" && (jQuery("#mm-products\\[\\]").val() == null || jQuery("#mm-products\\[\\]").val() == "")) 
		{
			alert("Please select one or more products or set the bundle type to Free");
			return false;
		}
		
		var autogen_shortname = (jQuery('#autogen_shortname').val() == "true");

		//short name
		if(!autogen_shortname && (jQuery('#mm-short-name').val() == "")){
			alert("Please enter a short name");
			return false;
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

var mmjs = new MM_BundlesViewJS("MM_BundlesView", "Bundle");