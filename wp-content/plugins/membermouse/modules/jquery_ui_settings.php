<?php
/**
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */

if(isset($_POST["mm_use_jquery_ui"]))
{
	MM_OptionUtils::setOption(MM_OptionUtils::$OPTION_KEY_USE_JQUERY_UI, $_POST["mm_use_jquery_ui"]);
}

$useJQueryUI = MM_OptionUtils::getOption(MM_OptionUtils::$OPTION_KEY_USE_JQUERY_UI);
?>
<div style="width: 600px; margin-top: 8px;" class="mm-divider"></div> 

<script>
function updateJQueryUIForm()
{	
	if(jQuery("#mm_use_jquery_ui_cb").is(":checked")) 
	{
		jQuery("#mm_use_jquery_ui").val("1");
	} 
	else 
	{
		jQuery("#mm_use_jquery_ui").val("0");
	}
}
</script>

<div class="mm-wrap">
    <p class="mm-header-text">jQuery UI Options <span style="font-size:12px;"><a href="https://membermouse.uservoice.com/knowledgebase/articles/319087-disable-jquery-ui" target="_blank">Learn more</a></span></p>

	<div style="margin-top:10px;">
		<input id="mm_use_jquery_ui_cb" type="checkbox" <?php echo (($useJQueryUI=="1")?"checked":""); ?> onchange="updateJQueryUIForm();" />
		Use MemberMouse jQuery UI on the front-end
		<input id="mm_use_jquery_ui" name="mm_use_jquery_ui" type="hidden" value="<?php echo $useJQueryUI; ?>" />
	</div>
</div>