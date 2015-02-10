<div class="mm_report_datagrid_container">
<?php echo isset($p->columnFilteringInputs)?$p->columnFilteringInputs:""; ?>

<table class="qlabs_grid_container">

	<?php if (isset($p->showHeaderRow) && ($p->showHeaderRow == true)) { ?>
	<!-- TABLE HEADER -->
	<thead>
		<tr class="header_row">
			<td colspan="<?php echo isset($p->columnHeaderCount)?$p->columnHeaderCount:7; ?>" class="header_cell" nowrap="nowrap" align="center">
				<h2 class="table_header"><?php echo isset($p->gridTitle)?$p->gridTitle:""; ?></h2> <!-- header label -->
				
				<!-- table column filtering panel (optional) -->
				<?php if ($p->columnFilteringEnabled) { ?>
				<label for="table_config" class="table_config"><?php echo isset($p->columnFilterButtonLabel)?$p->columnFilterButtonLabel:"Table Filter";?></label> <!-- button label -->
				<input id="table_config" name="#" type="checkbox" />
				<div class="table_config">
					<h3 class="table_config"><?php echo isset($p->columnFilterPanelHeader)?$p->columnFilterPanelHeader:"Active columns"?></h3> <!-- panel header -->
					<?php echo isset($p->columnFilterPanelList)?$p->columnFilterPanelList:""; ?>			
				</div>
				<!-- / -->
				<?php } ?>
				
			</td>
		</tr>	
	</thead>
	<?php } ?>
	
	<!-- TABLE BODY -->
	<tbody class="data_container">
	
		<!-- SUBHEADER -->
		<!-- define optional columns width here -->
		<?php echo !empty($p->subheaderRow)?$p->subheaderRow:""; ?>
		<!-- / -->

		<?php echo !empty($p->dataSection)?$p->dataSection:""; ?>

	</tbody>
</table>
</div>
<?php if (isset($p->isPaginated) && ($p->isPaginated == true)) { ?>
<div class="mm_report_datagrid_pagecontrols">
	Page <select name="page" onChange="mmjs.changeDirective('<?php echo $p->gridIdentifier;?>',{'page':this.value});" ><?php echo $p->pageSelector; ?></select> of <?php echo $p->totalPages; ?> 
</div>
<?php } ?>