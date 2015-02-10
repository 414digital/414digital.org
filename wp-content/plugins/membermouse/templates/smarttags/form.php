<h2>[MM_Form]</h2>

<a onclick="stl_js.insertContent('[MM_Form type=\'\'][/MM_Form]');" class="mm-ui-button blue">Insert SmartTag</a>
<?php $docUrl = "https://membermouse.uservoice.com/knowledgebase/articles/319149-mm-form-smarttag"; ?>
<a href="<?php echo $docUrl; ?>" target="_blank" class="mm-ui-button">View Documentation</a>

<p>This tag is the primary tag required for inserting a form into a page. Using this tag alone won't result in anything 
being rendered to the screen for the user to see. Its function is to specify the form type and provide any default parameters
to all form tags contained within this tag so that MemberMouse knows how to process them. This tag is meant to be used in conjunction 
with other form tags.</p>