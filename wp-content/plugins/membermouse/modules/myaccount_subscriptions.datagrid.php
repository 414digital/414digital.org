<?php 
/**
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */
?>
<table id="mm-subscriptions-table">
	<thead>
		<tr>
      		<th id="mm-subscriptions-date-column">Start Date</th>
			<th id="mm-subscriptions-description-column">Description</th>
		 	<th id="mm-subscriptions-amount-column">Amount</th>
			<th id="mm-subscriptions-action-column"></th>
			<th id="mm-subscriptions-status-column"></th>
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