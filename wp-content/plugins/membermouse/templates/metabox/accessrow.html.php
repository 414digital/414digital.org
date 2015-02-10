<?php 
/**
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */
?>
<div style="font-size:12px; text-align:center; float:left; line-height:22px;">
	<?php echo $p->type_icon; ?>
	<?php echo $p->access_name; ?> on day <?php echo $p->days; ?>
	<input type='hidden' id='has_access_rigths' value='1' />
</div>
<div style="text-align:right; line-height:22px;">
	<a href='#' onclick="accessrights_js.edit('mm-post-meta-dialog','<?php echo $p->access_id; ?>','<?php echo $p->access_type; ?>')"><?php echo $p->edit_icon; ?></a>
	<a href='#'  onclick="accessrights_js.remove('<?php echo $p->access_id; ?>','<?php echo $p->access_type; ?>')"><?php echo $p->delete_icon; ?></a>
</div>
<div class="clear"></div>
		