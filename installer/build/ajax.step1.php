<?php
// Exit if accessed directly
if (! defined('DUPLICATOR_INIT')) {
	$_baseURL = "http://" . strlen($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : $_SERVER['HTTP_HOST'];
	header("HTTP/1.1 301 Moved Permanently");
	header("Location: $_baseURL");
	exit;
}

//POST PARAMS
$_POST['package_name']		= isset($_POST['package_name']) ? $_POST['package_name'] : null;


$JSON = array();
$JSON['pass'] = 0;

/* JSON RESPONSE: Most sites have warnings turned off by default, but if they're turned on the warnings
cause errors in the JSON data Here we hide the status so warning level is reset at it at the end*/
$ajax1_error_level = error_reporting();
error_reporting(E_ERROR);

$JSON['pass'] = 1;

echo json_encode($JSON);
error_reporting($ajax1_error_level);
die('');
?>