<?php

$ch = curl_init();
			$headers = array('Content-Type: application/x-www-form-urlencoded',"Accept: application/json", "Authorization: Basic ". base64_encode("$authorizationID:$authorizationPass"));
			$username = "8432334590e5d6fa2092aa50dc842bb6";
			$password = ";62Tq@M<";
			$data = "address=16506900414&senderAddress=15708050026&message=NuStar+Test+from+SDN+for+Textmunication";
			
		$url = "https://enabler.intelligentcloud.biz/SendSmsService/OneAPI_REST_v1_0/routing/1/smsmessaging/outbound/15708050026/requests";
		//https://enabler.intelligentcloud.biz/SendSmsService/OneAPI_REST_v1_0/routing/1/smsmessaging/outbound/15708050026/requests
			//set the url, number of POST vars, POST data
			curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
			curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
			curl_setopt($ch, CURLOPT_HTTPHEADER, false);
			curl_setopt($ch, CURLOPT_POST, 6);
			curl_setopt($ch, CURLOPT_TIMEOUT, 5000);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data); 
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			//curl_setopt($ch, CURLOPT_HEADER, true);

			//execute POST
			$result = curl_exec($ch);
			print_r($result);
			echo "curl closing here";
			//close connection
			curl_close($ch);
			
			
?>