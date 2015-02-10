<?php // if successfully retrieved coinbase checkout, go to it ?>
<?php if($p!=false): ?>
	<script>document.location.replace('<?php echo $p; ?>');</script>
<?php // if not, show error and go back ?>
<?php else: ?>
	<script>
	alert("Sorry, a connection error is preventing this bitcoin transaction from working. Could you please choose another payment method or try again later?");
	history.go('-1');
	</script>
<?php endif; ?>