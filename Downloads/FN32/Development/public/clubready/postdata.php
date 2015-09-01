<?php

//   $apiUrl = "www.clubready.com/api/users/prospect";
$first = "Norman";
$last = "Alibab";
$phone = "2343212233";
$birthday="2000-01-01";
$email = "em@alibaba.com";
$clubid = 1782;
$sendmail = false;
postLeadsClubready($first,$last,$phone,$birthday,$email,$clubid,$sendmail);

function postLeadsClubready($first,$last,$phone,$birthday,$email,$clubid,$sendmail) {
     $info = array('ApiKey'=>'bf14aefb-1eb8-454b-a27c-e0f117d40352',
         'StoreID'=>$clubid,
         'FirstName'=>$first,
         'LastName'=>$last,
         'CellPhone'=>"$phone",
         'DateOfBirth'=>$birthday,
         'Email'=>$email,
         'SendEmail'=>$sendmail);
     $items = json_encode($info);
     echo $items.'<br>';
//     exit;
//     ApiKey = bf14aefb-1eb8-454b-a27c-e0f117d40352
  //   http://www.clubready.com/api/users/prospect?ApiKey=bf14aefb-1eb8-454b-a27c-e0f117d40352
  //   &StoreId=1782&FirstName=Test&LastName=Test&Email=some%40some.com
  //   &CellPhone=19253050723&SendEmail=fals
//    $apiUrl = "http://www.clubready.com/api/docs/index.htm#!/users/postusersprospect_post";
//    $apiUrl = "http://www.clubready.com/api/json/metadata?op=CreateProspectRequest";
     
    $apiUrl = "http://www.clubready.com/api/users/prospect?";
//create cURL connection
    $curl_connection = curl_init($apiUrl);
    curl_setopt($curl_connection, CURLOPT_CONNECTTIMEOUT, 30);
    curl_setopt($curl_connection, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl_connection, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($items)));
    curl_setopt($curl_connection, CURLOPT_POST, true);
    curl_setopt($curl_connection, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl_connection, CURLOPT_FOLLOWLOCATION, 1);
//set data to be posted
    curl_setopt($curl_connection, CURLOPT_POSTFIELDS, $items);
    
//perform our request
  echo $result = curl_exec($curl_connection);
//show information regarding the request
    print_r(curl_getinfo($curl_connection));
//    echo curl_errno($curl_connection) . '-' . curl_error($curl_connection);
//close the connection
    curl_close($curl_connection);
}

?>
