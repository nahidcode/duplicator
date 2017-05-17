<?php
/**
 * Used to display notices in the WordPress Admin area
 * This class takes advatage of the 'admin_notice' action.
 *
 * Standard: PSR-2
 * @link http://www.php-fig.org/psr/psr-2
 *
 * @package Duplicator
 * @subpackage classes/ui
 * @copyright (c) 2017, Snapcreek LLC
 * @since 1.1.0
 *
 */

// Exit if accessed directly
if (!defined('DUPLICATOR_VERSION')) {
    exit;
}

class DUP_UI_Notice
{


    /**
     * Shows a display message in the wp-admin if any researved files are found
     * 
     * @return string   Html formated text notice warnings
     */
    public static function showReservedFilesNotice()
    {
        //Show only on Duplicator pages and Dashboard when plugin is active
        $dup_active = is_plugin_active('duplicator/duplicator.php');
        $dup_perm   = current_user_can('manage_options');
        if (!$dup_active || !$dup_perm) return;
		
		$screen = get_current_screen();
        if (!isset($screen)) return;



        //Hide on save permalinks to prevent user distraction
        //if ($screen->id == 'options-permalink') return;

        if (DUP_Server::hasInstallerFiles()) {

            $screen         = get_current_screen();
            $on_active_tab  = isset($_GET['tab']) && $_GET['tab'] == 'cleanup' ? true : false;
			$dup_nonce		= wp_create_nonce('duplicator_cleanup_page');
			$msg1			= __('This site has been successfully migrated!', 'duplicator');
			$msg2			= __('Please complete these final steps:', 'duplicator');

			echo '<div class="updated notice" id="dup-global-error-reserved-files"><p>';
			echo "<b class='pass-msg'>{$msg1}</b> <br/>";
			//On Cleanup Page
			if ($screen->id == 'duplicator_page_duplicator-tools' && $on_active_tab) {
				echo "{$msg2}";
				echo '<p class="pass-lnks">';
				@printf("1. <a href='https://wordpress.org/support/plugin/duplicator/reviews/#new-post' target='wporg'>%s</a> <br/>", __('Optionally, rate the plugin at WordPress.org.', 'duplicator'));
				@printf("2. <a href='javascript:void(0)' onclick='jQuery(\"#dup-remove-installer-files-btn\").click()'>%s</a>", __('Delete installation files now to remove this message!', 'duplicator'));
				echo '</p>';
			//All other Pages
			} else {
				echo '<p class="pass-lnks">';
				_e('Reserved Duplicator installation files have been detected in the root directory.  Please delete these installation files to complete setup and avoid security issues. <br/>', 'duplicator');
				_e('Go to: Tools > Cleanup > and click the "Delete Installation Files" button.', 'duplicator');
				@printf("<br/><a href='admin.php?page=duplicator-tools&tab=cleanup&_wpnonce={$dup_nonce}'>%s</a> <br/>", __('Take me there now!', 'duplicator'));
				echo '</p>';
			}

			echo "</p></div>";
        } 
    }
}