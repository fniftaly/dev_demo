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
//$logpath = realpath(dirname(__FILE__)) . '/log/';
// Path to our application
$apppath = realpath(dirname(__FILE__) . '/..');

// Add the application path to the include path
set_include_path(get_include_path() . PATH_SEPARATOR . $apppath);

//$logfile = $logpath . date('Ymd') . '-SNFC';
//
//// If there is no log for today, create it
//if (!file_exists($logfile)) {
//	touch($logfile);
//}


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

$nvobj = new Application_Model_Nevdata();
  $res1 = $nvobj ->getClubstatusNFC();
//   $blngArr = array(6, 13, 22, 37);
   $blngArr = array(1,15,31,61);
  
  $blng_one = $nvobj->selectingBlngSNFC($blngArr[0]);
  $blng_sec = $nvobj->selectingBlngSNFC($blngArr[1]);
  $blng_third = $nvobj->selectingBlngSNFC($blngArr[2]);
  $blng_fourth = $nvobj->selectingBlngSNFC($blngArr[3]);
  
  foreach($res1 as $arr=>$res){
       if($res->getBodconf()){
//           $phones = $nvobj->getClubBdPhones($res->getNevid());
//           $nvobj->sendCampaign($res->getBodmsg(), $phones, $res->getBodsend(), $res->getTimezone(), $res->getClubid());
       }
       if($res->getBlngfirstconf()){
           $phones = $nvobj->getphonelistSNFCloc($res->getNevid(), $blng_one);
           if($phones){
             $bd='bng1';  
            $nvobj->sendCampaignSNFC($res->getBlngfirstmsg(), $phones, $res->getBlngfirstsend(), $res->getTimezone(), $res->getClubid());
            $nvobj->nevusageBlng($res->getClubid(), $phones,$res->getNevid(),$bd);
           }
       }
       if($res->getBlngsecondconf()){
           $phones = $nvobj->getphonelistSNFCloc($res->getNevid(), $blng_sec);
           if($phones){
             $bd='bng2';  
            $nvobj->sendCampaignSNFC($res->getBlngsecondmsg(), $phones, $res->getBlngsecondsend(), $res->getTimezone(), $res->getClubid());
            $nvobj->nevusageBlng($res->getClubid(), $phones,$res->getNevid(),$bd);
           }
       }
       if($res->getBlngthirdconf()){
           $phones = $nvobj->getphonelistSNFCloc($res->getNevid(), $blng_third);
           if($phones){
             $bd='bng3';  
            $nvobj->sendCampaignSNFC($res->getBlngthirdmsg(), $phones, $res->getBlngthirdsend(), $res->getTimezone(), $res->getClubid());
            $nvobj->nevusageBlng($res->getClubid(), $phones,$res->getNevid(),$bd);
           }
       }
       if($res->getBlngfourthconf()){
           $phones = $nvobj->getphonelistSNFCloc($res->getNevid(), $blng_fourth);
           if($phones){
            $bd='bng4';   
            $nvobj->sendCampaignSNFC($res->getBlngfourthmsg(), $phones, $res->getBlngfourthsend(), $res->getTimezone(), $res->getClubid());
            $nvobj->nevusageBlng($res->getClubid(), $phones,$res->getNevid(),$bd);
           }
       }
       if($res->getCampaignconf()){
//           $clbphones = $nvobj->getClubphones($res->getNevid());
//          $nvobj->sendCampaign($res->getCampaignmsg(), $phones, $res->getCampaignsend(), $res->getTimezone(), $res->getClubid());
       }
     }
//$msql->disconnect();
//Tell it to purge its log
//$dr->purgeLog();

// End the process
return 0;
     function logWrite($msg) {
	global $logfile;
	$fh = fopen($logfile, 'a');
	fwrite($fh, "$msg\n");
	fclose($fh);
}
