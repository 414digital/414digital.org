<?php 
/**
 *
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */
?>
<div style="margin-top: 8px; margin-bottom: 8px; font-size: 11px;">
	<?php echo MM_Utils::getIcon('cogs', 'grey', '1.4em', '2px'); ?>
	
	<span style="margin-left: 10px;">
	Page 
	<a onclick="mmjs.dgPreviousPage('<?php echo $p->crntPage; ?>')" style="cursor:pointer" title="previous"><?php echo MM_Utils::getIcon('chevron-circle-left', 'light-blue', '1.4em', '1px'); ?></a>
	<?php echo (intval($p->crntPage) + 1); ?>
	<a onclick="mmjs.dgNextPage('<?php echo $p->crntPage; ?>', '<?php echo $p->totalPages; ?>')" style="cursor:pointer" title="next"><?php echo MM_Utils::getIcon('chevron-circle-right', 'light-blue', '1.4em', '1px'); ?></a>
	of
	<?php echo $p->totalPages; ?>
	pages
	</span>
	
	<span style="margin-left: 30px;">
		Show 
		<select onchange="mmjs.dgSetResultSize(this)">
		<?php echo MM_HtmlUtils::getDataGridResultsCount($p->resultSize); ?>
		</select> 
		per page
	</span>
	
	<span style="margin-left: 30px;">
		<?php echo number_format($p->totalRecords); ?> <?php echo $p->recordName; ?> found
	</span>
</div>