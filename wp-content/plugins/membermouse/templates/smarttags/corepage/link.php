<h2>[MM_CorePage_Link]</h2>

<a onclick="stl_js.insertContent('&lt;a href=&quot;[MM_CorePage_Link type=\'\']&quot;&gt;Click here&lt;/a&gt;');" class="mm-ui-button blue">Insert SmartTag</a>
<?php $docUrl = "https://membermouse.uservoice.com/knowledgebase/articles/319161-mm-corepage-link-smarttag"; ?>
<a href="<?php echo $docUrl; ?>" target="_blank" class="mm-ui-button">View Documentation</a>

<p>This tag outputs the link to a core page based on the type attribute passed. In order to become a functional link, this tag 
should be used in conjunction with a anchor tag (<code>&lt;a&gt;</code>), a button tag (<code>&lt;button&gt;</code>) or some other
method of executing a link. For certain core page types, this link is dynamically generated based on the account information of the 
logged in member. For example, you can create a different home page for each membership level, the core page link of type 
<code>homepage</code> will automatically output the appropriate URL based on your configuration and the account infomration of the 
logged in member.</p>