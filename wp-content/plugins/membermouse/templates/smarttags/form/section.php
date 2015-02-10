<h2>[MM_Form_Section]</h2>

<a onclick="stl_js.insertContent('[MM_Form_Section type=\'\'][/MM_Form_Section]');" class="mm-ui-button blue">Insert SmartTag</a>
<?php $docUrl = "https://membermouse.uservoice.com/knowledgebase/articles/319155-mm-form-section-smarttag"; ?>
<a href="<?php echo $docUrl; ?>" target="_blank" class="mm-ui-button">View Documentation</a>

<p>This tag is used in conjunction with the <code>[MM_Form]</code> tag. It is used to identify sections of
content that have a certain significance within the current form. Identifying the content allows MemberMouse to perform
specific actions that enhnace the user experience. For example, on a checkout form by using this tag to create a shipping info section, 
MemberMouse can dynamically show or hide the shipping information based on if the product being purchased is shippable. This ensures that
the user will see only those fields which are necessary for them to fill out based on their unique situation.</p>