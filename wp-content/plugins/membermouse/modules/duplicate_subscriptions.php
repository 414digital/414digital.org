<?php
/**
 * 
 * MemberMouse(TM) (http://www.membermouse.com)
 * (c) MemberMouse, LLC. All rights reserved.
 */

$view = new MM_DuplicateSubscriptionsView();

?>
<div class="mm-wrap">
	<div id="mm-grid-container">
		<?php echo $view->generateDataGrid($_POST); ?>
	</div>
</div>
