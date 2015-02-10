/*!
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */

var MM_1ClickPurchaseView = MM_Core.extend({
	formatMoney:function(number, places, symbol, thousand, decimal) 
	{
		ci = MemberMouseGlobal.currencyInfo;
		currencyParams = {"places":"frac_digits", "symbol":"currency_symbol", "thousand":"mon_thousands_sep", "decimal":"mon_decimal_point"};
		
		number = number || 0;
		places = !isNaN(places = Math.abs(places)) ? places : 2;
		symbol = symbol !== undefined ? symbol : ci[currencyParams['symbol']];
		thousand = thousand || ci[currencyParams['thousand']];
		decimal = decimal || ci[currencyParams['decimal']];
		var negative = number < 0 ? "-" : "",
		    i = parseInt(number = Math.abs(+number || 0).toFixed(places), 10) + "",
		    j = (j = i.length) > 3 ? j % 3 : 0;
		
		var retval =  negative + (j ? i.substr(0, j) + thousand : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousand) + (places ? decimal + Math.abs(number - i).toFixed(places).slice(2) : "");
		retval = (ci.p_cs_precedes == "1")?(symbol + retval):(retval + symbol);	//position the symbol correctly based on the currency info
		retval = (ci.postfixIso == 1)?(retval + " " + ci.currency):retval; //postfix iso currency code if selected
	
		return retval;
	},
	
	unformatMoney: function(formattedNumber)
	{
		if (formattedNumber == undefined)
		{
			return 0.00;
		}
		
		unformattedNumber = formattedNumber.replace(/&[^;]*;/g,""); //remove html entities
		
		//thousands separator is always superfluos
		var reThou = new RegExp(oneclickpurchase_js.regexEscape(ci[currencyParams['thousand']]),"g");
		unformattedNumber = unformattedNumber.replace(reThou,"");
		
		
		//replace decimal seperator with the decimal point if necessary
		if (ci[currencyParams['decimal']] != ".")
		{
			var reDec = new RegExp(oneclickpurchase_js.regexEscape(ci[currencyParams['decimal']]),"g");
			unformattedNumber = unformattedNumber.replace(reDec,".");
		}
		
		return unformattedNumber.replace(/[^0-9-.]/g,"");
	},
	
	regexEscape: function(text) 
	{
		  return text.replace(/[-[\]{}()*+?.,\\^$|#\s]/g, "\\$&");
	},
	
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
	
   	validate:function()
   	{
   		var required_fields = new Array();
   	    var required_fields_label = new Array();
   		
   		required_fields[0]='mm_field_shipping_address';
   		required_fields_label[0]='shipping address';
   		
   		required_fields[1]='mm_field_shipping_city';
   		required_fields_label[1]='shipping city';
   		
   		required_fields[2]='mm_field_shipping_state';
   		required_fields_label[2]='shipping state';
   		
   		required_fields[3]='mm_field_shipping_zip';
   		required_fields_label[3]='shipping zip code or postal code';
   		
   		required_fields[4]='mm_field_shipping_country';
   		required_fields_label[4]='shipping country';
   		
      	var crntField;
      	var crntValue;
      	var shippingReq = (parseInt(jQuery("#mm_is_shippable").val()) == 1) ? true : false;
      	
      	// validate standard fields
      	for (i=0; i < required_fields.length; i++)
      	{
      		// skip shipping fields if shipping is not required
			if(required_fields[i].indexOf("shipping") != -1 && shippingReq == false)
			{
				continue;
			}
			
			if(jQuery("#" + required_fields[i]).length > 0)
			{
	         	crntField = jQuery("#" + required_fields[i]);
	         
		        crntValue = oneclickpurchase_js.ltrim(crntField.val());
					
		     	if ((crntValue == '') || (crntValue == ' ') || (crntField.val().length == 0) 
		     			|| (crntField.val() == null) || (crntField.val() == ''))
		        {
		     		alert('Please enter your ' + required_fields_label[i]);
			       	jQuery("#" + required_fields[i]).focus();
			       	return false;
		      	}
			}
		}
      	
      	return true;
	},

   	onlyNumbers:function(e,type)
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
            	result2 = true;
            	break;
         	case 109: // -
         	case 189: // -
         	case 48:  // )
         	case 57:  // (
         	case 32:  // space
         	case 191: // /
            	if (type == 'phone')
            	{
               		result2 = true;
            	}
            	else
            	{
            		result2 = false;
            	}
         		break;
         	default:
            	result2 = numcheck.test(keychar);
            	break;
      	}

      	return result2;
 	},

 	toggleShippingInfo:function() 
 	{
 		if(jQuery("#mm_checkbox_billing_equals_shipping").length > 0)
 		{
			if(jQuery("#mm_checkbox_billing_equals_shipping").is(":checked"))
			{
				jQuery("#mm-shipping-info-block").hide();
				jQuery("#mm_field_billing_equals_shipping").val("1");
			}
			else
			{
				jQuery("#mm-shipping-info-block").show();
				jQuery("#mm_field_billing_equals_shipping").val("0");
			}
 		}
 	},

 	toggleGiftSection:function() 
 	{
 		if(jQuery("#mm_checkbox_is_gift").length > 0)
 		{
			if(jQuery("#mm_checkbox_is_gift").is(":checked"))
			{
				jQuery("#mm-gift-info-block").show();
				jQuery("#mm_is_gift").val("1");
			}
			else
			{
				jQuery("#mm-gift-info-block").hide();
				jQuery("#mm_is_gift").val("0");
			}
 		}
 	},

   	countryChangeHandler:function(type)
   	{
  		if(jQuery("#mm_field_" + type + "_country").val() == 'US')
  	  	{
  	 		jQuery("#mm_field_" + type + "_state").hide();
  	 		jQuery("#mm_field_" + type + "_state_dd").show();
  	 		oneclickpurchase_js.stateChangeHandler(type);
  	 	}
  	 	else
  	 	{
  	 		jQuery("#mm_field_" + type + "_state").show();
  	 		jQuery("#mm_field_" + type + "_state_dd").hide();
  	 	}
    },

    initStateDropdown:function(type, selectedState)
    {
    	if(jQuery("#mm_field_" + type + "_country").val() == 'US')
  	  	{
  	 		jQuery("#mm_field_" + type + "_state_dd").val(selectedState);
  	 		oneclickpurchase_js.stateChangeHandler(type);
  	 	}
    },
    
   	stateChangeHandler:function(type)
   	{
  	  	var selectedState = jQuery("#mm_field_" + type + "_state_dd").val();
  	  	jQuery("#mm_field_" + type + "_state").val(selectedState);
    },
    
   	shippingMethodChangeHandler:function()
   	{
   		var methodPrice = oneclickpurchase_js.formatMoney(0.00);
   		
   		if(jQuery("#mm_is_shippable").val() == "1" && jQuery("#mm_field_shipping_method").length > 0)
   	   	{
	  	  	var selectedMethod = jQuery("#mm_field_shipping_method option:selected").text();
	  	  	var startIndex = selectedMethod.lastIndexOf("(") + 1;
	  	  	var endIndex = selectedMethod.lastIndexOf(")");
	  	  	methodPrice = selectedMethod.substring(startIndex, endIndex);
   	   	}
   	   	
   	   	jQuery("#mm_data_shipping_price").val(methodPrice);

	  	if(jQuery("#mm_label_shipping_price").length > 0)
	  	{
	  		jQuery("#mm_label_shipping_price").html(jQuery("#mm_data_shipping_price").val());
	  	}
 	
	  	oneclickpurchase_js.updateOrderTotal();
    },
    
   	discountChangeHandler:function(discount)
   	{
  	  	jQuery("#mm_data_discount").val(discount);

  	  	if(jQuery("#mm_label_discount").length > 0)
  	  	{
    		jQuery("#mm_label_discount").html(jQuery("#mm_data_discount").val());
  	  	}
  	  	
    	discountAmt = parseFloat(oneclickpurchase_js.unformatMoney(discount));

    	if(isNaN(discountAmt))
    	{
    		discountAmt = 0;
    	}
    	
    	if(discountAmt > 0)
    	{
    		jQuery('div.mm-discount-true').each(function( index ) {
    			jQuery(this).show();
    		});
    		
    		jQuery('div.mm-discount-false').each(function( index ) {
    			jQuery(this).hide();
    		});
    	}
    	else
    	{
    		jQuery('div.mm-discount-true').each(function( index ) {
    			jQuery(this).hide();
    		});
    		
    		jQuery('div.mm-discount-false').each(function( index ) {
    			jQuery(this).show();
    		});
    	}
    	
    	oneclickpurchase_js.updateOrderTotal();
    },

    updateOrderTotal:function()
    {
        var productPrice = 0;
        var shippingPrice = 0;
        var discount = 0;
        var totalPrice = 0;

		if(jQuery("#mm_data_product_price").length > 0)
		{
			
        	productPrice = parseFloat(oneclickpurchase_js.unformatMoney(jQuery("#mm_data_product_price").val()));

        	if(isNaN(productPrice))
        	{
    			productPrice = 0;
        	}	
		}

		if(jQuery("#mm_data_shipping_price").length > 0) 
		{
        	shippingPrice = parseFloat(oneclickpurchase_js.unformatMoney(jQuery("#mm_data_shipping_price").val()));

        	if(isNaN(shippingPrice))
        	{
        		shippingPrice = 0;
        	}
		}

		if(jQuery("#mm_data_discount").length > 0) 
		{
        	discount = parseFloat(oneclickpurchase_js.unformatMoney(jQuery("#mm_data_discount").val()));

        	if(isNaN(discount))
        	{
        		discount = 0;
        	}
		}
        
        totalPrice = (productPrice + shippingPrice) - discount;

        if(totalPrice < 0)
        {
			totalPrice = 0;
        }

        if(jQuery("#mm_label_total_price").length > 0)
        {
    		jQuery("#mm_label_total_price").html(oneclickpurchase_js.formatMoney(totalPrice));
        }
    },
    
    couponCheck:function(evt)
   	{
      	var keynum;
      	var keychar;
      	var numcheck;
      	if(window.event) // IE
      	{
         	keynum = evt.keyCode;
      	}
      	else if(evt.which) // Netscape/Firefox/Opera
      	{
         	keynum = evt.which;
      	}
      	keychar = String.fromCharCode(keynum);
      	numcheck = /\d/;

      	switch (keynum)
      	{
      		case 13:   //enter
      			evt.preventDefault();
      			evt.stopPropagation();
      			oneclickpurchase_js.applyCoupon();
      			return false;
      			break;
      		
         	default:
            	return true;
            	break;
      	}

      	return true;
 	},
    
	applyCoupon:function()
	{	
		var values = {};
		values.mm_action = "applyCoupon";
		values.product_price = parseFloat(oneclickpurchase_js.unformatMoney(jQuery("#mm_data_product_price").val()));
		
		if(jQuery("#mm_field_coupon_code").length > 0 && jQuery("#mm_field_coupon_code").val().length > 0 && jQuery("#mm_field_coupon_code").val() != "")
		{
			values.coupon_code = jQuery("#mm_field_coupon_code").val();
		}
		else
		{
			values.coupon_code = "";
		}
		
	    values.product_id = jQuery("#mm_product_id").val();
	    
    	var ajax = new MM_Ajax('wp-admin/admin-ajax.php', this.module, this.action, this.method);
    	ajax.useLoader = false;
    	ajax.send(values, false, 'oneclickpurchase_js','applyCouponCallback');
	},
	  
	applyCouponCallback: function(response)
	{
		// hide and clear labels
		jQuery("#mm_label_coupon_success").hide();
		jQuery("#mm_label_coupon_success").html("");
		jQuery("#mm_label_coupon_error").hide();
		jQuery("#mm_label_coupon_error").html("");
		jQuery("#mm_do_comp").val("0");
		
		if(response.message != undefined)
		{
			var result = jQuery.parseJSON(response.message);
			
			if(result.isError == "1")
			{
				jQuery("#mm_label_coupon_error").html(result.message);
				jQuery("#mm_label_coupon_error").show();
				oneclickpurchase_js.discountChangeHandler(oneclickpurchase_js.formatMoney(result.discount));
			}
			else if(result.message != "")
			{
				jQuery("#mm_label_coupon_success").html(result.message);
				jQuery("#mm_label_coupon_success").show();
			}

			if(result.discount == "free")
			{
				// calculate order total (product price plus shipping cost)
				var orderTotal = 0;
				var productPrice;
				var shippingPrice;
				
				if(jQuery("#mm_data_product_price").length > 0)
				{
		        	productPrice = parseFloat(oneclickpurchase_js.unformatMoney(jQuery("#mm_data_product_price").val()));

		        	if(!isNaN(productPrice))
		        	{
		        		orderTotal += productPrice;
		        	}	
				}

				if(jQuery("#mm_data_shipping_price").length > 0) 
				{
		        	shippingPrice = parseFloat(oneclickpurchase_js.unformatMoney(jQuery("#mm_data_shipping_price").val()));

		        	if(!isNaN(shippingPrice))
		        	{
		        		orderTotal += shippingPrice;
		        	}
				}
				
				oneclickpurchase_js.discountChangeHandler(oneclickpurchase_js.formatMoney(orderTotal));
				jQuery("#mm_do_comp").val("1");
			}
			else
			{
				oneclickpurchase_js.discountChangeHandler(oneclickpurchase_js.formatMoney(result.discount));
			}
		}
		else
		{
			jQuery("#mm_label_coupon_error").html("Invalid coupon code");
			jQuery("#mm_label_coupon_error").show();
			oneclickpurchase_js.discountChangeHandler(oneclickpurchase_js.formatMoney(0.00));
		}
	}
});

var oneclickpurchase_js = new MM_1ClickPurchaseView("MM_CheckoutView", "1-Click Purchase");