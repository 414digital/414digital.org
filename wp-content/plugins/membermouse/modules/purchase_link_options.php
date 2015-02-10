<?php
/**
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */

if(isset($_POST["mm_purchase_link_style"]))
{
	MM_OptionUtils::setOption(MM_OptionUtils::$OPTION_KEY_PURCHASE_LINK_STYLE, $_POST["mm_purchase_link_style"]);
	
	if($_POST["mm_purchase_link_style"] == MM_LINK_STYLE_EXPLICIT)
	{
		MM_OptionUtils::setOption(MM_OptionUtils::$OPTION_KEY_DISABLE_EXPLICIT_LINKS, "0");
	}
	else
	{
		MM_OptionUtils::setOption(MM_OptionUtils::$OPTION_KEY_DISABLE_EXPLICIT_LINKS, $_POST["mm_disable_explicit_links"]);
	}
}

$crntLinkStyle = MM_OptionUtils::getOption(MM_OptionUtils::$OPTION_KEY_PURCHASE_LINK_STYLE);
$disableExplicitLinks = MM_OptionUtils::getOption(MM_OptionUtils::$OPTION_KEY_DISABLE_EXPLICIT_LINKS);
$linkSecurityDescription = "By default, MemberMouse allows both explicit and reference link styles. For increased security you can disable explicit links by checking off the box to the left. Doing this will keep customers from being able to manually modify purchase links to discover other products and membership levels that you're offering. If you have any links on your site or on 3rd party sites that use explicit links, you'll want to change these to reference links prior to disabling support for explicit links.";
?>
<script>
function disableExplicitLinksChangeHandler()
{
	if(jQuery("#mm_disable_explicit_links_cb").is(":checked")) 
	{
		jQuery("#mm_disable_explicit_links").val("1");
	} 
	else 
	{
		jQuery("#mm_disable_explicit_links").val("0");
	}
}
function linkStyleChangeHandler()
{
	if(jQuery("input:radio[name=mm_purchase_link_style]:checked").val() == "<?php echo MM_LINK_STYLE_EXPLICIT; ?>") 
	{
		jQuery("#mm-disable-explicit-link-options").hide();
	} 
	else 
	{
		jQuery("#mm-disable-explicit-link-options").show();
	}
}
</script>
<div class="mm-wrap" style="padding-bottom:5px;">
    <p class="mm-header-text">Purchase Link Options <span style="font-size:12px;"><a href="https://membermouse.uservoice.com/knowledgebase/articles/319031-configuring-purchase-links" target="_blank">Learn more</a></span></p>
  
    <div style="margin-bottom:10px; width:550px;">
    <p>In MemberMouse there are two purchase link styles that can be used: <em>Reference Links</em> and <em>Explicit Links</em>. Select the style you want to use below:</p>
    
   	<p><input onchange="linkStyleChangeHandler();" name="mm_purchase_link_style" value='<?php echo MM_LINK_STYLE_REFERENCE; ?>' type="radio" <?php echo (($crntLinkStyle == MM_LINK_STYLE_REFERENCE)?"checked":""); ?>  />
   	<strong>Reference Links</strong> (<em>recommended</em>)</p>
    
    <div style="margin-left:15px;">
    	<p>This style involves passing a 6-digit alphanumeric key that represents either a product or a 
    	membership level as follows:</p>
    	<p><code>http://yourdomain.com/checkout/?rid=4DuFx8</code></p>
    	
    	<div id="mm-disable-explicit-link-options">
    		<p>
    			<label>
				<input id="mm_disable_explicit_links_cb" value='1' type="checkbox" <?php echo ($disableExplicitLinks == "1") ? "checked":""; ?> onchange="disableExplicitLinksChangeHandler();" />
				<input id="mm_disable_explicit_links" name="mm_disable_explicit_links" value='<?php echo $disableExplicitLinks; ?>' type="hidden" />
				Increase security by disabling support for explicit links (<em>recommended</em>)
				</label><?php echo MM_Utils::getInfoIcon($linkSecurityDescription); ?>
			</p>
			<p></p>
    
			
		</div>
    </div>
    
    <p><input onchange="linkStyleChangeHandler();" name="mm_purchase_link_style" value='<?php echo MM_LINK_STYLE_EXPLICIT; ?>' type="radio" <?php echo (($crntLinkStyle == MM_LINK_STYLE_EXPLICIT)?"checked":""); ?>  />
    <strong>Explicit Links</strong></p> 
    
    <div style="margin-left:15px;">
    	<p>This style involves passing the ID of a product or membership level to the checkout page as follows:</p>
    	<p><code>http://yourdomain.com/checkout/?pid=2</code><br/><code>http://yourdomain.com/checkout/?mid=4</code></p>
    </div>
    </div>
</div>
<script type='text/javascript'>
linkStyleChangeHandler();
</script>