<?php
/**
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */
if(isset($_POST["mm_acct_sharing_max_ips"]))
{
	if(isset($_POST["mm_enable_acct_sharing_prevention"]) && $_POST["mm_enable_acct_sharing_prevention"] == "on") {
		if(intval($_POST["mm_acct_sharing_max_ips"]) < 1)
		{
		?>
			<script>
			alert("Please enter the maximum number of IP addresses for Account Sharing Protection. It must be greater than 0.");
			</script>
		<?php
		}
		else
		{
			MM_OptionUtils::setOption(MM_OptionUtils::$OPTION_KEY_ACCT_SECURITY_ENABLED, "1");
			MM_OptionUtils::setOption(MM_OptionUtils::$OPTION_KEY_ACCT_SECURITY_MAX_IPS, $_POST["mm_acct_sharing_max_ips"]);
		}
	}
	else 
	{
		MM_OptionUtils::setOption(MM_OptionUtils::$OPTION_KEY_ACCT_SECURITY_ENABLED, "0");
	}
}

$enabled = MM_OptionUtils::getOption(MM_OptionUtils::$OPTION_KEY_ACCT_SECURITY_ENABLED);
$maxIPs = MM_OptionUtils::getOption(MM_OptionUtils::$OPTION_KEY_ACCT_SECURITY_MAX_IPS);

if($maxIPs === false || $maxIPs === "")
{
	$maxIPs = MM_OptionUtils::$DEFAULT_ACCT_SECURITY_MAX_IPS;
}
?>
<div style="width: 600px; margin-top: 8px;" class="mm-divider"></div> 
<div class="mm-wrap">
    <p class="mm-header-text">Account Sharing Protection <span style="font-size:12px;"><a href="https://membermouse.uservoice.com/knowledgebase/articles/319079-account-sharing-protection" target="_blank">Learn more</a></span></p>
   
	<div style="margin-top:10px;">
		<input onchange="mmjs.showAccountSharingForm()" id="mm-cb-enable-acct-sharing-prevention" name="mm_enable_acct_sharing_prevention" type="checkbox"  <?php echo (($enabled=="0")?"":"checked"); ?>  />
		Enable account sharing protection
			
		<input id="mm-enable-acct-sharing-prevention" type="hidden" />
	</div>
	<div id="mm-acct-sharing-prevention" style="margin-top:5px; display:none; padding-top:5px;">
		Max IP Addresses Allowed in 24-Hour Period
		<input id="mm-acct-sharing-max-ips" name="mm_acct_sharing_max_ips" type="text" size="5" value="<?php echo $maxIPs; ?>" />
	</div>
</div>

<script type='text/javascript'>
mmjs.showAccountSharingForm();
</script>