<script type="text/javascript">
clickbank_selected_site = "<?php echo $p->getSiteName(); ?>";

function validateClickbankProductConfigButton()
{
	var configButtonDisabled = (jQuery("#clickbank_configure_products[disabled]").length > 0);
	var shouldDisableConfigButton = false;

	jQuery(".clickbank_required_for_product_config").each(function() {
		if (jQuery(this).val() == "")
		{
			shouldDisableConfigButton = true;
		}
	});

	if (jQuery("#clickbank_site_name").val() == "repopulate_from_server")
	{
		shouldDisableConfigButton = true;
	}
	
	if (!configButtonDisabled && shouldDisableConfigButton)
	{
		jQuery("#clickbank_configure_products").attr("disabled","disabled");
	}
	else if (!shouldDisableConfigButton && configButtonDisabled)
	{
		jQuery("#clickbank_configure_products").removeAttr("disabled");
	}
}


function clickbankPopulateSites(data)
{
	if ((data.type != 'error') && (data.message) && (data.message.length > 0))
	{
		var defaultOption = jQuery('<option></option>').attr("value", "repopulate_from_server").text("<Click here to retrieve site list from ClickBank>");
		//jQuery("#clickbank_site_name").empty().append(defaultOption);
		jQuery("#clickbank_site_name").empty();
		jQuery.each(data.message,function(key,value) {
			if (value == clickbank_selected_site)
			{
				jQuery("#clickbank_site_name").append(jQuery("<option></option>")
					     .attr("value", value).attr('selected','selected').text(value));
			}
			else
			{
				jQuery("#clickbank_site_name").append(jQuery("<option></option>")
				     .attr("value", value).text(value));
			}
		});
		validateClickbankProductConfigButton();
	}
	else
	{
		var errorMessage = "Error retrieving ClickBank sites";
		if (data.message)
		{
			errorMessage += ": " + data.message;
		}
		alert(errorMessage);
	}	
}

function clickbankRetrieveProducts(data)
{
	if ((data.type != 'error') && (data.message))
	{
		if(jQuery("#clickbank-configure-products-dialog").length == 0)
		{
			jQuery("<div id='clickbank-configure-products-dialog'></div>").hide().appendTo("body").fadeIn();
		}
		jQuery("#clickbank-configure-products-dialog").html(data.message).dialog({
			width: 600,
            height: 500,
            modal: true,
		}).dialog('open');
	}
	else
	{
		alert("Error retrieving ClickBank products");
	}
}

function saveClickbankProductMappings()
{
	values = jQuery("#mm-clickbank-configure-products-container :input").serializeArray();
	values.push({name:"payment_service[clickbank][command]", value:"save_product_mappings"});
	pymtSettings_js.performIntermediateAction(values,'clickbank','clickbankReportSaveStatus');
}

function clickbankReportSaveStatus(data)
{
	if ((data.type != 'error'))
	{
		alert("Saved ClickBank product mappings successfully");
	}
	else
	{
		errMsg = "Error saving ClickBank product mappings";
		if (data.message)
			errMsg += (":" + data.message);
		alert(errMsg);
	}

	if (jQuery('#clickbank-configure-products-dialog').length > 0)
		jQuery('#clickbank-configure-products-dialog').dialog('close');
}

jQuery(function($){
	//set initial button state
	validateClickbankProductConfigButton();

	//dynamically modify button state based on field contents
	$(".clickbank_required_for_product_config").keyup(validateClickbankProductConfigButton);
	$("#clickbank_site_name").on("change",validateClickbankProductConfigButton());
	
	$("#clickbank_site_name").click(function(e) {
		if ($("#clickbank_site_name").val() == "repopulate_from_server")
		{
			values = jQuery("#payment_service_clickbank :input").serializeArray();
			values.push({name:"payment_service[clickbank][command]", value:"populate_sites"});
			pymtSettings_js.performIntermediateAction(values,'clickbank','clickbankPopulateSites');
		}
	});

	$("#clickbank_configure_products").click(function(e) {
		e.preventDefault();
		values = jQuery("#payment_service_clickbank :input").serializeArray();
		values.push({name:"payment_service[clickbank][command]", value:"retrieve_products"});
		pymtSettings_js.performIntermediateAction(values,'clickbank','clickbankRetrieveProducts');
	});
		
});
</script>

<div style="padding:10px;">
<img src='https://dl.dropboxusercontent.com/u/265387542/plugin_images/logos/clickbank-logo.png' />

<div style="margin-top:5px; margin-bottom:10px;">
<a href='https://membermouse.uservoice.com/knowledgebase/articles/317013-configuring-clickbank' target='_blank'>Need help configuring ClickBank?</a>
</div>

<div style="margin-bottom:10px;">
	Clerk API Key
	
	<p style="margin-left:10px; font-family:courier; font-size:11px;">
		<input type='text' value='<?php echo $p->getAPIClerkKey(); ?>' id='clickbank_api_clerk_key' class='clickbank_required_for_product_config' name='payment_service[clickbank][api_clerk_key]' style='width: 275px;' />
	</p>
</div>

