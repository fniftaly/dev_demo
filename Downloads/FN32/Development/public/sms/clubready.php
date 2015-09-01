<?php
//echo $apppath = realpath(dirname(__FILE__).'/../..');
////echo "Hello";
//exit;
// Path to the logs
//$logpath = realpath(dirname(__FILE__)) . '/log/';
//
//// Path to our application
$apppath = realpath(dirname(__FILE__) . '/../..');

// Add the application path to the include path
set_include_path(get_include_path() . PATH_SEPARATOR . $apppath);

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
$first = $_POST['first'];
$last = $_POST['last'];
$day = $_POST['day'];
$month = $_POST['month'];
$email = $_POST['email'];
$year = $_POST['year'];
$confirmed = $_POST['cnfy'];

        $user = new Application_Model_User((int)$id);
        $frm = new Application_Model_Form();
        if($user->reward == '1'){
            $clubreadyid = $user->accountstatus;
        }
        $sendemail = FALSE;
        $dob = date($year.'-'.$month.'-'.$day);
        $res = $frm->postLeadsClubready($first, $last, $phone, $dob, $email, $clubreadyid, $sendemail);
//        {"UserId":3597061,"Success":true,"EmailSent":false,"PackageAdded":false}
         echo $res;  
//         echo $clubreadyid;  
//        echo $phone." : ".$id." : ".$last." :  ".$day." : ".$month." : ".$year." : ".$confirmed." : ".$dob." CRR: ".$clubreadyid;
//
exit;
