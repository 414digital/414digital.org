<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery("#chargify_subdomain").change(chargifyCheckCredentials);
		jQuery("#chargify_api_key").change(chargifyCheckCredentials);
		jQuery("#chargify_product_family_select").change(function() { chargifyDisplaySyncButton(true);});
		jQuery("#chargify_synchronize_products_button").click(chargifySynchronizeProducts);
		chargifyCheckCredentials();
	});
	
	function chargifyCheckCredentials()
	{
		if ((jQuery("#chargify_subdomain").val() != "") && (jQuery("#chargify_api_key").val() != ""))
		{
			values = jQuery("#payment_service_chargify :input").serializeArray();
			mmjs.performIntermediateAction(values,'chargify','chargifyShowProductFamily');
		}
		else 
		{
			jQuery("#chargify_product_family_section").hide();
			chargifyDisplaySyncButton(false);
		}
	}

	function chargifyShowProductFamily(data)
	{
		if ((data.type != 'error') && (data.message))
		{
			jQuery("#chargify_product_family_section").show();
			jQuery("#chargify_product_family_select").html("<option></option>" + data.message);
			chargifyDisplaySyncButton(true);
		}
		else
		{
			jQuery("#chargify_product_family_select").html("");
			jQuery("#chargify_product_family_section").hide();
			chargifyDisplaySyncButton(false);
		}
	}

	function chargifyDisplaySyncButton(show)
	{
		if(typeof show !== 'undefined')
		{
			if (jQuery("#chargify_product_family_select").val())
			{
				jQuery("#chargify_synchronize_products_button").removeAttr('disabled');
			}
			else
			{
				jQuery("#chargify_synchronize_products_button").attr('disabled','disabled');
			}

			if (show === true)
			{
				jQuery("#chargify_synchronize_products").show();
			}
			else
			{
				jQuery("#chargify_synchronize_products").hide();
			}
		}
	}

	function chargifySynchronizeProducts(e)
	{
		alert('not implemented yet!');
		e.preventDefault();
	}
</script>
<table>
	<tr>
		<td colspan='2'>
			<img src='https://dl.dropboxusercontent.com/u/265387542/plugin_images/logos/chargify.png' />
		</td>
	</tr>
	<tr>
		<td>
			Subdomain
		</td>
		<td>
			<input type='text' value='<?php echo $p->getSubdomain(); ?>' id='chargify_subdomain' name='payment_service[chargify][subdomain]' style='width: 275px;' />
		</td>
	</tr>
	<tr>
		<td>
			API Key
		</td>
		<td>
			<input type='text' value='<?php echo $p->getApiKey(); ?>' id='chargify_api_key' name='payment_service[chargify][api_key]' style='width: 275px;' />
		</td>
	</tr>
	<tr>
		<td>
			Shared Key
		</td>
		<td>
			<input type='text' value='<?php echo $p->getSharedKey(); ?>' id='chargify_shared_key' name='payment_service[chargify][shared_key]' style='width: 275px;' />
		</td>
	</tr>
	<tr style='display:none' id="chargify_product_family_section">
		<td>
			Product Family
		</td>
		<td>
			<select id='chargify_product_family_select' name='payment_service[chargify][product_family]'>
			</select>
		</td>
	</tr>
	<tr style='display:none' id='chargify_synchronize_products'>
		<td colspan='2'>
			<button id='chargify_synchronize_products_button'>Synchronize Products</button>
		</td>
	</tr>
</table>