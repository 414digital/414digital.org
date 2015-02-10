<?php
/**
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */

// ---- HELPER FUNCTIONS ---- //

function createTextField($name, $type = "text", $value="")
{
	$str = "<input type='{$type}' name='{$name}' ";
	
	if($value != "")
	{
		$str .= "value='{$value}' ";
	}
	
	return $str."/>";
}

function createRow($label, $field, $colspan="")
{
	if(intval($colspan) == 2)
	{
		return "<tr>
  <td colspan='2'>{$field}</td>
</tr>\n";
	}
	
	return "<tr>
  <td>{$label}</td>
  <td>{$field}</td>
</tr>\n";
}

function generateRows($fields, $requirePost=false)
{
	$generatedHtml = "";
	
	foreach($fields as $field=>$typeArr)
	{
		$type = $typeArr['type'];
		$title = $typeArr['title'];
		
		if(!$requirePost || ($requirePost && isset($_POST[$field])))
		{
			$generatedHtml .= createRow($title, createTextField($field, $type));
		}
	}
	
	return $generatedHtml;
}

// ---- ---- //

$selectedMembershipId = (isset($_POST["membership_level"])) ? $_POST["membership_level"] : 0;
$freeMembershipList = MM_HtmlUtils::getMemberships($selectedMembershipId, true, MM_MembershipLevel::$SUB_TYPE_FREE);

$generatedHtml = "";

if(isset($_POST["membership_level"]))
{
	$hiddenFields = array(
		'membership_level'
	);
	
	$reqFields = array(
		'email'=>array('type'=>'text', 'title'=>'Email'),
	);
	
	$optFields = array(
		'username'=>array('type'=>'text', 'title'=>'Username'),
		'password'=>array('type'=>'password', 'title'=>'Password'),
		'first_name'=>array('type'=>'text', 'title'=>'First Name'),
		'last_name'=>array('type'=>'text', 'title'=>'Last Name'),
		'phone'=>array('type'=>'text', 'title'=>'Phone Number')
	);
	
	$generatedHtml = "<form method=\"post\" action=\"".MM_API_BASE_URL."/webform.php\">\n";
	$generatedHtml .= "<table>\n";
	$generatedHtml .= generateRows($reqFields);
	$generatedHtml .= generateRows($optFields, true);
	
	$customFields = MM_CustomField::getCustomFieldsList();
	
	if(count($customFields) > 0)
	{
		foreach($customFields as $id=>$displayName)
		{
			$customField = new MM_CustomField($id);
			
			if($customField->isValid() && isset($_POST["custom_field_{$id}"]))
			{
				$generatedHtml .= createRow($customField->getDisplayName(), $customField->draw("", "", "custom_field_", false));
			}
		}
	}
	
	// get submit button and hidden fields
	$submitField = createTextField("submit", "submit", "Sign Up");
	
	$hiddenFieldsHtml = "";
	
	foreach($hiddenFields as $field)
	{
		$value = (isset($_POST[$field])) ? $_POST[$field] : "";
		$hiddenFieldsHtml.= createTextField($field, "hidden", $value);
	}
	
	$generatedHtml.= createRow("", "\n  ".$submitField."\n  ".$hiddenFieldsHtml."\n  ");
	$generatedHtml.="</table>\n</form>";
	
	$generatedHtml = str_replace("<", "&lt;", $generatedHtml);
	$generatedHtml = str_replace(">", "&gt;", $generatedHtml);
}
?>

<form method='post'>
<div class="mm-wrap">
	<div style='padding-left: 10px;margin-top:10px;'>
	        
	    <div style='width:650px'>
			<p>On this page you can build a webform that can be included on any site. It allows 
			prospects to sign up for a free membership on your site. To create the form, just select the 
			free membership level you want prospects to sign up for, then check off the fields that you want to 
			include in the form and click <i>Generate HTML</i>.</p>
	
			<p>The HTML for your webform will show up in a text box to the right. Just copy and paste this code to your site.</p>
		</div>
	</div>
	
	<div style="padding-left:10px; float:left; width:350px;">
		<?php
	    if(empty($freeMembershipList))
		{
			echo "Please <a href=\"".MM_ModuleUtils::getUrl(MM_MODULE_PRODUCT_SETTINGS, MM_MODULE_MEMBERSHIP_LEVELS)."\">add a free membership level</a> in order to create a free member webform."; 
		}
		else 
		{
		?>	
		<h3>Membership Level</h3>
	
		<select name='membership_level'>
		<?php echo $freeMembershipList; ?>
		</select>
		
	    <h3>Form Fields</h3>

		<table>
			<tr>
				<td width='145px'><span style='color: red;'>*</span> Email</td>
				<td>
					<input type='checkbox' name='email' value='1' checked disabled='disabled'/>
				</td>
			</tr>
			<tr>
				<td>Username</td>
				<td>
					<input type='checkbox' name='username' value='1' <?php echo ((isset($_POST["username"]))?"checked":""); ?>/> 
				</td>
			</tr>
			<tr>
				<td>Password</td>
				<td>
					<input type='checkbox' name='password' value='1' <?php echo ((isset($_POST["password"]))?"checked":""); ?>/> 
				</td>
			</tr>
			<tr>
				<td>First Name</td>
				<td>
					<input type='checkbox' name='first_name' value='1' <?php echo ((isset($_POST["first_name"]))?"checked":""); ?>/> 
				</td>
			</tr>
			<tr>
				<td>Last Name</td>
				<td>
					<input type='checkbox' name='last_name' value='1' <?php echo ((isset($_POST["last_name"]))?"checked":""); ?>/> 
				</td>
			</tr>
			<tr>
				<td>Phone</td>
				<td>
					<input type='checkbox' name='phone' value='1' <?php echo ((isset($_POST["phone"]))?"checked":""); ?>/> 
				</td>
			</tr>
			<?php 
			$customFields = MM_CustomField::getCustomFieldsList();
			
			if(count($customFields) > 0)
			{
				foreach($customFields as $id=>$displayName)
				{
					$customField = new MM_CustomField($id);
					
					if($customField->isValid())
					{
					?>
			<tr>
				<td><?php echo $customField->getDisplayName(); ?></td>
				<td>
					<input type='checkbox' name='custom_field_<?php echo $id; ?>' value='1' <?php echo ((isset($_POST["custom_field_{$id}"]))?"checked":""); ?>/>
				</td>
			</tr>
					<?php
					}
				}
			}
			?>
			<tr>
				<td colspan='2'>
				&nbsp;
				</td>
			</tr>
			<tr>
				<td colspan='2'><input type='submit' name='submit' value='Generate HTML' class="mm-ui-button blue" /></td>
			</tr>
		</table>
		<?php } ?>
	</div>
	
	<?php if($generatedHtml != "") { ?>
	<script type="text/javascript">
    function selectText(containerid) {
        if (document.selection) {
            var range = document.body.createTextRange();
            range.moveToElementText(document.getElementById(containerid));
            range.select();
        } else if (window.getSelection) {
            var range = document.createRange();
            range.selectNode(document.getElementById(containerid));
            window.getSelection().addRange(range);
        }
    }
	</script>
	
	<div style='padding-top: 15px; padding-left: 10px; float:left; '>
		<pre id='mm-form-html' style='font-family: Courier New; border:1px solid #ccc; padding:5px; font-size:11px; width:600px; height:400px; overflow:auto;' onclick="selectText('mm-form-html');"><?php echo $generatedHtml; ?></pre>
	</div>
	<?php } ?>
</div>
</form>