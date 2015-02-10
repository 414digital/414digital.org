<?php
/**
 *
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */

function getExpandedRows(){
	$expandedRows = array();
	if(isset($_POST["mm_expanded_rows_copy"])){
		$expandedRows = explode(",",$_POST["mm_expanded_rows_copy"]);
	}
	else if(isset($_POST["mm_expanded_rows"])){
		$expandedRows = explode(",",$_POST["mm_expanded_rows"]);
	}
	
	$rows = array();
	foreach($expandedRows as $row){
		if(!empty($row)){
			$rows[$row] = $row;
		}
	}
	return $rows;
}

function getObject($val){
	$id = preg_replace("/[^0-9]+/", "", $val);
	if(preg_match("/(at_|access_tag)/", $val)){
		$obj = new MM_Bundle($id);
		if($obj->isValid()){
			return $obj;
		}
	}
	else if(preg_match("/(mt_|member_type)/", $val)){
		$obj = new MM_MembershipLevel($id);
		if($obj->isValid()){
			return $obj;
		}	
	}
	return false;
}
	
$generatedRows = array();
$selectedTypes = null;
$entries = array();
$isPosted=false;

$expandedRows = getExpandedRows();
if(isset($_POST["view_schedules"])){
	
	$isPosted=true;
	foreach($_POST["view_schedules"] as $key){
		$selectedTypes[$key] = $key;
		$obj = null;
		$entry = new stdClass();
		$id = preg_replace("/[^0-9]+/", "", $key);
		if(preg_match("/(mt_)/", $key)){
			$obj = new MM_MembershipLevel($id);
			if(!$obj->isValid()){
				$obj = null;
			}
			else{
				$entry->access_id = $id;
				$entry->access_type = 'member_type';
			}
		}
		else if(preg_match("/(at_)/", $key)){
			$obj = new MM_Bundle($id);
			if(!$obj->isValid()){
				$obj = null;
			}
			else{
				$entry->access_id = $id;
				$entry->access_type = 'access_tag';
			}
		}
		$entries[] = $entry;
	}
	$generatedRows = MM_ContentDeliveryEngine::getContentSchedule($entries);
}

$copyScheduleResponse = "";
$selectedFromCopy = "";
$selectedToCopy = "";
if(isset($_POST["copy_schedule"])){
	$overwrite = false;
	if(isset($_POST["overwrite"])){
		$overwrite = true;
	}
	if(!isset($_POST["copy_schedules_from"]) || !isset($_POST["copy_schedules_to"])){
		$copyScheduleResponse = "Invalid selections. Choose an access right from the left and one from the right.";
	}
	else{
		$selectedFromCopy = $_POST["copy_schedules_from"];
		$selectedToCopy = $_POST["copy_schedules_to"];
		
		$from = getObject($_POST["copy_schedules_from"]);
		$to = getObject($_POST["copy_schedules_to"]);
		
		if($from->getId() == $to->getId() && get_class($from) === get_class($to)){
			$copyScheduleResponse = "Invalid selections. Cannot copy a schedule to itself.";
		}
		else{
			if($to===false || $from===false){
				$copyScheduleResponse = "Invalid selections. Could not copy drip content schedules.";
			}
			else{
				$response = MM_ContentDeliveryEngine::copySchedule($from, $to, $overwrite);
				$copyScheduleResponse = $response->message;	
			}
		}
	}
}

$showRows = array();
$rows=  array();
$options = array();
$memberTypes = MM_MembershipLevel::getMembershipLevelsList();
foreach($memberTypes as $id=>$name)
{
	$mt = new MM_MembershipLevel($id);
	$obj = new stdClass();
	$obj->id = "mt_".$id;
	$obj->value = $name;
	$obj->image = MM_Utils::getAccessIcon(MM_OrderItemAccess::$ACCESS_TYPE_MEMBERSHIP, '', 'float:left; padding-right: 5px; padding-left: 5px;');
	$options[$obj->id] = $obj;
	$rows[] = $obj;
	
	if(isset($selectedTypes[$obj->id]))
	{
		$showRows[] = $obj;
	}
}
$accessTags = MM_Bundle::getBundlesList();
foreach($accessTags as $id=>$name)
{
	$at = new MM_Bundle($id);
	$obj = new stdClass();
	$obj->id ="at_". $id;
	$obj->value = $name;
	$obj->image = MM_Utils::getAccessIcon(MM_OrderItemAccess::$ACCESS_TYPE_BUNDLE, '', 'float:left; padding-right: 5px; padding-left: 5px;');
	$options[$obj->id] = $obj;
	$rows[] = $obj;
	
	if(isset($selectedTypes[$obj->id]))
	{
		$showRows[] = $obj;
	}
}

