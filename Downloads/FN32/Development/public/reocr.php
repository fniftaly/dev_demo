<?php

// Path to the logs
$logpath = realpath(dirname(__FILE__)) . '/log/';

// Path to our application
$apppath = realpath(dirname(__FILE__) . '/..');

// Add the application path to the include path
set_include_path(get_include_path() . PATH_SEPARATOR . $apppath);

// Make sure we have enough memory available to this script
ini_set('memory_limit', '512M');
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
$weekdays = array('Sun'=>'sunday','Mon'=>'monday','Tue'=>'tuesday','Wed'=>'wednesday','Thu'=>'thursday','Fri'=>'friday','Sat'=>'saterday');
// Log the start of this read
logWrite("####################################");
logWrite("Begin batch send: $timestamp");
logWrite("------------------------------------");

// Get the queue and counts
// Log our counts
logWrite("Reoccurring");
//logWrite("")
//$msg = new Application_Model_Message();
//$arr = $msg->selectWeeklyCampaignSend();
//$msg->weeklycampToqueue($arr);
// echo $week = $msg->getWeeks(date('Y-m-d'),$weekdays[date('D')]);
//$arr = $msg->selectMonthlCampaignSend((int)($week+1));
//$msg->weeklycampToqueue($arr);
//$user = new Application_Model_User(362);
//$rpt = new Application_Model_Report();
//$rpt->saveUserDailyactivity();
//$usrids = $rpt->accountids();
// $usrids = $rpt->corparateIdList(185,5);
//echo '<pre>'; print_r($user);
//$kobj    = new Application_Model_Keyword;
//$kobj->create_crkewords("bassan", 4444, 1111, 66666)
$apd = new Application_Model_Apidataentry();
//$dt = array(8888888,8888888,8888888);
//$apd->insertApidataentry($dt, 444, 87365);
// $jsn = $apd->getDataStatus(4853);
$sbrs = array(17602173754, 17072350832, 17072916500, 19178032141);
$jr = json_encode($sbrs);
 $jsn = $apd->requestfromDelivery($jr, 87365);
 echo '<pre>';print_r($jsn);
logWrite("\n---------------------");
logWrite("Reoccuring: $timestamp");
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