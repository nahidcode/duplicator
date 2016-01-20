<?php

$view_state = DUP_UI::GetViewStateArray();
$ui_css_general = (isset($view_state['dup-package-dtl-general-panel']) && $view_state['dup-package-dtl-general-panel']) ? 'display:block' : 'display:none';
$ui_css_storage = (isset($view_state['dup-package-dtl-storage-panel']) && $view_state['dup-package-dtl-storage-panel']) ? 'display:block' : 'display:none';
$ui_css_archive = (isset($view_state['dup-package-dtl-archive-panel']) && $view_state['dup-package-dtl-archive-panel']) ? 'display:block' : 'display:none';
$ui_css_install = (isset($view_state['dup-package-dtl-install-panel']) && $view_state['dup-package-dtl-install-panel']) ? 'display:block' : 'display:none';

$link_sql			= "{$package->StoreURL}{$package->NameHash}_database.sql";
$link_archive 		= "{$package->StoreURL}{$package->NameHash}_archive.zip";
$link_installer		= "{$package->StoreURL}{$package->NameHash}_installer.php?get=1&file={$package->NameHash}_installer.php";
$link_log			= "{$package->StoreURL}{$package->NameHash}.log";
$link_scan			= "{$package->StoreURL}{$package->NameHash}_scan.json";

$mysqldump_on	 = DUP_Settings::Get('package_mysqldump') && DUP_Database::GetMySqlDumpPath();
$mysqlcompat_on  = isset($Package->Database->Compatible) && strlen($Package->Database->Compatible);
$mysqlcompat_on  = ($mysqldump_on && $mysqlcompat_on) ? true : false;
$dbbuild_mode    = ($mysqldump_on) ? 'mysqldump (fast)' : 'PHP (slow)';
?>

