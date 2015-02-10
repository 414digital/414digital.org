<?php 
/**
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */
?>
<table id="mm-social-login-linked-accounts-table">
	<thead>
		<tr>
      		<th id="mm-social-login-network-column">Network</th>
		 	<th id="mm-social-login-account-name-column">Account Name</th>
			<th id="mm-social-login-actions-column"></th>
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