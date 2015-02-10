<?php
/**
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */

if(isset($_POST["mm_allow_duplicate_subscriptions"]))
{
	MM_OptionUtils::setOption(MM_OptionUtils::$OPTION_KEY_ALLOW_DUPLICATE_SUBSCRIPTIONS, $_POST["mm_allow_duplicate_subscriptions"]);
}

$allowDuplicateSubscriptions = MM_OptionUtils::getOption(MM_OptionUtils::$OPTION_KEY_ALLOW_DUPLICATE_SUBSCRIPTIONS);
?>
<script>
function allowSubscriptionChangeHandler()
{
	if(jQuery("#mm_allow_duplicate_subscriptions_cb").is(":checked")) 
	{
		jQuery("#mm_allow_duplicate_subscriptions").val("1");
	} 
	else 
	{
		jQuery("#mm_allow_duplicate_subscriptions").val("0");
	}
}
</script>
<div class="mm-wrap">
    <p class="mm-header-text">Duplicate Subscription Settings <span style="font-size:12px;"><a href="https://membermouse.uservoice.com/knowledgebase/articles/319034-duplicate-subscription-settings" target="_blank">Learn more</a></span></p>
    <div style="clear:both; height: 10px;"></div>
    <div style="margin-bottom:10px; width:550px;">Checking the check box below indicates that when a member attempts to purchase a subscription that 
    they already have active, MemberMouse will allow the purchase to go through and create a duplicate subscription. If the option below is unchecked, 
    an error will be displayed to the member indicating that they have already purchased that subscription and it's still active.</div>
	
	<div>
		<label>
			<input id="mm_allow_duplicate_subscriptions_cb" value='1' type="checkbox" <?php echo ($allowDuplicateSubscriptions == "1") ? "checked":""; ?> onchange="allowSubscriptionChangeHandler();" />
			<input id="mm_allow_duplicate_subscriptions" name="mm_allow_duplicate_subscriptions" value='<?php echo $allowDuplicateSubscriptions; ?>' type="hidden" />
			Allow members to have more than one of the same subscription active
		</label>
	</div>
</div>