<style>
	/*COMMON*/
	div.toggle-box {float:right; margin: 5px 5px 5px 0}
	div.dup-box {margin-top: 15px; font-size:14px; clear: both}
	table.dup-dtl-data-tbl {width:100%}
	table.dup-dtl-data-tbl tr {vertical-align: top}
	table.dup-dtl-data-tbl tr:first-child td {margin:0; padding-top:0 !important;}
	table.dup-dtl-data-tbl td {padding:0 6px 0 0; padding-top:15px !important;}
	table.dup-dtl-data-tbl td:first-child {font-weight: bold; width:150px}
	table.dup-sub-list td:first-child {white-space: nowrap; vertical-align: middle; width: 70px !important;}
	table.dup-sub-list td {white-space: nowrap; vertical-align:top; padding:0 !important; font-size:12px}
	div.dup-box-panel-hdr {font-size:14px; display:block; border-bottom: 1px dotted #efefef; margin:5px 0 5px 0; font-weight: bold; padding: 0 0 5px 0}
	tr.sub-item td:first-child {padding:0 0 0 40px}
	tr.sub-item td {font-size: 12px}
	tr.sub-item-disabled td {color:gray}
	
	/*GENERAL*/
	div#dup-name-info {display: none; font-size:11px; line-height:20px; margin:4px 0 0 0}
	div#dup-downloads-area {padding: 5px 0 5px 0; }
	div#dup-downloads-msg {margin-bottom:-5px; font-style: italic}
</style>

<?php if ($package_id == 0) :?>
	<div class="error below-h2"><p><?php DUP_Util::_e("Invlaid Package ID request.  Please try again!"); ?></p></div>
<?php endif; ?>
	
<div class="toggle-box">
	<a href="javascript:void(0)" onclick="Duplicator.Pack.OpenAll()">[open all]</a> &nbsp; 
	<a href="javascript:void(0)" onclick="Duplicator.Pack.CloseAll()">[close all]</a>
</div>
	
<!-- ===============================
GENERAL -->
<div class="dup-box">
<div class="dup-box-title">
	<i class="fa fa-archive"></i> <?php DUP_Util::_e('General') ?>
	<div class="dup-box-arrow"></div>
</div>			
<div class="dup-box-panel" id="dup-package-dtl-general-panel" style="<?php echo $ui_css_general ?>">
	<table class='dup-dtl-data-tbl'>
		<tr>
			<td><?php DUP_Util::_e("Name") ?>:</td>
			<td>
				<a href="javascript:void(0);" onclick="jQuery('#dup-name-info').toggle()"><?php echo $package->Name ?></a> 
				<div id="dup-name-info">
					<b><?php DUP_Util::_e("ID") ?>:</b> <?php echo $package->ID ?><br/>
					<b><?php DUP_Util::_e("Hash") ?>:</b> <?php echo $package->Hash ?><br/>
					<b><?php DUP_Util::_e("Full Name") ?>:</b> <?php echo $package->NameHash ?><br/>
				</div>
			</td>
		</tr>
		<tr>
			<td><?php DUP_Util::_e("Notes") ?>:</td>
			<td><?php echo strlen($package->Notes) ? $package->Notes : DUP_Util::__("- no notes -") ?></td>
		</tr>
			
		<tr>
			<td><?php DUP_Util::_e("Version") ?>:</td>
			<td><?php echo $package->Version ?></td>
		</tr>
		<tr>
			<td><?php DUP_Util::_e("Runtime") ?>:</td>
			<td><?php echo strlen($package->Runtime) ? $package->Runtime : DUP_Util::__("error running"); ?></td>
		</tr>
		<tr>
			<td><?php DUP_Util::_e("Status") ?>:</td>
			<td><?php echo ($package->Status >= 100) ? DUP_Util::__("completed")  : DUP_Util::__("in-complete") ?></td>
		</tr>
		<tr>
			<td><?php DUP_Util::_e("Files") ?>: </td>
			<td>
				<div id="dup-downloads-area">
					<?php if  (!$err_found) :?>
						<button class="button" onclick="Duplicator.Pack.DownloadFile('<?php echo $link_installer; ?>', this);return false;"><i class="fa fa-bolt"></i> Installer</button>						
						<button class="button" onclick="Duplicator.Pack.DownloadFile('<?php echo $link_archive; ?>', this);return false;"><i class="fa fa-file-archive-o"></i> Archive - <?php echo $package->ZipSize ?></button>
						<button class="button" onclick="Duplicator.Pack.DownloadFile('<?php echo $link_sql; ?>', this);return false;"><i class="fa fa-table"></i> &nbsp; SQL - <?php echo DUP_Util::ByteSize($package->Database->Size)  ?></button>
						<button class="button" onclick="Duplicator.Pack.DownloadFile('<?php echo $link_log; ?>', this);return false;"><i class="fa fa-list-alt"></i> &nbsp; Log </button>
					<?php else: ?>
							<button class="button" onclick="Duplicator.Pack.DownloadFile('<?php echo $link_log; ?>', this);return false;"><i class="fa fa-list-alt"></i> &nbsp; Log </button>
					<?php endif; ?>
				</div>		
				<?php if (!$err_found) :?>
				<table class="dup-sub-list">
					<tr>
						<td><?php DUP_Util::_e("Archive") ?>: </td>
						<td><?php echo $package->Archive->File ?></td>
					</tr>
					<tr>
						<td><?php DUP_Util::_e("Installer") ?>: </td>
						<td><?php echo $package->Installer->File ?></td>
					</tr>
					<tr>
						<td><?php DUP_Util::_e("Database") ?>: </td>
						<td><?php echo $package->Database->File ?></td>
					</tr>
				</table>
				<?php endif; ?>
			</td>
		</tr>	
	</table>
</div>
</div>

<!-- ===============================
STORAGE -->
<?php 
	$css_file_filter_on = $package->Archive->FilterOn == 1  ? '' : 'sub-item-disabled';
	$css_db_filter_on   = $package->Database->FilterOn == 1 ? '' : 'sub-item-disabled';
?>
<div class="dup-box">
<div class="dup-box-title">
	<i class="fa fa-database"></i> <?php DUP_Util::_e('Storage') ?>
	<div class="dup-box-arrow"></div>
</div>			
<div class="dup-box-panel" id="dup-package-dtl-storage-panel" style="<?php echo $ui_css_storage ?>">
	<table class="widefat package-tbl">
		<thead>
			<tr>
				<th style='width:150px'><?php DUP_Util::_e('Name') ?></th>
				<th style='width:100px'><?php DUP_Util::_e('Type') ?></th>
				<th style="white-space: nowrap"><?php DUP_Util::_e('Location') ?></th>
			</tr>
		</thead>
			<tbody>
				<tr class="package-row">
					<td><i class="fa fa-server"></i>&nbsp;<?php  _e('Default', 'duplicator');?></td>
					<td><?php _e("Local", 'duplicator'); ?></td>
					<td><?php echo DUPLICATOR_SSDIR_PATH; ?></td>				
				</tr>
				<tr>
					<td colspan="4">
						<div style="font-size:12px; font-style:italic;"> 
							<img src="<?php echo DUPLICATOR_PLUGIN_URL ?>assets/img/amazon-64.png" style='height:16px; width:16px; vertical-align: text-top'  /> 
							<img src="<?php echo DUPLICATOR_PLUGIN_URL ?>assets/img/dropbox-64.png" style='height:16px; width:16px; vertical-align: text-top'  /> 
							<img src="<?php echo DUPLICATOR_PLUGIN_URL ?>assets/img/google_drive_64px.png" style='height:16px; width:16px; vertical-align: text-top'  /> 
							<img src="<?php echo DUPLICATOR_PLUGIN_URL ?>assets/img/ftp-64.png" style='height:16px; width:16px; vertical-align: text-top'  /> 
							
							<?php echo sprintf(__('%1$s, %2$s, %3$s, %4$s and other storage options available in', 'duplicator'), 'Amazon', 'Dropbox', 'Google Drive', 'FTP'); ?>
                            <a href="http://snapcreek.com/duplicator/?free-storage-detail" target="_blank">Duplicator Pro</a> 
                        </div>                            
					</td>
				</tr>
			</tbody>
	</table>
</div>
</div>


<!-- ===============================
ARCHIVE -->
<?php 
	$css_file_filter_on = $package->Archive->FilterOn == 1  ? '' : 'sub-item-disabled';
	$css_db_filter_on   = $package->Database->FilterOn == 1 ? '' : 'sub-item-disabled';
?>
<div class="dup-box">
<div class="dup-box-title">
	<i class="fa fa-file-archive-o"></i> <?php DUP_Util::_e('Archive') ?>
	<div class="dup-box-arrow"></div>
</div>			
<div class="dup-box-panel" id="dup-package-dtl-archive-panel" style="<?php echo $ui_css_archive ?>">

	<!-- FILES -->
	<div class="dup-box-panel-hdr"><i class="fa fa-files-o"></i> <?php DUP_Util::_e('FILES'); ?></div>
	<table class='dup-dtl-data-tbl'>
		<tr>
			<td><?php DUP_Util::_e("Build Mode") ?>: </td>
			<td><?php DUP_Util::_e('ZipArchive'); ?></td>
		</tr>			
		<tr>
			<td><?php DUP_Util::_e("Filters") ?>: </td>
			<td><?php echo $package->Archive->FilterOn == 1 ? 'On' : 'Off'; ?></td>
		</tr>
		<tr class="sub-item <?php echo $css_file_filter_on ?>">
			<td><?php DUP_Util::_e("Directories") ?>: </td>
			<td>
				<?php 
					echo strlen($package->Archive->FilterDirs) 
						? str_replace(';', '<br/>', $package->Archive->FilterDirs)
						: DUP_Util::__('- no filters -');	
				?>
			</td>
		</tr>
		<tr class="sub-item <?php echo $css_file_filter_on ?>">
			<td><?php DUP_Util::_e("Extensions") ?>: </td>
			<td>
				<?php
					echo isset($package->Archive->Extensions) && strlen($package->Archive->Extensions) 
						? $package->Archive->Extensions
						: DUP_Util::__('- no filters -');
				?>
			</td>
		</tr>
		<tr class="sub-item <?php echo $css_file_filter_on ?>">
			<td><?php DUP_Util::_e("Files") ?>: </td>
			<td><i><?php DUP_Util::_e("Available in Duplicator Pro") ?></i></td>
		</tr>			
	</table><br/>

	<!-- DATABASE -->
	<div class="dup-box-panel-hdr"><i class="fa fa-table"></i> <?php DUP_Util::_e('DATABASE'); ?></div>
	<table class='dup-dtl-data-tbl'>
		<tr>
			<td><?php DUP_Util::_e("Type") ?>: </td>
			<td><?php echo $package->Database->Type ?></td>
		</tr>
		<tr>
			<td><?php DUP_Util::_e("Build Mode") ?>: </td>
			<td>
				<a href="?page=duplicator-settings" target="_blank"><?php echo $dbbuild_mode; ?></a>
				<?php if ($mysqlcompat_on) : ?>
					<br/>
					<small style="font-style:italic; color:maroon">
						<i class="fa fa-exclamation-circle"></i> <?php DUP_Util::_e('MySQL Compatibility Mode Enabled'); ?>
						<a href="https://dev.mysql.com/doc/refman/5.7/en/mysqldump.html#option_mysqldump_compatible" target="_blank">[<?php DUP_Util::_e('details'); ?>]</a>
					</small>										
				<?php endif; ?>
			</td>
		</tr>			
		<tr>
			<td><?php DUP_Util::_e("Filters") ?>: </td>
			<td><?php echo $package->Database->FilterOn == 1 ? 'On' : 'Off'; ?></td>
		</tr>
		<tr class="sub-item <?php echo $css_db_filter_on ?>">
			<td><?php DUP_Util::_e("Tables") ?>: </td>
			<td>
				<?php 
					echo isset($package->Archive->FilterTables) && strlen($package->Archive->FilterTables) 
						? str_replace(';', '<br/>', $package->Database->FilterTables)
						: DUP_Util::__('- no filters -');	
				?>
			</td>
		</tr>			
	</table>		
</div>
</div>


<!-- ===============================
INSTALLER -->
<div class="dup-box" style="margin-bottom: 50px">
<div class="dup-box-title">
	<i class="fa fa-bolt"></i> <?php DUP_Util::_e('Installer') ?>
	<div class="dup-box-arrow"></div>
</div>			
<div class="dup-box-panel" id="dup-package-dtl-install-panel" style="<?php echo $ui_css_install ?>">
	<table class='dup-dtl-data-tbl'>
		<tr>
			<td><?php DUP_Util::_e("Host") ?>:</td>
			<td><?php echo strlen($package->Installer->OptsDBHost) ? $package->Installer->OptsDBHost : DUP_Util::__("- not set -") ?></td>
		</tr>
		<tr>
			<td><?php DUP_Util::_e("Database") ?>:</td>
			<td><?php echo strlen($package->Installer->OptsDBName) ? $package->Installer->OptsDBName : DUP_Util::__("- not set -") ?></td>
		</tr>
		<tr>
			<td><?php DUP_Util::_e("User") ?>:</td>
			<td><?php echo strlen($package->Installer->OptsDBUser) ? $package->Installer->OptsDBUser : DUP_Util::__("- not set -") ?></td>
		</tr>	
		<tr>
			<td><?php DUP_Util::_e("New URL") ?>:</td>
			<td><?php echo strlen($package->Installer->OptsURLNew) ? $package->Installer->OptsURLNew : DUP_Util::__("- not set -") ?></td>
		</tr>
	</table>
</div>
</div>

<?php if ($global->package_debug) : ?>
	<div style="margin:0">
		<a href="javascript:void(0)" onclick="jQuery(this).parent().find('.dup-pack-debug').toggle()">[<?php DUP_Util::_e("View Package Object") ?>]</a><br/>
		<pre class="dup-pack-debug" style="display:none"><?php @print_r($package); ?> </pre>
	</div>
<?php endif; ?>	


<script type="text/javascript">
jQuery(document).ready(function ($) {

	/*	METHOD:  */
	Duplicator.Pack.OpenAll = function () {
		$("div.dup-box").each(function() {
			var panel_open = $(this).find('div.dup-box-panel').is(':visible');
			if (! panel_open)
				$( this ).find('div.dup-box-title').trigger("click");
		 });
	};

	/*	METHOD: */
	Duplicator.Pack.CloseAll = function () {
			$("div.dup-box").each(function() {
			var panel_open = $(this).find('div.dup-box-panel').is(':visible');
			if (panel_open)
				$( this ).find('div.dup-box-title').trigger("click");
		 });
	};
});
</script>