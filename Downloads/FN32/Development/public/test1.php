<?php

//$apiUrl = 'https://text.mes.syniverse.com/SMSSend';
$apiUrl ='https://bulktext.mes.syniverse.com/bulk/SendBulk';
$shortCode = '87365';
//$sendMessage = urlencode("Short code testing for Canada -28776. Please reply 'temu yes' or 'temu Stop'");
//$sendMessage = urlencode("Guys free pivo (beer) and nice girls from Ukraine for u today at 3:00 pm in conference room");
$sendMessage = urlencode("This is test");
$username = '4400';
$password = 'Fq0^Hc0^';

//$sbrs = array(19253050723,19252504282,19254511515,19257275620);
//for($n =0; $n< 10; $n++)
//           {
//                $random = '100'.substr(number_format(time() * rand(),0,'',''),0,8);
//                 $sbrs[]=$random;
//           }  
echo $impar = implode('&phone=', $sbrs);
//exit;
//Pizza Factory Temecula, French Valley & Menifee
//$pp = "Happy Birthday from your Pizza Factory Temecula, French Valley & Menifee friends, today is a special day in your life so enjoy it to the fullest, live long
$pp = "Happy Birthday from your Pizza Factory Temecula, French Valley & Menifee friends, today is a special day in your life so enjoy it to the fullest, live long
and prosperous";
//$arCount = count($sbrs);

    sendToSynivers($apiUrl, $username, $password, $shortCode, $impar, $sendMessage);

function sendToSynivers($apiUrl, $username, $password, $shortCode, $phone, $sendMessage) {
//                                           $arr = array();
    $uri = $apiUrl;
    $ch = curl_init($uri);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "user=$username&pass=$password&smsfrom=$shortCode&smsto=$phone&smsmsg=$sendMessage");
    // Now set some params, start with username and password
//			curl_setopt($ch, CURLOPT_USERPWD, $username . ':' . $password);
    // Turn off header output in the response
    curl_setopt($ch, CURLOPT_HEADER, false);

    // Disable SSL peer verification
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    // Capture the output instead of echoing it
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Execute our request
//			echo 
    echo '<br>' .$rs = curl_exec($ch);
//     echo implode(' ',$arr);
    // Close the cURL handle
    curl_close($ch);

    // Now lets inspect it and see if we have what we need
    $response = simplexml_load_string($rs);
    // Type cast the response code and description for use
    echo '<br>' . $code = intval($response->trackingid);
}

?>