<?php
/**
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */

if(isset($_POST["mm_member_homepage_setting"]))
{
	MM_OptionUtils::setOption(MM_OptionUtils::$OPTION_KEY_ON_LOGIN_USE_WP_FRONTPAGE, $_POST["mm_member_homepage_setting"]);
}

if(isset($_POST["mm_member_homepage_use_mbr_homepage"]))
{
	MM_OptionUtils::setOption(MM_OptionUtils::$OPTION_KEY_AFTER_LOGIN_USE_WP_FRONTPAGE, $_POST["mm_member_homepage_use_mbr_homepage"]);
}

$useWPFrontpageOnLogin = MM_OptionUtils::getOption(MM_OptionUtils::$OPTION_KEY_ON_LOGIN_USE_WP_FRONTPAGE);
$useWPFrontpageAfterLogin = MM_OptionUtils::getOption(MM_OptionUtils::$OPTION_KEY_AFTER_LOGIN_USE_WP_FRONTPAGE);

?>

<div class="mm-wrap">
    <p class="mm-header-text">Member Homepage Settings <span style="font-size:12px;"><a href="https://membermouse.uservoice.com/knowledgebase/articles/319080-member-homepage-settings" target="_blank">Learn more</a></span></p>
	<div id="mm-form-container2" style="margin-top: 10px;">
	<p>When members login...</p>
		<div>
			<input onchange="mmjs.showMbrHomepageOptions();" name="mm_member_homepage_setting" value='0' type="radio" <?php echo (($useWPFrontpageOnLogin!="1")?"checked":""); ?>  />
			Redirect to a homepage specific to their membership level
		</div>
		<div style="magin-top:30px; margin-left:20px; margin-botton:20px; line-height:22px;" id="mm-mbr-homepage-options-div">
			When members are logged in and click to go to the site's homepage...<br/>
			<input name="mm_member_homepage_use_mbr_homepage" value='0' type="radio" <?php echo (($useWPFrontpageAfterLogin!="1")?"checked":""); ?>  />
				Send them to the homepage associated with their membership level<br/>
			<input name="mm_member_homepage_use_mbr_homepage" value='1' type="radio" <?php echo (($useWPFrontpageAfterLogin=="1")?"checked":""); ?>  />
				Send them to the default homepage associated with the WordPress site as defined <a href='options-reading.php'>here</a>
		</div>
		<div style="margin-top:10px;">
			<input onchange="mmjs.showMbrHomepageOptions();" name="mm_member_homepage_setting"  value='1' type="radio" <?php echo (($useWPFrontpageOnLogin=="1")?"checked":""); ?>  />
			Redirect to the default homepage associated with the WordPress site as defined <a href='options-reading.php'>here</a>
		</div>
		
	</div>
</div>
<script type='text/javascript'>
mmjs.showMbrHomepageOptions();
</script>