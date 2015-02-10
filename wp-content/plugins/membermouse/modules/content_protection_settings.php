<?php
/**
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */

if(isset($_POST["mm_allow_overdue_access"]))
{
	MM_OptionUtils::setOption(MM_OptionUtils::$OPTION_KEY_ALLOW_OVERDUE_ACCESS, $_POST["mm_allow_overdue_access"]);
}

$allowOverdueAccess = MM_OptionUtils::getOption(MM_OptionUtils::$OPTION_KEY_ALLOW_OVERDUE_ACCESS);
?>
<div style="width: 600px; margin-top: 8px;" class="mm-divider"></div> 

<script>
function updateContentProtectionForm()
{	
	if(jQuery("#mm_allow_overdue_access_cb").is(":checked")) 
	{
		jQuery("#mm_allow_overdue_access").val("1");
	} 
	else 
	{
		jQuery("#mm_allow_overdue_access").val("0");
	}
}
</script>

<div class="mm-wrap">
    <p class="mm-header-text">Content Protection Settings</p>

	<div style="margin-top:10px;">
		<input id="mm_allow_overdue_access_cb" type="checkbox" <?php echo (($allowOverdueAccess=="1")?"checked":""); ?> onchange="updateContentProtectionForm();" />
		Allow members with overdue memberships or bundles to access protected content <a href="https://membermouse.uservoice.com/knowledgebase/articles/319091-configure-access-to-content-when-status-is-overdue" target="_blank">Learn more</a>
		<input id="mm_allow_overdue_access" name="mm_allow_overdue_access" type="hidden" value="<?php echo $allowOverdueAccess; ?>" />
	</div>
</div>