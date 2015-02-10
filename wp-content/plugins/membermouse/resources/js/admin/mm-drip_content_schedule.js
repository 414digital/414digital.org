/*!
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */
var MM_DripContentScheduleJS = MM_Core.extend({
	verifyCopy: function()
	{
		var areYouSure = confirm("Are you sure you want to copy the schedule?");
		if(areYouSure){
			return true;
		}
		return false;
	},
	
	createDialogDiv: function(id)
	{	
		jQuery("<div id=\""+id+"\"></div>").hide().appendTo("body").fadeIn();
	},
	
	saveAccessRight:function()
	{
		var post = jQuery("#mm-gar-post").val();
		var page = jQuery("#mm-gar-page").val();
		var day = jQuery("#mm-gar-day").val();
		
		if(day=='' || day<0)
		{
			alert("Invalid day specified.");
			return false;
		}
		
		var values =  {};
		values.post_id = 0;
		if(this.isTypePost())
		{
			values.post_id = post;
		}
		else
		{
			values.post_id = page;
		}
		values.day = day;
		values.access_id = jQuery("#mm_access_id").val();
		values.access_type = jQuery("#mm_access_type").val();

	    values.mm_action = "addAccessRights";
	    
	    var module = this.module;
	    var method = "performAction";
	    var action = 'module-handle';
	      
	    var ajax = new MM_Ajax(false, module, action, method);
	    ajax.send(values, false, 'mmjs','saveAccessRightsCallback');
	},
	
	saveAccessRightsCallback: function(data)
	{
		var dialogId ="mm-dsm-container";
		mmdialog_js.close(dialogId);

		if(data.type != 'error')
		{
			jQuery("#mm_dsm_form_tag").submit();
		}
		else
		{
			alert(data.message);
		}
	},
	
	isTypePost: function()
	{
		if(jQuery("#mm-gar-page-type-post").is(":checked"))
		{
			return true;
		}
		
		return false;
	},
	
	isTypePage: function()
	{
		if(jQuery("#mm-gar-page-type-post").is(":checked"))
		{
			return false;
		}
		
		return true;
	},
	
	onTypeChange: function()
	{
		if(!this.isTypePost()) 
		{
			jQuery("#mm-gar-post").attr('disabled','disabled');
			jQuery("#mm-gar-page").removeAttr('disabled');
		}
		else
		{
			jQuery("#mm-gar-post").removeAttr('disabled');
			jQuery("#mm-gar-page").attr('disabled','disabled');
		}
	},
	
	saveManualAccessRight: function(accessId, accessType, postId, day, cellId, accessTypeName, pageName)
	{
		var areYouSure = confirm("Are you sure you want to grant '"+accessTypeName+"' members access to '"+pageName+"' on day "+day+"?");
		
		if(areYouSure)
		{
			var values =  {};
			values.day = day;
			values.access_id = accessId;
			values.access_type = accessType;
			values.post_id = postId;
			values.cell_id = cellId;
			values.mm_action = "addAccessRights";
		    
		    var module = this.module;
		    var method = "performAction";
		    var action = 'module-handle';
		      
		    var ajax = new MM_Ajax(false, module, action, method);
		    ajax.send(values, false, 'mmjs','manualUpdateCallback');
		}
		
	},
	
	manualUpdateCallback: function(data)
	{
		if(data.type!='error')
		{
			jQuery("#mm_dsm_form_tag").submit();
		}
		else
		{
			alert(data.message);
		}
	},
	
	updateAccessRights: function()
	{
		var accessId = jQuery("#mm_access_id").val();
		var accessType = jQuery("#mm_access_type").val();
		var postId = jQuery("#mm_post_id").val();
		var day = jQuery("#mm_gar_day").val();
		
		if(day<0 || day==''){
			alert("Day must be greater or equal to 0.");
			return false;
		}

		var values =  {};
		values.should_remove = 0;
		if(jQuery("#mm-gar-remove").is(":checked")){
			values.should_remove = 1;
		}
		
		values.day = day;
		values.access_id = accessId;
		values.access_type = accessType;
		values.post_id = postId;

	    values.mm_action = "updateAccessRights";
	    
	    var module = this.module;
	    var method = "performAction";
	    var action = 'module-handle';
	      
	    var ajax = new MM_Ajax(false, module, action, method);
	    ajax.send(values, false, 'mmjs','updateAccessRightsCallback');
	},
	
	updateAccessRightsCallback: function(data)
	{
			var dialogId ="mm-dsm-container";
			mmdialog_js.close(dialogId);
			if(data.type!='error')
			{
				jQuery("#mm_dsm_form_tag").submit();
			}
	},
	
	updateAccessRightsDialog: function(accessId, accessType, postId, day)
	{
		var dialogId ="mm-dsm-container";
		this.createDialogDiv(dialogId);
		
		jQuery("#"+dialogId).dialog({autoOpen: false});
		var values =  {};
		values.access_id = accessId;
		values.access_type = accessType;
		values.post_id = postId;
		values.day = day;
		values.mm_action = "updateAccessRightsDialog";
		
		mmdialog_js.showDialog(dialogId, this.module, 440, 200, "Access Rights", values);
	},
	
	addAccessRights: function(type, typeName)
	{	
		var dialogId ="mm-dsm-container";
		this.createDialogDiv(dialogId);
		
		jQuery("#"+dialogId).dialog({autoOpen: false});
		var values =  {};
		values.type = type;
		values.type_name = typeName;
		values.mm_action = "accessRightsDialog";
		
		mmdialog_js.showDialog(dialogId, this.module, 420, 300, "Access Rights", values);
	},
	
	
	/** 
	 * Drip Content Schedule Form Functions
	 */
	expandRows: function(totalRows, totalColumns)
	{
	   for(j=1; j<=totalRows; j++){
		   this.expandRow(j,totalColumns);
		   this.showCollapseImage(j);
	   }
	},
	
	collapseRows: function(totalRows, totalColumns)
	{
		for(j=1; j<=totalRows; j++){
			this.collapseRow(j,totalColumns);
			this.showExpandImage(j);
		}
	},
	
	showCollapseImage: function(row)
	{
		var image = jQuery("#mm-dsm-row"+row+"col0-image").attr("src");
		if(image !=undefined)
		{
			image = image.replace("expand", "collapse");
			jQuery("#mm-dsm-row"+row+"col0-image").attr("src", image);
		}
	},
	
	showExpandImage: function(row)
	{
		var image = jQuery("#mm-dsm-row"+row+"col0-image").attr("src");
		if(image !=undefined)
		{
			image = image.replace("collapse", "expand");	
			jQuery("#mm-dsm-row"+row+"col0-image").attr("src", image);
		}
	},
	
	toggleRow: function(row, totalRows)
	{
		if(jQuery("#mm-dsm-row"+row+"col1-expanded").is(":visible")){
			this.collapseRow(row,totalRows);
			this.showExpandImage(row);
		}
		else
		{
			this.expandRow(row,totalRows);
			this.showCollapseImage(row);
		}
	},
	
	clearCache: function()
	{
		jQuery("#mm-expanded-rows").val("");
		jQuery("#mm-expanded-rows-copy").val("");
	},
	
	resizeDaysColumn: function(row, col, type)
	{
		var rowHeight = jQuery("#mm-dsm-row"+row+"col"+col+"-"+type).height();
		jQuery('#mm-dsm-row'+row+'col0').css('height',rowHeight);
	},
	
	cacheExpandedRow: function(row)
	{
		var day = jQuery("#row-"+row).val();
		
		var cache = jQuery("#mm-expanded-rows").val();
		if(cache.length>0)
		{
			jQuery("#mm-expanded-rows").val(cache+","+day);
			jQuery("#mm-expanded-rows-copy").val(cache+","+day);
		}
		else
		{
			jQuery("#mm-expanded-rows").val(day);
			jQuery("#mm-expanded-rows-copy").val(day);
		}
	},
	
	removeExpandedCacheRow: function(row)
	{
		var day = jQuery("#row-"+row).val();
		var cache = jQuery("#mm-expanded-rows").val();
		var arr = cache.split(",");
		var newCache = "";
		for(i=0; i<arr.length; i++)
		{
			if(arr[i]!=day && arr[i] != "")
			{
				newCache+=arr[i]+",";
			}
		}
		jQuery("#mm-expanded-rows").val(newCache);
		jQuery("#mm-expanded-rows-copy").val(newCache);
	},
	
	expandRow: function(row,totalColumns)
	{
		this.cacheExpandedRow(row);
		for(i=1; i<=totalColumns+2; i++)
		{
		  jQuery("#mm-dsm-row"+row+"col"+i+"-expanded").show();
		  jQuery("#mm-dsm-row"+row+"col"+i+"-collapsed").hide();
		}
		this.resizeDaysColumn(row, 1, 'expanded');
	},
	
	collapseRow: function(row,totalColumns)
	{
		this.removeExpandedCacheRow(row);
		for(i=1; i<=totalColumns+2; i++)
		{
		   jQuery("#mm-dsm-row"+row+"col"+i+"-expanded").hide();
		   jQuery("#mm-dsm-row"+row+"col"+i+"-collapsed").show();
		}
		this.resizeDaysColumn(row, 1, 'collapsed');
	},
	 
});

var expandTracker = new Array();
var mmjs = new MM_DripContentScheduleJS("MM_DripContentScheduleView", "Drip Content Schedule");