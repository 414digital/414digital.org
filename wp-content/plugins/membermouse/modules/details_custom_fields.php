<?php 
if(isset($_REQUEST[MM_Session::$PARAM_USER_ID])) 
{
	$user = new MM_User($_REQUEST[MM_Session::$PARAM_USER_ID]);
	
	if($user->isValid()) 
	{
		// check to make sure current employee has access to manage this member
		global $current_user;
		$employee = MM_Employee::findByUserId($current_user->ID);
		$allowAccess = true;
		
		if($employee->isValid())
		{
			$allowAccess = $employee->canManageMember($user);
		}
		
		if($allowAccess)
		{
			include_once MM_MODULES."/details.header.php";
			
			$message = "";
			
			if(isset($_POST["custom_submit"]))
			{
				foreach($_POST as $k=>$v)
				{
					if(preg_match("/(mm_custom_field_)/", $k))
					{	
						// bypass radio button and checkbox helper fields
						if(strpos($k, 'helper') === false)
						{
							$fieldId = preg_replace("/[^0-9]+/", "", $k);
							$response = $user->setCustomData($fieldId, $v);
							
							if(MM_Response::isError($response))
							{
								if(!empty($v))
								{
									$message = $response->message;
								}
							}
						}
					}
				}
				
				// does account update event need to be dispatched?
				$fields = MM_CustomField::getCustomFieldsList();
				foreach($fields as $id=>$val)
				{
					if(MM_CustomFieldData::wasRecentlyUpdated($id, $user->getId()))
					{
						do_action(MM_Event::$MEMBER_ACCOUNT_UPDATE, MM_Event::packageMemberData($user->getId()));
						break;
					}
				}
				
				if(empty($message))
				{
					$message = "Custom fields updated successfully";
				}
			}
			
			$fields = MM_CustomField::getCustomFieldDataByUser($user->getId());
?>
<style>
	.mm-short-text-field { width: 250px; }
	.mm-long-text-field { width:400px; height:75px; }
	.mm-radio-button { display:block; clear:both; }
</style>
<form name='mm_custom_post' method='post'>
<div id="mm-form-container">
	
	<div style='margin-top:10px;'>
		<table cellspacing="8">
		<?php 
			foreach($fields as $field) 
			{ 
				$crntField = new MM_CustomField($field->id);
				
				if($crntField->isValid())
				{
		?>
			<tr>
				<td width="120px">
					<span style="margin-right:15px;"><?php echo $field->display_name; ?></span>
				</td>
				<td>
					<?php 
						$class = "";
						
						switch($crntField->getType())
						{
							case MM_CustomField::$TYPE_INPUT:
							case MM_CustomField::$TYPE_DROPDOWN:
								$class = "mm-short-text-field";
								break;
								
							case MM_CustomField::$TYPE_TEXT:
								$class = "mm-long-text-field";
								break;
								
							case MM_CustomField::$TYPE_RADIO:
								$class = "mm-radio-button";
								break;
						}
						
						echo $crntField->draw($field->value, $class, "mm_custom_field_", false, true); ?>
				</td>
			</tr>
		<?php 
				} 
			} 
		?>
		</table>
	</div>
	<div style='margin-top:10px;'>
		<input type="submit" name='custom_submit' class="mm-ui-button blue" value="Update Custom Fields" >	
	</div>
</div>
</form>
<?php 	}
		else
		{
			echo "<div style=\"margin-top:10px;\"><em>You do not have permission to manage this member.</em></div>";
		}
	}
	else 
	{
		echo "<div style=\"margin-top:10px;\"><i>Invalid Member ID</i></div>";
	} 
?>
<?php } ?>

<?php if(!empty($message)) { ?>
<script type='text/javascript'>
alert('<?php echo $message; ?>');
</script>
<?php } ?>