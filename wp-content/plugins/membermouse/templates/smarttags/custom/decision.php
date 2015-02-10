<h2>[MM_Custom_Decision]</h2>

<a onclick="stl_js.insertContent('[MM_Custom_Decision][/MM_Custom_Decision]');" class="mm-ui-button blue">Insert SmartTag</a>
<?php $docUrl = "https://membermouse.uservoice.com/knowledgebase/articles/319162-mm-custom-decision-smarttag"; ?>
<a href="<?php echo $docUrl; ?>" target="_blank" class="mm-ui-button">View Documentation</a>

<p>This tag is used to show or hide content based on custom information associated with the current visitor. Custom attributes are created 
dynamically whenever you pass querystring parameters to a URL on your MemberMouse site. For example, if you visit this URL 
<code>http://www.mydomain.com?offer=promo&amp;source=google</code>, MemberMouse will automatically create two custom attributes 
called <code>offer</code> and <code>source</code>. Now you can create dynamic areas across your site that show/hide content based on the 
values stored in those attributes.</p>