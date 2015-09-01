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
$timestamp = date('Y-m-d H:i:s');

// Log the start of this read
logWrite("####################################");
logWrite("Begin batch send: $timestamp");
logWrite("------------------------------------");

// Get the queue and counts
// Log our counts
logWrite("Testing ABC");
//logWrite("")
$abcObj = new Application_Model_Abcdata();
$abcObj->getURL(7278);
//$rs = $abcObj->abcClientData($abcObj->URL_UPDATE_INFO);
$rs = $abcObj->abcClientData($abcObj->URL_ALL_DATA);
$abs = $abcObj->setAbcclubObjects($rs);
//echo 'RS: '.count($rs).' ABCOBJ: '.count($abs);
//$oob = $abs[234];
//
//echo '<pre>';print_r($oob);
//echo $oob->getFirstname();
//echo '<pre>';
//echo $oob->getLastname();
//echo '<pre>';
//echo $oob->getMemberid();
$fid = 8528;
//$abcObj->abcSubscribers($abs,$fid);
//$abcObj->storeAbsdata($abs);
//$rs = $abcObj->getAccountsId();
echo '<pre>'; print_r($rs);
//
//echo "ID-------------------Memberid----------------Name-------------CellPhone-------------BirthDay<br>";
//foreach ($rs as $key => $val){
//    
//    $cell = ($val['contact']['cellPhone'] != NULL)?$val['contact']['cellPhone']:"NULL";
//    $bod = ($val['personal']['birthDate'] != NULL)?$val['personal']['birthDate']:'NULL';
//    $duepaymentdate = ($val['agreement']['payment']['nextDueDate'] != NULL)?$val['agreement']['payment']['nextDueDate']:'NULL';
//    if($cell == "NULL" || $bod =="NULL"){
//        continue;
//    }else{
//        $count++;
//    echo $count."-".$val['@attributes']['id']."  --  ".$val['homeClub']."  --  ".$val['personal']['firstName']."     ".$val['personal']['lastName']."  --  ".$cell."  --  ".$bod.
//           "  --  ".$val['personal']['gender']. "  --  ".$val['agreement']['payment']['nextDueAmount']."  --  ".$duepaymentdate.'<br>';
//    }
//}

logWrite("\n---------------------");
logWrite("ABC: $timestamp");
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