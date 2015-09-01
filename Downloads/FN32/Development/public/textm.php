<?php

//www.clubreadystage.com/services/ss/sms/textmunication/event

//keyword("munda");
function keyword($keyword) {
    $apiUrl = "http://api.textmunication.com/createkeyword/";
    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_GET, 1);
    $shortcode = 28776;
        curl_setopt($ch, CURLOPT_POSTFIELDS, "username=clubready&password=cr2014&shortcode=$shortcode&keyword=$keyword");
   
    // Turn off header output in the response
    curl_setopt($ch, CURLOPT_HEADER, false);

    // Disable SSL peer verification
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    // Capture the output instead of echoing it
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Execute our request
   echo $rs = curl_exec($ch);
//   $jsn = json_decode($rs,true);
//   print_r($jsn);
    // Close the cURL handle
    curl_close($ch);
}
function sendMessage($phones, $msg, $event) {
    $message = urlencode($msg);
    if ($event == "send") {
        $apiUrl = "http://api.textmunication.com/textm/";
    } else {
        $apiUrl = "https://api.textmunication.com/textmdr/";
    }

    $dt = time();
    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_GET, 1);
    if ($event == "send") {
        curl_setopt($ch, CURLOPT_POSTFIELDS, "username=farad&password=0099&recipients=$phones&subject=Testing&message=$message");
    } else {
        curl_setopt($ch, CURLOPT_POSTFIELDS, "username=farad&password=0099&shortcode=0&timestamp=$dt");
    }
    //curl_setopt($ch, CURLOPT_USERPWD, $username . ':' . $password);
    // Turn off header output in the response
    curl_setopt($ch, CURLOPT_HEADER, false);

//			curl_setopt($ch,CURLOPT_HTTPHEADER,array('X-Api-Key: 7663a9d863ee4dfbac8c42affd51f149'));
    // Disable SSL peer verification
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    // Capture the output instead of echoing it
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Execute our request
    echo curl_exec($ch);

    // Close the cURL handle
    curl_close($ch);
}

// end of alert messages 
//$rr = array(19253050723, 19252504282, 19257275620, 12345678901, 10034567890,10034467890,19258267486);
//$jr = json_encode($rr);
$message = "Testing api conneciton for clubready 09-2014. Please reply buno or stop";
$send = "send";
$recive = "recive";
//      sendMessage(19253050723);
//      sendMessage($jr);
sendMessage(19253050723, $message, $send);

//function postDataClubready($items) {
//    $apiUrl = "www.clubreadystage.com/services/ss/sms/textmunication/event";
////traverse array and prepare data for posting (key1=value1)
//    foreach ($items as $key => $value) {
//        $post_items[] = $key . '=' . $value;
//    }
////create the final string to be posted using implode()
//    $post_string = implode('&', $post_items);
////create cURL connection
//    $curl_connection = curl_init($apiUrl);
////set options
//    curl_setopt($curl_connection, CURLOPT_CONNECTTIMEOUT, 30);
////curl_setopt($curl_connection, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
//    curl_setopt($curl_connection, CURLOPT_RETURNTRANSFER, true);
//    curl_setopt($curl_connection, CURLOPT_SSL_VERIFYPEER, false);
//    curl_setopt($curl_connection, CURLOPT_FOLLOWLOCATION, 1);
////set data to be posted
//    curl_setopt($curl_connection, CURLOPT_POSTFIELDS, $post_string);
////perform our request
//    $result = curl_exec($curl_connection);
////show information regarding the request
//    print_r(curl_getinfo($curl_connection));
//    echo curl_errno($curl_connection) . '-' . curl_error($curl_connection);
////close the connection
//    curl_close($curl_connection);
//}

// end of alert messages 
?>
