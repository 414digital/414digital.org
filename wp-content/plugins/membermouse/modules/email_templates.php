<?php 
$error = "";
$settingsSaved = false;

// handle forgot password email
$forgotPasswordEmail = new stdClass();

$forgotPasswordEmail->subject = MM_OptionUtils::getOption(MM_OptionUtils::$OPTION_KEY_FORGOT_PASSWORD_SUBJECT);
$forgotPasswordEmail->body = MM_OptionUtils::getOption(MM_OptionUtils::$OPTION_KEY_FORGOT_PASSWORD_BODY);

if(!isset($forgotPasswordEmail->subject))
{
	$forgotPasswordEmail->subject = "";
}

if(!isset($forgotPasswordEmail->body))
{
	$forgotPasswordEmail->body = "";
}

if(isset($_POST[MM_OptionUtils::$OPTION_KEY_FORGOT_PASSWORD_SUBJECT]) && $_POST[MM_OptionUtils::$OPTION_KEY_FORGOT_PASSWORD_SUBJECT] != $forgotPasswordEmail->subject)
{
	MM_OptionUtils::setOption(MM_OptionUtils::$OPTION_KEY_FORGOT_PASSWORD_SUBJECT, $_POST[MM_OptionUtils::$OPTION_KEY_FORGOT_PASSWORD_SUBJECT]);
	$forgotPasswordEmail->subject = stripslashes($_POST[MM_OptionUtils::$OPTION_KEY_FORGOT_PASSWORD_SUBJECT]);
	$settingsSaved = true;
}

if(isset($_POST[MM_OptionUtils::$OPTION_KEY_FORGOT_PASSWORD_BODY]) && $_POST[MM_OptionUtils::$OPTION_KEY_FORGOT_PASSWORD_BODY] != $forgotPasswordEmail->body)
{
	MM_OptionUtils::setOption(MM_OptionUtils::$OPTION_KEY_FORGOT_PASSWORD_BODY, $_POST[MM_OptionUtils::$OPTION_KEY_FORGOT_PASSWORD_BODY]);
	$forgotPasswordEmail->body = stripslashes($_POST[MM_OptionUtils::$OPTION_KEY_FORGOT_PASSWORD_BODY]);
	$settingsSaved = true;
}
?>
<form name='configure_site_text' method='post' >
<div class="mm-wrap">
    <span class="mm-section-header">Forgot Password Email</span>
	<div id="mm-form-container" style="margin-top: 10px; margin-bottom: 15px;">	
		<div style="margin-top:5px">
			Subject*
			<input name="mm-forgot-password-email-subject" type="text" style="width:454px; font-family:courier; font-size: 11px;" value="<?php echo $forgotPasswordEmail->subject ?>"/>
		</div>
		
		<div style="margin-top:5px">
			Body* <?php echo MM_SmartTagLibraryView::smartTagLibraryButtons("mm-forgot-password-email-body"); ?>
			<?php 
				$validSmartTags = "Only the following SmartTags can be used here:\n";
				$validSmartTags .= "[MM_Access_Decision] (you must provide an ID)\n";
				$validSmartTags .= "[MM_Content_Data] (you must provide an ID)\n";
				$validSmartTags .= "[MM_Content_Link] (you must provide an ID)\n";
				$validSmartTags .= "[MM_CorePage_Link]\n";
				$validSmartTags .= "[MM_CustomField_Data]\n";
				$validSmartTags .= "[MM_Employee_Data]\n";
				$validSmartTags .= "[MM_Member_Data]\n";
				$validSmartTags .= "[MM_Member_Decision]\n";
				$validSmartTags .= "[MM_Member_Link]\n";
				$validSmartTags .= "[MM_Purchase_Link]";
			?>
			<span style="font-size:11px; color:#666666; margin-left: 5px;"><em>Note: Only certain SmartTags can be used here</em></span>
			<?php echo MM_Utils::getInfoIcon($validSmartTags); ?>
		</div>
		
		<div style="margin-top:5px">
			<textarea id='mm-forgot-password-email-body' name='mm-forgot-password-email-body' style="width:500px; height:180px; font-family:courier; font-size: 11px;"><?php echo htmlentities($forgotPasswordEmail->body, ENT_QUOTES, 'UTF-8', true); ?></textarea>
		</div>
	</div>
</div>

<input type='submit' name='submit' value='Save Settings' class="mm-ui-button blue" />
</form>

<script type='text/javascript'>
<?php if(!empty($error)){ ?>
alert('<?php echo $error; ?>');
<?php  } ?>
<?php if($settingsSaved){ ?>
alert('Settings saved successfully');
<?php  } ?>
</script>