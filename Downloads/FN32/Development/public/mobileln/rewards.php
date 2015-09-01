<?php
$reward = ($_POST['reward'])?$_POST['reward']:"";

$phone = $_POST['rphone'];
$rid = $_POST['rid'];
if($rid && $phone)
addRewords($rid,$phone, $reward);

function addRewords($rid,$phone,$reward){
         $apiUrl = 'http://api.textmunication.com/rewards/';
		$ch = curl_init($apiUrl);
			 curl_setopt ($ch, CURLOPT_GET, 1);
                         curl_setopt ($ch, CURLOPT_POSTFIELDS, "username=farad&password=0099&busid=$rid&phone=$phone&reward=$reward");
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
			
}// end of createUser messages

?>
