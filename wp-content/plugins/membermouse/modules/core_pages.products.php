	<tr>
		<td>
			<div id='cp_confirmation_type'  style='width: 270px'>
				<select id='is_free' name='save-mm-corepages[confirmation_type]' onchange="corepages_js.getReferences()">
					<option value='paid' <?php echo (($p->is_free=="0")?"selected":""); ?>>Paid</option>
					<option value='free' <?php echo (($p->is_free=="1")?"selected":""); ?>>Free</option>
				</select>
			</div>	
		</td>
	</tr>
	<tr>
		<td>
			<div id='cp_products'  style='width: 270px'>
				<?php echo $p->options; ?>
				<input type='hidden' name='save-mm-corepages[ref_type]' value='<?php echo $p->ref_type; ?>' />
			</div>	
		</td>
	</tr>
