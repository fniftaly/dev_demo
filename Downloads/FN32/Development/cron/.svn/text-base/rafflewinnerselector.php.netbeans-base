#!/usr/bin/php
<?php
// Before we ever get started on sending any messages make sure we're not already running
// We might want to consider this, especially if we hang on a send...
//exec('ps -aef | grep rafflewinnerselector.php | grep -v grep', $running);
//if (count($running) > 1) {
//	die("The raffle winner process is running already and cannot be started again.\n\nProcess information:\n{$running[0]}");
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

// Build up the contest model
$contest = new Application_Model_Contest();

// Get our active auto raffles
$raffles = $contest->getAllAutoRaffles();

// Loop and work
if ($raffles && is_array($raffles)) {
	foreach ($raffles as $raffle) {
		// See if there are winners to pick for this raffle(contest id)
		$picks = $contest->hasWinnersToPick($raffle->id);
		if ($picks) {
			// ACtually select our winners now, and send them their message
			$winners = $contest->pickWinners($picks, $raffle->id);
			if ($winners) {
				$first = true;
				foreach ($winners as $winner) {
					if ($first) {
						$message = new Application_Model_Message(new Application_Model_User($winner->userid));
						$message->body = $winner->response;
						$message->keywordid  = $winner->keywordid;
						$message->folderid = $winner->folderid;
					}
					
					$message->recipients[$winner->id] = $winner->phonenumber;
				}
				
				// Send them their message
				if (isset($message) && $message instanceof Application_Model_Message) {
					$out = new Application_Model_Smsoutbound($message);
					$out->sendNow();
				}
			}
		}
	}
}

// Clean up
exit(0);