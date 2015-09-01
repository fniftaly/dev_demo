<?php

// Path to the logs
$logpath = realpath(dirname(__FILE__)) . '/log/';

// Path to our application
$apppath = realpath(dirname(__FILE__) . '/..');

// Add the application path to the include path
set_include_path(get_include_path() . PATH_SEPARATOR . $apppath);

// Log file for today
$logfile = $logpath . date('Ymd') . '-appoinmentconfirm';

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

// Get the messages model
$prosalon = new Application_Model_Prosalon();

// Set up our current timestamp
$timestamp = date('Y-m-d H:i:s');

// Log the start of this read
logWrite("####################################");
logWrite("Begin batch send: $timestamp");
logWrite("------------------------------------");

if ($prosalon->update_prosalon_from_recipients()) {
    logWrite("Prosalon update is started.");
} else {
	logWrite("Prosalon can not be updateted.");
}

// Set up our current timestamp for closing out
$timestamp = date('Y-m-d H:i:s');

// Log the end of this send
logWrite("\n---------------------");
logWrite("Process complete\nEnd Prosalon update set: $timestamp");
logWrite("####################################\n");

logWrite(".....................................");
logWrite("Appointment confirm begin: $timestamp");
logWrite("------------------------------------");
$prosalon->confim_update_from_inbound();
$prosalon->not_confimed_update_from_inbound();
logWrite(".....................................");
logWrite("Appointment confirm END: $timestamp");
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
