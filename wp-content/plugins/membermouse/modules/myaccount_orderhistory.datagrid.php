<?php 
/**
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */
?>
<table id="mm-order-history-table">
	<thead>
		<tr>
      		<th id="mm-order-history-date-column">Order #</th>
			<th id="mm-order-history-id-column">Order Date</th>
		 	<th id="mm-order-history-description-column">Description</th>
			<th id="mm-order-history-amount-column">Amount</th>
			<th id="mm-order-history-type-column">Type</th>
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