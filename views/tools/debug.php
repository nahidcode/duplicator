<?php
DUP_Util::CheckPermissions('read');

require_once(DUPLICATOR_PLUGIN_PATH . '/assets/js/javascript.php');
require_once(DUPLICATOR_PLUGIN_PATH . '/views/inc.header.php');
?>
<style>


</style>



<div class="wrap dup-wrap dup-support-all">
	
    <?php duplicator_header(__("Debug", 'duplicator')) ?>
    <hr size="1" />


	<div class="debug-area">
		<h2>Controller Debug</h2>
		
		<form action="admin-ajax.php?action=DUP_CTRL_Tools_RunScanValidator" method="post" target="duplicator_debug">
			<?php wp_nonce_field('DUP_CTRL_Tools_RunScanValidator', 'nonce'); ?>
			<b>DUP_CTRL_Tools_RunScanValidator:</b> <br/>
			<input type="submit" class="button button-small" value="Run Test"/>
		</form>
	
		
	</div>



</div>

