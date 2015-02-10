<h2>[MM_Employee_Data]</h2>

<a onclick="stl_js.insertContent('[MM_Employee_Data name=\'\']');" class="mm-ui-button blue">Insert SmartTag</a>
<?php $docUrl = "https://membermouse.uservoice.com/knowledgebase/articles/319164-mm-employee-data-smarttag"; ?>
<a href="<?php echo $docUrl; ?>" target="_blank" class="mm-ui-button">View Documentation</a>

<p>This tag outputs data associated with the employee account associated with the ID passed. If not ID is passed either the default account is used or,
if the tag is used in the context of a welcome email or other email template, the employee account used to send that message is used.</p>