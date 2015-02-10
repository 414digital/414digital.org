<?php
/**
 *
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */

$error = "";
if(isset($_POST[MM_OptionUtils::$OPTION_KEY_AFFILIATE_LIFESPAN]))
{
	if(!MM_Utils::isGetParamAllowed($_POST[MM_OptionUtils::$OPTION_KEY_AFFILIATE]))
	{
		$error = $_POST[MM_OptionUtils::$OPTION_KEY_AFFILIATE]." is a WordPress reserved keyword";
	}
	
	if(!MM_Utils::isGetParamAllowed($_POST[MM_OptionUtils::$OPTION_KEY_SUB_AFFILIATE]))
	{
		$error = $_POST[MM_OptionUtils::$OPTION_KEY_SUB_AFFILIATE]." is a WordPress reserved keyword";
	}
	
	if(!empty($_POST[MM_OptionUtils::$OPTION_KEY_AFFILIATE_ALIAS]))
	{
		$affiliateAliases = explode(",", $_POST[MM_OptionUtils::$OPTION_KEY_AFFILIATE_ALIAS]);
		
		foreach($affiliateAliases as $alias)
		{
			if(!MM_Utils::isGetParamAllowed(trim($alias)))
			{
				$error = $alias." is a WordPress reserved keyword";
				break;
			}
		}
	}
	
	if(!empty($_POST[MM_OptionUtils::$OPTION_KEY_SUB_AFFILIATE_ALIAS]))
	{
		$affiliateAliases = explode(",", $_POST[MM_OptionUtils::$OPTION_KEY_SUB_AFFILIATE_ALIAS]);
		
		foreach($affiliateAliases as $alias)
		{
			if(!MM_Utils::isGetParamAllowed(trim($alias)))
			{
				$error = $alias." is a WordPress reserved keyword";
				break;
			}
		}
	}
	
	if(empty($error))
	{
		MM_OptionUtils::setOption(MM_OptionUtils::$OPTION_KEY_AFFILIATE, $_POST[MM_OptionUtils::$OPTION_KEY_AFFILIATE]);
		MM_OptionUtils::setOption(MM_OptionUtils::$OPTION_KEY_AFFILIATE_ALIAS, $_POST[MM_OptionUtils::$OPTION_KEY_AFFILIATE_ALIAS]);
		MM_OptionUtils::setOption(MM_OptionUtils::$OPTION_KEY_SUB_AFFILIATE, $_POST[MM_OptionUtils::$OPTION_KEY_SUB_AFFILIATE]);
		MM_OptionUtils::setOption(MM_OptionUtils::$OPTION_KEY_SUB_AFFILIATE_ALIAS, $_POST[MM_OptionUtils::$OPTION_KEY_SUB_AFFILIATE_ALIAS]);
		MM_OptionUtils::setOption(MM_OptionUtils::$OPTION_KEY_AFFILIATE_LIFESPAN, $_POST[MM_OptionUtils::$OPTION_KEY_AFFILIATE_LIFESPAN]);
	}
	else
	{
		unset($_POST[MM_OptionUtils::$OPTION_KEY_AFFILIATE_LIFESPAN]);
	}
}

$affiliateId = MM_OptionUtils::getOption(MM_OptionUtils::$OPTION_KEY_AFFILIATE);
$affiliateIdAlias = MM_OptionUtils::getOption(MM_OptionUtils::$OPTION_KEY_AFFILIATE_ALIAS);
$subAffiliateId = MM_OptionUtils::getOption(MM_OptionUtils::$OPTION_KEY_SUB_AFFILIATE);
$subAffiliateIdAlias = MM_OptionUtils::getOption(MM_OptionUtils::$OPTION_KEY_SUB_AFFILIATE_ALIAS);
$lifespan = MM_OptionUtils::getOption(MM_OptionUtils::$OPTION_KEY_AFFILIATE_LIFESPAN);

if(!preg_match("/[0-9]+/", $lifespan))
{
	$lifespan = "1";
}

$aliasDescription = "You can use aliases if there's more than one querystring parameter you want to be treated as an affiliate ID. When MemberMouse sees an alias parameter in the querystring it will store that value as the affiliate ID provided that an affiliate is not already set. If two or more affiliate parameters are passed at the same time they will be processed in alphabetical order and the first one is the ordered list will take precedence.";
?>

