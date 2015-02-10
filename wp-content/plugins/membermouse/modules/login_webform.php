<?php
/**
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */
?>

<div class="mm-wrap">
<div style='padding-left: 10px;margin-top:10px;'>
    
<div style='width:650px'>
<p>Below is a login webform that can be included anywhere which allows your 
members to login from any location.<br/>Just copy and paste the HTML code below:</p>
</div>
	
<textarea id='mm-form-html' rows='18' cols='90' readonly style='font-family: Courier New; font-size:11px;' onclick="jQuery('#mm-form-html').focus(); jQuery('#mm-form-html').select();">
<form action="<?php echo get_option('siteurl'); ?>/wp-login.php" method="post">
<table>
  <tr>
    <td>Username</td>
    <td><input type="text" id="log" name="log" /></td>
  </tr>
  <tr>
    <td>Password</td>
    <td><input type="password" id="pwd" name="pwd" /></td>
  </tr>
  <tr>
    <td></td>
    <td>
      <input type="submit" name="submit" value="Login" />
	  <input name="rememberme" type="checkbox" checked="checked" value="forever" />
	  Remember me
    </td>
  </tr>
  <tr>
    <td></td>
    <td>
      <a href="<?php echo MM_CorePageEngine::getUrl(MM_CorePageType::$FORGOT_PASSWORD); ?>">Forgot Password</a>
    </td>
  </tr>
</table>
</form></textarea>
</div>
</div>