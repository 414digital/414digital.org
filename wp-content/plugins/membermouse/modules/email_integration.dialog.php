<?php 
/**
 *
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */

global $wpdb;

$bundleId = "";
$activeListId = "";
$canceledListId = "";
$provider = MM_EmailServiceProviderFactory::getActiveProvider();

$provider_entity = new MM_EmailServiceProvider();
$provider_entity->setToken($provider->getToken());
$provider_entity->getData();
$provider_entity_id = ($provider_entity->isValid())?$provider_entity->getId():"";

$lists_response = $provider->getLists();
if ($lists_response->type == MM_Response::$ERROR)
{
	return $lists_response;
}
else if (is_array($lists_response->message) && (count($lists_response->message) == 0))
{
	echo "This email service provider has no lists that can be subscribed to";
	exit;
}
$lists = array(""=>"None") + $lists_response->message;

if(!empty($p->id))
{
	$sql = "SELECT * FROM ".MM_TABLE_EMAIL_PROVIDER_BUNDLE_MAPPINGS." WHERE bundle_id='{$p->id}';";
	$bundleMapping = $wpdb->get_results($sql);
	
	if($bundleMapping)
	{
		foreach($bundleMapping as $bundleMapping)
		{
			$bundleId = $bundleMapping->bundle_id;
			
			if($bundleMapping->list_type == MM_AbstractEmailServiceProvider::$LIST_TYPE_ACTIVE)
			{
				$activeListId = $bundleMapping->list_id;
			}
			else if($bundleMapping->list_type == MM_AbstractEmailServiceProvider::$LIST_TYPE_CANCELED)
			{
				$canceledListId = $bundleMapping->list_id;
			}
		}
	}
}

// make sure bundle list only contains bundles that aren't mapped yet
$bundleList = MM_Bundle::getBundlesList();
$sql = "SELECT * FROM ".MM_TABLE_EMAIL_PROVIDER_BUNDLE_MAPPINGS." WHERE email_service_provider_id = {$provider_entity_id} GROUP BY bundle_id;";
$rows = $wpdb->get_results($sql);

foreach($rows as $row)
{
	if($row->bundle_id == $bundleId)
	{
		continue;
	}
	
	if(array_key_exists($row->bundle_id, $bundleList))
	{
		unset($bundleList[$row->bundle_id]);
	}
}

if(count($bundleList) > 0)
{
?>
<div id="mm-form-container">
<input type='hidden' id='mm_last_bundle_id' value='<?php echo $p->id; ?>' />
<input type='hidden' id='mm_provider_id' value='<?php echo $provider_entity_id; ?>' />
<table>
<tr>
	<td>Bundle</td>
	<td><select id="mm_bundle_id" name="mm_bundle_id"><?php echo MM_HtmlUtils::generateSelectionsList($bundleList, $bundleId); ?></select></td>
</tr>
<tr>
	<?php 
		$activeListDesc = "This is the mailing list that you want members added to when the bundle selected above is active on a member&apos;s account. If you have a Canceled List defined as well, members will be removed from this list when the bundle becomes active.";
		$canceledListDesc = "This is the mailing list that you want members added to when the bundle selected above is canceled or paused on a member&apos;s account.  If you have an Active List defined as well, members will be removed from this list when the bundle becomes canceled or paused.";
	?>
	<td>Active List<?php echo MM_Utils::getInfoIcon($activeListDesc); ?></td>
	<td><select id="mm_active_list_id" name="mm_active_list_id"><?php echo MM_HtmlUtils::generateSelectionsList($lists, $activeListId); ?></select></td>
</tr>
<tr>
	<td>Canceled List<?php echo MM_Utils::getInfoIcon($canceledListDesc); ?></td>
	<td><select id="mm_canceled_list_id" name="mm_canceled_list_id"><?php echo MM_HtmlUtils::generateSelectionsList($lists, $canceledListId); ?></select></td>
</tr>
</table>
</div>

<div class="mm-dialog-footer-container">
<div class="mm-dialog-button-container">
<a href="javascript:mmjs.save();" class="mm-ui-button blue">Save Bundle Mapping</a>
<a href="javascript:mmjs.closeDialog();" class="mm-ui-button">Cancel</a>
</div>
</div>
<?php } else { ?>
All bundles have been mapped.
<?php } ?>