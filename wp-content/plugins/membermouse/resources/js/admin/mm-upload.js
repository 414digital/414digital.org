/*!
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */
function ajaxFileUpload(options)
{    		    
	if (options.lock) {
		doAjaxLock( options.lock );	
	}
    var ajaxurl = 'admin-ajax.php';
    var data = {
                action: "member-types",
                module: "MM_MembershipLevel",
                method: "uploadFile"
    };
    var xhr = jQuery.ajaxFileUpload
    (
        {
            url:			ajaxurl, 			// PHP file url
            secureuri:		false,
            data: data,
            fileElementId:	options.fileElementId,  // ID of input element from page
            dataType: 		options.dataType || 'json',
            success: 		function(data, status)
            				{
            					// Process messages
								if (data.messages)
								{
		   							jQuery(data.messages).each(function(){
		   								alert(this);
		   							});
								}
		   						
								// Process errors
								if (data.errors) 
								{
		   							jQuery(data.errors).each(function(){
		   								alert(__('Error: ') + this);
		   							});
		   							
		   							if (options.onError)
					   					options.onError(data.errors);
								}
								
		   						// Process redirect
		   						if (data.redirect)
		   						{	
		   							// and do redirect.
		   							alert( sprintf( __('Session expired. You will be redirected to "%s" page.'), data.redirect) );
		   							document.location = data.redirect;
		   							return;
		   						}
		   						
		   						// Process data
		   						if (options.onSuccess && !data.errors)
					   				options.onSuccess(data, status);
					   				
					   			// Unlock
					   			if (options.lock)
									doAjaxUnlock( options.lock );
									
            				},            				
            error: 			function(data, status, e)
            				{
            					// Process error 
            					alert(__('Ajax upload error: ') + e);							
            					
            					// Unlock
					   			if (options.lock)
									doAjaxUnlock( options.lock );
            				}            				
        }
    )
    
    // Add options
	var request = {	
		xhr: 		xhr,
		options: 	options	
	};

	return request;
}  