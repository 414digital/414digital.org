<?php
/**
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */

$provider_entity = MM_EmailServiceProvider::getActiveEmailServiceProvider();
if (!$provider_entity->isValid()) 
{
	//TODO: handle error retrieving provider, shouldnt happen	
}
$active_provider_token = $provider_entity->getToken();

?>

<div class="mm-wrap">
		<form id="provider-config-form" name="form" method="post" action="#" autocomplete="off">
		<div id="mm-email-service-provider-select" style="margin-top: 15px; margin-bottom: 15px; line-height: 26px;">
			Select Provider: 
			<select name="provider_token" id="provider_token" onChange="mmjs.showNewProviderOptions(jQuery('#provider-config-form :input').serializeArray());"><?php echo MM_HtmlUtils::getEmailServiceProvidersList($active_provider_token)?></select>
		</div>
		
		<div>
			<div id="mm-email-service-provider-options" style="margin-bottom:10px;">
				<?php 
					$provider = MM_EmailServiceProviderFactory::getActiveProvider();
					$form = $provider->renderOptionsDialog(array());
					echo $form->dialog;
				?>
			</div>
			<div id="mm-email-service-provider-controls">
				<?php echo $form->controls; ?>
			</div>
			<div class="spacer"></div>	
		</div>
		
		<div style="width: 96%; margin-top: 15px;" class="mm-divider"></div>
		
		<table><tr>
			<td>
				<div id="mm-email-service-provider-list-mappings" style="display:none; margin-top:20px; margin-right:20px;">
					<iframe id="export_frame" style="display:none"></iframe>
					<div id="mm-membertype-to-list" style="margin-bottom:20px;"></div>
					<button id="email-service-provider-save" class="mm-ui-button blue" onClick="mmjs.providerOptionsSave(jQuery('#provider-config-form :input').serializeArray()); return false;" style="cursor:pointer;">Save Settings</button>
				</div>
			</td>
			
			<td style="border-left: 1px dotted #C1C1C1;"></td>
			
			<td valign="top">
				<div id="mm-email-service-provider-list-bundle-mappings" style="display:none; margin-top: 20px; margin-left:20px;">
					<p class="mm-section-header" style="margin-bottom:10px;">Bundle Mappings</p>
					
					<?php 
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
					$listNames = $lists_response->message;
					
					$view = new MM_EmailIntegrationView();
					$dataGrid = new MM_DataGrid($_REQUEST, "bundle_id", "desc", 10);
					$data = $view->getViewData($dataGrid);
					$dataGrid->setTotalRecords($data);
					$dataGrid->recordName = "bundle mapping";
					
					// pre-process results 
					$bundleMappings = array();
					
					foreach($data as $key=>$item)
					{
						if(array_key_exists($item->bundle_id, $bundleMappings))
						{
							$crntItem = $bundleMappings[$item->bundle_id];
						}
						else
						{
							$crntItem = new stdClass();
						}

						$crntItem->id = $item->id;
						$crntItem->bundle_id = $item->bundle_id;
						
						if($item->list_type == MM_AbstractEmailServiceProvider::$LIST_TYPE_ACTIVE)
						{
							$crntItem->active_list_id = $item->list_id;
						}
						else if($item->list_type == MM_AbstractEmailServiceProvider::$LIST_TYPE_CANCELED)
						{
							$crntItem->canceled_list_id = $item->list_id;
						}
						
						$bundleMappings[$item->bundle_id] = $crntItem;
					}
					
					// generate rows
					$rows = array();
					$export_url = MM_PLUGIN_URL.'/com/membermouse/esp/util/export_members.php';
					
					foreach($bundleMappings as $key=>$item)
					{
						$bundle = new MM_Bundle($item->bundle_id);
						$bundleName = MM_NO_DATA;
						
						if(!$bundle->isValid())
						{
							continue;
						}
						
						$bundleName = $bundle->getName();
						
						// Actions
						$editActionUrl = 'onclick="mmjs.edit(\'mm-bundle-mapping-dialog\', \''.$item->bundle_id.'\', 475, 235)"';
						$deleteActionUrl = 'onclick="mmjs.remove(\''.$item->bundle_id.'\')"';
						$actions = MM_Utils::getEditIcon("Edit Bundle Mapping", '', $editActionUrl);
						$actions .= MM_Utils::getDeleteIcon("Delete Bundle Mapping", 'margin-left:5px;', $deleteActionUrl);
						
						$actions .= "<a onclick='mmjs.exportMembers(\"{$export_url}?bundle_id={$item->bundle_id}\"); return false;' style='cursor:pointer; margin-left:5px;' title='Export members with &apos;{$bundleName}&apos; bundle active on their account'>".MM_Utils::getIcon('download', 'green', '1.3em', '2px')."</a>";
						$actions .= "<a onclick='mmjs.exportMembers(\"{$export_url}?bundle_id={$item->bundle_id}&export_type=canceled_bundles\"); return false;' style='cursor:pointer; margin-left:5px;' title='Export members with &apos;{$bundleName}&apos; bundle canceled on their account'>".MM_Utils::getIcon('download', 'red', '1.3em', '2px')."</a>";
						
						$activeListName = MM_NO_DATA;
						
						if(isset($item->active_list_id) && !empty($listNames[$item->active_list_id]))
						{
							$activeListName = $listNames[$item->active_list_id];
						}

						$canceledListName = MM_NO_DATA;
						
						if(isset($item->canceled_list_id) && !empty($listNames[$item->canceled_list_id]))
						{
							$canceledListName = $listNames[$item->canceled_list_id];
						}
						
						$rows[] = array
						(
								array('content' => MM_Utils::abbrevString($bundleName, 30)),
								array('content' => MM_Utils::abbrevString($activeListName, 30)),
								array('content' => MM_Utils::abbrevString($canceledListName, 30)),
								array('content' => $actions),
						);
					}
					
					$headers = array
					(
							'name'				=> array('content' => 'Bundle'),
							'active_list'		=> array('content' => 'Active List', "attr" => ""),
							'canceled_list'		=> array('content' => 'Canceled List', "attr" => ""),
							'actions'			=> array('content' => 'Actions', "attr" => "style='width:86px;'")
					);
					
					$dataGrid->setHeaders($headers);
					$dataGrid->setRows($rows);
					$dataGrid->width = "600px";
					
					$dgHtml = $dataGrid->generateHtml();
					
					if($dgHtml == "") {
						$dgHtml = "<p><i>No bundle mappings.</i></p>";
					}
					?>
					<div class="mm-button-container">
						<a onclick="mmjs.create('mm-bundle-mapping-dialog', 475, 235)" class="mm-ui-button green"><?php echo MM_Utils::getIcon('plus-circle', '', '1.2em', '1px'); ?> Create Bundle Mapping</a>
					</div>
					
					<div class="clear"></div>
					
					<?php echo $dgHtml; ?>
				</div>
			</td>
		</tr></table>
	</form>
		
</div>
<script type='text/javascript'>
<?php if($active_provider_token!="default"){ ?>
	jQuery(document).ready(function() {
	jQuery("#provider_token").removeAttr("disabled"); //resolve a potential issue with initial state 
	mmjs.showListMappingDialog(jQuery('#provider-config-form :input').serializeArray());
	});
<?php }else{ ?>

jQuery(document).ready(function() {
	jQuery("#mm-email-service-provider-list-mappings").hide();
	jQuery("#mm-email-service-provider-list-bundle-mappings").hide();
	jQuery("#provider_token").removeAttr("disabled");
});

<?php }?>
</script>