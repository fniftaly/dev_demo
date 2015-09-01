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
  $res1 = $nvobj ->getClubstatus(3);
  
//   $blngArr = array(1,15,31,61);
   $blngArr = array(1,15,31,45,61,75);
   
//  $bd =   $nvobj->selectingBirthdayJV();
  
  $blng_one = $nvobj->selectingBlngJV($blngArr[0]);
  $blng_sec = $nvobj->selectingBlngJV($blngArr[1]);
  $blng_third = $nvobj->selectingBlngJV($blngArr[2]);
  $blng_fourth = $nvobj->selectingBlngJV($blngArr[3]);
  $blng_fifth = $nvobj->selectingBlngJV($blngArr[4]);
  $blng_sixth = $nvobj->selectingBlngJV($blngArr[5]);
  
  foreach($res1 as $arr=>$res){
       if($res->getBodconf()){
//           $phones = $nvobj->getClubBdPhones();
//           $nvobj->sendCampaign($res->getBodmsg(), $phones, $res->getBodsend(), $res->getTimezone(), $res->getClubid());
       }
       if($res->getBlngfirstconf()){
           $phones = $nvobj->getphonelistSNFCloc($res->getNevid(), $blng_one);
           if($phones){
             $bd='bng1';  
//            $nvobj->sendCampaign($res->getBlngfirstmsg(), $phones, $res->getBlngfirstsend(), $res->getTimezone(), $res->getClubid());
//            $nvobj->nevusageBlng($res->getClubid(), $phones,$res->getNevid(),$bd);
             echo $bd.": ".count($phones).'<br>';
             echo "<pre>"; print_r($phones);
           }
       }
       if($res->getBlngsecondconf()){
           $phones = $nvobj->getphonelistSNFCloc($res->getNevid(), $blng_sec);
           if($phones){
             $bd='bng2';  
//            $nvobj->sendCampaign($res->getBlngsecondmsg(), $phones, $res->getBlngsecondsend(), $res->getTimezone(), $res->getClubid());
//            $nvobj->nevusageBlng($res->getClubid(), $phones,$res->getNevid(),$bd);
             echo $bd.": ".count($phones).'<br>';
             echo "<pre>"; print_r($phones);
           }
       }
       if($res->getBlngthirdconf()){
           $phones = $nvobj->getphonelistSNFCloc($res->getNevid(), $blng_third);
           if($phones){
             $bd='bng3';  
//            $nvobj->sendCampaign($res->getBlngthirdmsg(), $phones, $res->getBlngthirdsend(), $res->getTimezone(), $res->getClubid());
//            $nvobj->nevusageBlng($res->getClubid(), $phones,$res->getNevid(),$bd);
             echo $bd.": ".count($phones).'<br>';
             echo "<pre>"; print_r($phones);
           }
       }
       if($res->getBlngfourthconf()){
           $phones = $nvobj->getphonelistSNFCloc($res->getNevid(), $blng_fourth);
           if($phones){
            $bd='bng4';   
//            $nvobj->sendCampaign($res->getBlngfourthmsg(), $phones, $res->getBlngfourthsend(), $res->getTimezone(), $res->getClubid());
//            $nvobj->nevusageBlng($res->getClubid(), $phones,$res->getNevid(),$bd);
             echo $bd.": ".count($phones).'<br>';
             echo "<pre>"; print_r($phones);
           }
       }
       if($res->getBlngfifthconf()){
           $phones = $nvobj->getphonelistSNFCloc($res->getNevid(), $blng_fifth);
           if($phones){
            $bd='bng5';   
//            $nvobj->sendCampaign($res->getBlngfourthmsg(), $phones, $res->getBlngfourthsend(), $res->getTimezone(), $res->getClubid());
//            $nvobj->nevusageBlng($res->getClubid(), $phones,$res->getNevid(),$bd);
            echo $bd.": ".count($phones).'<br>';
            echo "<pre>"; print_r($phones);
           }
       }
       if($res->getBlngsixthconf()){
           $phones = $nvobj->getphonelistSNFCloc($res->getNevid(), $blng_sixth);
           if($phones){
            $bd='bng6';   
//            $nvobj->sendCampaign($res->getBlngfourthmsg(), $phones, $res->getBlngfourthsend(), $res->getTimezone(), $res->getClubid());
//            $nvobj->nevusageBlng($res->getClubid(), $phones,$res->getNevid(),$bd);
            echo $bd.": ".count($phones).'<br>';
            echo "<pre>"; print_r($phones);
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
//return 0;
