<?php
/**
 *
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */
if(isset($_POST["cancel_method"]))
{
	MM_OptionUtils::setOption(MM_OptionUtils::$OPTION_KEY_DFLT_CANCELLATION_METHOD, $_POST["cancel_method"]);
}

$cancelChecked = MM_OptionUtils::getOption(MM_OptionUtils::$OPTION_KEY_DFLT_CANCELLATION_METHOD);
?>

<form method='post'>
<div class="mm-wrap" style='width: 600px;'> 
	
<p>MemberMouse supports two different cancellation methods: a hard cancel and pause. With a hard cancel, the member won't be 
able to log in at all. With a pause, the member will be able to log in and access all the protected content they had access to 
up until the time their account was paused. With the paused status, their drip content schedule won't progress so they won't 
get access to any additional content unless their account is reactivated.</p>

<p>There are 3 ways a member's account can be canceled:</p>

<ol>
<li>By the member themselves through the <code>[MM_Member_Link]</code> SmartTag</li>
<li>By an administrator through the Member Details &gt; Manage Access Rights page by clicking <em>Cancel Membership</em> or <em>Pause Membership</em></li>
<li>By MemberMouse, when responding to an event that occurs in your payment service (i.e. stop recurring, void or refund)</li>
</ol>

<p>It's the third case that you need to tell MemberMouse which method to use.</p>

<p>Which cancellation method do you want to use?<p>

<p><input type='radio' value='<?php echo MM_CancellationMethod::$CANCEL_HARD; ?>' id='cancel_method' name='cancel_method' <?php echo (($cancelChecked!="pause")?"checked":""); ?> /> Hard Cancel</p>

<p><input type='radio' value='<?php echo MM_CancellationMethod::$CANCEL_PAUSE; ?>' id='cancel_method' name='cancel_method' <?php echo (($cancelChecked=="pause")?"checked":""); ?>  /> Pause</p>

<input type='submit' value='Save Settings' class="mm-ui-button blue" />
</div>
</form>

<?php if(isset($_POST["cancel_method"])) { ?>
<script>alert("Settings saved successfully");</script>
<?php } ?>