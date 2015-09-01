#!/usr/bin/php
<?php
// Before we ever get started on sending any messages make sure we're not already running
// We might want to consider this, especially if we hang on a send...
//exec('ps -aef | grep messagesender.php | grep -v grep', $running);
//if (count($running) > 1) {
//	die("The message sender process is running already and cannot be started again.\n\nProcess information:\n{$running[0]}");
//}
// Make sure we have enough memory available to this script
ini_set('memory_limit', '512M');

// Path to the logs
$logpath = realpath(dirname(__FILE__)) . '/log_rcpt/';

// Path to our application
$apppath = realpath(dirname(__FILE__) . '/..');

// Add the application path to the include path
set_include_path(get_include_path() . PATH_SEPARATOR . $apppath);

// Log file for today
$logfile = $logpath . date('Ymd') . '-updateAllphonenumbers';

// If there is no log for today, create it
if (!file_exists($logfile)) {
    touch($logfile);
}

// Get the Zend Framework loader setup
require_once 'Zend/Loader/Autoloader.php';
$autoloader = Zend_Loader_Autoloader::getInstance();

// Setup routing of model autoloaders
$loader = new Zend_Loader_Autoloader_Resource(array(
            'basePath' => $apppath . '/application/',
            'namespace' => 'Application',
        ));

// Name, path, namepsace
$loader->addResourceType('model', 'models', 'Model');
$weekdays = array('Sun'=>'sunday','Mon'=>'monday','Tue'=>'tuesday','Wed'=>'wednesday','Thu'=>'thursday','Fri'=>'friday','Sat'=>'saterday');
// Get our config file
$config = new Zend_Config_Ini($apppath . '/application/configs/application.ini');
Zend_Registry::set('config', $config->production); // Because our models need it this way
// Get the messages model
//$message = new Application_Model_Message();
// Set up our current timestamp
$timestamp = date('Y-m-d H:i:s');

// Log the start of this read
logWrite("**********************************");
logWrite("Begin truncate: $timestamp");
logWrite("------------------------------------");
$rpt = new Application_Model_Report();
$rpt->saveUserDailyactivity();
// Log our counts
//logWrite("")
/*Memcache server: BDAYCLUB info stores in the memcache memory*/
$msgobj = new Application_Model_Message();
$msgobj->addMemcahceBdclub();

/*end of memcache server use*/
$dataObj = new Application_Model_DataReporting();
$rst = $dataObj->allphonenumbers();

if ($rst) {
    $log = "Rows inserted: $rst";
    logWrite($log);
} else {
    logWrite("No data inserted!!");
}
// Set up our current timestamp for closing out
$timestamp = date('Y-m-d H:i:s');

// Log the end of this send
logWrite("\n---------------------");
logWrite("End of inserting: $timestamp");
logWrite("**********************************\n");

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