<?php
/**
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */

if(isset($_POST["mm_smarttag_version"]))
{
	MM_OptionUtils::setOption(MM_OptionUtils::$OPTION_KEY_SMARTTAG_VERSION, $_POST["mm_smarttag_version"]);
}

$smartTagVersion = MM_OptionUtils::getOption(MM_OptionUtils::$OPTION_KEY_SMARTTAG_VERSION);
?>

<div style="width: 600px; margin-top: 8px;" class="mm-divider"></div> 

<div class="mm-wrap">
    <p class="mm-header-text">SmartTag Version <span style="font-size:12px;"><a href="https://membermouse.uservoice.com/knowledgebase/articles/319086-set-the-smarttag-version" target="_blank">Learn more</a></span></p>
	
	<div style="margin-top:10px;">
		<input name="mm_smarttag_version" value='2.1' type="radio" <?php echo (($smartTagVersion=="2.1")?"checked":""); ?>  />
		SmartTags 2.1 (<em>recommended</em>)
		
		<input name="mm_smarttag_version" value='2.0' type="radio" <?php echo (($smartTagVersion!="2.1")?"checked":""); ?> style="margin-left:10px;" />
		SmartTags 2.0
	</div>
</div>