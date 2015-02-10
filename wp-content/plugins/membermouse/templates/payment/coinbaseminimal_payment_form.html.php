<?php if(!MM_PaymentServiceResponse::isError($p)): ?>

<!--successfully got bitcoin payment info from coinbase-->
<script>window.jQuery || document.write('\x3Cscript src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js">\x3C\/script>');</script>
<script>
jQuery(function(){
	jQuery('title').text('Send Bitcoin payment');
	jQuery('#mm-bitcoin-address').click(function(){jQuery(this).focus().select();});
});
</script>
<style type="text/css">
@media(max-width:650px){
	#mm-bitcoin {
		width:100% !important;
		margin-left:0 !important;
		top:0 !important;
		left:0 !important;
		bottom: 0;
		border-radius:0 !important;
		height: 100% !important;
	}
	#mm-bitcoin > div {
		text-align: center !important;
	}
	#mm-bitcoin h3 {
		padding-top: 0 !important;
		clear: both;
	}
	#mm-bitcoin br {
		display: none;
	}
	#mm-bitcoin-qr {
		float: none !important;
		margin: 0  !important;
	}
}
</style>
<iframe src="<?php bloginfo('wpurl'); ?>" style="position:fixed; height:100%; width:100%; border:0; top:0; left:0; opacity:.5;"></iframe>

<div id="mm-bitcoin" style="position:fixed; width:800px; height:400px; background:white; top:40px; left: 50%; margin:0 0 0 -400px; border:solid 2px #f3f3f3; border-radius:4px; box-shadow: 4px 4px 40px rgba(0,0,0,.1);">
	<h2>Send your Bitcoin Payment</h2>
	<p style="font-style: italic; font-size: .9em;border-color: #f0f0f0;border-style: solid;border-width: 1px 0 1px;padding: 1em;background: #f4f3f3;">
          Please send the amount listed below to the wallet address listed and your<br> purchase will be activated as soon as your payment is confirmed.
	</p>
	<div style="text-align:left">
		<a id="mm-bitcoin-qr" href="<?php echo $p->link; ?>" style="float:left; margin-left:200px;"><img src="<?php echo $p->qr; ?>" alt="Pay with Bitcoin" /></a>
		<h3 style="padding-top:1em;margin-bottom:0.5em;"><?php echo $p->amount; ?> BTC <small>to</small></h3>
		<input type="text" id="mm-bitcoin-address" value="<?php echo $p->address; ?>" readonly style="padding:4px 8px; background:#eee; border:solid 1px #ddd; width:21em; font-family:Courier,monospace;"/>
		<a href="<?php echo $p->confirmation_url; ?>" style="display:inline-block; clear:left; background:#3C7; border-radius:5px; color:white; text-decoration:none; margin-top:12px; padding: .5em 1em;">I've sent that, Continue</a>
	</div>
</div>

<?php else: ?>
	
<!--getting payment info failed-->
<script>
alert("Sorry, a connection error is preventing this bitcoin transaction from working. Could you please choose another payment method or try again later?");
history.go('-1');
</script>

<?php endif; ?>