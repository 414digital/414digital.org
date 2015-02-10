/*!
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */
var MM_AccessRightsView = MM_Core.extend({
  
  /** DATA GRID FUNCTIONS **/
  refreshGrid: function(sortBy, sortDir)
  {
    var values = {
      post_ID: jQuery("#post_ID").val(),
      post_type: jQuery("#post_type").val(),
    };
    
    var module = "MM_AccessRightsView";
    var method = "refreshMetaBox";
    var action = 'module-handle';
    var ajax = new MM_Ajax(false, module, action, method);
    ajax.send(values, false, 'accessrights_js','listCallback'); 
  },
  
  showError: function(str)
  {
      alert(str);
  },
  
  validate: function()
  {
    var day = "";
    var type_dd = "";
    var type_error = "";
    if(jQuery("#access_rights_container_at_table").is(':hidden')) 
    {
        type_dd =jQuery("#mm_member_types_opt").val();
        day = jQuery("#mt_day").val();
         type_error = "Membership Levels not defined.";
    }
    else
    {
         type_error = "Bundles not defined.";
        type_dd =jQuery("#mm_access_tags_opt").val(); 
        day = jQuery("#at_day").val();
    }
     if(type_dd =="" || type_dd<=0)
     {
        this.showError(type_error);
        return false;
    }
      var reg = new RegExp("^[0-9]+$");
      if(!reg.test(day))
      {
        this.showError("Days field must be greater than or equal to 0");
        return false;
      }    
      return true;
  },
  
  /** DATABASE FUNCTIONS **/
  save: function() 
  {
      var form_obj = new MM_Form('mm-access_container_div');
      var values = form_obj.getFields();
      values['post_ID'] = jQuery("#post_ID").val();
      values['type'] = 'access_tag';

      if(jQuery("#access_rights_container_at_table").is(':hidden')) 
      {
          values['day'] = jQuery("#mt_day").val();
          values['type'] = 'member_type';
      }
      else
      {
        values['day'] = jQuery("#at_day").val();
      }
      
      values.mm_action = "save";
      if(!this.validate()) 
      {
        return false;
      }
      
      var module = "MM_AccessRightsView";
      var method = "performAction";
      var action = 'module-handle';
      var ajax = new MM_Ajax(false, module, action, method);
      ajax.send(values, false, 'accessrights_js','saveCallback'); 
  },
  
  edit: function(dialogId, access_id, access_type)
  {
	var values = {};
	values.post_ID=jQuery("#post_ID").val();
	values.access_type = access_type;
	values.access_id = access_id;
	mmdialog_js.method = 'editAccessRight';
	mmdialog_js.showDialog(dialogId, this.module, 420, 250, "Edit "+this.entityName,values);
  },
  
  remove: function(access_id, access_type)
  {
    var removeOk = confirm("Are you sure you want to remove this access right?");
    if(removeOk)
    {
        var values = {
            access_id: access_id,
            access_type: access_type,
            post_ID: jQuery("#post_ID").val(),
        };
        var module = "MM_AccessRightsView";
        var method = "removeAccessRights";
        var action = 'module-handle';
        var ajax = new MM_Ajax(false, module, action, method);
        ajax.send(values, false, 'accessrights_js','removeCallback'); 
    }
  },
  
  removeCallback: function(data)
  {
    if(data.type=='error')
    {
    	alert(data.message);
        return false;
    }
    this.refreshGrid();
  },

  listCallback: function(data)
  {
	  if(data.type=='error')
	  {
		  alert(data.message);  
	  }
	  else
	  {
		  jQuery("#mm_publish_box").html(data.message);
	  }
 	  this.closeDialog();
  },
  
  saveCallback: function(data)
  {
	  if(data.type=='error')
	  {
		alert(data.message);  
	  }
	  else
	  {
          this.refreshGrid(); 
	  }
   },
   optionsCallback: function(data)
   {
	   if(data.type=='error')
	   {
		   alert(data.message);
	   }
	   else
	   {
			if(jQuery("#access_rights_container_at_table").is(':hidden')) 
			{
			    jQuery("#mm_member_types_opt").find('option').remove().end().append(data.message);
			}
			else
			{
			    jQuery("#mm_access_tags_opt").find('option').remove().end().append(data.message);
			}
	   }
   },
   
   /** DIALOG FUNCTIONS **/
   showOptions: function(id, access_type)
   {    
        var rights = ""; //(access_type!='')?access_type:jQuery("#access_rights_choice").val();
        if(access_type=="member_type")   
            rights = 'mt';
        else if(access_type == "access_tag")
            rights = 'at';
        else
            rights = jQuery("#access_rights_choice").val();
            
        var module = "MM_AccessRightsView";
        var action = 'module-handle';
        var method = "getAccessRightsOptions";
        var values = {
            id:id,
            type:rights, 
            post_ID:jQuery("#post_ID").val(),
        };  
        
        if(rights=='mt')
        {
            jQuery("#access_rights_container_at_table").hide();
            jQuery("#access_rights_container_mt_table").show();
        }   
        else
        {
            jQuery("#access_rights_container_at_table").show();
            jQuery("#access_rights_container_mt_table").hide();
        }   
        
        var ajax = new MM_Ajax(false, module, action, method);
        ajax.send(values, false, 'accessrights_js','optionsCallback'); 
   }
});

var accessrights_js = new MM_AccessRightsView("MM_AccessRightsView", "Access Rights");

