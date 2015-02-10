/*!
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */
var MM_ReportJSBase = Class.extend({
  
	pollTimer: "", //handle to the poll Timer
	
	pollCounter: 0, //number of polling attempts
	
	activeParams: {}, //parameters used to render each visual component
	
	previousStates: {}, //stores DOM nodes containing previous component states
	
	/**
	 * Performs validation on form elements prior to submission. Meant to be overridden in subclasses
	 */
	validate: function()
	{
		return this._validateStartAndEndDate()
	},

	/**
	 * Constructor. Sets up parameters to allow AJAX system to communicate with the view (defaults to MM_ReportingView)
	 * @param reportName The name of the class implementing the reporting being displayed
	 */
	init: function(reportName) 
	  {	
		  if(reportName == undefined) 
		  {
			  this._alert("MM_ReportJSBase.js: report name is required (i.e. MM_NewMembersReport)");
		  }
		  
		  this.module = 'MM_ReportingView';
		  this.method = "performAction";
		  this.action = "module-handle";
		  this.reportName = reportName;
	  },
	
	/**
	 * Submit gathers the parameters for the on-page form, and sends them to the server for use in generating visual elements
	 * Returned elements are sent for display, elements where data was not-ready are set up to be polled again later
	 */
	submit: function()
	{	
		if ((this.pollTimer != "") && (this.pollTimer != undefined))
		{
			clearTimeout(this.pollTimer);
		}
		
		if (!this.validate())
		{
			return false;
		}
		
		var action = this.action;
		var module = this.module;
		var method = this.method;
		var _this = this;
		var params = {};
		var globalParams = jQuery(".mm-report-params :input").serializeArray();
		var values = {};
		
		values.mm_action = "pollReportData"; //the receiving method in the view
		values.reportName = this.reportName; //the report that will process the data
		
		//create a values array
		jQuery.each(globalParams, function() {
	        if (params[this.name] !== undefined) 
	        {
	        	if (!params[this.name].push)
	        	{
	        		params[this.name] = [params[this.name]];
	        	}
	            params[this.name].push(this.value || '');
	        } 
	        else 
	        {
	            params[this.name] = this.value || '';
	        }
	    });
	    
		//provide each element with the information it needs to poll and render independantly later
		jQuery(".mm-report-visual-element").each(function(){
			var elementId = jQuery(this).attr('id');
			var elementWidth = jQuery(this).attr("elementWidth") || '';
			var elementHeight = jQuery(this).attr("elementHeight") || '';
			if (values.elements == undefined) 
			{
				values.elements = [{"id":elementId, "elementWidth":elementWidth, "elementHeight":elementHeight,"params":params}];
			}
			else
			{
				values.elements.push({"id":elementId, "elementWidth":elementWidth, "elementHeight":elementHeight,"params":params});
			}
			_this.pushParams(elementId, params);
			jQuery(this).attr('data-status','loading');
			_this.showLoadingIcon(elementId);
		});
		
		var ajax = new MM_Ajax(false, module, action, method);
		ajax.useLoader = false;
	    ajax.send(values, false, 'mmjs','pollCallbackHandler');
	},
	
	/**
	 * pollCallbackHandler serves as the AJAX callback handler for both submit() and poll()
	 * It interprets the response received from the server, and for each visual element in the  
	 * response it calls either the success handler, the error handler, or sets a timer to poll again later
	 * 
	 * @param response The MM_Response object from the server.
	 */
	pollCallbackHandler: function(response)
	{
		if ((this.pollTimer != "") && (this.pollTimer != undefined))
		{
			clearTimeout(this.pollTimer);
		}
		
		if ((response.type == 'success') && (response.data != undefined) && (response.data.elements))
		{
			var elementsStillPending = false;
			for (elementId in response.data.elements)
			{
				elementResponse = response.data.elements[elementId];
				if (elementResponse.type == 'not_ready')
				{
					elementsStillPending = true;
				}
				else if (elementResponse.type == 'success')
				{
					this.successHandler(elementId, elementResponse);
				}
				else 
				{
					this.errorHandler(elementId, elementResponse);
				}
			}
			
			if (elementsStillPending)
			{
				if (this.pollCounter > 3)
				{
					//if the report can't be rendered in 3 mins, terminate
					this.errorHandler({message: "There was an error generating your report, please try again"});
				}
				else
				{
					var _this = this;
					this.pollCounter++;
					this.pollTimer = setTimeout(function() {
							_this.poll();
						},2000);
				}
			}
		}
		else
		{
			//error receiving batch response, display the error and set the status of all elements to loaded so they stop polling
			this.errorHandler(response);
			jQuery(".mm-report-visual-element").attr("data-status","data-loaded");
		}
	},
	
	/**
	 * successHandler is called when an AJAX response is successful, and is responsible for hiding the loading indicator,
	 * updating the element container with the returned markup, and changing the element status to reflect that data is loaded (which stops the polling mechanism)
	 */
	successHandler: function(elementId, elementResponse)
	{
		if ((elementResponse != undefined) && (elementResponse.message != undefined) && (elementResponse.type == 'success'))
		{
			this.hideLoadingIcon(elementId);
			jQuery("#"+ elementId).html(elementResponse.message).attr("data-status","data-loaded");
		}
	},
	
	/**
	 * errorHandler is called when an AJAX response is an error, and is responsible for hiding the loading icon and displaying 
	 * an error message
	 */
	errorHandler: function(elementId, elementResponse)
	{
		this.hideLoadingIcon(elementId);
		jQuery("#mm-report-errors").html(elementResponse.message).show();
	},
	
	poll: function()
	{
		var action = this.action;
		var module = this.module;
		var method = this.method;
		var values = {};
		var _this  = this;
		
		values.mm_action = "pollReportData"; //the receiving method in the view
		values.reportName = this.reportName; //the report that will process the data
		
		jQuery(".mm-report-visual-element[data-status='loading']").each(function(){
			var elementId = jQuery(this).attr('id');
			var elementWidth = jQuery(this).attr("elementWidth") || '';
			var elementHeight = jQuery(this).attr("elementHeight") || '';
			var paramObject = _this.peekParams(elementId);
			
			if (values.elements == undefined) 
			{
				values.elements = [{"id":elementId, "elementWidth":elementWidth, "elementHeight":elementHeight,"params":paramObject.params,"directives":paramObject.directives}];
			}
			else
			{
				values.elements.push({"id":elementId, "elementWidth":elementWidth, "elementHeight":elementHeight,"params":paramObject.params,"directives":paramObject.directives});
			}
		});
		var ajax = new MM_Ajax(false, module, action, method);
		ajax.useLoader = false;
		ajax.send(values, false, 'mmjs','pollCallbackHandler');
	},
	
	
	showLoadingIcon: function(elementId)
	{
		if (!jQuery("#" + elementId).hasClass("mm-report-loading"))
		{
			jQuery("#" + elementId).fadeOut();
			jQuery("#" + elementId).append("<span class='mm-report-loader-image-helper'></span>");
			jQuery("#" + elementId).addClass("mm-report-loading").fadeIn();
		}
	},
	
	
	hideLoadingIcon: function(elementId)
	{
		jQuery(".mm-report-loader-image-helper").remove();
		jQuery("#" + elementId).removeClass("mm-report-loading");
	},
	
	
	changeDirective: function(elementId, newDirectives)
	{
		var objRef = this.peekParams(elementId);
		for (var key in newDirectives)
		{
			objRef.directives[key] = newDirectives[key];
		}
		
		
		jQuery("#" + elementId).attr('data-status','loading');
		this.showLoadingIcon(elementId);

		this.poll();
	},
	
	
	refreshCallbackHandler: function(response) 
	{
		this.pollCallbackHandler(response);
	},
	
	
	pushParams: function(elementId, params, directives)
	{
		if ((elementId == undefined) || (params == undefined))
		{
			return false;
		}
		
		if (directives == undefined)
		{
			directives = {};
		}
		
		if (this.activeParams[elementId] == undefined)
		{
			this.activeParams[elementId] = [{"params":params,"directives":directives}];
		}
		else
		{
			this.activeParams[elementId].push({"params":params,"directives":directives});
		}
	},
	
	
	popParams: function(elementId)
	{
		if (this.activeParams[elementId] != undefined)
		{
			return this.activeParams[elementId].pop();
		}
	},
	
	
	peekParams: function(elementId)
	{
		if (this.activeParams[elementId] != undefined)
		{
			var len = this.activeParams[elementId].length;
			if (len>0) 
			{
				return this.activeParams[elementId][len-1];
			}
		}
	},
	
	
	navigate: function(elementId, overrideParams)
	{
		//push params, set status to loading, and poll (?)
		var paramObject = jQuery.extend(true,{}, this.peekParams(elementId)); //clone the current params object
		for (var key in overrideParams)
		{
			paramObject.params[key] = overrideParams[key];
		}
		
		this.previousStates[elementId] = jQuery("#" + elementId).html(); //store current DOM element in memory
		jQuery("#" + elementId).attr("data-status","loading");
		this.showLoadingIcon(elementId);
		this.pushParams(elementId, paramObject.params,paramObject.directives);
		this.poll();
	},
	
	
	navigateBack: function(elementId)
	{
		this.popParams(elementId);
		jQuery("#" + elementId).html(this.previousStates[elementId]);
	},
	
	paramsEqual: function(first,second)
	{
	    // if either array is a falsy value, return
	    if (!first || !second)
	    {
	        return false;
	    }
	    
	    // compare lengths - can save a lot of time
	    if (first.length != second.length)
	    {
	    	return false;
	    }

	    for (var i = 0, l=first.length; i < l; i++) 
	    {
	        // Check if we have nested arrays
	        if ((first[i] instanceof Array) && (second[i] instanceof Array)) 
	        {
	            // recurse into the nested arrays
	            if (!first[i].compare(second[i]))
	            {
	                return false;
	            }
	        }
	        else if (first[i] != second[i]) 
	        {
	            // Warning - two different object instances will never be equal: {x:20} != {x:20}
	            return false;
	        }
	    }
	    return true;	
	},
	
	resetForm: function() 
	{
		$("input[type=text], textarea").val("");
	},
	
	
	clearDataCache: function()
	{
		var action = this.action;
		var module = this.module;
		var method = this.method;
		var values = {'mm_action':'clearDataCache','reportName':this.reportName};
		var _this  = this;
		
		var ajax = new MM_Ajax(false, this.module, this.action, this.method);
		ajax.useLoader = false;
	    ajax.send(values, false, 'mmjs','cacheActionCallbackHandler');
	},
	
	
	cacheActionCallbackHandler: function(response)
	{
		if ((response != undefined) && (response.type == 'success'))
		{
			alert("Operation completed successfully");
		}
		else
		{
			alert("There was an error processing your request");
		}
	},
	
	
	_validateStartAndEndDate: function(startDate,endDate)
	{
		 var dateReg = /^[0,1]?\d{1}\/(([0-2]?\d{1})|([3][0,1]{1}))\/(([1]{1}[9]{1}[9]{1}\d{1})|([2-9]{1}\d{3}))$/;
		 startDate = (startDate == undefined)?"start_date":startDate;
		 endDate = (endDate == undefined)?"end_date":endDate;
		 
		 var testValues = {"Start Date":startDate, "End Date":endDate};
		 for (I in testValues)
		 {
			 var testVal = jQuery("#"+testValues[I]).val();
			 if (testVal == "")
			 {
				 alert(I + " is required");
				 return false;
			 }
			 else if(!dateReg.test(testVal)) 
			 {
				 alert(I + " must be in the format mm/dd/yyyy");
				 return false;
			 }
		 }
		 
		 return true;
	}
	
});

/* global event handlers */

jQuery(function() {
	jQuery(".mm-report-submitter").on("click.membermouse-report",function(e) {
		e.preventDefault();
		mmjs.submit();
	});
	
	jQuery(".mm-reporting-datepicker").datepicker();
	
	if (jQuery(".mm-report-visual-element[data-status='loading']").length) {
		mmjs.poll();
	}
});