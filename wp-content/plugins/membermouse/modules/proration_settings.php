<?php
/**
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */

if(isset($_POST["mm_enable_membership_proration"]))
{
	MM_OptionUtils::setOption(MM_OptionUtils::$OPTION_KEY_ENABLE_MEMBERSHIP_PRORATION, $_POST["mm_enable_membership_proration"]);
}

$enableMembershipProration = MM_OptionUtils::getOption(MM_OptionUtils::$OPTION_KEY_ENABLE_MEMBERSHIP_PRORATION);
?>
<script>
function enableProrationChangeHandler()
{
	if(jQuery("#mm_enable_membership_proration_cb").is(":checked")) 
	{
		jQuery("#mm_enable_membership_proration").val("1");
	} 
	else 
	{
		jQuery("#mm_enable_membership_proration").val("0");
	}
}
</script>
<div class="mm-wrap">
    <p class="mm-header-text">Prorate Membership Subscription on Upgrade/Downgrade <span style="font-size:12px;"><a href="http://support.membermouse.com/knowledgebase/articles/395932" target="_blank">Learn more</a></span></p>
    <div style="clear:both; height: 10px;"></div>
    <div style="margin-bottom:10px; width:550px;">Checking the checkbox below will enable proration when an existing member upgrades/downgrades 
    their membership. If there's an unused balance on the member's current subsription at the time they siwtch, this will be applied a credit to 
    the first charge on the new subscription. In order for a proration to be applied the following must be true:
    <ul style="margin-left:25px; list-style-type: circle;"><li>The member must have an active subscription associated with their current membership</li>
    <li>The member must be switching to another membership that has a subscription associated with it</li>
    <li>The charge to be prorated must be billed immediately at the time of upgrading/downgrading (i.e. the new membership cannot have a free trail)</li>
    </ul></div>
	
	<div>
		<label>
			<input id="mm_enable_membership_proration_cb" value='1' type="checkbox" <?php echo ($enableMembershipProration == "1") ? "checked":""; ?> onchange="enableProrationChangeHandler();" />
			<input id="mm_enable_membership_proration" name="mm_enable_membership_proration" value='<?php echo $enableMembershipProration; ?>' type="hidden" />
			Enable proration on membership uprade/downgrade
		</label>
	</div>
</div>