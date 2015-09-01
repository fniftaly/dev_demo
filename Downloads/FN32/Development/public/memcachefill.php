<!--#!/usr/bin/php-->
<?php
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

$inb = new Application_Model_Smsinbound();
$inb->optoutsListFromInboundToMemcache();
//$kwds = $inb->addMemcahceKeword();

//echo '<pre>'; print_r($kwds);


//foreach($kwds as $key=>$val){
//    $mobj->delData(strtolower($key));
//}
//foreach($kwds as $key=>$val){
//   $mobj->setData(strtolower($key), $val); 
//}
//$mobj = memcache_connect('10.179.252.160', 11211);



      
//$optin_key_list = "OPTIN_ALL_KEYS";
//
// $mobj->delData($optin_key_list);
// $mobj->setData($optin_key_list, $optinarrkeys);
// 
// $get_all_keys = $mobj->get($optin_key_list);
 
//foreach($get_all_keys as $key13=>$val13){
//    $mobj->delete($val13);
//}  
//$mobj->delete($optin_key_list);

////echo 'DONE:'.$cnt;
//echo '<pre>'; print_r($get_all_keys);

//$a1 = $mobj->get((int)$get_all_keys[1]);

//echo '<br>A1: '.print_r($a1);

//foreach($get_all_keys as $key11=>$val11){
//  echo '<pre>'; 
//  $inarr = $mobj->get($val11);
//  echo $val11.' : : '.count($inarr);  
//}
//echo '<pre>'; print_r($mobj->get('13109941092'));
//$tm = 12099141214;  
//$tm = 16023306957;  
//$tm = 16238243768; 
$tm = 19253050723;
//$tm = 14804141416;

$phk='';
statusofSubscriber($tm);
 function statusofSubscriber($phone){
        
        $mobj = memcache_connect('10.179.252.160', 11211);
        
        $keys = $mobj ->get('OPTIN_ALL_KEYS');
       
        for($n = 0; $n< count($keys); $n++){
            
            if($keys[$n] >= $phone){
                
             $phk = $keys[$n];
              
              $rarr = $mobj->get((int)$phk);
              
              if($rarr[$phone]){
                  echo 1;
                  return 1;
                  
              }else{echo 0 ; return 0; }
            }
        }
    }// end of statusofSubscriber

//        for($n = 0; $n< count($get_all_keys); $n++){
//            
//            if($get_all_keys[$n] >= $tm){
//                
//              echo "<br>".$phk = $get_all_keys[$n]."<br>";
//              
//              $rarr = $mobj->get((int)$phk);
//              
//              echo "<br>".count($rarr)."<br>";
//              
//              if($rarr[$tm]){
//                  echo 1;
//                 return 1;
//              }else{ 
//                  echo 0;
//                  return 0;
//                  }
//            }
//        }
//        
//     echo '<pre>';
//     echo $phk;
//     echo '<pre>'; print_r($mobj->get((int)$phk));   