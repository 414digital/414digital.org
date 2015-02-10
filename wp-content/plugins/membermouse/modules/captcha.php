<?php 
if(isset($_POST["mm_captcha_public_key"]))
{
	require_once(MM_PLUGIN_ABSPATH.'/lib/recaptchalib.php');
	$publicKey = $_POST["mm_captcha_public_key"];
	$privateKey = $_POST["mm_captcha_private_key"];
	
	// validate the public key
	if(empty($publicKey))
	{
		MM_OptionUtils::setOption(MM_OptionUtils::$OPTION_KEY_CAPTCHA_KEY, $publicKey);
	}
	else 
	{
		$contents = MM_Utils::sendRequest(RECAPTCHA_API_SERVER. '/challenge?k=' . $publicKey,"",0);
		
		if(preg_match("/(invalid)|(input error)/", strtolower($contents)))
		{
			$error = "The Captcha public key provided is invalid. Please try again.";
		}
		else 
		{
			MM_OptionUtils::setOption(MM_OptionUtils::$OPTION_KEY_CAPTCHA_KEY, $publicKey);
		}
	}
	    
	MM_OptionUtils::setOption(MM_OptionUtils::$OPTION_KEY_CAPTCHA_PRIVATE_KEY, $privateKey);
}

$publicKey = MM_OptionUtils::getOption(MM_OptionUtils::$OPTION_KEY_CAPTCHA_KEY);
$privateKey = MM_OptionUtils::getOption(MM_OptionUtils::$OPTION_KEY_CAPTCHA_PRIVATE_KEY);
?>
<div class="mm-wrap">
    <p class="mm-header-text">Captcha Settings <span style="font-size:12px;"><a href="https://membermouse.uservoice.com/knowledgebase/articles/319226-add-captcha-validation-to-the-checkout-page" target="_blank">Learn more</a></span></p>
	<div style="margin-bottom:10px;">
		<img src="https://dl.dropboxusercontent.com/u/265387542/plugin_images/logos/recaptcha.png" style="vertical-align:middle; margin-right:10px;" />
		<a target="_blank" href="https://www.google.com/recaptcha/admin/create" class="mm-ui-button green">Create a Free Account</a> 
		<a target="_blank" href="https://www.google.com/recaptcha/admin/" class="mm-ui-button">Access Existing Account</a>
	</div>
	
	<table>
		<tr>
			<td width='70'>Public Key </td>
			<td>
				<span style="font-family: courier; font-size: 11px;">
				<input type='text' id='mm_captcha_public_key' name='mm_captcha_public_key' value='<?php echo $publicKey; ?>' size="45" />
				</span>
			</td>
		</tr>
		<tr>
			<td width='70'>Private Key</td>
			<td>
				<span style="font-family: courier; font-size: 11px;">
				<input type='text' id='mm_captcha_private_key' name='mm_captcha_private_key' value='<?php echo $privateKey; ?>' size="45" />
				</span>
			</td>
		</tr>
	</table>
</div>