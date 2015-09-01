<?php	
$apiUrl = 'http://api.textmunication.com/subscribers';
//$shortCode = '87365';
//$sendTo = array("919503056171");
//$sendMessage = urlencode("This is another test by smartData, confirm once received");
//$username = 'textmu';
//$password = 'textmu1';

		//$reportingkey1 = time();
		//$reportingkey2 = md5(uniqid() . serialize("19252504282"));
	//echo $uri = $apiUrl . '?user=brandstandmm&pass=brandnostand&recipients=' . implode('&recipients=', $sendTo) . '&message=' . $sendMessage;
			//$uri = 'http://www.askantcolony.com/';
			$uri = $apiUrl;
		
		$ch = curl_init($uri);
			 curl_setopt ($ch, CURLOPT_POST, 1);
 curl_setopt ($ch, CURLOPT_POSTFIELDS, "user=stouch&pass=stouch&phonenumber=18212122123&folder=test&firstname=testfname&lastname=ltestname&email=asb@asb.com&birthday=06-19&language=spanish");
			// Now set some params, start with username and password
			//curl_setopt($ch, CURLOPT_USERPWD, $username . ':' . $password);
			
			// Turn off header output in the response
			curl_setopt($ch, CURLOPT_HEADER, false);
			
			curl_setopt($ch,CURLOPT_HTTPHEADER,array('X-Api-Key: 7663a9d863ee4dfbac8c42affd51f149'));
			
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
			
			
			
			?>