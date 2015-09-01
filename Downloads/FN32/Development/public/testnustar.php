<?php	
$apiUrl = 'http://api.textmunication.com/inboundmo1';
//$shortCode = '87365';
//$sendTo = array("919503056171");
//$sendMessage = urlencode("This is another test by smartData, confirm once received");
//$username = 'textmu';
//$password = 'textmu1';

		//$reportingkey1 = time();
		//$reportingkey2 = md5(uniqid() . serialize("19252504282"));
	//echo $uri = $apiUrl . '?user=brandstandmm&pass=brandnostand&recipients=' . implode('&recipients=', $sendTo) . '&message=' . $sendMessage;
			//$uri = 'http://www.askantcolony.com/';
		/*		$uri = $apiUrl;
		
		$ch = curl_init($uri);
		 curl_setopt ($ch, CURLOPT_POST, 1);
 curl_setopt ($ch, CURLOPT_POSTFIELDS, "device_address=18212122123&message=test okay for inboundmo1");
			// Now set some params, start with username and password
			//curl_setopt($ch, CURLOPT_USERPWD, $username . ':' . $password);
			
			// Turn off header output in the response
			curl_setopt($ch, CURLOPT_HEADER, false);
			
			//curl_setopt($ch,CURLOPT_HTTPHEADER,array('X-Api-Key: 7663a9d863ee4dfbac8c42affd51f149'));
			
			// Disable SSL peer verification
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			
			// Capture the output instead of echoing it
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			
			// Execute our request
			echo $rs = curl_exec($ch);
			
			// Close the cURL handle
			curl_close($ch);
			
			// Now lets inspect it and see if we have what we need
			$response = simplexml_load_string($rs);
			
			// Type cast the response code and description for use
			$code = intval($response->code);
			echo $description = "$response->description";
			*/
			
			$data = array("name" => "Hagrid", "age" => "36");                                                                    
$data_string = json_encode($data);                                                                                   
 
$ch = curl_init($apiUrl);                                                                      
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
    'Content-Type: application/json',                                                                                
    'Content-Length: ' . strlen($data_string))                                                                       
);                                                                                                                   
 
$result = curl_exec($ch);
	print_r($result);		
			
			?>