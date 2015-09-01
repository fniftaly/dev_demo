<?php
//define('SALT', 'Ww7Iu/Qvg8AiDw+JdXHtq1N99dJJiS='); // SALT for encryption
//define('PASSWORD_SALT', '0SmxDs39sX9jBnu2');
//
//
//function password_encrypt($password) 
//{
//
//  if($password!=null || $password!='')
//  {  
//  $password =  crypt($password,'$6$rounds=5000$'.PASSWORD_SALT.'$');
//  }
//
//  return $password;
//
//}
//
//function password_encrypt1($password) 
//{
//
//  if($password!=null || $password!='')
//  {  
//  
//  $password =  crypt($password,crypt($password));
//  }
//
//  return $password;
//
//}
//
//echo password_encrypt('smartData');
//
//$salt = '$6$rounds=5000$'.PASSWORD_SALT.'$';
////echo system(crypt('smartData', $salt));
//echo system(crypt(abc, sss));

$mobj = memcache_connect('10.179.252.160', 11211);

//$kobj = $mobj->get('amigo');
//
// echo $kobj->id.'<br>'.$kobj->replybody.'<br>'.$kobj->createuser;
// echo '<pre>';
// 
// echo '<pre>'; print_r($mobj->getStats());
// 
// $ps = $mobj->get('OPTEDOUT');
// echo '<pre>';
// 
// echo count($ps);
// 19257978350
//			1025438429
//			1231111234
//			1235345345
//			1235551111
//			1252505767
//			1400034048
//			1407234787
//			1408591413
//			1408712593
//			1743929024
//			17073847351
//			19256425789
//			19258583462
//			14156020876
//			15103843802
//			14253309620
//			14089105970
//			17148763665
//			18016020036

//			17148783085
//			17147247192
//			19177694217
//			13104048390
//			13104982634
//			13104567210
//			13235226521
//  echo '<pre>';
//  echo count($mobj->get('OPTEDOUT_INBOUND'));
//phpinfo();
$keys = $mobj->get('OPTIN_ALL_KEYS');

$phone = 19999999998;
foreach($keys as $kkk=>$vvv){
    $b_flag = 'FALSE';
    if($phone < $vvv){
       $in_keys = $mobj->get($vvv); 
       if( $in_keys[$phone]){
           $b_flag = 'TRUE';
       }
        break;
    }else{ continue;}
    return $b_flag;
}
echo '<pre>'; print_r($keys);
echo '<pre>';
echo $b_flag;
?>