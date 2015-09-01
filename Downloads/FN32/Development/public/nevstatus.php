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
echo "Data: ".date("Y-m-d");
  $res1 = $nvobj ->getClubstatus(0);
  
  $blngArr = array(1,15,31,61);
//  $blngArr = array(6, 13, 22, 37);
//  $bd = $nvobj->selectingBirthdayUFC();
  $blng_one = $nvobj->selectingBlngUFC($blngArr[0]);
  $blng_sec = $nvobj->selectingBlngUFC($blngArr[1]);
  $blng_third = $nvobj->selectingBlngUFC($blngArr[2]);
  $blng_fourth = $nvobj->selectingBlngUFC($blngArr[3]);
  
  //selectingBlngUFC
//  echo '<pre>';print_r($res1);
//  exit;
  foreach($res1 as $arr=>$res){
       echo "Clubid: ".$res->getClubid().'<br>';
       if($res->getBodconf()){
           $phones = $nvobj->getClubBdPhones($res->getNevid());
//           $phones = $nvobj->getphonelistSNFCloc($res->getNevid(), $bd);
            echo count($phones).'<br>';
            echo "<pre>"; print_r($phones);
//           $nvobj->sendCampaign($res->getBodmsg(), $phones, $res->getBodsend(), $res->getTimezone(), $res->getClubid());
       }
       if($res->getBlngfirstconf()){
           $phones = $nvobj->getphonelistSNFCloc($res->getNevid(), $blng_one);
           echo count($phones).'<br>';
           echo "<pre>"; print_r($phones);
           if($phones){
//            $nvobj->sendCampaign($res->getBlngfirstmsg(), $phones, $res->getBlngfirstsend(), $res->getTimezone(), $res->getClubid());
           }
       }
       if($res->getBlngsecondconf()){
           $phones = $nvobj->getphonelistSNFCloc($res->getNevid(), $blng_sec);
           echo count($phones).'<br>';
           echo "<pre>"; print_r($phones);
           if($phones){
//            $nvobj->sendCampaign($res->getBlngsecondmsg(), $phones, $res->getBlngsecondsend(), $res->getTimezone(), $res->getClubid());
           }
       }
       if($res->getBlngthirdconf()){
           $phones = $nvobj->getphonelistSNFCloc($res->getNevid(), $blng_third);
           echo count($phones).'<br>';
           echo "<pre>"; print_r($phones);
           if($phones){
//            $nvobj->sendCampaign($res->getBlngthirdmsg(), $phones, $res->getBlngthirdsend(), $res->getTimezone(), $res->getClubid());
           }
       }
       if($res->getBlngfourthconf()){
            $phones = $nvobj->getphonelistSNFCloc($res->getNevid(), $blng_fourth);
           echo count($phones).'<br>';
           echo "<pre>"; print_r($phones);
           if($phones){
//            $nvobj->sendCampaign($res->getBlngfourthmsg(), $phones, $res->getBlngfourthsend(), $res->getTimezone(), $res->getClubid());
           }
       }
       if($res->getCampaignconf()){
//           $clbphones = $nvobj->getClubphones($res->getNevid());
//          $nvobj->sendCampaign($res->getCampaignmsg(), $phones, $res->getCampaignsend(), $res->getTimezone(), $res->getClubid());
       }
     }

//Tell it to purge its log
//$dr->purgeLog();

// End the process
//return 0;
