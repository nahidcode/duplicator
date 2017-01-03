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
	 * @param string $_POST['scan-path']		The path to start scanning from, defaults to DUPLICATOR_WPROOTPATH
	 * @param bool   $_POST['scan-recursive']	Recursivly search the path
	 * 
	 * @notes: Testing = /wp-admin/admin-ajax.php?action=DUP_CTRL_Tools_RunScanValidator
     */
	public static function RunScanValidator() 
	{
		//SETUP		
		$Result = new DUP_CTRL_Result();
		DUP_Util::CheckPermissions('read');
		check_ajax_referer('DUP_CTRL_Tools_RunScanValidator', 'nonce');
		header('Content-Type: application/json');
		
		//====================
		//CONTROLLER LOGIC
		$path = isset($_POST['scan-path']) ? $_POST['scan-path'] : DUPLICATOR_WPROOTPATH;
		$ScanChecker = new DUP_ScanValidator();
		$ScanChecker->Recursion = isset($_POST['scan-recursive']) ? true : false;
		$payload = $ScanChecker->Run($path);

	
		//====================
		//RETURN RESULT
		$Result->Payload = $payload;
		$Result->Report->Results = 1;
		$Result->Report->TestStatus = ($Result->Payload->FileCount > 0) 
				? DUP_CTRL_ResultStatus::SUCCESS
				: DUP_CTRL_ResultStatus::FAILED;
		$Result->GetProcessTime();
		die(json_encode($Result));
    }
}
?>