<form method='post'>
<div class="mm-wrap">
<div style="width:600px">

<p>Affiliate IDs and Sub-affiliate IDs are passed to your site via parameters appended to the end of the URL (read this article to 
<a href="http://support.membermouse.com/knowledgebase/articles/319248-create-an-affiliate-link" target="_blank">learn more about creating affiliate links</a>). Enter the parameter keywords below that you want to use
to pass affiliate and sub-affiliate IDs to your site:</p>
</div>
<div id="mm-form-container" style="margin-top: 10px; margin-bottom: 15px;">	
	<table>
		<?php 
			$affiliateProvider = MM_AffiliateProviderFactory::getActiveProvider();
			if($affiliateProvider->supportsFeature(MM_AffiliateProviderFeatures::DEFAULT_AFFILIATE_ID))
			{
				$affiliateProviderDescription = "This affiliate keyword is configured by default because {$affiliateProvider->getName()} is the currently active affiliate provider.";
		?>
		<tr>
			<td>
				<?php echo $affiliateProvider->getName(); ?> Keyword
				<?php echo MM_Utils::getInfoIcon($affiliateProviderDescription, ""); ?>
			</td>
			<td>
				<span style="font-family:courier;">
					<?php echo $affiliateProvider->getAffiliateTrackingId(); ?>
				</span>
			</td>
		</tr>
		<tr><td style="height:5px;"></td></tr>
		<?php } ?>
		
		<tr>
			<td width='150px'>
				Affiliate Keyword
			</td>
			<td>
				<input type='text' name='<?php echo MM_OptionUtils::$OPTION_KEY_AFFILIATE; ?>' value='<?php echo $affiliateId; ?>' size='30' style="font-family:courier;" />
			</td>
		</tr>
		<tr>
			<td width='125px'>
				Affiliate Keyword Aliases
			</td>
			<td>
				<input type='text' name='<?php echo MM_OptionUtils::$OPTION_KEY_AFFILIATE_ALIAS; ?>' value='<?php echo $affiliateIdAlias; ?>' size='30' style="font-family:courier;" />
				
				<span style="margin-left:5px; color:#999;">
					<?php echo MM_Utils::getInfoIcon($aliasDescription, ""); ?>
					<em>separate multiple aliases with commas</em>
				</span>
			</td>
		</tr>
		<tr><td style="height:5px;"></td></tr>
		<tr>
			<td>
				Sub-Affiliate Keyword
			</td>
			<td>
				<input type='text' name='<?php echo MM_OptionUtils::$OPTION_KEY_SUB_AFFILIATE; ?>' value='<?php echo $subAffiliateId; ?>' size='30' style="font-family:courier;" />
			</td>
		</tr>
		<tr>
			<td width='125px'>
				Sub-Affiliate Keyword Aliases
			</td>
			<td>
				<input type='text' name='<?php echo MM_OptionUtils::$OPTION_KEY_SUB_AFFILIATE_ALIAS; ?>' value='<?php echo $subAffiliateIdAlias; ?>' size='30' style="font-family:courier;" />
				
				<span style="margin-left:5px; color:#999;">
					<?php echo MM_Utils::getInfoIcon($aliasDescription, ""); ?>
					<em>separate multiple aliases with commas</em>
				</span>
			</td>
		</tr>
		<tr><td style="height:5px;"></td></tr>
		<tr>
			<td>
				Lifespan
			</td>
			<td>
				<input type='text' style='width: 50px;' name='<?php echo MM_OptionUtils::$OPTION_KEY_AFFILIATE_LIFESPAN; ?>' value='<?php echo $lifespan; ?>' style="font-family:courier;" /> days
				</td>
			</tr>
		</table>
<p><input type='submit' value='Save Settings' class="mm-ui-button blue" /></p>
</div>
</div>
</form>

<script type='text/javascript'>
<?php if(!empty($error)){ ?>
alert('<?php echo $error; ?>');
<?php  } ?>
</script>

<?php if(isset($_POST[MM_OptionUtils::$OPTION_KEY_AFFILIATE_LIFESPAN])) { ?>
<script>alert("Settings saved successfully");</script>
<?php } ?>