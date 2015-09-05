<?php
if ( ! defined('DUPLICATOR_VERSION') ) exit; // Exit if accessed directly

/**
 * Helper Class for UI internactions
 * @package Dupicator\classes
 */
class DUP_UI {
	
	/**
	 * The key used in the wp_options table
	 * @var string 
	 */
	private static $OptionsTableKey = 'duplicator_ui_view_state';
	
	/** 
     * Save the view state of UI elements
	 * @param string $key A unique key to define the ui element
	 * @param string $value A generic value to use for the view state
     */
	static public function SaveViewState($key, $value) {
	   
		$view_state = array();
		$view_state = get_option(self::$OptionsTableKey);
		$view_state[$key] =  $value;
		$success = update_option(self::$OptionsTableKey, $view_state);
		
		return $success;
    }
	
	
    /** 
     * Saves the state of a UI element via post params
	 * @return json result string
	 * <code>
	 * //JavaScript Ajax Request
	 * Duplicator.UI.SaveViewStateByPost('dup-pack-archive-panel', 1);
	 * 
	 * //Call PHP Code
	 * $view_state       = DUP_UI::GetViewStateValue('dup-pack-archive-panel');
	 * $ui_css_archive   = ($view_state == 1)   ? 'display:block' : 'display:none';
	 * </code>
     */
    static public function SaveViewStateByPost() {
		
		DUP_Util::CheckPermissions('read');
		
		$post  = stripslashes_deep($_POST);
		$key   = esc_html($post['key']);
		$value = esc_html($post['value']);
		$success = self::SaveViewState($key, $value);
		
		//Show Results as JSON
		$json = array();
		$json['key']    = $key;
		$json['value']  = $value;
		$json['update-success'] = $success;
		die(json_encode($json));
    }
	
	
	/** 
     *	Gets all the values from the settings array
	 *  @return array Returns and array of all the values stored in the settings array
     */
    static public function GetViewStateArray() {
		return get_option(self::$OptionsTableKey);
	}
	
	 /** 
	  * Return the value of the of view state item
	  * @param type $searchKey The key to search on
	  * @return string Returns the value of the key searched or null if key is not found
	  */
    static public function GetViewStateValue($searchKey) {
		$view_state = get_option(self::$OptionsTableKey);
		if (is_array($view_state)) {
			foreach ($view_state as $key => $value) {
				if ($key == $searchKey) {
					return $value;	
				}
			}
		} 
		return null;
	}
	
	/**
	 * Shows a display message in the wp-admin if any researved files are found
	 * @return type void
	 */
	static public function ShowReservedFilesNotice() {
		
		$dup_page_items = array('duplicator', 'duplicator-settings', 'duplicator-tools', 'duplicator-help', 'duplicator-about');
		
		//Show only on Duplicator pages when plugin is active
		$dup_active = is_plugin_active('duplicator/duplicator.php');
		$dup_page   = isset($_REQUEST['page']) && in_array($_REQUEST['page'], $dup_page_items) ? true : false;
		$dup_perm   = (current_user_can( 'install_plugins' ) && current_user_can( 'import' ));

		if (! $dup_active || ! $dup_page || ! $dup_perm) 
			return;

		if (DUP_Server::InstallerFilesFound()) {
			$duplicator_nonce = wp_create_nonce('duplicator_cleanup_page');

			echo '<div class="error"><p>';
			@printf("%s <br/> <a href='admin.php?page=duplicator-tools&tab=cleanup&action=installer&_wpnonce=%s'>%s</a>",
					DUP_Util::__('Reserved Duplicator install file(s) still exists in the root directory.  Please delete these file(s) to avoid security issues.'),
					$duplicator_nonce,
					DUP_Util::__('Remove reserved files now!'));
			echo "</p></div>";
		} 
	}
	
}
?>
