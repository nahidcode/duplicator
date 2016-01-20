<?php

?>


<style>
	h3 {margin:10px 0 5px 0}
	div.transfer-panel {padding: 20px 5px 10px 10px;}
	div.transfer-hdr { border-bottom: 1px solid #dfdfdf; margin: -15px 0 0 0}

	div#step1-section {margin: 5px 0 40px 0}
	div#step1-section label {font-weight: bold; padding-right: 20px}

	div#step2-section {margin: 5px 0 40px 0}
	div#location-quick-opts {display:none}
	div#location-quick-opts input[type=text] {width:300px}

	div#step3-section {margin: 5px 0 40px 0}
	div#dpro-progress-bar-area {width:300px; margin:5px auto 0 auto; ext-align: center}
	div.dpro-active-status-area { display: none; }
	
	#dup-pro-stop-transfer-btn { margin-top: 10px; }
	button.dpro-btn-stop {width:150px !important}	
</style>


<div class="transfer-panel">

	<div class="transfer-hdr">
		<h2><i class="fa fa-arrow-circle-right"></i> <?php DUP_PRO_U::_e('Manual Transfer'); ?></h2>
	</div>
	<br/>
	
	<div style="font-size:16px; text-align: center; line-height: 30px">
		<img src="<?php echo DUPLICATOR_PLUGIN_URL ?>assets/img/logo-dpro-300x50-nosnap.png"  /> 
	<?php 		
		echo '<h2 style="margin-top:10px">' . DUP_Util::__('This option is available only Duplicator Professional.') . '</h2>';
		DUP_Util::_e('Manual Transfer copies a package to Amazon S3, Dropbox, Google Drive, FTP, or another server directory.');
		echo '<br/>';
		DUP_Util::_e('Select where you want to transfer the package files to then hit the transfer button. Simple as that.');
	?>
</div>


</div>


<script type="text/javascript">
    jQuery(document).ready(function ($) {

    });
</script>