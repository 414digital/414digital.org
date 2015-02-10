<?php
/**
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */

$provider_entity = MM_AffiliateProvider::getActiveAffiliateProvider();
if (!$provider_entity->isValid())
{
	//TODO: handle error retrieving provider, shouldnt happen
}
$active_provider_token = $provider_entity->getToken();
?>

<div class="mm-wrap">
	<form id="provider-config-form" name="form" method="post" action="#" autocomplete="off">

	<div id="mm-affiliate-provider-select" style="margin-top: 15px; margin-bottom: 15px; line-height: 26px;">
		Select Provider: 
		<select name="provider_token" id="provider_token" onChange="mmjs.showNewProviderOptions(jQuery('#provider-config-form :input').serializeArray());"><?php echo MM_HtmlUtils::getAffiliateProvidersList($active_provider_token)?></select>
	</div>

	<div>
		<div id="mm-affiliate-provider-options" style="margin-bottom:10px;">
			<?php 
				$provider = MM_AffiliateProviderFactory::getActiveProvider();
				$form = $provider->renderOptionsDialog(array());
				echo $form->dialog;
			?>
		</div>
		<div id="mm-affiliate-provider-controls">
			<?php echo $form->controls; ?>
		</div>
		<div class="spacer"></div>	
	</div>
	
	<div id="mm-affiliate-provider-additional-options" style="display:none; margin-top:20px;">
		<div id="mm-membership-to-profile" style="margin-bottom:20px;"></div>
		<button id="affiliate-provider-save" class="mm-ui-button blue" onClick="mmjs.providerOptionsSave(jQuery('#provider-config-form :input').serializeArray()); return false;" style="cursor:pointer;">Save Settings</button>
	</div>
	
	<div class="clear"></div>
	</form>
</div>
<script type='text/javascript'>
<?php if($active_provider_token!="default"){ ?>
	jQuery(document).ready(function() {
	jQuery("#provider_token").removeAttr("disabled"); //resolve a potential issue with initial state 
	mmjs.showAdditionalOptionsDialog(jQuery('#provider-config-form :input').serializeArray());
	});
<?php }else{ ?>

jQuery(document).ready(function() {
	jQuery("#mm-affiliate-provider-additional-options").hide();
	jQuery("#provider_token").removeAttr("disabled");
});

<?php }?>
</script>