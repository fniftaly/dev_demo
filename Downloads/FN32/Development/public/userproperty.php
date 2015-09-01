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
$logfile = $logpath . date('Ymd') . '-userpropertyupdate';
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
            'basePath' => $apppath . '/application/',
            'namespace' => 'Application',
        ));

// Name, path, namepsace
$loader->addResourceType('model', 'models', 'Model');

// Get our config file
$config = new Zend_Config_Ini($apppath . '/application/configs/application.ini');
Zend_Registry::set('config', $config->production); // Because our models need it this way
// Get the messages model
// Set up our current timestamp

// Log the start of this read
logWrite("####################################");
logWrite("User property update starts ". date('Y-m-d H:i:s'));
logWrite("------------------------------------");
$usrprop = array(63, 64, 74, 65, 66,77,78);
$mrkinfo = array(67, 68, 69, 70, 71, 72, 75, 76, 73);
$rep = new Application_Model_Report();

//$accounts_id = $rep->accountids();
$accounts_id = $rep->corparateIdList(185,5);

$countusrid = count($accounts_id);
if($rep->subscribers_reportUpdate()){
$myla = $rep->getMylaFoldersId();

for ($uid = 0; $uid < $countusrid; $uid++) {
    $val = $rep->reportCountRegGrpBySubscribersByUserIdNewKeys($accounts_id[$uid],"daily");
    $rep->activateUserproperty($accounts_id[$uid], $usrprop[0], $val);
    $val_w = $rep->dashboard_totalOptedInsByWebform($accounts_id[$uid],"daily");
    $rep->activateUserproperty($accounts_id[$uid], $usrprop[1], $val_w);
    if ($myla[$accounts_id[$uid]]) {
        $fd = $myla[$accounts_id[$uid]];
        $val_m = $rep->getMyla($accounts_id[$uid], $fd,"daily");
        $rep->activateUserproperty($accounts_id[$uid], $usrprop[2], $val_m);
    } else {
        $val_m = 0;
        $rep->activateUserproperty($accounts_id[$uid], $usrprop[2], $val_m);
    }
    $val_u = $rep->dashboard_totalUpload($accounts_id[$uid]);
    $rep->activateUserproperty($accounts_id[$uid], $usrprop[3], $val_u);
    $val_t = $rep->dashboard_totalOptedIn($accounts_id[$uid],'daily');
    $rep->activateUserproperty($accounts_id[$uid], $usrprop[4], $val_t);
    
    $val_totaloptin = $rep->totalOptedIn_optedout_fortheday($accounts_id[$uid], "optin");
    $rep->activateUserproperty($accounts_id[$uid], $usrprop[5], $val_totaloptin);
    
    $val_totaloptout = $rep->totalOptedIn_optedout_fortheday($accounts_id[$uid], "optout");
    $rep->activateUserproperty($accounts_id[$uid], $usrprop[6], $val_totaloptout);
}

for ($uid = 0; $uid < $countusrid; $uid++) {
    $val_kwd = $rep->getUserMarketingInfo_Mo($accounts_id[$uid]);

    $rep->activateUserproperty($accounts_id[$uid], $mrkinfo[0], $val_kwd);

    $val_web = $rep->getUserMarketingInfo(202, $accounts_id[$uid]);
    $rep->activateUserproperty($accounts_id[$uid], $mrkinfo[1], $val_web);
    $val_bod = $rep->getUserMarketingInfo(303, $accounts_id[$uid]);
    $rep->activateUserproperty($accounts_id[$uid], $mrkinfo[2], $val_bod);

    $val_mrk = $rep->getUserMarketingInfo(404, $accounts_id[$uid]);
    $rep->activateUserproperty($accounts_id[$uid], $mrkinfo[3], $val_mrk);

    $val_mla = $rep->getUserMarketingInfo(505, $accounts_id[$uid]);
    $rep->activateUserproperty($accounts_id[$uid], $mrkinfo[4], $val_mla);
    
//    $val_camp = $rep->getUserTotalCampaign($accounts_id[$uid]); 
    $yearmonth = date('Y-m');
    $val_camp = $rep->totalCampaignFromUserAccount($accounts_id[$uid],$yearmonth); // this is temporarly
    
    $rep->activateUserproperty($accounts_id[$uid], $mrkinfo[5], $val_camp);
    $val_totalmrk = $rep->getTotalMarketingMt($accounts_id[$uid],'ym');
    $rep->activateUserproperty($accounts_id[$uid], $mrkinfo[6], $val_totalmrk);
    $val_dailymt = $rep->getTotalMarketingMt($accounts_id[$uid],'ymd');
    $rep->activateUserproperty($accounts_id[$uid], $mrkinfo[7], $val_dailymt);
}
//       $val_camp = $rep->$rep->getUserTotalCampaign($accounts_id[$uid]);
//       $rep->activateUserproperty($accounts_id[$uid], $usrprop[4], $val_camp);
}
//for($uid = 0; $uid < $countusrid; $uid++ ){
//     $val = $rep->reportCountRegGrpBySubscribersByUserIdNewKeys($accounts_id[$uid]);
//     $rep->activateUserproperty($accounts_id[$uid], $usrprop[0], $val);
//      echo '<br>'.$accounts_id[$uid].'  Key: '.$val;
//      $val_w = $rep->dashboard_totalOptedInsByWebform($accounts_id[$uid]);
//      $rep->activateUserproperty($accounts_id[$uid], $usrprop[1], $val_w); 
//       echo ' Web: '.$val_w;
//       if($myla[$accounts_id[$uid]]){
//         $fd = $myla[$accounts_id[$uid]];
//        $val_m = $rep->getMyla($accounts_id[$uid],$fd);
//        $rep->activateUserproperty($accounts_id[$uid], $usrprop[2], $val_m);
//       }else{$val_m=0;
//        $rep->activateUserproperty($accounts_id[$uid], $usrprop[2], $val_m);
//       }
//       echo ' MyLa: '.$val_m;
//       $val_u = $rep->dashboard_totalUpload($accounts_id[$uid]);
//       $rep->activateUserproperty($accounts_id[$uid], $usrprop[3], $val_u);
//        echo ' Upload: '.$val_u;
//       $val_t = $rep->dashboard_totalOptedIn($accounts_id[$uid]);
//       $rep->activateUserproperty($accounts_id[$uid], $usrprop[4], $val_t);
//        echo ' Ttl: '.$val_t;
//        }
logWrite("\n---------------------");
logWrite("User property update ends: ".date('Y-m-d H:i:s'));
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