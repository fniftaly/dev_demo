#!/usr/bin/php
<?php
// Make sure we have enough memory available to this script
ini_set('memory_limit', '512M');

// Path to our application
$apppath = realpath(dirname(__FILE__) . '/..');
$logpath = realpath(dirname(__FILE__)) . '/log_cron/';
// Add the application path to the include path
set_include_path(get_include_path() . PATH_SEPARATOR . $apppath);
// Log file for today
$logfile = $logpath . date('Ymd') . '_Memcache.log';

// If there is no log for today, create it
if (!file_exists($logfile)) {
    touch($logfile);
}
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
// Get the Delivery Report model
$timestamp = date('Y-m-d H:i:s');

// Log the start of this read
logWrite("####################################");
logWrite("Begin store memcache optouts: $timestamp");
logWrite("------------------------------------");

$inb = new Application_Model_Smsinbound();
$inb->optoutsListFromInboundToMemcache();

$endofset = date('Y-m-d H:i:s');
logWrite("End of storing optouts into memcache: $endofset");
logWrite("------------------------------------");
  

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