<?php
/**
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */

if(isset($_POST["mm_checkout_item_type"]))
{
	MM_OptionUtils::setOption(MM_OptionUtils::$OPTION_KEY_DEFAULT_CHECKOUT_ITEM_TYPE, $_POST["mm_checkout_item_type"]);
	
	if($_POST["mm_checkout_item_type"] == "membership_level")
	{
		MM_OptionUtils::setOption(MM_OptionUtils::$OPTION_KEY_DEFAULT_CHECKOUT_ITEM_ID, $_POST["mm_membership_level_selector"]);
	}
	else
	{
		MM_OptionUtils::setOption(MM_OptionUtils::$OPTION_KEY_DEFAULT_CHECKOUT_ITEM_ID, $_POST["mm_product_selector"]);
	}
}

$dfltCheckoutItemType = MM_OptionUtils::getOption(MM_OptionUtils::$OPTION_KEY_DEFAULT_CHECKOUT_ITEM_TYPE);
$dfltCheckoutItemId = MM_OptionUtils::getOption(MM_OptionUtils::$OPTION_KEY_DEFAULT_CHECKOUT_ITEM_ID);

if(empty($dfltCheckoutItemId) || intval($dfltCheckoutItemId) == -1)
{
	$dfltCheckoutItemType = "membership_level";
	$dfltMembership = MM_MembershipLevel::getDefaultMembership();
	$dfltCheckoutItemId = $dfltMembership->getId();
}
?>
<script>
function itemTypeChangeHandler()
{
	if(jQuery("input:radio[name=mm_checkout_item_type]:checked").val() == "membership_level") 
	{
		jQuery("#mm_membership_level_selector").show();
		jQuery("#mm_product_selector").hide();
	} 
	else 
	{
		jQuery("#mm_membership_level_selector").hide();
		jQuery("#mm_product_selector").show();
	}
}
</script>
<div class="mm-wrap">
    <p class="mm-header-text">Default Membership Level or Product <span style="font-size:12px;"><a href="https://membermouse.uservoice.com/knowledgebase/articles/319028-set-the-default-membership-level-or-product" target="_blank">Learn more</a></span></p>
    <div style="clear:both; height: 10px;"></div>
    <div style="margin-bottom:10px; width:550px;">Select the membership level or product MemberMouse should use if a customer visits the checkout
    page without specifying a specific item to purchase:</div>
	
	<div>
		<label>
			<input onchange="itemTypeChangeHandler();" name="mm_checkout_item_type" value='membership_level' type="radio" <?php echo (($dfltCheckoutItemType == "membership_level")?"checked":""); ?>  />
			Membership Level
		</label>
		
		&nbsp;&nbsp;
		
		<label>
			<input onchange="itemTypeChangeHandler();" name="mm_checkout_item_type" value='product' type="radio" <?php echo (($dfltCheckoutItemType == "product")?"checked":""); ?>  />
			Product
		</label>
	</div>
	
	<div style="margin-top:10px;">
		<select id="mm_membership_level_selector" name="mm_membership_level_selector" style="visible:false;">
		<?php echo MM_HtmlUtils::getMemberships($dfltCheckoutItemId); ?>
		</select>
		
		<select id="mm_product_selector" name="mm_product_selector" style="visible:false;">
		<?php echo MM_HtmlUtils::getProducts($dfltCheckoutItemId); ?>
		</select>
	</div>
</div>
<script type='text/javascript'>
itemTypeChangeHandler();
</script>