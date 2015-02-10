<?php 
/**
 *
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */

$view = new MM_SmartTagLibraryView();
$items = $view->getSmartTags();

?>
<div style="width:170px; height:100%; overflow:auto; float:left;">
<?php 
	foreach($items as $item) {
		echo $item->generateHtml();	
	}
?>
</div>
<div style="height:100%; width:1px; border-right: 1px dotted #C1C1C1; float: left;"></div>
<div id="mm-smarttag-documentation" style="width:570px; height:100%; padding-left: 10px; padding-right: 10px; font-size: 11px; float:left; overflow:auto;">
<i>Select a tag from the list on the left</i>
</div>