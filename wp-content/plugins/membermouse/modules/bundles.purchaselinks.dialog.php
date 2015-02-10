<?php 
/**
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */

$bundle = new MM_Bundle($p->accessTypeId);

function generatePurchaseSection($productId)
{
?>
	<div id="mm-purchaselinks-<?php echo $productId; ?>" style="display:none;">
	<p><strong>Purchase Link SmartTag</strong><?php echo MM_Utils::getInfoIcon("You can use this Purchase Link SmartTag in any post or page on your site. When using this SmartTag MemberMouse will automatically generate a link customers can click on to purchase this bundle."); ?></p>
	
	<?php $smartTag = "<a href=\"[MM_Purchase_Link productId='{$productId}']\">Buy Now</a>" ?>
	<input id="mm-smart-tag-<?php echo $productId; ?>" type="text" readonly value="<?php echo htmlentities($smartTag); ?>" style="width:440px; font-family:courier; font-size:11px;" onclick="jQuery('#mm-smart-tag-<?php echo $productId; ?>').focus(); jQuery('#mm-smart-tag-<?php echo $productId; ?>').select();" />
	
	<ul style="margin-left:20px;">
	<li>Set the <code>isGift</code> attribute to <code>true</code> to indicate that this purchase is a gift.</li>
	</ul>
	
	<p style="margin-left:20px;">
	Read this article to 
		<a href="https://membermouse.uservoice.com/knowledgebase/articles/319170-mm-purchase-link-smarttag" target="_blank">learn more about the <code>MM_Purchase_Link</code> SmartTag</a>.
	</p>
	
	<p><strong>Static Link</strong><?php echo MM_Utils::getInfoIcon("You can use this link anywhere -- in a PPC or banner ad, email, on your site, on a 3rd party site, etc. Customers can click on this link to purchase this bundle."); ?></p>
	
	<input id="mm-static-link-<?php echo $productId; ?>" type="text" readonly value="<?php echo htmlentities(MM_CorePageEngine::getCheckoutPageStaticLink($productId)); ?>" style="width:440px; font-family:courier; font-size:11px;" onclick="jQuery('#mm-static-link-<?php echo $productId; ?>').focus(); jQuery('#mm-static-link-<?php echo $productId; ?>').select();" />
	
	<?php 
		$affiliateId = MM_OptionUtils::getOption(MM_OptionUtils::$OPTION_KEY_AFFILIATE);
		$subAffiliateId = MM_OptionUtils::getOption(MM_OptionUtils::$OPTION_KEY_SUB_AFFILIATE);
	?>
	<p><strong>Add Affiliate Tracking</strong></p>
	<p>To add affiliate tracking to the above purchase links you simply need to append affiliate tracking parameters to the URL as follows:</p>
	<p><em>Purchase Link SmartTag</em>: <br/><code>&lt;a href="[MM_Purchase_Link productId='#']<strong>&<?php echo $affiliateId; ?>=###&<?php echo $subAffiliateId; ?>=###</strong>"&gt;Buy Now&lt;/a&gt;</code></p>
	<p><em>Static Link</em>: <br/><code>http://yourdomain.com/checkout/?rid=p4K7d<strong>&<?php echo $affiliateId; ?>=###&<?php echo $subAffiliateId; ?>=###</strong></code></p>
	<p>Where all <code>#</code>'s would be replaced with the appropriate values. Read this article to <a href="http://support.membermouse.com/knowledgebase/articles/319248-create-an-affiliate-link" target="_blank">learn more about creating an affiliate link</a>.</p>
	</div>
<?php 
}	
?>

<div id="mm-form-container" style="width:460px;">
	<div style="font-size:11px;">
	<p><span class="mm-section-header">Purchase Links for '<?php echo $p->accessTypeName; ?>'</span></p>
	
	<?php 
		if(!$bundle->isFree()) { 
	?>
		<p>MemberMouse offers two methods for creating purchase links. First, select the product you want to create
		a purchase link for and then use one of the links below to allow customers to purchase access to the 
		'<?php echo $p->accessTypeName; ?>' bundle.</p>
		
		<input id="mm-last-selected-product-id" type="hidden" value="0" /> 
		<select id="mm-product-selector" onchange="mmjs.productChangeHandler();">
		<option value='0'>Select a product</option>
		<?php
			foreach($p->productIds as $id) 
			{
				$product = new MM_Product($id);
				
				if($product->isValid())
				{
					echo "<option value='{$product->getId()}'>{$product->getName()}</option>";
				}
			}
		?>
		</select>
		
	<?php
			foreach($p->productIds as $id) 
			{
				generatePurchaseSection($id);
			}
		} 
		else 
		{ 
	?>
		<p>Cannot create purchase links for free bundles.</p>
	<?php } ?>
	</div>
</div>