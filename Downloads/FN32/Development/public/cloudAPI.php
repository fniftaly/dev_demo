<?PHP
$authorizationID = "8432334590e5d6fa2092aa50dc842bb6";//used as the username in CURL authentication
$authorizationPass = ";62Tq@M<";//used as the password in CURL authentication
$registrationID = "####REGISTRATIONID####";//used in the URL to correctly find Cloud project

	function get_web_page( $url )
	{//########################################################################
		$options = array(
			CURLOPT_RETURNTRANSFER => true,     // return web page
			CURLOPT_HEADER         => false,    // don't return headers
			CURLOPT_FOLLOWLOCATION => true,     // follow redirects
			CURLOPT_SSL_VERIFYPEER => false,	//FOLLOW UP WITH REID AND JOHN ABOUT SSL ERROR
			CURLOPT_HTTPHEADER => array("Accept: application/json", "Authorization: Basic ". base64_encode("$authorizationID:$authorizationPass")),
			CURLOPT_ENCODING       => "",       // handle all encodings
			CURLOPT_AUTOREFERER    => true,     // set referer on redirect
			CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
			CURLOPT_TIMEOUT        => 120,      // timeout on response
			CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
		);

		$ch      = curl_init( $url );
		curl_setopt_array( $ch, $options );
		$content = curl_exec( $ch );
		$err     = curl_errno( $ch );
		$errmsg  = curl_error( $ch );
		$header  = curl_getinfo( $ch );
		curl_close( $ch );

		$header['errno']   = $err;
		$header['errmsg']  = $errmsg;
		$header['content'] = $content;
		return $header;
	}//########################################################################


		function deleteSMS( $url )
	{//########################################################################
		$options = array(
			CURLOPT_CUSTOMREQUEST  => "DELETE",
			CURLOPT_RETURNTRANSFER => true,     // return web page
			CURLOPT_HEADER         => false,    // don't return headers
			CURLOPT_FOLLOWLOCATION => true,     // follow redirects
			CURLOPT_SSL_VERIFYPEER => false,	
			CURLOPT_HTTPHEADER => array("Accept: application/json", "Authorization: Basic ". base64_encode("$authorizationID:$authorizationPass")),
			CURLOPT_ENCODING       => "",       // handle all encodings
			CURLOPT_AUTOREFERER    => true,     // set referer on redirect
			CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
			CURLOPT_TIMEOUT        => 120,      // timeout on response
			CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
		);

		$ch      = curl_init( $url );
		curl_setopt_array( $ch, $options );
		$content = curl_exec( $ch );
		$err     = curl_errno( $ch );
		$errmsg  = curl_error( $ch );
		$header  = curl_getinfo( $ch );
		curl_close( $ch );

		$header['errno']   = $err;
		$header['errmsg']  = $errmsg;
		$header['content'] = $content;
		return $header;
	}//########################################################################

		//Edit to include URL encoding:
		function sendSMS($url, $data)
		{//#############################################################	
			//open connection
			echo $url;
			echo "<br>";
			echo $data;
			$ch = curl_init();
			$headers = array('Content-Type: application/x-www-form-urlencoded',"Accept: application/json", "Authorization: Basic ". base64_encode("$authorizationID:$authorizationPass"));

			//set the url, number of POST vars, POST data
			curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
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
			return $result;
		}//#############################################################
		
		
		function checkDeliveryStatus($url){

		$response = get_web_page($url);
		$obj = json_decode($response['content']);

		try {
			if(property_exists ($obj,'requestError')){
			print_r($response);
				return false;
			}
			else
			{
				$arrayofStuff = $obj->{'deliveryInfoList'}->{'deliveryInfo'};
				switch($arrayofStuff[0]->{"deliveryStatus"}){
				   case "DELIVERED_TO_TERMINAL":
						return "1";
						break;
					case "DELIVERY_UNCERTAIN":
						return "2";
						break;
					case "DELIVERY_IMPOSSIBLE":
						return "3";
						break;
					case "MESSAGE_WAITING":
						return "1";
						break;
					case "DELIVERED_TO_NETWORK":
						return "1";
						break;
				}
			}
		} catch (Exception $e) {
			return false;
			print_r($response);
			die();
		}
	}//#############################################################


	function retrieveSMSFromBucket()
	{//#############################################################
	
	$fullURL = "https://enabler.intelligentcloud.biz/ReceiveSmsService/OneAPI_REST_v1_0/routing/1/smsmessaging/inbound/registrations/$registrationID/messages";


	$response = get_web_page($fullURL);//gets url from previous app

		 $getResponse = json_decode($response['content']);

		if(property_exists ($getResponse,'requestError')){
			return "3";
			
		}
		else
		{
			$arrayofStuff = $getResponse->{'inboundSMSMessageList'}->{'inboundSMSMessage'};
			
			//If it is empty, you will need to let client know:
			
			if(count($arrayofStuff) > 0){
			$rawReply = $arrayofStuff[0]->{"message"};
			
			$responseString = "You will be contacted within ".substr($rawReply,6,strlen ($rawReply))." minutes";


			$messageID = $arrayofStuff[0]->{"messageId"};

			//Optional: This will delete the SMS after you retrieve it from the bucket. 
			//$deleteResponse = deleteSMS("https://enabler.intelligentcloud.biz/ReceiveSmsService/OneAPI_REST_v1_0/routing/1/smsmessaging/inbound/registrations/$registrationID/messages/$messageID");
			}
			else{//If it is empty, you will need to let client know:
			return "2";
			}
		}
	}//########################################################################
	
	
	/*
	PHP Example for receiving an SMS:
	if($HTTP_RAW_POST_DATA){//the cloud will POST raw data to your web application, this is the global var that PHP puts it in

	$rawJSON = json_decode($HTTP_RAW_POST_DATA);//If your project is set to JSON outputting, this will decode it

	$message = $rawJSON->inboundSMSMessageNotification->inboundSMSMessage->message;//grabs the message from the JSON object
	$messageArray = explode(' ',$message);//turns the object into an array based on spaces
	$keyword = $messageArray[0];//grabs the first word in the array, this is the keyword that was sent
	
	$senderAddress = $rawJSON->inboundSMSMessageNotification->inboundSMSMessage->senderAddress;
	$messageId = $rawJSON->inboundSMSMessageNotification->inboundSMSMessage->messageId;
	}

	*/

?>