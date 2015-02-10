<h2>[MM_Purchase_Link]</h2>

<a onclick="stl_js.insertContent('&lt;a href=&quot;[MM_Purchase_Link productId=\'\']&quot;&gt;Buy Now&lt;/a&gt;');" class="mm-ui-button blue">Insert SmartTag</a>
<?php $docUrl = "https://membermouse.uservoice.com/knowledgebase/articles/319170-mm-purchase-link-smarttag"; ?>
<a href="<?php echo $docUrl; ?>" target="_blank" class="mm-ui-button">View Documentation</a>

<p>This tag outputs the link that a member can interact with to purchase a product or sign up for a free membership level. In order to become a 
functional link, this tag should be used in conjunction with a anchor tag (<code>&lt;a&gt;</code>), a button tag (<code>&lt;button&gt;</code>) or 
some other method of executing a link. Either a product ID or a membership level ID should be specified to indicate what the member is purchasing
or signing up for. If neither are specified, then it will serve as a sign up link for the default membership level.</p>