<?php
//phpinfo();
//exit; 
//clubid=string&loginName=string&loginPW=string&CanceledSince=string&NewSince=string


$req1 ="<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
$req1 .="<soap:Envelope xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\" xmlns:soap=\"http://schemas.xmlsoap.org/soap/envelope/\">";
$req1 .="<soap:Body>";    
$req1 .="<GetContactInf xmlns=\"http://asfint.com/asfwebservices\">"; 
$req1 .="<clubid>4000</clubid>"; 
$req1 .="<loginName>farad</loginName>"; 
$req1 .="<loginPW>a1111</loginPW>"; 
$req1 .="<CanceledSince></CanceledSince>"; 
$req1 .="<NewSince></NewSince>"; 
$req1 .="</GetContactInf>"; 
$req1 .="</soap:Body>"; 
$req1 .="</soap:Envelope>"; 

$header = array(
    "Content-type: text/xml;charset=\"utf-8\"",
    "Accept: text/xml",
    "Cache-Control: no-cache",
    "Pragma: no-cache",
    "SOAPAction: \"http://asfint.com/asfwebservices/GetContactInf\"",
    "Content-length: ".strlen($req1)
  );
$info = array('clubid'=>4000,'loginName'=>'baran', 'loginPW'=>'a1111','CanceledSince'=>'','NewSince'=>'20140301');
keyword($header,$info);
function keyword($items,$info) {
    $apiUrl = "https://www.asfint.com/ASFWebServices/ws2.asmx/GetContactInf";
    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "clubid=4000&loginName=baran&loginPW=a1111&CanceledSince=''&NewSince=20140301");
//        curl_setopt($ch, CURLOPT_POSTFIELDS,$info);
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
//asfapi($req1,$header);
//Clubid: 4000
//LoginPW: a1111
function asfapi($items,$header) {
    $soapUser = 4000;
    $soapPassword = 'a1111';
//    $apiUrl = "http://asfint.com/asfwebservices/GetContactInf/";
    $apiUrl = "https://www.asfint.com/ASFWebServices/ws2.asmx/GetContactInf";
//create cURL connection
    $curl_connection = curl_init($apiUrl);
    curl_setopt($curl_connection, CURLOPT_CONNECTTIMEOUT, 30);
    curl_setopt($curl_connection, CURLOPT_RETURNTRANSFER, true);
//    curl_setopt($curl_connection, CURLOPT_USERPWD, $soapUser.":".$soapPassword);
//    curl_setopt($curl_connection, CURLOPT_HTTPHEADER, array('Content-Type: application/xml', 'Content-Length: ' . strlen($items)));
//    curl_setopt($curl_connection, CURLOPT_HTTPHEADER, array('Content-Type: text/xml', 'Content-Length: ' . strlen($items)));
    curl_setopt($curl_connection, CURLOPT_POST, true);
    curl_setopt($curl_connection, CURLOPT_SSL_VERIFYPEER, false);
//    curl_setopt($curl_connection, CURLOPT_URL, $apiUrl);
    curl_setopt($curl_connection, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($curl_connection, CURLOPT_SSL_VERIFYHOST, false);
//set data to be posted
    curl_setopt($curl_connection, CURLOPT_POSTFIELDS, $items);
    curl_setopt($curl_connection, CURLOPT_HTTPHEADER, $header);
    
//perform our request
$result = curl_exec($curl_connection);
//show information regarding the request
    print_r(curl_getinfo($curl_connection));
//    echo curl_errno($curl_connection) . '-' . curl_error($curl_connection);
//close the connection
    curl_close($curl_connection);
}




//function asfapi() {
//    $username = 4000;
//    $password = a1111;
//    $apiUrl = "http://asfint.com/asfwebservices/RtvAcctInf";
//
//			$uri = $apiUrl;
//		               $ch = curl_init($uri);
//			 curl_setopt ($ch, CURLOPT_POST, 1);
//                                            curl_setopt ($ch, CURLOPT_POSTFIELDS, "Clubid=$username&LoginPW=$password");
//			// Now set some params, start with username and password
////			curl_setopt($ch, CURLOPT_USERPWD, $username . ':' . $password);
//			
//			// Turn off header output in the response
//			curl_setopt($ch, CURLOPT_HEADER, false);
//			
////			curl_setopt($ch,CURLOPT_HTTPHEADER,array('X-Api-Key: 7663a9d863ee4dfbac8c42affd51f149'));
//			
//			// Disable SSL peer verification
//			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//			
//			// Capture the output instead of echoing it
//			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//			
//			// Execute our request
//			 $rs = curl_exec($ch);
//			
//			// Close the cURL handle
//			echo curl_close($ch);
//			
//			// Now lets inspect it and see if we have what we need
//			echo $response = simplexml_load_string($rs).'<br>';
////			echo '<br>'.$response;
//			// Type cast the response code and description for use
////			return $code = $response->trackingid.'  Phone: '.$phone.'<br>';
////			 echo curl_errno($ch) . '-' . curl_error($ch);
//}
?>
