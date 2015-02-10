<?php 
/**
 *
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */
?>
<?php wp_nonce_field('save-mm-corepages','save-mm-corepages-nonce'); ?>

<style>
#membermouse_post_access .inside {
    margin: 0;
    padding: 0;
}

#mm_access_rights_meta {
	border-bottom-color: #DFDFDF;
	border-bottom-style: solid;
    border-bottom-width: 1px;
    box-shadow: 0 1px 0 #FFFFFF;
}

#mm_protected_category_meta {
	border-bottom-color: #DFDFDF;
	border-bottom-style: solid;
    border-bottom-width: 1px;
    box-shadow: 0 1px 0 #FFFFFF;
}

#mm_core_pages_meta {
	border-bottom-color: #DFDFDF;
	border-bottom-style: solid;
    border-bottom-width: 1px;
    box-shadow: 0 1px 0 #FFFFFF;
}
</style>
<div id='mm_publish_box'>
	<div id="mm_access_rights_meta">
		<div style="padding:8px;">
			<div style="font-size:13px; text-align:center; float:left; line-height:30px;">
				Access Rights
			</div>
			<div style="text-align:right;">
				<input type='button' name='access_rights' value='Grant Access' onclick="accessrights_js.create('mm-post-meta-dialog', 420, 250);" class="mm-ui-button green"  />
			</div>
			<div style="padding:10px;">
				<?php echo $p->existing_access_rights; ?>
			</div>
		</div>
	</div>	
	<div id="mm_protected_category_meta" style="<?php echo $p->mm_protected_category_meta; ?>">
		<div style="padding:8px;">
			<div style="font-size:13px; line-height:30px;">
				Access via Category
			</div>
			<div style="padding:10px;">
				<?php echo $p->protected_categories; ?>
			</div>
		</div>
	</div>	
	<div id='mm_core_pages_meta' style="<?php echo $p->mm_core_pages_meta_style; ?>">
		<div style="padding:8px;">
			<div style="font-size:13px; line-height:30px;">
				Core Page Settings
			</div>
			<div>
				<?php if(isset($p->default_icon)){ ?>
				<?php echo $p->default_icon; ?>
				<input type='hidden' name='save-mm-corepages[core_page_type_id]' value='<?php echo $p->corePageTypeId; ?>' />
				<?php } ?>
				
				<select id='core_page_type_id' name='save-mm-corepages[core_page_type_id]' onchange="corepages_js.getReferences();" <?php if(isset($p->default_icon)){ ?>disabled='disabled' <?php } ?>>
				<option value=''>None</option>
					<?php echo $p->existing_corepage_features; ?>
				</select> 
				
				<?php if(isset($p->default_icon)) { ?>
					<a id='default_core_page' onclick="mmdialog_js.showDialog('mm-corepage-dialog', 'MM_CorePagesView', 420, 200, 'Change Core Page');" style="cursor: pointer; margin-left:5px;">
						<?php echo  MM_Utils::getIcon('pencil', 'yellow', '1.3em', '2px'); ?>
					</a>
				<?php } ?>
			</div>
			<?php if(!isset($p->default_icon) || (isset($p->default_icon) && empty($p->default_icon))){ ?>
			<div id='subtypes'></div>
			<?php }?>
		</div>
	</div>
</div>

<!-- Post Meta Dialog -->
<div id="mm-corepage-dialog"></div>
<div id="mm-post-meta-dialog"></div>
<script>jQuery("#mm-post-meta-dialog").dialog({autoOpen: false});</script>
<script>jQuery("#mm-corepage-dialog").dialog({autoOpen: false});</script>

<script type='text/javascript'>
jQuery(document).ready(function(){
	<?php if(!isset($p->default_icon)) { ?>
		corepages_js.getReferences('<?php echo $p->is_free; ?>');
	<?php } ?>
	corepages_js.checkAccessRights();
});
</script>
