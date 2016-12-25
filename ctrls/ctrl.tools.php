<?php
if ( ! defined('DUPLICATOR_VERSION') ) exit; // Exit if accessed directly

require_once(DUPLICATOR_PLUGIN_PATH . '/ctrls/ctrl.base.php'); 
require_once(DUPLICATOR_PLUGIN_PATH . '/classes/scan.validator.php'); 

/**
 * Controller for Tools 
 * @package Dupicator\ctrls
 */
class DUP_CTRL_Tools extends DUP_CTRL_Base
{

	/** 
     * Calls the ScanValidator and returns a JSON result
	 * 
	 * @notes: Testing = /wp-admin/admin-ajax.php?action=DUP_CTRL_Tools_RunScanValidator
     */
	public static function RunScanValidator() 
	{
		//Security/Content
		$Result = new DUP_CTRL_Result();
		DUP_Util::CheckPermissions('read');
		check_ajax_referer('DUP_CTRL_Tools_RunScanValidator', 'nonce');
		header('Content-Type: application/json');
		
		$ScanChecker = new DUP_ScanValidator();
		$JSON_Result  = $ScanChecker->Run(DUPLICATOR_WPROOTPATH);
	
		$Result->Payload = $JSON_Result;
		$Result->GetProcessTime();
		
		//Show Results as JSON
		die(json_encode($Result));
    }
}
?>