$maxCheckboxLength = 18;
$maxHeaderLength = 14;
$maxPostLength = 25;
$maxPostLengthPlus = $maxPostLength+20;
$viewDeliveryAccessTypes = MM_HtmlUtils::createCheckboxGroup($options, "view_schedules[]", $selectedTypes, null, "", null, $maxCheckboxLength);
$copyAccessTypesFrom = MM_HtmlUtils::createCheckboxGroup($options, "copy_schedules_from", $selectedFromCopy, "", null,"", $maxCheckboxLength, true);
$copyAccessTypesTo = MM_HtmlUtils::createCheckboxGroup($options, "copy_schedules_to", $selectedToCopy, "", null,"", $maxCheckboxLength, true);

//style
$color = "#666";
$headerBackgroundColor = "#D8D8D8";
$bodyBackgroundColor="";
$bodyBorderColor ="#BBB";
$bodyColor = '#666';
$headerHeight = "20px";
$alternateColumnColor="#C0DFFF";
$headerBorderColor = "#666";
$cellMarginInt = 2;
$cellMargin = $cellMarginInt."px;";
$col1Width = 50;
$col2Width = 180;
$spaceForTypes = 400;
$tableWidth = $spaceForTypes+$col1Width+$col2Width;
$cols = count($rows);
$typesColWidth = floor($spaceForTypes/$cols)-($cols*$cellMarginInt);
$spaceForTypes-= 10;

