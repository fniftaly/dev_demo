<?php

// Path to the logs
$logpath = realpath(dirname(__FILE__)) . '/log/';

// Path to our application
$apppath = realpath(dirname(__FILE__) . '/..');

// Add the application path to the include path
set_include_path(get_include_path() . PATH_SEPARATOR . $apppath);

// Log file for today
$logfile = $logpath . date('Ymd') . '-weeklyreportsender';

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
$weeklyreport = new Application_Model_Report();

// Set up our current timestamp
$timestamp = date('Y-m-d H:i:s');

// Log the start of this read
logWrite("####################################");
logWrite("Begin batch send: $timestamp");
logWrite("------------------------------------");

// Get the queue and counts
$sendReportsTo = $weeklyreport->sendRportTo();
$queueCount = count($sendReportsTo);
$sendCount = 0;

// Log our counts
logWrite("Number of persons to whom report will bse sent: $queueCount\nBegin send loop:");
//logWrite("")
//logWrite("")
echo '<pre>';print_r($sendReportsTo);
if (is_array($sendReportsTo)) {
	if (!empty($sendReportsTo)) {
 foreach ($sendReportsTo as $ars=>$user) {
            $mail = new Zend_Mail();            
            $mail->setFrom('reports@textmunication.com', 'Textmunication.com');
            $mail->addTo($user['email']);
            $mail->addCc('wais@textmunication.com', 'Wais Asefi');
            $mail->setSubject('Weekly Reports');          
                    // Get the Excel model
//            $excelDataArray = array();
            $excelFile = "";
            if($weeklyreport->checkAdminUser($user['id']) AND $user['id']!=187)  
            { 
                if($user['edituser'] != '0'){
                    $excelDataArray = $weeklyreport->getWeeklyReportApiuser($user['id']);
                    $enddate = date("Ymd");
                    $startdate = date('Ymd', strtotime('-7 days'));
                    $excelFileName = "Prosalon_weekly_report" .$startdate.'_'. $enddate;
                    logWrite("Creating the Excel spreadsheets");
                    $excel = new Application_Model_Excel();
                    if(isset($excelDataArray)){
                       $excelFile = $excel->create($excelDataArray, $excelFileName);
                       logWrite("Attaching the spreadsheets");
                       $at = $mail->createAttachment(file_get_contents($excelFile['path']));
                       $at->filename = $excelFile['name'];
                    }
                    else{
                        continue;
                    }
                }
//                else{
//                    $excelDataArray = $weeklyreport->getWeeklyReport($user['id'],$user['typeidextra']); 
//                    $date = date('Ymd');
//                    $excelFileName = "Prosalon_weekly_optin" .$user['id'].'_'. $date;
//                    logWrite("Creating the Excel spreadsheets");
//                    $excel = new Application_Model_Excel();
//                    if(isset($excelDataArray)){
//                       $excelFile = $excel->create($excelDataArray, $excelFileName);
//                       logWrite("Attaching the spreadsheets");
//                       $at = $mail->createAttachment(file_get_contents($excelFile['path']));
//                       $at->filename = $excelFile['name'];
//                    }
//                    else{
//                        continue;
//                    }
//                }
            }
//            else{
//                    // Get the subscriber datasets
//                    $excelDataArray = $weeklyreport->getWeeklyReport(); 
//                    // Get a date stamp for the file
//                    $date = date('Ymd');
//                    // Create our file names
//                    $excelFileName = "weeklyreport_clientid" .$user['id'].'_'. $date;
//                    // Log the steps
//                    logWrite("Creating the Excel spreadsheets");
//                    $excel = new Application_Model_Excel();   
//                    // Make the Excel files for each day
//                    $excelFile = $excel->create($excelDataArray, $excelFileName); 
//                    logWrite("Attaching the spreadsheets");
//                    $at = $mail->createAttachment(file_get_contents($excelFile['path']));
//                    $at->filename = $excelFile['name'];                            
//            }                                        

            // Log the steps
            logWrite("Preparing to send...");
//            $mail->setBodyText('Please see the weekly report: https://admin.textmunication.com/reportdocs/'.$excelFile['name']);
            $mail->setBodyText('Weekly report....\n');
            // Send it on
            echo '<br>Preparing to send...';
            if (!$mail->send()) {
                logWrite("Report Not Sent");
//                echo '<br>is not send';
            } else {
                logWrite("Report sent");
                echo '<br>it is send';
            }           
		}
	} else {
		logWrite("No reports to send");
	}
} else {
	logWrite("Send to report was not properly fetched");
}
logWrite("Messages Sent: $sendCount");

// Set up our current timestamp for closing out
$timestamp = date('Y-m-d H:i:s');

// Log the end of this send
logWrite("\n---------------------");
logWrite("Process complete\nEnd message send: $timestamp");
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