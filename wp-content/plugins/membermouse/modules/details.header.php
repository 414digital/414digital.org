<?php
/**
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */
	$crntPage = MM_ModuleUtils::getPage();
	$module = MM_ModuleUtils::getModule();
	
	if($user->getFullName() != "") 
	{
		$displayName = $user->getFullName();
	}
	else 
	{
		$displayName = $user->getEmail();
	}
?> 
	<div class="mm-sub-header">
   		<h3>
   			<?php 
   				echo MM_Status::getImage($user->getStatus());
   				
   				if($user->isComplimentary()) 
   				{
   					echo MM_Utils::getIcon('ticket', 'purple', '1.2em', '1px', "Membership is complimentary", "margin-left:4px;");
   				} 
   			?>
   			Member Details for <?php echo $displayName; ?>
   		</h3>
   		<p>
   			<?php 
   			echo MM_Utils::getIcon('user', 'blue', '1.2em', '1px', "Membership Level", "margin-right:8px;");
   				
   			if($user->isImported()) 
   			{
   				echo MM_Utils::getIcon('sign-in', 'blue', '1.2em', '1px', "Membership Level (Member Imported)", "margin-right:8px;");
   			} 
   				
   			echo $user->getMembershipName(); 
   				
			$appliedBundles = $user->getAppliedBundleNames();
			
			if(!empty($appliedBundles)) 
			{
				echo MM_Utils::getIcon('cubes', 'yellow', '1.2em', '1px', "Bundles", "margin-left:15px;");
				echo $appliedBundles;
		 	} 
		 	?>
   		</p>
   	</div>