<div style="margin-bottom:10px;">
	Developer API Key
	
	<p style="margin-left:10px; font-family:courier; font-size:11px;">
		<input type='text' value='<?php echo $p->getAPIDeveloperKey(); ?>' id='clickbank_api_developer_key' class='clickbank_required_for_product_config' name='payment_service[clickbank][api_developer_key]' style='width: 275px;' />
	</p>
</div>

<div style="margin-bottom:10px;">
	Site Name
	
	<p style="margin-left:10px; font-family:courier; font-size:11px;">
		<select name='payment_service[clickbank][site_name]' id='clickbank_site_name'>
			<option value='repopulate_from_server'>&lt;Click here to retrieve site list from ClickBank&gt;</option>
			<?php if ($p->getSiteName() != "") { ?>
			<option SELECTED><?php echo $p->getSiteName(); ?></option>
			<?php } ?>
		</select>
	</p>
</div>

<div style="margin-bottom:10px;">
	Secret Key
	
	<p style="margin-left:10px; font-family:courier; font-size:11px;">
		<input type='text' value='<?php echo $p->getSecretKey(); ?>' id='clickbank_secret_key' class='clickbank_required_for_product_config' name='payment_service[clickbank][secret_key]' style='width: 275px;' />
	</p>
</div>

<div style="margin-bottom:10px;">
	Notification URL
	
	<p style="margin-left:10px;">
		<?php $ipnUrl = MM_PLUGIN_URL."/x.php?service=clickbank"; ?>
		
		<span style="font-family:courier; font-size:11px; margin-top:5px;">
			 <input id="mm-clickbank-ipn-url" type="text" value="<?php echo $ipnUrl; ?>" style="width:550px; background-color:#fff;" readonly onclick="jQuery('#mm-clickbank-ipn-url').focus(); jQuery('#mm-clickbank-ipn-url').select();" />
		</span>
	</p>
	
	<p style="font-size:11px; margin-left:10px; padding-right:20px;">
	<?php echo MM_Utils::getInfoIcon("", ""); ?>
	ClickBank uses instant notifications to inform 3rd party systems when events happen within ClickBank
	such as successful payments, subscription cancellations, etc. MemberMouse keeps member accounts in sync with ClickBank by listening 
	for these notifications. In order for this to work, you must register the notification URL above with ClickBank.</p>
</div>

<div style="margin-bottom:10px;">
	Confirmation Page URL
	
	<p style="margin-left:10px;">
		<?php $confirmationUrl = MM_PLUGIN_URL."/x.php?service=clickbank&op=conf"; ?>
		
		<span style="font-family:courier; font-size:11px; margin-top:5px;">
			 <input id="mm-clickbank-confirmation-page-url" type="text" value="<?php echo $confirmationUrl; ?>" style="width:550px; background-color:#fff;" readonly onclick="jQuery('#mm-clickbank-confirmation-page-url').focus(); jQuery('#mm-clickbank-confirmation-page-url').select();" />
		</span>
	</p>
	
	<p style="font-size:11px; margin-left:10px; padding-right:20px;">
	<?php echo MM_Utils::getInfoIcon("", ""); ?>
	For each product in ClickBank you need to specify a confirmation page to redirect to following a successful purchase. To ensure that customers are redirected to the 
	the appropriate confirmation page within your membership site, set the confirmation page URL for each product in ClickBank to the URL above.</p>
</div>

<div style="margin-bottom:10px;">
	Product Mappings
	
	<p style="margin-left:10px;">
		<button id='clickbank_configure_products' class="mm-ui-button">Configure ClickBank Products</button>
	</p>
	
	<p style="font-size:11px; margin-left:10px; padding-right:20px;">
	<?php echo MM_Utils::getInfoIcon("", ""); ?>
	All products that are purchased through ClickBank are defined in ClickBank. In order for MemberMouse to know how what to do when a 
	ClickBank product is purchased, you need to map it to a MemberMouse product.</p>
</div>

<div style="margin-bottom:10px;">
	Fallback Confirmation Page (<em>optional</em>)
	
	<p style="margin-left:10px; font-family:courier; font-size:11px;">
		<input type='text' value='<?php echo $p->getFallbackConfirmationPage(); ?>' id='clickbank_fallback_confirmation_page' name='payment_service[clickbank][fallback_confirmation_page]' style='width: 550px;' />
	</p>
	
	<p style="font-size:11px; margin-left:10px; padding-right:20px;">
	<?php echo MM_Utils::getInfoIcon("", ""); ?>
	As a result of how ClickBank notifies MemberMouse of new purchases, a situation can occur when the customer's account is in the process of 
	being created at the same time that MemberMouse needs to look up the account in order redirect them to the 
	appropriate confirmation page. In this case, since the account is in the process of being created, the look up fails and MemberMouse 
	doesn&#39;t know where to redirect the customer. This is when the fallback confirmation page is used. 
	Read this article to <a href="https://membermouse.uservoice.com/knowledgebase/articles/319045-create-a-fallback-confirmation-page-for-clickbank" target="_blank">learn more about creating a fallback confirmation page</a>.</p>
</div>
</div>