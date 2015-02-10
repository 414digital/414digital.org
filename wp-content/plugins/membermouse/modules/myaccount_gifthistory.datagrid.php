<?php 
/**
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */
?>
<table id="mm-gift-history-table">
	<thead>
		<tr>
      		<th id="mm-gift-history-date-column">Date Purchased</th>
		 	<th id="mm-gift-history-description-column">Description</th>
			<th id="mm-gift-history-status-column">Status</th>
			<th id="mm-gift-history-action-column"></th>
      	</tr>
	</thead>
	<tbody>
		<?php foreach($p->datagrid->rows as $key=>$record) { ?>
		<tr>
			<?php foreach($record as $key=>$field) { ?>
				<td><?php echo $field["content"]; ?></td>
			<?php } ?>
		</tr>
		<?php } ?>
	</tbody>
</table>