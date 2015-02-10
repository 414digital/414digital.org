<?php 
/**
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */

$REQUIRED_ROW_COUNT_FOR_BOTTOM_CONTROLS = 20; 
$isAlternate = true;
?>

<?php if($p->showPagingControls) { echo MM_Template::generate(MM_MODULES."/datagrid.controls.php", $p); } ?>
<table <?php echo $p->datagrid->attr; ?>>
	<thead>
		<tr>
		<?php foreach($p->datagrid->headers as $key=>$header) { ?>
			<th <?php echo ((isset($header["attr"]))?$header["attr"]:""); ?>><?php echo $header["content"]; ?></th>
		<?php } ?>
		</tr>
	</thead>
	
	<?php foreach($p->datagrid->rows as $key=>$record) { ?>
	<tr <?php echo ($isAlternate) ? "class=\"alternate\"" : ""; ?>>
		<?php foreach($record as $key=>$field){ ?>
				<td <?php echo ((isset($field["attr"]))?$field["attr"]:""); ?>><?php echo $field["content"]; ?></td>
		<?php } ?>
	</tr>
	<?php 
			$isAlternate = !$isAlternate;
		} 
	?>
	<?php if(count($p->datagrid->rows) > $REQUIRED_ROW_COUNT_FOR_BOTTOM_CONTROLS) { ?>
	<tfoot>
		<tr>
		<?php foreach($p->datagrid->headers as $key=>$header){ ?>
			<th <?php echo ((isset($header["attr"]))?$header["attr"]:""); ?>><?php echo $header["content"]; ?></th>
		<?php } ?>
		</tr>
	</tfoot>
	<?php } ?>
</table>
<?php if(count($p->datagrid->rows) > $REQUIRED_ROW_COUNT_FOR_BOTTOM_CONTROLS && $p->showPagingControls) { echo MM_Template::generate(MM_MODULES."/datagrid.controls.php", $p); } ?>

<script>
	if ('undefined' !== typeof mmjs)
	{
		mmjs.setDataGridProps('<?php echo $p->sortBy; ?>', '<?php echo $p->sortDir; ?>', '<?php echo $p->crntPage; ?>', '<?php echo $p->resultSize; ?>');
	}
	else if('undefined' !== typeof stl_js)
	{
		stl_js.setDataGridProps('<?php echo $p->sortBy; ?>', '<?php echo $p->sortDir; ?>', '<?php echo $p->crntPage; ?>', '<?php echo $p->resultSize; ?>');
	}
</script>