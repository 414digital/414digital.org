<?php 
/**
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */
?>
[MM_Member_Decision isMember='true']
You are already logged in! Need to log out? You can do that <a href="[MM_CorePage_Link type='logout']" title="Log out">here</a>.
[/MM_Member_Decision]

[MM_Member_Decision isMember='false']
[MM_Form type='login']
<div class="mm-login">
[MM_Form_Message type='error']

<h3>Enter your username and password below</h3>

<table>
    <tr>
      	<td class="mm-label-column">
      		<span class='mm-label'>Username</span>
      	</td>
      	<td class="mm-field-column">
      		[MM_Form_Field name='username']
      	</td>
    </tr>
    <tr>
      	<td class="mm-label-column">
      		<span class='mm-label'>Password</span>
      	</td>
      	<td class="mm-field-column">
      		[MM_Form_Field name='password']
      	</td>
    </tr>
    <tr>
      	<td class="mm-label-column"></td>
      	<td class="mm-field-column">
      		[MM_Form_Button type='login' label='Login']
      		[MM_Form_Field name='rememberMe' label='Remember me']
      	</td>
    </tr>
    <tr>
      	<td class="mm-label-column"></td>
      	<td class="mm-field-column">
      		<a href="[MM_CorePage_Link type='forgotPassword']" class="mm-forgot-password">Forgot Password</a>
      	</td>
    </tr>
</table>
</div>
[/MM_Form]
[/MM_Member_Decision]