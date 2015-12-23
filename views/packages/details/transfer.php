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
	<?php 
		DUP_Util::_e('This option is available only in the professional version.');
		echo '<br/>';
		DUP_Util::_e('Manual Transfer allows you to copy any package to another location such as Dropbox, Google Drive, FTP, or another directory on this server.');
		echo '<br/>';
		DUP_Util::_e('Simply check the destination checkbox you would like to transfer the package files to and hit the transfer button and thats it.');
	?>
</div>


</div>


<script type="text/javascript">
    jQuery(document).ready(function ($) {

    });
</script>