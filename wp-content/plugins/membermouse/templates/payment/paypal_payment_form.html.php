<form id="mm-offsite-billing-data" action="<?php echo isset($p->form_destination)?$p->form_destination:"";?>" method="post">
	<?php 
		if (isset($p->configArray) && is_array($p->configArray)) 
		{ 
			foreach ($p->configArray as $k=>$v)
			{
				echo "<input type=\"hidden\" name=\"{$k}\" value=\"{$v}\">\n";
			}
		}
	?>
</form>
<script>document.forms["mm-offsite-billing-data"].submit();</script>