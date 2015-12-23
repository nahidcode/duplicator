<?php
DUP_Util::CheckPermissions('manage_options');
global $wpdb;

//COMMON HEADER DISPLAY
$current_tab = isset($_REQUEST['tab']) ? esc_html($_REQUEST['tab']) : 'detail';
$package_id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : 0;

$package			= DUP_Package::GetByID($package_id);
$err_found		    = ($package == null || $package->Status < 100);
$err_link_pack		= "Duplicator.Pack.DownloadPackageFile(3, {$package->ID});return false;";
$err_link_log		= "<a target='_blank' href='#' onclick='{$err_link_pack}'>" . DUP_Util::__('Package Log') . '</a>';
$err_link_faq		= '<a target="_blank" href="https://snapcreek.com/duplicator/docs/faqs-tech/">' . DUP_Util::__('FAQ Pages') . '</a>';		
$err_link_ticket	= '<a target="_blank" href="https://snapcreek.com/ticket/">' . DUP_Util::__('Help Ticket') . '</a>';	

?>

<style>
    .narrow-input { width: 80px; }
    .wide-input {width: 400px; } 
	 table.form-table tr td { padding-top: 25px; }
	 div.all-packages {float:right; margin-top: -30px; }
	 div.all-packages a.add-new-h2 {font-size: 16px}
</style>

<div class="wrap">
    <?php 
		duplicator_header(DUP_Util::__("Package Details &raquo; {$package->Name}")); 
	?>
	
	<?php if ($err_found) :?>
	<div class="error">
		<p>
			<?php echo DUP_Util::__('An error was detected while creating this package. Please review the ') . $err_link_log . DUP_Util::__(' for more details.')  ; ?> <br/>
			<?php echo DUP_Util::__('Additional help is available online via the ') . $err_link_faq . DUP_Util::__(' or just contact support via a ') . $err_link_ticket; ?> 
		</p>
	</div>
	<?php endif; ?>
	
    <h2 class="nav-tab-wrapper">  
        <a href="?page=duplicator&action=detail&tab=detail&id=<?php echo $package_id ?>" class="nav-tab <?php echo ($current_tab == 'detail') ? 'nav-tab-active' : '' ?>"> <?php DUP_Util::_e('Details'); ?></a> 
		<a <?php if($enable_transfer_tab === false) { echo 'onclick="Duplicator.Pack.TransferDisabled(); return false;"';} ?> href="?page=duplicator&action=detail&tab=transfer&id=<?php echo $package_id ?>" class="nav-tab <?php echo ($current_tab == 'transfer') ? 'nav-tab-active' : '' ?>"> <?php DUP_Util::_e('Transfer'); ?></a> 		
    </h2>
	<div class="all-packages"><a href="?page=duplicator" class="add-new-h2"><i class="fa fa-archive"></i> <?php DUP_Util::_e('All Packages'); ?></a></div>
	

	
    <?php
    switch ($current_tab) {
        case 'detail': include('detail.php');            
            break;
		case 'transfer': include('transfer.php');
            break; 
    }
    ?>
</div>

<script type="text/javascript">
	Duplicator.Pack.TransferDisabled = function() {
		alert("<?php DUP_Util::_e('No package in default location so transfer is disabled.');?>")
	}
</script>
