<?php

//$shortCode = '87365';
$xml = "<?xml version='1.0' encoding='UTF-8'?>
<jobrequest 
      xmlns=\"http://textmunication.com/bulkmessaging\">
<username>4400</username>
<password>Fq0^Hc0^</password>
<jobid>234354659234</jobid>
<shortcode>87365</shortcode>
<recipientcount>1</recipientcount> 
<bulkmessage registeredDelivery='1'>
<body encoding='iso-8859-15'>This is sample txt message</body>
      <recipients>
<recipient>
<destination>19253050723</destination> 
</recipient> 
</recipients></jobrequest> ";
//bulk($xml);

$header = array(
    "Content-type: text/xml;charset=\"utf-8\"",
    "Accept: text/xml",
    "Cache-Control: no-cache",
    "Pragma: no-cache",
    "SOAPAction: \"run\"",
    "Content-length: ".strlen($xml),
  );

//asfapi($xml,$header) ;
bulk($xml,$header);
function bulk($xml,$header) {
    $apiUrl = 'https://bulktext.mes.syniverse.com/bulk/SendBulk';
    $uri = $apiUrl;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $uri);
    curl_setopt($ch, CURLOPT_POST, true);
//    curl_setopt($ch, CURLOPT_USERPWD, $username . ':' . $password);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, array('data' => $xml));
      curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    echo $result = curl_exec($ch);
    curl_close($ch);
}


function sendSMS($SC, $phone, $sendMessage1) {
    $sendMessage = urlencode("$sendMessage1");
    $apiUrl = 'https://bulktext.mes.syniverse.com/bulk/SendBulk';
    $username = '4400';
    $password = 'Fq0^Hc0^';

    $uri = $apiUrl;
    $ch = curl_init($uri);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "user=$username&pass=$password&smsfrom=$SC&smsto=$phone&smsmsg=$sendMessage");
    // Now set some params, start with username and password
// curl_setopt($ch, CURLOPT_USERPWD, $username . ':' . $password);
    // Turn off header output in the response
    curl_setopt($ch, CURLOPT_HEADER, false);

    // Disable SSL peer verification
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    // Capture the output instead of echoing it
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Execute our request
    $rs = curl_exec($ch);

    // Close the cURL handle
    curl_close($ch);

    // Now lets inspect it and see if we have what we need
    $response = simplexml_load_string($rs);
//			echo '<br>'.$response;
    // Type cast the response code and description for use
    return $code = $response->trackingid . '  Phone: ' . $phone . '<br>';
//			echo $description = "$response->description";
}

function asfapi($items,$header) {
    
     $apiUrl = 'https://bulktext.mes.syniverse.com/bulk/SendBulk';
//create cURL connection
    $curl_connection = curl_init($apiUrl);
    curl_setopt($curl_connection, CURLOPT_CONNECTTIMEOUT, 30);
    curl_setopt($curl_connection, CURLOPT_RETURNTRANSFER, true);
//    curl_setopt($curl_connection, CURLOPT_HTTPHEADER, array('Content-Type: application/xml', 'Content-Length: ' . strlen($items)));
//    curl_setopt($curl_connection, CURLOPT_HTTPHEADER, array('Content-Type: text/xml', 'Content-Length: ' . strlen($items)));
    curl_setopt($curl_connection, CURLOPT_POST, true);
    curl_setopt($curl_connection, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl_connection, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($curl_connection, CURLOPT_SSL_VERIFYHOST, false);
//set data to be posted
    curl_setopt($curl_connection, CURLOPT_POSTFIELDS, $items);
    curl_setopt($curl_connection, CURLOPT_HTTPHEADER, $header);
    
//perform our request
$result = curl_exec($curl_connection);
//show information regarding the request
    print_r(curl_getinfo($curl_connection));
    echo curl_errno($curl_connection) . '-' . curl_error($curl_connection);
//close the connection
    curl_close($curl_connection);
}

?>