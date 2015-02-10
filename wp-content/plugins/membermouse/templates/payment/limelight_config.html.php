<script type="text/javascript">
function validateLimeLightConfigButtons()
{
	var configButtonDisabled = (jQuery("#limelight_configure_products[disabled]").length > 0);
	var shouldDisableConfigButton = false;

	jQuery(".limelight_required_for_product_config").each(function() {
		if (jQuery(this).val() == "")
		{
			shouldDisableConfigButton = true;
		}
	});
	
	if (!configButtonDisabled && shouldDisableConfigButton)
	{
		jQuery("#limelight_configure_products").attr("disabled","disabled");
		jQuery("#limelight_configure_shipping_methods").attr("disabled","disabled");
	}
	else if (!shouldDisableConfigButton && configButtonDisabled)
	{
		jQuery("#limelight_configure_products").removeAttr("disabled");
		jQuery("#limelight_configure_shipping_methods").removeAttr("disabled");
	}
}

jQuery(function($){
	//set initial button state
	validateLimeLightConfigButtons();

	//dynamically modify button state based on field contents
	$(".limelight_required_for_product_config").keyup(validateLimeLightConfigButtons);

	$("#limelight_configure_products").click(function(e) {
		e.preventDefault();
		window.location.href = "<?php echo MM_ModuleUtils::getUrl(MM_MODULE_PRODUCT_SETTINGS, MM_MODULE_LIMELIGHT_PRODUCTS); ?>";
	});

	$("#limelight_configure_shipping_methods").click(function(e) {
		e.preventDefault();
		window.location.href = "<?php echo MM_ModuleUtils::getUrl(MM_MODULE_PRODUCT_SETTINGS, MM_MODULE_LIMELIGHT_SHIPPING_METHODS); ?>";
	});
});
</script>

<div style="padding:10px;">
<img src='https://dl.dropboxusercontent.com/u/265387542/plugin_images/logos/limelight.png' />

<div style="margin-top:5px; margin-bottom:10px;">
<a href='https://membermouse.uservoice.com/knowledgebase/articles/319049-configuring-lime-light' target='_blank'>Need help configuring Lime Light?</a>
</div>

<div style="margin-bottom:10px;">
	Lime Light URL
	
	<p style="margin-left:10px; font-family:courier; font-size:11px;">
		<input type='text' value='<?php echo $p->getURL(); ?>' id='limelight_url' class='limelight_required_for_product_config' name='payment_service[limelight][url]' style='width: 275px;' />
	</p>
</div>

<div style="margin-bottom:10px;">
	API Username
	
	<p style="margin-left:10px; font-family:courier; font-size:11px;">
		<input type='text' value='<?php echo $p->getAPIUsername(); ?>' id='limelight_api_username' class='limelight_required_for_product_config' name='payment_service[limelight][api_username]' style='width: 275px;' />
	</p>
</div>

<div style="margin-bottom:10px;">
	API Password
	
	<p style="margin-left:10px; font-family:courier; font-size:11px;">
		<input type='text' value='<?php echo $p->getAPIPassword(); ?>' id='limelight_api_password' class='limelight_required_for_product_config' name='payment_service[limelight][api_password]' style='width: 275px;' />
	</p>
</div>

<div style="margin-bottom:10px;">
	Success Location URL
	
	<p style="margin-left:10px;">
		<?php $confirmationUrl = MM_PLUGIN_URL."/x.php?service=limelight&op=conf"; ?>
		
		<span style="font-family:courier; font-size:11px; margin-top:5px;">
			 <input id="mm-limelight-confirmation-page-url" type="text" value="<?php echo $confirmationUrl; ?>" style="width:550px; background-color:#fff;" onclick="jQuery('#mm-limelight-confirmation-page-url').focus(); jQuery('#mm-limelight-confirmation-page-url').select();" readonly />
		</span>
	</p>
	
	<p style="font-size:11px; margin-left:10px; padding-right:20px;">
	<?php echo MM_Utils::getInfoIcon("", ""); ?>
	(<em>optional</em>) For each campaign in Lime Light you need to specify a success location to redirect to following a successful purchase. To ensure that customers are redirected to the 
	the appropriate confirmation page within your membership site, set the success location URL for each campaign in Lime Light to the URL above.</p>
</div>

<div style="margin-bottom:10px;">
	Product Mappings
	
	<p style="margin-left:10px;">
		<button id='limelight_configure_products' class="mm-ui-button">Configure Lime Light Products</button>
	</p>
	
	<p style="font-size:11px; margin-left:10px; padding-right:20px;">
	<?php echo MM_Utils::getInfoIcon("", ""); ?>
	Lime Light and MemberMouse both define products. Products in MemberMouse need to be mapped to products in Lime Light so that when a product is
	purchased in Lime Light, it can be mapped to a MemberMouse product and the appropriate actions can be taken. It also makes it so that when a
	product is purchased in MemberMouse, it can be mapped to the appropriate Lime Light product so the appropriate amount is charged to the customer.</p>
</div>

<div style="margin-bottom:10px;">
	Shipping Method Mappings
	
	<p style="margin-left:10px;">
		<button id='limelight_configure_shipping_methods' class="mm-ui-button">Configure Lime Light Shipping Methods</button>
	</p>
	
	<p style="font-size:11px; margin-left:10px; padding-right:20px;">
	<?php echo MM_Utils::getInfoIcon("", ""); ?>
	Lime Light and MemberMouse both define shipping methods. Shipping methods in MemberMouse need to be mapped to shipping methods in Lime Light 
	so that when a shippable product is purchased in Lime Light, the shipping method can be mapped to a MemberMouse shipping method. 
	It also makes it so that when a shippable product is purchased in MemberMouse, the shipping method can be mapped to the appropriate Lime Light shipping method 
	so the appropriate amount is charged to the customer for shipping.</p>
</div>
</div>