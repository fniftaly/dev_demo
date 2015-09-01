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

// Path to our application
$apppath = realpath(dirname(__FILE__) . '/..');

// Add the application path to the include path
set_include_path(get_include_path() . PATH_SEPARATOR . $apppath);

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

// Get our config file
$config = new Zend_Config_Ini($apppath . '/application/configs/application.ini');
Zend_Registry::set('config', $config->production); // Because our models need it this way
// Get the Delivery Nev info model
//$nvobj = new Application_Model_Nevdata();
//$res1 = $nvobj->getClubstatus(1);
//foreach ($res1 as $arr => $res) {
//    if ($res->getBodconf()) {
//        $phones = $nvobj->getClubBdPhones($res->getNevid());
//         if (count($phones) != 0) {
//            $bd = 'bod';
//            $nvobj->sendCampaign($res->getBodmsg(), $phones, $res->getBodsend(), $res->getTimezone(), $res->getClubid());
//            $nvobj->nevusage($res->getClubid(), $phones, $res->getNevid(),$bd);
//        }
//    }
//    if ($res->getBlngfirstconf()) {
//        $phones = $nvobj->selectingforBlng($res->getNevid(), 1);
//        if (count($phones) != 0) {
//            $bd='bng1';
//            $nvobj->sendCampaign($res->getBlngfirstmsg(), $phones, $res->getBlngfirstsend(), $res->getTimezone(), $res->getClubid());
//            $nvobj->nevusageBlng($res->getClubid(), $phones,$res->getNevid(),$bd);
//        }
//    }
//    if ($res->getBlngsecondconf()) {
//        $phones = $nvobj->selectingforBlng($res->getNevid(), 15);
//        if (count($phones) != 0) {
//            $bd='bng2';
//            $nvobj->sendCampaign($res->getBlngsecondmsg(), $phones, $res->getBlngsecondsend(), $res->getTimezone(), $res->getClubid());
//            $nvobj->nevusageBlng($res->getClubid(), $phones,$res->getNevid(),$bd);
//        }
//    }
//    if ($res->getBlngthirdconf()) {
//        $phones = $nvobj->selectingforBlng($res->getNevid(), 31);
//        if (count($phones) != 0) {
//            $bd='bng3';
//            $nvobj->sendCampaign($res->getBlngthirdmsg(), $phones, $res->getBlngthirdsend(), $res->getTimezone(), $res->getClubid());
//            $nvobj->nevusageBlng($res->getClubid(), $phones,$res->getNevid(),$bd);
//        }
//    }
//    if ($res->getBlngfourthconf()) {
//        $phones = $nvobj->selectingforBlng($res->getNevid(), 61);
//        if (count($phones) != 0) {
//            $bd='bng4';
//            $nvobj->sendCampaign($res->getBlngfourthmsg(), $phones, $res->getBlngfourthsend(), $res->getTimezone(), $res->getClubid());
//            $nvobj->nevusageBlng($res->getClubid(), $phones,$res->getNevid(),$bd);
//        }
//    }
//    if ($res->getCampaignconf()) {
////           $clbphones = $nvobj->getClubphones($res->getNevid());
////           if(count($clbphones) != 0){
////             $nvobj->sendCampaign($res->getBlngfourthmsg(), $phones, $res->getBlngfourthsend(), $res->getTimezone(), $res->getClubid());
////             $nvobj->nevusage($res->getClubid(), $phones,$res->getNevid());
////           }
//    }
//}
//     phpinfo();
//Tell it to purge its log
//$dr->purgeLog();
// End the process
return 0;
