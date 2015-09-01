<?php
$usrinfo=array
   (
    'businessname' => $_POST['businessname'],
    'firstname' => $_POST['firstname'],
    'lastname' => $_POST['lastname'],
    'title' => $_POST['title'],
     'cell' => $_POST['cell'],
     'email' => $_POST['email'],
    'address' =>$_POST['address'],
    'city' => $_POST['city'],
    'state' => $_POST['state'],
    'zip' => $_POST['zip'],
    'messagelimit' => $_POST['messagelimit'],
    'username' =>$_POST['username'],
    'password' => $_POST['password'],
    'cardname' => $_POST['cardname'],
    'cardnumber' => $_POST['cardnumber'],
    'cardsecurecode' => $_POST['cardsecurecode'],
    'cardexpdate' => $_POST['cardexpdate'],
    'activatetime' => date('Y-m-d H:i:s')
);
//echo print_r($usrinfo);
//exit;
function createUser($userdata){
         $apiUrl = 'http://api.textmunication.com/createuser';
  
         $usrdecode = json_encode($userdata);
		$ch = curl_init($apiUrl);
			 curl_setopt ($ch, CURLOPT_GET, 1);
                         curl_setopt ($ch, CURLOPT_POSTFIELDS, "username=farad&password=0099&newuser=$usrdecode");
			// Now set some params, start with username and password
			//curl_setopt($ch, CURLOPT_USERPWD, $username . ':' . $password);
			
			// Turn off header output in the response
			curl_setopt($ch, CURLOPT_HEADER, false);
			
			// Disable SSL peer verification
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			
			// Capture the output instead of echoing it
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			
			// Execute our request
			echo $rs = curl_exec($ch);
			
			// Close the cURL handle
			curl_close($ch);
                        $response = simplexml_load_string($rs);
                        $response = json_decode($rs,true);
			
			// Type cast the response code and description for use
//			$code = intval($response->code);
//			echo '<pre>'; print_r($response);
}// end of createUser messages
createUser($usrinfo);
?>
