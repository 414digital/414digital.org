<?php 
/**
 *
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */

global $current_user;
$memberTypeId=$p->member_type_id;
$user = new MM_User($current_user->ID);
$userMemberType = new MM_MembershipLevel($user->getMembershipId());
$memberType = new MM_MembershipLevel($memberTypeId);

$costOfNewMemberType = "Free";
if(!$memberType->isFree()){
	$product = new MM_Product($memberType->getDefaultProduct());
	$costOfNewMemberType = "\$".$product->getPrice(true);
	
}

$refund ="N/A";
$refundFloat = 0;
$currentMembershipPrice = "Free";
if(!$userMemberType->isFree())
{
	$existingProduct = new MM_Product($userMemberType->getDefaultProduct());

	if($existingProduct->isValid())
	{
		$currentMembershipPrice = "\$".$existingProduct->getPrice(true);	
	}
}
?>
<input type='hidden' id='mm-member-type-id' value='<?php echo $memberTypeId; ?>' />
<table style='font-size: 14px;'> 
<tr>
	<td style='font-size: 14px;'> 
		Are you sure you'd like to change your membership level from <?php echo $user->getMembershipName(); ?> to  <?php echo $memberType->getName(); ?>?
	</td> 
</tr> 
<tr>
	<td align='center'>
		<table border='0' style='width: 80%; font-size: 14px;'>
			<tr>
				<td>Current membership price</td>
				<td><?php echo $currentMembershipPrice; ?></td>
			</tr>
			<?php if($refundFloat>0){ ?>
			<tr>
				<td>Refund for current cycle</td>
				<td><?php echo $refund; ?></td>
			</tr>
			<?php } ?>
			<tr>
				<td>Charge today for new membership</td>
				<td><?php echo $costOfNewMemberType; ?></td>
			</tr>
		</table>
		
	</td>
</tr>
</table> 