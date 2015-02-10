<?php 
/**
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */
?>

<h3>Thank you for your order, [MM_Member_Data name='firstName']!</h3>

<p>
<strong>Your login credentials are:</strong><br/>
Username: [MM_Member_Data name='username']<br/>
Password: <em>Hidden for security purposes</em>
</p>

[MM_Order_Decision isGift='true']
<p>
This item was purchased as a gift. The following link can be used to redeem the gift:<br/>
[MM_Order_Data name='giftLink']
</p>
[/MM_Order_Decision]

<p>
<strong>Here are your order details:</strong><br/>
Name: [MM_Member_Data name='firstName'] [MM_Member_Data name='lastName']<br/>
Email: [MM_Member_Data name='email']
</p>

[MM_Order_Decision isFree='false']
<p>
Order ID: [MM_Order_Data name='id']<br/>
Subtotal: [MM_Order_Data name='subtotal' doFormat='true']<br/>
[MM_Order_Subdecision isDiscounted='true']
Discount: [MM_Order_Data name='discount' doFormat='true']<br/>
[/MM_Order_Subdecision]
[MM_Order_Subdecision isShippable='true']
Shipping: [MM_Order_Data name='shipping' doFormat='true']<br/>
[/MM_Order_Subdecision]
Order Total: [MM_Order_Data name='total' doFormat='true']
</p>

<p>
Billing Address:<br/>
[MM_Order_Data name='billingAddress']<br/>
[MM_Order_Data name='billingCity'], [MM_Order_Data name='billingState'] [MM_Order_Data name='billingZipCode']<br/>
[MM_Order_Data name='billingCountry']<br/>
</p>
[/MM_Order_Decision]

[MM_Order_Decision isShippable='true']
<p>
Shipping Address:<br/>
[MM_Order_Data name='shippingAddress']<br/>
[MM_Order_Data name='shippingCity'], [MM_Order_Data name='shippingState'] [MM_Order_Data name='shippingZipCode']<br/>
[MM_Order_Data name='shippingCountry']<br/><br/>

Shipping Method: [MM_Order_Data name='shippingMethod']
</p>
[/MM_Order_Decision]

<p>If you have any questions concerning your order, feel free to contact us at <a href="mailto:[MM_Employee_Data name='email']">[MM_Employee_Data name='email']</a>.</p>