<h2>[MM_Access_Decision]</h2>

<a onclick="stl_js.insertContent('[MM_Access_Decision access=\'\'][/MM_Access_Decision]');" class="mm-ui-button blue">Insert SmartTag</a>
<?php $docUrl = "https://membermouse.uservoice.com/knowledgebase/articles/319157-mm-access-decision-smarttag"; ?>
<a href="<?php echo $docUrl; ?>" target="_blank" class="mm-ui-button">View Documentation</a>

<p>This tag is used to show or hide content based on the currently logged in member's access rights to a particular page/post. 
MemberMouse's default content protection scheme is to hard-block pages that are protected. This means that if a non-member tries 
to view a protected page, they'll be redirected to an error page. <code>[MM_Access_Decision]</code> tags provide you with an 
opportunity to expose portions of protected content based on the visitor's access rights. Within a single page, you could have
teaser content for non-members and protected content for paying members. By using the <code>[MM_Access_Decision]</code> tag with 
different access types you can tailor the visitor's experience on the page.</p>

<p><strong>IMPORTANT NOTE:</strong> As soon as you use <code>[MM_Access_Decision access='false']</code>
or <code>[MM_Access_Decision access='future']</code> on a page/post, you're instructing MemberMouse to 
bypass the standard hard-block protection mechanism. This means that as soon as you use one of these tags, any content outside of 
a <code>[MM_Access_Decision access='']</code> tag, will be visible to anyone who can access the page.</p>

<p>For example, when you use the <code>[MM_Access_Decision access='false']</code> tag on a page, you're 
instructing MemberMouse to allow everyone to access that page and that you'll control what they can see by placing content within 
the appropriate <code>[MM_Access_Decision access='']</code> tag. Placing content outside of the 
<code>[MM_Access_Decision access='']</code> tag gives you an opportunity to insert content that everyone should see while only entering it once, 
but just be aware that it will not be protected and everyone can see it.</p>
