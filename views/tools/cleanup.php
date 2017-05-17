<?php

    $nonce = wp_create_nonce('duplicator_cleanup_page');
	$ajax_nonce	= wp_create_nonce('DUP_CTRL_Tools_deleteInstallerFiles');
	$_GET['action'] = isset($_GET['action']) ? $_GET['action'] : 'display';
	
	if (isset($_GET['action'])) {
		if (($_GET['action'] == 'installer') || ($_GET['action'] == 'legacy') || ($_GET['action'] == 'tmp-cache')) {
			$verify_nonce = $_REQUEST['_wpnonce'];
			if (!wp_verify_nonce($verify_nonce, 'duplicator_cleanup_page')) {
				exit; // Get out of here bad nounce!
			}
		}
	}

	$installer_files = DUP_Server::getInstallerFiles();
        
	switch ($_GET['action']) {            
		case 'installer' :     
			$action_response = __('Installer file cleanup ran!', 'duplicator');
			$css_hide_msg = 'div.error {display:none}';
			get_option('duplicator_tried_installer_cleanup');
			
			break;		
		case 'legacy': 
			DUP_Settings::LegacyClean();			
			$action_response = __('Legacy data removed.', 'duplicator');
			break;
		case 'tmp-cache': 
			DUP_Package::tempFileCleanup(true);
			$action_response = __('Build cache removed.', 'duplicator');
			break;		
	} 

	$txt_found = __('File Found', 'duplicator');
	$txt_not_found = __('File Removed', 'duplicator');
	$package_name = (isset($_GET['package'])) ? DUPLICATOR_WPROOTPATH . esc_html($_GET['package']) : '';
	
?>

