<?php

// Path to the logs
$logpath = realpath(dirname(__FILE__)) . '/log/';

// Path to our application
$apppath = realpath(dirname(__FILE__) . '/..');

// Add the application path to the include path
set_include_path(get_include_path() . PATH_SEPARATOR . $apppath);

// Log file for today
$logfile = $logpath . date('Ymd') . '-weeklyreportsender';
//echo $logpath.'<br/>';
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
//$timestamp = date('Y-m-d H:i:s');
//
//// Log the start of this read
//logWrite("####################################");
//logWrite("Begin batch send: $timestamp");
//logWrite("------------------------------------");
//
//// Get the queue and counts
//// Log our counts
//logWrite("Testing ABC");
////logWrite("")
//
//
//logWrite("\n---------------------");
//logWrite("ABC: $timestamp");
//logWrite("####################################\n");
$obj = new Application_Model_Abcdata();
$data = $obj->getPfacnumbers();

$ids = array();
$num = array();
foreach($data as $k=>$v){
    $ids[$v['id']]= array();
}
//echo '<pre>';print_r($ids);
$msg = "PIZZA FACTORY:Thanks for being a customer. As a reminder u are receiving periodic text message rewards from Pizza Factory. To discontinue reply STOP";
$timezone = "US/Pacific";
$sendtime = date('2013-12-04 10:00:00');
$message     = new Application_Model_Message();
foreach($data as $k=>$v){
       $ids[$v['id']][$v['phonenumber']] = $v['phonenumber'];
}
$cntr = 0;
 foreach($ids as $ik=>$iv){
  $array = array_values($iv); 
  if($cntr > 30){$sendtime = date('2013-12-04 11:00:00');}
  if($cntr > 65){$sendtime = date('2013-12-04 12:30:00');}
  $message->queue($msg, $array, $sendtime, $timezone,0,$ik);  
  $cntr++;
 }
//echo '<pre>';print_r($ids);
return 0;

/**
 * [0] => Array
        (
            [phonenumber] => 19252504282
            [id] => 174
        )

    [1] => Array
        (
            [phonenumber] => 19512653524
            [id] => 174
        )
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