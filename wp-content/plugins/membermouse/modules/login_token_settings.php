<?php
/**
 *
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */

$error = "";
if(isset($_POST[MM_OptionUtils::$OPTION_KEY_LOGIN_TOKEN_LIFESPAN]))
{
	if(!preg_match("/[0-9]+/", $_POST[MM_OptionUtils::$OPTION_KEY_LOGIN_TOKEN_LIFESPAN]) || intval($_POST[MM_OptionUtils::$OPTION_KEY_LOGIN_TOKEN_LIFESPAN]) <= 0)
	{
		$error = "Login token lifespan must be greater than 0.";
	}
	
	if(empty($error))
	{
		MM_OptionUtils::setOption(MM_OptionUtils::$OPTION_KEY_LOGIN_TOKEN_LIFESPAN, $_POST[MM_OptionUtils::$OPTION_KEY_LOGIN_TOKEN_LIFESPAN]);
	}
}

$lifespan = MM_OptionUtils::getOption(MM_OptionUtils::$OPTION_KEY_LOGIN_TOKEN_LIFESPAN);

if(!preg_match("/[0-9]+/", $lifespan))
{
	$lifespan = MM_OptionUtils::$DEFAULT_LOGIN_TOKEN_LIFESPAN;
}

$loginTokenLifespanDesc = "When the auto-login attribute is set to true on certain [MM_..._Link] SmartTags, a login token is created that allows the customer to be automatically logged in when they click the link. ";
$loginTokenLifespanDesc .= "This setting indicates how long that login token will be valid.";
?>

<div style="width: 600px; margin-top: 8px;" class="mm-divider"></div> 

<div class="mm-wrap">
	<p class="mm-header-text">Login Token Settings</p>
	
	<div style="margin-top:10px;">
		Login tokens are valid for
		<input type='text' style='width: 50px;' name='<?php echo MM_OptionUtils::$OPTION_KEY_LOGIN_TOKEN_LIFESPAN; ?>' value='<?php echo $lifespan; ?>' /> days
		<?php echo MM_Utils::getInfoIcon($loginTokenLifespanDesc, ""); ?>
	</div>
</div>