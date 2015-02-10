/*!
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */
var MM_CommissionProfileViewJS = MM_Core.extend({	
	setDefault: function(id)
	{
		var doSet = confirm("Are you sure you want to set this commission profile as the default?");
	    
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
	
	renderRebillOptions: function()
	{
		if(jQuery("#mm-enable-rebill-commissions-checkbox").is(":checked"))
		{
			jQuery("#rebill_commission_options").show();
			
			if(jQuery("#mm-limit-rebill-commissions-checkbox").is(":checked"))
			{
				jQuery("#limit_rebill_commission_options").show();
			}
			else
			{
				jQuery("#limit_rebill_commission_options").hide();
			}
		}
		else
		{
			jQuery("#rebill_commission_options").hide();
		}
	},
	
	checkRebillCommission: function(e)
	{
		var keynum;
      	var keychar;
      	var numcheck;
      	if(window.event) // IE
      	{
         	keynum = e.keyCode;
      	}
      	else if(e.which) // Netscape/Firefox/Opera
      	{
         	keynum = e.which;
      	}
      	keychar = String.fromCharCode(keynum);
      	numcheck = /\d/;
      
      	switch (keynum)
      	{
         	case 8:    //backspace
         	case 9:    //tab
         	case 13:   //enter
         	case 35:   //end
         	case 36:   //home
         	case 37:   //left arrow
         	case 38:   //right arrow
         	case 39:   //insert
         	case 45:   //delete
         	case 190:  // .
         	case 46:   //0
         	case 48:   //1
         	case 49:   //2
         	case 50:   //3
         	case 51:   //4
         	case 52:   //5
         	case 54:   //6
         	case 55:   //7
         	case 56:   //8
         	case 57:   //9
         	case 96:   //0
         	case 97:   //1
         	case 98:   //2
         	case 99:   //3
         	case 100:  //4
         	case 101:  //5
         	case 102:  //6
         	case 103:  //7
         	case 104:  //8
         	case 105:  //9
            	return true;
            	break;
         	default:
         		return numcheck.test(keychar);
            	break;
      	}
      	
      	return false;
	},
	
  processForm: function()
  {	
	  jQuery("#mm-enable-initial-commission").val("0");
	  jQuery("#mm-enable-rebill-commissions").val("0");
	  jQuery("#mm-limit-rebill-commissions").val("0");
	  jQuery("#mm-enable-reverse-commissions").val("0");
	  jQuery("#mm-rebill-commission-type").val('default');
	  
	  if(jQuery("#mm-enable-initial-commission-checkbox").is(":checked"))
	  {
		  jQuery("#mm-enable-initial-commission").val("1");
	  }
	  
	  if(jQuery("#mm-enable-rebill-commissions-checkbox").is(":checked"))
	  {
		  jQuery("#mm-enable-rebill-commissions").val("1");
	  }
	  
	  if(jQuery("#mm-limit-rebill-commissions-checkbox").is(":checked"))
	  {
		  jQuery("#mm-limit-rebill-commissions").val("1");
	  }
	  
	  if(jQuery("#mm-enable-reverse-commissions-checkbox").is(":checked"))
	  {
		  jQuery("#mm-enable-reverse-commissions").val("1");
	  }
	  
	  if(jQuery('#rebill_commission_type_selection input:radio:checked').val() == 'custom')
	  {
		  jQuery("#mm-rebill-commission-type").val(jQuery("#rebill_commission_type_selector").val());
	  }
  },
   
  validateForm: function()
  {
	   // display name 
	   if(jQuery('#mm-display-name').val() == "") {
		   this._alert("Please enter a commission profile name");
		   return false;
	   }
	   
	   if(jQuery("#mm-enable-rebill-commissions-checkbox").is(":checked"))
	   {
		   if(jQuery("#mm-rebill-commission-type").val() == "percent")
		   {
			   var commissionValue = parseInt(jQuery("#mm_rebill_commission_value").val());
			  
			   if(commissionValue < 0 || commissionValue > 100)
			   {
				   this._alert("Rebill commission value must be between 0 and 100");
				   return false;
			   }
		   }
	   }
	   
	   return true;
  }
});

var mmjs = new MM_CommissionProfileViewJS("MM_CommissionProfilesView", "Commission Profile");
