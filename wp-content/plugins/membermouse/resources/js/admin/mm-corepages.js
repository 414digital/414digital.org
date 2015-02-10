/*!
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */
var MM_CorePagesViewJS = MM_Core.extend({
  
  /**** UI Arrangement & Dialog *****/
  updateElements: function(should_hide, ref_id)
  {
    if(should_hide)
    {
       jQuery("#core_page_type_id").attr("disabled","");  
       jQuery("#core_page_type_id").val("");
       
       if(ref_id!=undefined)
            jQuery("#core_page_type_id option[value='"+ref_id+"']").remove();
       
       jQuery("#default_core_page").hide();
       jQuery("#default_core_page_icon").hide();
    }
  },
  
  checkAccessRights: function()
  {
    if(jQuery("#core_page_type_id").val() > 0)
    {
        jQuery("#mm_access_rights_meta").hide();
    }
    else
    {
        jQuery("#mm_access_rights_meta").show();
    }
  },
  
  showMessage: function(str)
  {
    jQuery("#message").html(str);
  },
  
  /**** END UI Arrangement *****/
  
  /***** Callbacks *****/
  corePageSelectionCallback: function(data)
  {
  	if(data.type=='error')
  	{
  		alert(data.message);
  	}
  	else
  	{
      jQuery("#subtypes").find("tr").remove().end();
      
      if(data.message.content != undefined){
    	  jQuery("#subtypes").append(data.message.content);
      }
      else
      {
    	  jQuery("#subtypes").append(data.message);
      }
      this.checkAccessRights();
    }
  },
  
  updateCorePageCallback: function(data)
  {
    if(data.type=='error')
    {
        alert(data.message);   
    }
    else
    {
       jQuery("#mm_access_rights_meta").show();
       this.showMessage("You have successfully re-assigned the core page.");
       jQuery("#core_page_type_id").attr("disabled","");
       this.updateElements(true); 
       this.closeDialog();
    }
  },
  /***** END Callbacks *****/

  
  /** DATABASE FUNCTIONS **/
  
  updateCorePage: function()
  {
      var form_obj = new MM_Form('mm-adminpreview');
      var values = form_obj.getFields();
      values.post_ID = jQuery("#post_ID").val();
      values.new_page_id = jQuery("#new_page_id").val();
      
      var module = "MM_CorePagesView";
      var method = "changeDefaultPage";
      var action = 'module-handle';
      
      var ajax = new MM_Ajax(false, module, action, method);
      ajax.send(values, false, 'corepages_js','updateCorePageCallback');
  },
  
  getReferences: function(isConfFree)
  {
    var isFree = '';
	if(isConfFree!=undefined)
	{
		isFree= isConfFree;
	}
	
    var do_corepage = true;
    if(!jQuery("#mm_access_rights_meta").is(":hidden") && jQuery("#has_access_rigths").length && jQuery("#core_page_type_id").val()!='')
    {
        do_corepage = confirm("If you save this page as a core page you will remove any access rights associated with it. Do you want to continue?");
    }
    if(jQuery("#core_page_type_id").val()=="")
    {
        jQuery("#subtypes").find("tr").remove().end();
        do_corepage = false;
    }
    
    if(do_corepage)
    {
        if(isFree == '' && jQuery("#is_free").length)
        {
        	isFree = jQuery("#is_free").val();
        }
        
        var module = "MM_CorePagesView";
        var action = 'module-handle';
        var method = "getOptionsByCorePageType";
        var values = {
            post_ID:jQuery("#post_ID").val(),
            core_page_type_id:jQuery("#core_page_type_id").val(),
            is_free: isFree,
        };  
        var ajax = new MM_Ajax(false, module, action, method);
        ajax.send(values, false, 'corepages_js','corePageSelectionCallback'); 
    }
    else
    {
        jQuery("#core_page_type_id").val(''); 
        this.checkAccessRights();   
    }
  },
});

var corepages_js = new MM_CorePagesViewJS("MM_CorePagesView", "Core Pages");

