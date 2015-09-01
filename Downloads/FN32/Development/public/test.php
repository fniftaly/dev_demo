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


$phone = $_POST['phone'];
$id = $_POST['id'];
$last = $_POST['last'];

        $user = new Application_Model_User((int)$id);
        if($user->additionalinfo == "CR"){
            $clubreadyid = $user->accountstatus;
        }
        echo $phone." : ".$id." : ".$last." Pizda ".$clubreadyid;

exit;
