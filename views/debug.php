<?php
DUP_Util::CheckPermissions('read');

require_once(DUPLICATOR_PLUGIN_PATH . '/assets/js/javascript.php');
require_once(DUPLICATOR_PLUGIN_PATH . '/views/inc.header.php');
?>
<style>
	div.debug-area {line-height: 26px}
	div.debug-area form {margin: 15px 0 0 0; border-top: 1px solid #dfdfdf}
	div.debug-area label {width:150px; display:inline-block}
	div.debug-area input[type=text] {width:400px}
</style>

<div class="wrap dup-wrap dup-support-all">
	
    <?php duplicator_header(__("Debug", 'duplicator')) ?>
    <hr size="1" />

	<div class="debug-area">
		<h2>TOOLS CONTROLLER</h2>
		
		<form action="admin-ajax.php?action=DUP_CTRL_Tools_RunScanValidator" method="post" target="duplicator_debug">
			<?php wp_nonce_field('DUP_CTRL_Tools_RunScanValidator', 'nonce'); ?>
			<b>DUP_CTRL_Tools_RunScanValidator</b> <br/>
			
			<label>Allow Recursion:</label>
			<input type="checkbox" name="scan-recursive" class="param" /><br/>
			
			<label>Search Path:</label> 
			<input type="text" name="scan-path" class="param" value="<?php echo DUPLICATOR_WPROOTPATH ?>" /> <br/>
			
			<a href="javascript:void(0)" onclick="jQuery(this).parent('form').submit()">[Run Test]</a>
		</form>
		
		<form action="admin-ajax.php?action=duplicator_package_scan" method="post" target="duplicator_debug">
			<?php wp_nonce_field('duplicator_package_scan', 'nonce'); ?>
			<b>duplicator_package_scan:</b> <br/>
			<a href="javascript:void(0)" onclick="jQuery(this).parent('form').submit()">[Run Test]</a>
		</form>
	
		
	</div>

</div>