$totalRows = 0;
$totalColumns = count($showRows);
if(is_array($generatedRows)){
$totalRows = count($generatedRows);
   	$usedPages = array();
	foreach($generatedRows as $day=>$pages){
    		foreach($pages as $row){
    			if(!isset($usedPages[$row->post_id])){
    				$usedPages[$row->post_id] = $row->post_id;
    				//$totalRows++;
    			}
    		}
	}
}
$rowCache = array();
?>
<div class="mm-wrap">
    <?php if(MM_MemberMouseService::hasPermission(MM_MemberMouseService::$FEATURE_DRIP_CONTENT_SCHEDULE)) { ?>
    <div style='padding-top: 10px;'>
    	<div class='mm-dsm-view-schedule'>
    	<form id="mm_dsm_form_tag" method='post'>
    	<input type='hidden' id='mm-expanded-rows' name="mm_expanded_rows" value='' />
    	<div class='mm-dsm-section-summary' style="width:280px;">
    		View the drip content schedule for the following...
    	</div>
    	<div class='mm-dsm-options'>
    		<?php echo $viewDeliveryAccessTypes; ?>
    	</div>
    	<div class='mm-dsm-clear10'></div>
    	<div id='mm-custom'></div>
    		<input type='submit' class="mm-ui-button blue"  onclick="mmjs.clearCache();" name='view_schedule' value='View Schedule' /></form>
    	</div>
    	<div class='mm-dsm-copy-schedule'>
    	<form id="mm_dsm_copy_form_tag" method='post' onsubmit="return mmjs.verifyCopy();">
    	<input type='hidden' id='mm-expanded-rows-copy' name="mm_expanded_rows_copy" value='' />
    			<div class='mm-dsm-section-summary'>
    			Copy an existing drip content schedule...
    			</div>
    			<div class='mm-dsm-options-right'>
    				<?php echo $copyAccessTypesFrom; ?>
    			</div>
    			<div class='mm-dsm-options-right-middle'>
    				<?php echo MM_Utils::getIcon('copy', 'blue', '1.3em', '1px'); ?>
    			</div>
    			<div class='mm-dsm-options-right'>
    				<?php echo $copyAccessTypesTo; ?>
    			</div>
    			<div class='mm-dsm-clear10'></div>
    			<div id='mm-custom-copy'></div>
	    			<input type='submit' class="mm-ui-button"  name='copy_schedule' value='Copy Schedule' />
	    			<label for="overwrite" style="margin-left:10px;">
	    				<input type='checkbox' id="overwrite" name='overwrite' value='1' onclick="mmjs.clearCache();" /> 
	    				Overwrite existing schedule
	    			</label>
	    	</form>
    	</div>
    </div>
    
    <div style='clear:both;padding-top: 10px'></div>
    
    <div style="clear:both; width: 100%; margin-top: 20px; margin-bottom: 20px;" class="mm-divider"></div>
   		<div style='clear;both;width: 100%; '>&nbsp;</div>
    	<?php
    	if(count($generatedRows)<=0 && is_null($selectedTypes)) { ?>
    		Please choose at least one Membership Level or Bundle to view a drip content schedule.
    	<?php } else if(count($generatedRows)<=0 && !is_null($selectedTypes)) {
    		echo "<div style='line-height:22px; font-size:14px; margin-bottom:5px;'>There is currently no content protected by the selected access rights.</div>";
    		
    		foreach($showRows as $type) {
    		?>
    			<div style="line-height:35px;">
    				<a onclick="mmjs.addAccessRights('<?php echo $type->id; ?>','<?php echo preg_replace("/[\"\']+/", "", $type->value); ?>');" class="mm-ui-button green">
    					<?php echo MM_Utils::getIcon('plus-circle', '', '1.4em', '2px'); ?>
    					Grant Access to <?php echo $type->value; ?>
    				</a> 
    			</div>
    		<?php 
    		}
    	} else { ?>
    	
    <table>
    	<tr>
    		<td colspan='2' class='mm-dsm-expand-collapse-links'>
    			<a onclick="mmjs.expandRows('<?php echo $totalRows; ?>','<?php echo $totalColumns; ?>')" style='cursor: pointer;'>Expand All</a> |
    			<a onclick="mmjs.collapseRows('<?php echo $totalRows; ?>','<?php echo $totalColumns; ?>')" style='cursor: pointer;'>Collapse All</a>
    		</td>
    		<td  align='center' class='mm-dsm-header' colspan='<?php echo count($rows); ?>'>
    			Access Rights
    		</td>
    	</tr>
    	<tr>
    		<td align='center'  class='mm-dsm-header' >Day</td>
    		<td align='center'  class='mm-dsm-header'>Content</td>
    		<?php 
    			foreach($showRows as $type) {
    				?>
    				<td  class='mm-dsm-header-access-types'>
    					<?php if(!empty($type->image)){ ?>
    					<?php echo $type->image; ?>
    					<?php } ?>
    					<span title='<?php echo $type->value; ?>'><?php echo MM_Utils::abbrevString($type->value, $maxHeaderLength); ?> </span>
    					
    					<a title="Grant Access" onclick="mmjs.addAccessRights('<?php echo $type->id; ?>','<?php echo preg_replace("/[\"\']+/", "", $type->value); ?>');" class='mm-dsm-imagelink'><?php echo MM_Utils::getIcon('plus-circle', 'white', '1.4em', '2px'); ?></a> 
    					</td>
    				<?php 
    			}
    		?>
    	</tr>
    	<!-- rows -->
    	<?php 
    		$rowNum = 1;
    		foreach($generatedRows as $day=>$pages){
    			$rowCache[$day] = $rowNum;
    			?>
		    	<tr>
		    		<td class='mm-dsm-cell'>
		    			<table id='mm-dsm-row<?php echo $rowNum; ?>col0' class="mm-dsm-cell-days">
		    				<tr valign='top'>
		    					<td class='mm-dsm-day-part1'>
		    						<a onclick="mmjs.toggleRow('<?php echo $rowNum; ?>','<?php echo $totalColumns; ?>');"><img id='mm-dsm-row<?php echo $rowNum; ?>col0-image' src="<?php echo MM_Utils::getImageUrl('expand'); ?>" /></a>
		    					</td>
		    					<td  class='mm-dsm-day-number'>
		    						
		    						<input type='hidden' id='row-<?php echo $rowNum; ?>' value='<?php echo $day; ?>' />
		    						<?php echo $day; ?>
		    					</td>
		    				</tr>
		    			</table>
		    		</td>
		    		<td class="mm-dsm-cell mm-dsm-cell-content">
		    			<table id='mm-dsm-row<?php echo $rowNum; ?>col1-collapsed'>
		    				<tr>
		    					<td>
		    				<?php 
		    					$index=0;
		    					$usedPages = array();
		    					$links = "";
    							foreach($pages as $row){
		    						$link = "post.php?action=edit&post=".($row->post_id);
		    						
		    						if(!isset($usedPages[$row->post_id])){
		    							$usedPages[$row->post_id] = $row->post_id;
		    							$index++;
		    						}
		    						else{
		    							continue;
		    						}
    							
		    						if($index>2){
		    							continue;	
		    						}
		    						
		    						if($index != 0 && $links != "") {
		    							$links .= ", ";	
		    						}
		    						$post_title = ((strlen($row->post_title)>$maxPostLength)?substr($row->post_title,0,$maxPostLength)."...":$row->post_title);
		    						$links .= "<a href='{$link}' title='".$row->post_title."'  target='_blank'>{$post_title}</a>";
				    		?>
		    						
		    				<?php }
		    					echo preg_replace("/(\,)$/", "", $links);
		    					$pagesLeft = (count($usedPages)>2)?count($usedPages)-2:0;
								if($pagesLeft>0){
		    					?>
		    					and <?php echo $pagesLeft; ?> more.
		    					<?php } ?>
		    					</td>
		    				</tr>
		    			</table>
		    			<table width='100%'  id='mm-dsm-row<?php echo $rowNum; ?>col1-expanded' style='display:none;'>
		    				<?php 
		    					$used = array();
		    					$index=0;
    							foreach($pages as $row){
    								if(isset($used[$row->post_id])){
    									continue;
    								}
    								$used[$row->post_id]=1;
		    						$link = "post.php?action=edit&post=".($row->post_id);
		    						$className = "mm-dsm-cell-content-expanded";
		    						if($index == count($usedPages)-1){
		    							$className = "mm-dsm-cell-content-expanded-none";
		    						}
		    						$post_title = ((strlen($row->post_title)>$maxPostLengthPlus)?substr($row->post_title,0,$maxPostLengthPlus)."...":$row->post_title);
				    		?>
		    				<tr>
		    					<td class='<?php echo $className; ?>'><a href='<?php echo $link; ?>' title='<?php echo $row->post_title; ?>' target='_blank'><?php echo $post_title; ?></a></td>
		    				</tr>
		    				<?php 
		    				$index++;
				    			}
				    		?>
		    			</table>
		    		</td>
		    		
    			<?php 
    				$colNum=2;
    			foreach($showRows as $type){
    						$overall = "";
    						$overallArr = array();
	    					$id = preg_replace("/[^0-9]+/","", $type->id);
	    					$currentType = (preg_match("/(mt_)/",$type->id))?"member_type":"access_tag";
		    					$usedPages = array();	
		    				foreach($pages as $page){
			    				$usedPages[$page->post_id] = 1;
		    					if($currentType==$page->access_type){
		    						
		    						if($id==$page->access_id){
		    							$overallArr[] = MM_Utils::getCheckIcon();	
		    						}
		    					}
		    				}
		    				
		    				if(count($overallArr) == count($usedPages)){
		    					$overall = MM_Utils::getCheckIcon();	
		    				}
		    				else if(count($overallArr)>0){
		    					$overall = MM_Utils::getCheckIcon()." ".MM_Utils::getCrossIcon();	
		    				}

		    		$alt = "-alt";
			    	if($colNum%2==0){
						$alt="";
			    	}
			    				
    				?>
			    		<td class='mm-dsm-cell-types<?php echo $alt; ?>'>
			    			<table width='100%'  id='mm-dsm-row<?php echo $rowNum; ?>col<?php echo $colNum; ?>-collapsed' >
			    				<tr>
			    					<td>
			    					<?php if(empty($overall)){ ?>
			    					<?php echo MM_NO_DATA; ?>
			    					<?php }else{ ?>
			    					<?php echo $overall; ?>
			    					<?php } ?>
			    					</td>
			    				</tr>
			    			</table>
			    			<table width='100%'  id='mm-dsm-row<?php echo $rowNum; ?>col<?php echo $colNum; ?>-expanded' style='display:none; '>
						    						<?php 
						    							
		    					$usedPages = array();	
			    				foreach($pages as $page){
			    					if(!isset($usedPages[$page->post_id])){
			    							$usedPages[$page->post_id] = array();
			    					}
			    							
			    							if($currentType==$page->access_type){
					    						
					    						if($id==$page->access_id){
					    							$usedPages[$page->post_id][] = array(
					    								'id'=>$id,
					    								'type'=>$currentType,
					    							);
					    						}
					    					}
			    				}
			    				$index=0;
			    				$subRows = $rowNum;
			    				foreach($usedPages as $pageId=>$accessItems){
			    						$image = "";
			    						
			    						foreach($accessItems as $item){
			    							
			    							if($item["type"]==$currentType){
												if($item["id"] == $id){
													$image = MM_Utils::getCheckIcon();	
												}
			    							}
			    						}
			    						$className = "mm-dsm-cell-content-expanded";
			    						if($index == count($usedPages)-1){
			    							$className = "mm-dsm-cell-content-expanded-none";
			    						}
							    				?>
						    				<tr>
						    						<?php if(empty($image)){
						    							$key = $id."_".$currentType."_".$subRows;
						    							$obj = getObject($currentType."_".$id);
						    							$post = get_post($pageId);
						    							?>
						    							
						    					<td id='mm_<?php echo $key; ?>' title="Grant Access" class='<?php echo $className; ?>' style='cursor: pointer;' onclick="mmjs.saveManualAccessRight('<?php echo $id; ?>','<?php echo $currentType; ?>','<?php echo $pageId; ?>','<?php echo $day; ?>','mm_<?php echo $key; ?>','<?php echo preg_replace("/[\"']+/", "",  $obj->getName()); ?>','<?php echo preg_replace("/[\"']+/", "", $post->post_title); ?>');">
						    							<?php 
															echo MM_NO_DATA;
						    						}else{
						    							?>
						    					<td class='<?php echo $className; ?>'>
						    						<a style='cursor: pointer;' title="Edit Access" onclick="mmjs.updateAccessRightsDialog('<?php echo $id; ?>','<?php echo $currentType; ?>','<?php echo $pageId; ?>','<?php echo $day; ?>');"><?php echo $image; ?></a>
						    						<?php } ?>
						    						
						    					</td>
						    				</tr>
			    			<?php 
			    				$subRows++;
			    				$index++;
			    				} ?>
			    			</table>
			    		</td>
    				<?php 
					    	$colNum++;
		    			}
		    		?>
    			<?php 
    			$rowNum++;
    		}
    	?>
    	</tr>
    </table>
    <?php } ?>
</div>

<script type='text/javascript'>
<?php if($copyScheduleResponse != "") { ?>
var message = '<?php echo preg_replace("/[\']+/", "", $copyScheduleResponse); ?>';
alert(message.replace(/[\|]+/g, "\n\n"));
<?php } ?>
<?php foreach($expandedRows as $row){
	if(isset($rowCache[$row])){
	?>
mmjs.toggleRow('<?php echo $rowCache[$row]; ?>', '<?php echo $totalRows; ?>');
<?php } } ?>
</script>
<?php } else { ?>
	<?php echo MM_Utils::getIcon('lock', 'yellow', '1.2em', '1px'); ?>
	This feature is not available on your current plan. To get access, <a href="<?php echo MM_MemberMouseService::getUpgradeUrl(MM_MemberMouseService::$FEATURE_DRIP_CONTENT_SCHEDULE); ?>" target="_blank">upgrade your plan now</a>.
<?php } ?>