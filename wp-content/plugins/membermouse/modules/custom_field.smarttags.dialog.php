<?php 
/**
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */

$smarttag = "[MM_Form_Field type='custom' id='{$p->customFieldId}' isRequired='false' class='']";
?>

<div id="mm-form-container" style="width:460px;">
	<div style="font-size:11px;">
		<p>
			Use the SmartTag below to add the '<?php echo $p->customFieldName; ?>' custom field to a 
			MemberMouse checkout form. The SmartTag must be added between the <code>[MM_Form type='checkout']...[/MM_Form]</code> or 
			<code>[MM_Form type='custom']...[/MM_Form]</code> SmartTags.
		</p>
		
		<p style="margin-top:30px; margin-bottom:20px;"><input id="mm-smart-tag" type="text" readonly value="<?php echo htmlentities($smarttag); ?>" style="width:440px; font-family:courier; font-size:11px;" onclick="jQuery('#mm-smart-tag').focus(); jQuery('#mm-smart-tag').select();" /></p>
		
		<ul>
		<li>Set the <code>isRequired</code> attribute to <code>true</code> if you want the field to be required.</li> 
		<li>Set the <code>class</code> attribute to apply a custom CSS class to the field. </li>
		<li>Set the <code>type</code> attribute to <code>custom-hidden</code> to make this a hidden field.</li>
		</ul>
		
		<p>
		Read this article to 
			<a href="https://membermouse.uservoice.com/knowledgebase/articles/319153-mm-form-field-smarttag" target="_blank">learn more about the <code>MM_Form_Field</code> SmartTag</a>.
		</p>
	</div>
</div>