<?php 
// This file demonstrates how to use the MemberMouse PHP Interface.
?>

<h2>[MM_Content_Data]</h2>

<!-- NOTE: The id attribute should be changed to a valid Post/Page ID from your MemberMouse site. -->
<p>Title: <?php echo mm_content_data(array("id"=>"33", "name"=>"title")); ?></p>
<p>Date Available: <?php echo mm_content_data(array("id"=>"33", "name"=>"dateavailable")); ?></p>


<h2>[MM_Content_Link]</h2>

<!-- NOTE: The id attribute should be changed to a valid Post/Page ID from your MemberMouse site. -->
<p><a href="<?php echo mm_content_link(array("id"=>"33")); ?>"><?php echo mm_content_data(array("id"=>"33", "name"=>"title")); ?></a></p>


<h2>[MM_CorePage_Link]</h2>

<p><a href="<?php echo mm_corepage_link(array("type"=>"login")); ?>">Login</a></p>
<p><a href="<?php echo mm_corepage_link(array("type"=>"logout")); ?>">Logout</a></p>
<p><a href="<?php echo mm_corepage_link(array("type"=>"homepage")); ?>">Member Homepage</a></p>


<h2>[MM_CustomField_Data]</h2>

<!-- NOTE: The id attribute should be changed to a valid Custom Field ID from your MemberMouse site. -->
<p>Display Name: <?php echo mm_customfield_data(array("id"=>"2", "name"=>"displayName")); ?></p>


<h2>[MM_Employee_Data]</h2>

<!-- NOTE: The id attribute should be changed to a valid Employee ID from your MemberMouse site. -->
<p>Display Name: <?php echo mm_employee_data(array("id"=>"2", "name"=>"displayName")); ?></p>
<p>Email: <?php echo mm_employee_data(array("id"=>"2", "name"=>"email")); ?></p>


<h2>[MM_Member_Data]</h2>

<p>First Name: <?php echo mm_member_data(array("name"=>"firstName")); ?></p>
<p>Last Name: <?php echo mm_member_data(array("name"=>"lastName")); ?></p>
<p>Email: <?php echo mm_member_data(array("name"=>"email")); ?></p>


<h2>[MM_Member_Link]</h2>

<!-- NOTE: The value attribute should be changed to a valid Bundle ID from your MemberMouse site. -->
<p><a href="<?php echo mm_member_link(array("type"=>"cancelMembership")); ?>">Cancel Membership</a></p>
<p><a href="<?php echo mm_member_link(array("type"=>"cancelBundle", "value"=>"4")); ?>">Cancel Bundle</a></p>


<h2>[MM_Order_Data]</h2>

<!-- NOTE: Order data is only available in the context of a confirmation core page page. -->
<p>Order ID: <?php echo mm_order_data(array("name"=>"id")); ?></p>
<p>Total: <?php echo mm_order_data(array("name"=>"total")); ?></p>


<h2>[MM_Purchase_Link]</h2>

<!-- NOTE: The membershipId and productId attributes should be changed to valid Membership Level 
  -- and Product IDs from your MemberMouse site. 
  -->
<p><a href="<?php echo mm_purchase_link(array("membershipId"=>"1")); ?>">Sign Up for Free!</a></p>
<p><a href="<?php echo mm_purchase_link(array("productId"=>"2")); ?>">Buy Now</a></p>


<h2>[MM_Access_Decision]</h2>

<p>
	Does the current visitor have access to the current content?
	<?php echo (mm_access_decision(array("access"=>"true")) == true) ? "Yes" : "No"; ?>
</p>
<!-- NOTE: The id attribute should be changed to a valid Page/Post ID from your MemberMouse site. -->
<p>
	Does the current visitor have access to a specific piece of content?
	<?php echo (mm_access_decision(array("id"=>"33", "access"=>"true")) == true) ? "Yes" : "No"; ?>
</p>


<h2>[MM_Affiliate_Decision]</h2>

<!-- NOTE: The affiliate attribute should be changed to a valid affiliate ID. -->
<p>
	Is the current visitor a referral of affiliate <em>superaffiliate</em>?
	<?php echo (mm_affiliate_decision(array("affiliate"=>"superaffiliate")) == true) ? "Yes" : "No"; ?>
</p>


<h2>[MM_Custom_Decision]</h2>

<p>
	Did the current visitor come from a banner?
	<?php echo (mm_custom_decision(array("source"=>"banner")) == true) ? "Yes" : "No"; ?>
	<a href="<?php echo MM_Utils::appendUrlParam(MM_Utils::constructPageUrl(), "source", "banner"); ?>">Click here to test</a>
</p>
<p>
	Did the current visitor come from an email?
	<?php echo (mm_custom_decision(array("source"=>"email")) == true) ? "Yes" : "No"; ?>
	<a href="<?php echo MM_Utils::appendUrlParam(MM_Utils::constructPageUrl(), "source", "email"); ?>">Click here to test</a>
</p>


<h2>[MM_Member_Decision]</h2>

<!-- NOTE: The membershidId attribute should be changed to a valid Membership Level ID from your MemberMouse site. -->
<p>
	Is the current visitor a member? 
	<?php echo (mm_member_decision(array("isMember"=>"true")) == true) ? "Yes" : "No"; ?>
</p>
<p>
	Is the current member signed up for the membership level with ID 2?
	<?php echo (mm_member_decision(array("membershipId"=>"2")) == true) ? "Yes" : "No"; ?>
</p>


<h2>[MM_Order_Decision]</h2>

<!-- NOTE: Order decisions can only be used within checkout forms and on confirmation core pages. -->
<p>
	Is the current order shippable? 
	<?php echo (mm_order_decision(array("isShippable"=>"true")) == true) ? "Yes" : "No"; ?>
</p>
<p>
	Is the customer purchasing product ID 2?
	<?php echo (mm_order_decision(array("productId"=>"2")) == true) ? "Yes" : "No"; ?>
</p>