/*!
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */

var MM_CustomFormView = MM_Core.extend({
	ltrim:function(str)
	{
		if(str != undefined)
		{
			return str.replace(/^\s+/,"");
		}
		else
		{
			return "";
		}
	},
	
   	checkFields:function()
   	{   		
      	if(jQuery('#hasFormSubmitted').val() != '')
      	{
         	var prevPost = new Date(jQuery('#hasFormSubmitted').val());
         	prevPost.setSeconds(prevPost.getSeconds() + 3);
         	var currentTime = new Date();
         	
         	if (prevPost < currentTime)
         	{
            	// -- blank out the formsubmitted because user went back and forth and hidden values still cached
         		jQuery('#hasFormSubmitted').val("");
         	}
      	}
   
      	var crntValue;
      	
      	// validate custom fields
      	var customFields = jQuery(':input[id^="mm_custom"]').serializeArray();
      	
      	for(i = 0; i < customFields.length; i++)
      	{
      		if(jQuery("#" + customFields[i].name + "_required").length > 0 && jQuery("#" + customFields[i].name + "_required").val() == "1")
      		{
      			crntValue = customform_js.ltrim(customFields[i].value);
      			
      			// get the custom field type
      			if(jQuery("#" + customFields[i].name + "_type").length > 0)
      			{
      				fieldType = jQuery("#" + customFields[i].name + "_type").val();
      			}
      			else 
      			{
      				fieldType = "input";
      			}
      			
      			if(fieldType == "checkbox")
      			{
      				if(crntValue == "mm_cb_off")
      				{
      					// the checkbox implementation always puts the checkbox after the off value with the same name
      					// so if the next field value equals mm_cb_on the checkbox is checked off, otherwise it's not
      					nextValue = "";
      					if(customFields.length > (i + 1))
      					{
      						nextValue = customform_js.ltrim(customFields[i + 1].value);
      					}
      					
      					if(nextValue != "mm_cb_on")
      					{
		      				if(jQuery("#" + customFields[i].name + "_label").length > 0)
		          			{
		          				alert('Please check off ' + jQuery("#" + customFields[i].name + "_label").val());
		          			}
		          			else
		          			{
		          				alert('This field is required');
		          			}
		      				
		      				jQuery("#" + customFields[i].name).focus();
				       		
			      			return false;
      					}
      				}
      			}
      			else
      			{
	      			if ((crntValue == '') || (crntValue == ' ') || (customFields[i].value.length == 0) 
		    	     		|| (customFields[i].value == null) || (customFields[i].value == ''))
		        	{
	      				if(jQuery("#" + customFields[i].name + "_label").length > 0)
	          			{
	          				alert('Please enter your ' + jQuery("#" + customFields[i].name + "_label").val());
	          			}
	          			else
	          			{
	          				alert('This field is required');
	          			}
	      				
		      			jQuery("#" + customFields[i].name).focus();
			       		
		      			return false;
		        	}
      			}
      		}   
      	}
	   
      	if (jQuery('#hasFormSubmitted').val() == '')
    	{
        	var d = new Date();
            jQuery('#hasFormSubmitted').val(d.toUTCString());
            document.charset = 'ISO-8859-1';
           
       		document.mm_custom_form.submit();
      	}
	}
});

var customform_js = new MM_CustomFormView("MM_CustomFormView", "");