<?php
/**
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */

$saved = false;

if(isset($_POST["mm-country"]))
{	
	if(isset($_POST["mm-country"]) && is_array($_POST["mm-country"]) && count($_POST["mm-country"]) > 0)
	{
		$selections = array();
		
		foreach($_POST["mm-country"] as $iso)
		{	
			$selections[$iso] = $iso;
		}
		
		MM_OptionUtils::setOption(MM_OptionUtils::$OPTION_KEY_COUNTRY_SELECTIONS, $selections);
		
		$saved = true;
	}
	
	if(isset($_POST["mm-default-country"]))
	{
		MM_OptionUtils::setOption(MM_OptionUtils::$OPTION_KEY_DFLT_COUNTRY_SELECTION, $_POST["mm-default-country"]);
	}
}

// get country selections
$selections = MM_OptionUtils::getOption(MM_OptionUtils::$OPTION_KEY_COUNTRY_SELECTIONS);
$dfltCountry = MM_OptionUtils::getOption(MM_OptionUtils::$OPTION_KEY_DFLT_COUNTRY_SELECTION);
$fullCountryList = MM_HtmlUtils::getFullCountryList($selections);
$countryList = MM_HtmlUtils::getCountryList($dfltCountry);
?>
<div class="mm-wrap">
<form name='savecountry' method='post'>
   <p><strong>Select the countries customers can make purchases from below:</strong></p>   
	
	<p style="margin-left:15px;"><select name='mm-country[]' multiple size='100' style='height: 300px; width: 400px; font-size: 12px;'>
	<?php echo $fullCountryList; ?>
	</select></p>
	
	<p style="font-size:11px; margin-left:15px;">
	Select Multiple Countries: PC <code>ctrl + click</code>
	Mac <code><img width="9" height="9" src="http://km.support.apple.com/library/APPLE/APPLECARE_ALLGEOS/HT1343/ks_command.gif" alt="Command key icon" data-hires="true">
(Command key) + click</code>
	</p>
	
	<p><strong>Select the default country to display to customers:</strong></p> 
	<p style="margin-left:15px;"><select name="mm-default-country">
	<?php echo $countryList; ?>
	</select></p>
	
	<p><input type='submit' name='save' value='Save Settings' class="mm-ui-button blue" /></p>
</form>
</div>

<?php if($saved) { ?>
<script type='text/javascript'>
	alert("Settings saved successfully");
</script>
<?php } ?>