<!--#!/usr/bin/php-->
<?php
// Before we ever get started on sending any messages make sure we're not already running
// We might want to consider this, especially if we hang on a send...
//exec('ps -aef | grep messagesender.php | grep -v grep', $running);
//if (count($running) > 1) {
//	die("The message sender process is running already and cannot be started again.\n\nProcess information:\n{$running[0]}");
//}

// Make sure we have enough memory available to this script
ini_set('memory_limit', '512M');

// Path to our application
$apppath = realpath(dirname(__FILE__) . '/..');

// Add the application path to the include path
set_include_path(get_include_path() . PATH_SEPARATOR . $apppath);

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
$msg = "Your time cart is out of pay";
  $phones = array(10000000001,10000000002,10000000003,10000000004);
  $send = date('Y-m-d H:i:s');
  $nvobj->sendCampaignSNFC($msg, $phones,$send, "US/Pacific", 9131);
   echo "Data: ".date("Y-m-d");
//  $res1 = $nvobj ->getClubstatusNFC();
  
//   $blngArr = array(6, 13, 22, 37);
//   $blngArr = array(1,15,31,61);
//  
//  $blng_one = $nvobj->selectingBlngSNFC($blngArr[0]);
//  $blng_sec = $nvobj->selectingBlngSNFC($blngArr[1]);
//  $blng_third = $nvobj->selectingBlngSNFC($blngArr[2]);
//  $blng_fourth = $nvobj->selectingBlngSNFC($blngArr[3]);
////  echo '<pre>';print_r($res1);
////  exit;
//  foreach($res1 as $arr=>$res){
//       echo "Clubid: ".$res->getClubid().'<br>';
//       if($res->getBodconf()){
//           echo 'Clubid: BODHERE<br>';
////           $phones = $nvobj->getClubBdPhones($res->getNevid());
//            echo count($phones).'<br>';
//            echo "<pre>"; print_r($phones);
////           $nvobj->sendCampaign($res->getBodmsg(), $phones, $res->getBodsend(), $res->getTimezone(), $res->getClubid());
//       }
//       if($res->getBlngfirstconf()){
//           echo 'Clubid: Bassan<br>';
////           $phones = $nvobj->selectingforBlngSNFC($res->getNevid(), 1);
//           $phones = $nvobj->getphonelistSNFCloc($res->getNevid(), $blng_one);
//           echo count($phones).'<br>';
//           echo "<pre>"; print_r($phones);
//           if($phones){
////            $nvobj->sendCampaign($res->getBlngfirstmsg(), $phones, $res->getBlngfirstsend(), $res->getTimezone(), $res->getClubid());
//           }
//       }
//       if($res->getBlngsecondconf()){
////           $phones = $nvobj->selectingforBlngSNFC($res->getNevid(), 15);
//           $phones = $nvobj->getphonelistSNFCloc($res->getNevid(), $blng_sec);
//           echo count($phones).'<br>';
//           echo "<pre>"; print_r($phones);
//           if($phones){
////            $nvobj->sendCampaign($res->getBlngsecondmsg(), $phones, $res->getBlngsecondsend(), $res->getTimezone(), $res->getClubid());
//           }
//       }
//       if($res->getBlngthirdconf()){
////           $phones = $nvobj->selectingforBlngSNFC($res->getNevid(), 31);
//           $phones = $nvobj->getphonelistSNFCloc($res->getNevid(), $blng_third);
//           echo count($phones).'<br>';
//           echo "<pre>"; print_r($phones);
//           if($phones){
////            $nvobj->sendCampaign($res->getBlngthirdmsg(), $phones, $res->getBlngthirdsend(), $res->getTimezone(), $res->getClubid());
//           }
//       }
//       if($res->getBlngfourthconf()){
////           $phones = $nvobj->selectingforBlngSNFC($res->getNevid(), 61);
//           $phones = $nvobj->getphonelistSNFCloc($res->getNevid(), $blng_fourth);
//           echo count($phones).'<br>';
//           echo "<pre>"; print_r($phones);
//           if($phones){
////            $nvobj->sendCampaign($res->getBlngfourthmsg(), $phones, $res->getBlngfourthsend(), $res->getTimezone(), $res->getClubid());
//           }
//       }
//       if($res->getCampaignconf()){
////           $clbphones = $nvobj->getClubphones($res->getNevid());
////          $nvobj->sendCampaign($res->getCampaignmsg(), $phones, $res->getCampaignsend(), $res->getTimezone(), $res->getClubid());
//       }
//     }
//$msql->disconnect();
//Tell it to purge its log
//$dr->purgeLog();

// End the process
//return 0;
