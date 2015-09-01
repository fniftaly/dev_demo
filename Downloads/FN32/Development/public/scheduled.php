<?php

// Path to the logs
$logpath = realpath(dirname(__FILE__)) . '/log/';

// Path to our application
$apppath = realpath(dirname(__FILE__) . '/..');

// Add the application path to the include path
set_include_path(get_include_path() . PATH_SEPARATOR . $apppath);

// Log file for today
$logfile = $logpath . date('Ymd') . '-weeklyreportsender';
// If there is no log for today, create it
if (!file_exists($logfile)) {
	touch($logfile);
}
//echo $logfile;
// Get the Zend Framework loader setup
require_once 'Zend/Loader/Autoloader.php';
$autoloader = Zend_Loader_Autoloader::getInstance();

// Setup routing of model autoloaders
$loader = new Zend_Loader_Autoloader_Resource(array(
    'basePath'  => $apppath . '/application/',
    'namespace' => 'Application',
));

// Name, path, namepsace
$loader->addResourceType('model', 'models', 'Model');
       
// Get our config file
$config = new Zend_Config_Ini($apppath . '/application/configs/application.ini');
Zend_Registry::set('config', $config->production); // Because our models need it this way

// Get the messages model

// Set up our current timestamp
$timestamp = date('Y-m-d H:i:s');
// Log the start of this read
logWrite("####################################");
logWrite("Begin batch send: $timestamp");
logWrite("------------------------------------");

// Get the queue and counts
// Log our counts
logWrite("Scheduled campaign");
//logWrite("")
$msg = new Application_Model_Message();
// $arr = $msg->scheduledCampaignsSend();
 echo date('Y-m-d H:i:s').'<br>';
// echo '<pre>'; print_r($arr);
//$msg->weeklycampToqueue($arr);
//$msg->weeklycampDirectSend($arr);
 $rs = $msg->push_data_to_aggregator();
// $msg->direct_send($rs);
 echo '<pre>'; print_r($rs);
logWrite("\n---------------------");
logWrite("Scheduled: $timestamp");
logWrite("####################################\n");

return 0;

/**
 * Simply writes a log message line to the log file
 * 
 * @param string $msg The message to write
 */
function logWrite($msg) {
	global $logfile;
	$fh = fopen($logfile, 'a');
	fwrite($fh, "$msg\n");
	fclose($fh);
}