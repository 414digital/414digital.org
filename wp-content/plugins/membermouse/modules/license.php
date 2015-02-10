<?php
/**
 *
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */

if(isset($_REQUEST["mm-update-license"]))
{
	MM_MemberMouseService::authorize(false);
}

$license = new MM_License();

$permissions = $license->getPermissions();

if(!empty($permissions))
{
	$permissions = "";
	
	$obj = json_decode($license->getPermissions());
	if($obj)
	{
		foreach($obj as $key=>$value)
		{
			$permissions .= $key.": ".$value."<br/>";
		}
	}
	else
	{
		$permissions = $data->$fieldName;
	}
}

?>
<div class="mm-wrap">
    <p class="mm-header-text">License</p>
	
	<form method="post">
		<input type="hidden" name="mm-update-license" value="1" />
		<input type='submit' value='Update License' class="mm-ui-button blue" />
	</form>
	
	<div style="width:500px; line-height:1.2em;">
		<p><strong>ID</strong> <?php echo $license->getId(); ?></p>
		<p><strong>Name</strong> <?php echo $license->getName(); ?></p>
		<p><strong>URL</strong> <?php echo $license->getUrl(); ?></p>
		<p><strong>Major Version</strong> <?php echo $license->getMajorVersion(); ?></p>
		<p><strong>Minor Version</strong> <?php echo $license->getMinorVersion(); ?></p>
		<p><strong>Profile ID</strong> <?php echo $license->getpermissionsProfileId(); ?></p>
		<p><strong>Permissions</strong><br/> <?php echo $permissions ?></p>
		<p><strong>Date Added</strong> <?php echo date('M j, Y g:i a', strtotime($license->getDateAdded())); ?></p>
		<p><strong>Last Updated</strong> <?php echo date('M j, Y g:i a', strtotime($license->getLastUpdated())); ?></p>
	</div>
</div>