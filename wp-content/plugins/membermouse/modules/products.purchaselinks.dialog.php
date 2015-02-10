<?php 
/**
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */
?>

<div id="mm-form-container" style="width:460px;">
	<p><span class="mm-section-header">Purchase Links for '<?php echo $p->productName; ?>'</span></p>
	
	<div style="font-size:11px;">
		<p>MemberMouse offers two methods for creating purchase links. Use one of the links below 
		to allow customers to purchase the '<?php echo $p->productName; ?>' product.</p>
		
		<p><strong>Purchase Link SmartTag</strong><?php echo MM_Utils::getInfoIcon("You can use this Purchase Link SmartTag in any post or page on your site. When using this SmartTag MemberMouse will automatically generate a link customers can click on to purchase this product."); ?></p>

		<input id="mm-smart-tag" type="text" readonly value="<?php echo htmlentities($p->smartTag); ?>" style="width:440px; font-family:courier; font-size:11px;" onclick="jQuery('#mm-smart-tag').focus(); jQuery('#mm-smart-tag').select();" />
		
		<ul style="margin-left:20px;">
		<li>Set the <code>isGift</code> attribute to <code>true</code> to indicate that this purchase is a gift.</li>
		</ul>
		
		<p style="margin-left:20px;">
		Read this article to 
			<a href="https://membermouse.uservoice.com/knowledgebase/articles/319170-mm-purchase-link-smarttag" target="_blank">learn more about the <code>MM_Purchase_Link</code> SmartTag</a>.
		</p>
		
		<p><strong>Static Link</strong><?php echo MM_Utils::getInfoIcon("You can use this link anywhere -- in a PPC or banner ad, email, on your site, on a 3rd party site, etc. Customers can click on this link to purchase this product"); ?></p>
		
		<input id="mm-static-link" type="text" readonly value="<?php echo htmlentities($p->staticLink); ?>" style="width:440px; font-family:courier; font-size:11px;" onclick="jQuery('#mm-static-link').focus(); jQuery('#mm-static-link').select();" />
	
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
</div>