<style>
	<?php echo isset($css_hide_msg) ? $css_hide_msg : ''; ?>
	div.success {color:#4A8254}
	div.failed {color:red}
	table.dup-reset-opts td:first-child {font-weight: bold}
	table.dup-reset-opts td {padding:10px}
	form#dup-settings-form {padding: 0px 10px 0px 10px}
	button.dup-fixed-btn {min-width: 150px; text-align: center}
	div#dup-tools-delete-moreinfo {display: none; padding: 5px 0 0 20px; border:1px solid silver; background-color: #fff; border-radius: 5px; padding:10px; margin:5px; width:750px }
</style>

<form id="dup-settings-form" action="?page=duplicator-tools&tab=cleanup" method="post">
	
	<?php if ($_GET['action'] != 'display')  :	?>
		<div id="message" class="updated below-h2">
			<p><?php echo $action_response; ?></p>
			<?php if ( $_GET['action'] == 'installer') :  ?>
				<?php	
//					$html = "";
//
//					$package_name = (isset($_GET['package'])) ? DUPLICATOR_WPROOTPATH . esc_html($_GET['package']) : '';
//					foreach($installer_files as $file => $path)
//					{
//						@unlink($path);
//						echo (file_exists($path))
//							? "<div class='failed'><i class='fa fa-exclamation-triangle'></i> {$txt_found} - {$path}  </div>"
//							: "<div class='success'> <i class='fa fa-check'></i> {$txt_not_found} - {$path}	</div>";
//					}
//
//
//					//No way to know exact name of archive file except from installer.
//					//The only place where the package can be remove is from installer
//					//So just show a message if removing from plugin.
//					if (! empty($package_name) )
//					{
//						$path_parts = pathinfo($package_name);
//						$path_parts = (isset($path_parts['extension'])) ? $path_parts['extension'] : '';
//						if ($path_parts  == "zip"  && ! is_dir($package_name))
//						{
//							$lang1 = __('Successfully removed', 'duplicator');
//							$lang2 = __('Does not exist or unable to remove archive file.', 'duplicator');
//							$html .= (@unlink($package_name))
//								?  "<div class='success'>{$lang1} {$package_name}</div>"
//								:  "<div class='failed'>{$lang2}</div>";
//						}
//						else
//						{
//							$lang = __('Does not exist or unable to remove archive file.  Please validate that an archive file exists.', 'duplicator');
//							$html .= "<div class='failed'>{$lang}</div>";
//						}
//					} else {
//						$lang = __('It is recommended to remove your archive file from the root of your WordPress install.  This will need to be done manually', 'duplicator');
//						$html .= "<br/><div>{$lang}</div>";
//					}
//					echo $html;
				 ?>

				<i><br/>
				 <?php _e('If the installer files did not successfully get removed, then you WILL need to remove them manually', 'duplicator')?>. <br/>
				 <?php _e('Please remove all installer files to avoid leaving open security issues on your server', 'duplicator')?>. <br/><br/>
				</i>
			
			<?php endif; ?>
		</div>
	<?php endif; ?>	
	

	<h2><?php _e('Data Cleanup', 'duplicator')?></h2><hr size="1"/>
	<table class="dup-reset-opts">
		<tr style="vertical-align:text-top">
			<td>
				<button id="dup-remove-installer-files-btn" type="button" class="button button-small dup-fixed-btn" onclick="Duplicator.Tools.deleteInstallerFiles();">
					<?php _e("Delete Installation Files", 'duplicator'); ?>
				</button>
			</td>
			<td>
				<?php _e("Removes all reserved installer files.", 'duplicator'); ?>
				<a href="javascript:void(0)" onclick="jQuery('#dup-tools-delete-moreinfo').toggle()">[<?php _e("more info", 'duplicator'); ?>]</a>
				<br/>
				<div id="dup-tools-delete-moreinfo">
					<?php
						_e("Clicking on the 'Delete Reserved Files' button will remove the following reserved files.  These files are typically from a previous Duplicator install. "
								. "If you are unsure of the source, please validate the files.  These files should never be left on production systems for security reasons.  "
								. "Below is a list of all the reserved files used by Duplicator.  Please be sure these are removed from your server.", 'duplicator');
						echo "<br/><br/>";
						
						foreach($installer_files as $file => $path) 
						{
							echo (file_exists($path)) 
								? "<div class='failed'><i class='fa fa-exclamation-triangle'></i> {$txt_found} - {$file}  </div>"
								: "<div class='success'> <i class='fa fa-check'></i> {$txt_not_found} - {$file}	</div>";		
						}
					?>
				</div>
			</td>
		</tr>
		<tr>
			<td>
				<button type="button" class="button button-small dup-fixed-btn" onclick="Duplicator.Tools.ConfirmClearBuildCache()">
					<?php _e("Clear Build Cache", 'duplicator'); ?>
				</button>
			</td>
			<td><?php _e("Removes all build data from:", 'duplicator'); ?> [<?php echo DUPLICATOR_SSDIR_PATH_TMP ?>].</td>
		</tr>	
	</table>
</form>

<!-- ==========================================
THICK-BOX DIALOGS: -->
<?php	

	$confirm2 = new DUP_UI_Dialog();
	$confirm2->title			= __('Clear Build Cache?', 'duplicator');
	$confirm2->message			= __('This process will remove all build cache files.  Be sure no packages are currently building or else they will be cancelled.', 'duplicator');
	$confirm2->jscallback		= 'Duplicator.Tools.ClearBuildCache()';
	$confirm2->initConfirm();
?>

<script>

	Duplicator.Tools.deleteInstallerFiles = function()
	{
		var data = {action : 'DUP_CTRL_Tools_deleteInstallerFiles', nonce: '<?php echo $ajax_nonce; ?>', 'archive-path': '<?php echo $package_name;?>'};

		jQuery.ajax({
			type: "POST",
			url: ajaxurl,
			dataType: "json",
			data: data,
			success: function(data) {
				window.location = '?page=duplicator-tools&tab=cleanup&action=installer&_wpnonce=<?php echo $nonce; ?>';
			},
			error: function(data) {console.log(data)},
			done: function(data) {console.log(data)}
		});
	}



jQuery(document).ready(function($) 
{
   	Duplicator.Tools.ConfirmClearBuildCache = function () 
	{
		 <?php $confirm2->showConfirm(); ?>
	}
	
	Duplicator.Tools.ClearBuildCache = function () 
	{
		window.location = '?page=duplicator-tools&tab=cleanup&action=tmp-cache&_wpnonce=<?php echo $nonce; ?>';
	}
});
</script>

