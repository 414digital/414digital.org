<h2>[MM_Form_Button]</h2>

<a onclick="stl_js.insertContent('&lt;a href=&quot;[MM_Form_Button type=\'\']&quot;&gt;Perform Action&lt;/a&gt;');" class="mm-ui-button blue">Insert SmartTag</a>
<?php $docUrl = "https://membermouse.uservoice.com/knowledgebase/articles/319151-mm-form-button-smarttag"; ?>
<a href="<?php echo $docUrl; ?>" target="_blank" class="mm-ui-button">View Documentation</a>

<p>This tag is used in conjunction with the <code>[MM_Form]</code> tag. You use it to create buttons 
that perform actions relevant to the form. More specifically, it outputs a URL that can be placed in the 
<code>href</code> attribute of an HTML anchor tag so when the link is clicked the appropriate action
will be executed. Depending on the type of the parent form, different button types are supported.</p>