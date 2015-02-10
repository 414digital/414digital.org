<form data-parsley-validate>
<div id="<?php echo !empty($p->id)?$p->id:""; ?>" class="<?php echo !empty($p->class)?$p->class:""; ?>" style="<?php echo !empty($p->style)?$p->style:"background-color: #EAF2FA; padding-top:2px; padding-left:8px; padding-bottom:8px; border: 1px dotted black;";?>">
	<table>
		<tr>
			<!-- LEFT COLUMN -->
			<td valign="top">
			<table cellspacing="5">
				<?php echo isset($p->content)?$p->content:""; ?>
			</table>
			</td>
		</tr>
	</table>
	
	<?php echo isset($p->buttons)?$p->buttons:""; ?>
</div>
</form>