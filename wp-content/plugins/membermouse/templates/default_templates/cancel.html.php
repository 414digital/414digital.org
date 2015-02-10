<?php 
/**
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */
?>
[MM_Member_Decision isMember='true' status='active|overdue']
Click the link below to cancel your membership:
<a href="[MM_Member_Link type='cancelMembership']">Cancel Membership</a>
[/MM_Member_Decision]

[MM_Member_Decision isMember='true' status='pending_cancel']
Your subscription has been canceled and you will no longer be billed.
Your account will remain active until [MM_Member_Data name='cancellationDate' dateFormat='M j, Y'].
[/MM_Member_Decision]

[MM_Member_Decision status='canceled']
Your account is now canceled.
[/MM_Member_Decision]

[MM_Member_Decision isMember='false']
Your account is canceled.
[/MM_Member_Decision]