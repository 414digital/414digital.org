<?php 
/**
 *
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */

global $current_user;
?>

<table>
<tr>
	<td><span style='font-size: 14px;'>Are you sure you want to cancel?</span>
	<input type='hidden' id='mm-membership-cancellation-redirect' value='<?php echo $p->redirect_to; ?>' />
	</td>
</tr>
</table>