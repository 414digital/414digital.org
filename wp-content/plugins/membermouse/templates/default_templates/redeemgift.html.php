<?php 
/**
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */
?>
<div class="mm-checkoutContainer">
[MM_Form type='checkout']
[MM_Form_Message type='error']
	<div class="mm_left_column">
		[MM_Form_Section type='accountInfo']
		<div id="mm-account-information-section" class="mm-checkoutInfoBlock">
			<h3>Account Information</h3>
			<p class="mm-formField">
				<label>First Name:</label>
				[MM_Form_Field type='input' name='firstName' customAttributes='placeholder="First Name"']
			</p>
			<p class="mm-formField">
				<label>Last Name:</label>
				[MM_Form_Field type='input' name='lastName' customAttributes='placeholder="Last Name"'] 
			</p>
			<p class="mm-formField">
				<label>Email:</label>
				[MM_Form_Field type='input' name='email' customAttributes='placeholder="Email Address"'] 
			</p>
			<p class="mm-formField">
				<label>Password:</label>
				[MM_Form_Field type='input' name='password' customAttributes='placeholder="Password"'] 
			</p>
			<p class="mm-formField">
				<label>Phone:</label>
				[MM_Form_Field type='input' name='phone' isRequired='false' customAttributes='placeholder="Phone Number"'] 
			</p>
		</div>
		[/MM_Form_Section]		
		
		[MM_Form_Section type='coupon']
		<div id="mm-coupon-block" class="mm-couponSection mm-checkoutInfoBlock">

			<h3>Gift Code</h3>
			<p class="mm-formField"> 
				[MM_Form_Field name='couponCode'] 
				<a href="[MM_Form_Button type='applyCoupon']" class="mm-button">Apply Gift Code</a>
			</p>
				
			[MM_Form_Message type='couponSuccess']
			[MM_Form_Message type='couponError']  
		</div>
		[/MM_Form_Section]
	</div>
	<div class="mm_right_column">
		<div class="mm-checkoutSection2">
			<h2>[MM_Form_Data name='productName']</h2>
			<p class="mm-productDesc">[MM_Form_Data name='productDescription']</p>
		</div>
		<div class="mm-purchaseSection">
			[MM_Order_Decision isDiscounted='true']
				<a href="[MM_Form_Button type='submit']" class="mm-button large green">Redeem Gift</a>
			[/MM_Order_Decision]
			[MM_Order_Decision isDiscounted='false']
				<em>Enter a valid gift code to redeem your gift</em>
			[/MM_Order_Decision]
		</div>
	</div>
	
	[MM_Form_Field type='hidden' name='phone'] 
	[MM_Form_Field type='hidden' name='billingAddress'] 
	[MM_Form_Field type='hidden' name='billingCity']
	[MM_Form_Field type='hidden' name='billingState'] 
	[MM_Form_Field type='hidden' name='billingZipCode'] 
	[MM_Form_Field type='hidden' name='billingCountry'] 
	[MM_Form_Field type='hidden' name='shippingAddress'] 
	[MM_Form_Field type='hidden' name='shippingCity'] 
	[MM_Form_Field type='hidden' name='shippingState'] 
	[MM_Form_Field type='hidden' name='shippingZipCode'] 
	[MM_Form_Field type='hidden' name='shippingCountry']
[/MM_Form]
</div>