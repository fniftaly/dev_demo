<?php	


$apiUrl = 'https://api.textmunication.com/inboundmo';
$ch = curl_init();
//$fields_string = array("message=Stop&device_address=9503056171");

$curl = curl_init();
    curl_setopt($curl, CURLOPT_URL,"https://api.textmunication.com/inboundmo");
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, "message=fromqdon't ere&device_address=12222222222");

    curl_exec ($curl);
	curl_error($curl);
    curl_close ($curl);
	echo "I am here";
?>