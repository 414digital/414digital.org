<?php
/**
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */
$submodule = !empty($_GET[MM_Session::$PARAM_SUBMODULE]) ? $_GET[MM_Session::$PARAM_SUBMODULE] : "";
?>
<style type='text/css'>
.mm-extension-outer {
	width: 95%;
	margin: 5px auto;
	overflow: hidden;
}
.mm-extension {
	color: #797478;
	width: 27%;
	margin:  2% 2% 50px 2%;
    padding: 1%;
	float: left;
	-webkit-transition: color 0.5s ease;
}

.mm-extension h3 {
	text-transform: uppercase;
	line-height: 2;
}

.mm-extension-active {
	border-radius: 5px;
	background-color: #CEEA9B;
}

.mm-extension img {
	max-width: 100%;
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	border-radius: 5px;
}

/* MEDIA QUERIES*/
@media only screen and (max-width : 940px),
only screen and (max-device-width : 940px){
	.mm-extension {width: 25%;}
}

@media only screen and (max-width : 530px),
only screen and (max-device-width : 530px){
	.mm-extension {width: 46%;}
	.header h1 {font-size: 28px;}
}

@media only screen and (max-width : 320px),
only screen and (max-device-width : 320px){
	.mm-extension {width: 96%;}
	.mm-extension img {width: 96%;}
	.mm-extension h3 {font-size: 18px;}
	.mm-extension p, .header p {font-size: 18px;}
	.header h1 {font-size: 70px;}
}
</style>
<div class="mm-extension-outer">
	<?php if (!empty($submodule)) 
	{ 
		$extension = MM_ExtensionsFactory::getExtension($submodule);
				
		if(!is_null($extension))
		{
			echo $extension->displayConfigScreen();
		}
		else 
		{
			echo "<em>{$submodule}</em> is not a valid extension";
		}
	}
	else 
	{
		$extensionList = MM_ExtensionsFactory::getAvailableExtensions();
		foreach ($extensionList as $anExtension)
		{
			$anExtension->displayListingItem();
		}
	}
	?>
</div>