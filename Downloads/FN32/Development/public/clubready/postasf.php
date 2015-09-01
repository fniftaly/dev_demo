<?php
//phpinfo();
//exit; 
//clubid=string&loginName=string&loginPW=string&CanceledSince=string&NewSince=string


$info = array('clubid'=>40,'loginName'=>'baran', 'loginPW'=>'a1111','CanceledSince'=>'','NewSince'=>'20140301');

keyword();

function keyword($items,$info) {
    $apiUrl = "https://www.asfint.com/ASFWebServices/ws2.asmx/GetContactInf?";
    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "clubid=4000&loginName=farad&loginPW=a1&CanceledSince=''&NewSince=20140301");
//    curl_setopt($ch, CURLOPT_HTTPHEADER, $info);
    // Turn off header output in the response
    curl_setopt($ch, CURLOPT_HEADER, false);
   curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
    // Disable SSL peer verification
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    // Capture the output instead of echoing it
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Execute our request
   echo $rs = curl_exec($ch);
    print_r(curl_getinfo($ch));
//   $jsn = json_decode($rs,true);
//   print_r($jsn);
    // Close the cURL handle
    curl_close($ch);
}
