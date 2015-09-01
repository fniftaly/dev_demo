<?php	
$apiUrl = 'https://secure-mrr.air2web.com/a2w_preRouter/httpApiRouter';
//$apiUrl = 'https://text.mes.syniverse.com/SMSSend';
//$shortCode = '28776';
$shortCode = '87365';
$sendTo = array("14159906399");
$sendMessage = urlencode("This is another test by smartData, confirm on skype once received");
$username = 'textmu';
$password = 'textmu1';

		$reportingkey1 = time();
		$reportingkey2 = md5(uniqid() . serialize("14159906399"));
	echo $uri = $apiUrl . '?reply_to=' . $shortCode . '&recipient=' . implode('&recipient=', $sendTo) . '&body=' . $sendMessage . '&reporting_key1=' . $reportingkey1 . '&reporting_key2=' . $reportingkey2;
			
			
		
		$ch = curl_init($uri);
			
			// Now set some params, start with username and password
			curl_setopt($ch, CURLOPT_USERPWD, $username . ':' . $password);
			
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
			
			// Type cast the response code and description for use
			$code = intval($response->code);
			echo $description = "$response->description";
			
			
			
			?>