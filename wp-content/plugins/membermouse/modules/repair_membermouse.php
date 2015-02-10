<?php
/**
 *
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */

$canCache = MM_Utils::cacheIsWriteable();
?>
<div class="mm-wrap">
    <p class="mm-header-text">Repair MemberMouse</p>
	<div class="mm-button-container">
	</div>
	
	<div class="clear"></div>
	<div id='mm-repair-content' style='width: 700px;'>
		<?php if (!$canCache) { ?>
		In order to access repair options, please make the MemberMouse cache directory writeable<br/>
		Cache directory location: <strong><?php echo MM_Utils::getCacheDir(); ?></strong>
		<?php } else { ?>
		<div id='mm-pre-repair-membermouse'>
			The repair option will restore all MemberMouse files to their default state. This process only affects the files related to
			the MemberMouse application; No membership or order data will be affected. 
			<p>This process may take several minutes, click the button below to begin.</p>
			<input type="button" class="button" id="mm-begin-repair" value="Begin Repair" onClick="mmjs.fetchSourceArchive();">
		</div>
		
		<div id='mm-repair-membermouse' style='display:none;'>
			<ul>
				<li id='mm-repair-membermouse-step-one'> Step One - Retrieving source archive from MemberMouse</li>
				<li id='mm-repair-membermouse-step-two'> Step Two - Replacing files</li>
			</ul>
		</div>
		
		<?php } ?>
	</div>
</div>