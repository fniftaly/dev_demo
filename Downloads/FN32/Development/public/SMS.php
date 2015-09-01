<?PHP
include("cloudAPI.php");
class SMS{
	private $stop = false;
	private $smsNumber = "15708050026";
	
	/*
	How to use:
	
	include("SMS.php");
	$SMSMessage = new SMS();
	$SMSMessage->sendSMS($senderAddress, "Your message goes here");	

	This will send a SMS given the recipient number
	*/
	function sendSMS($mobileNumber, $notificationText)
	{//#############################################################
			$baseURL = "https://enabler.intelligentcloud.biz/SendSmsService/OneAPI_REST_v1_0/routing";

			$version = "1";
			$relativeURL = "/" . $version . "/smsmessaging/outbound/" .
			urlencode($this->smsNumber) . "/requests";

			$urlString = $baseURL . $relativeURL;

			$data = array(
						'address'=>urlencode($mobileNumber),
						'senderAddress'=>urlencode($this->smsNumber),
						'message'=>urlencode($notificationText),
					);
			$data_string = "";
			foreach($data as $key=>$value) { $data_string .= $key.'='.$value.'&'; }
			rtrim($data_string,'&');

			$obj = json_decode(sendSMS($urlString, $data_string));
			
			if(property_exists ($obj,'requestError')){
				//do something, log error.
			}
			else
			{
				$confirmationURL = $obj->{'resourceReference'}->{'resourceURL'}."/deliveryInfos";
				$this->followupSMS($confirmationURL);
			}
	}//#############################################################

	/*
	check for status 3 times @ 10 sec each. 
	*/
	function followupSMS($confirmationURL)
	{//#############################################################
	
			for($statusCount = 0; $statusCount < 3; $statusCount++){//check for status max of 3 times
				
				$status = checkDeliveryStatus($confirmationURL);
				
				if(!$status){//fatal error during delivery
					$stop = true;
					break;
				}
				else{
					if($status == "1"){//Successfully delivered to terminal
					$stop = true;
					//do something
						break;
					}
					if($status == "2"){//Still pending, check again in 10 seconds:
						sleep(10);
						//check for receipt of message again, pass through for loop
					}
					if($status == "3"){//Delivery Impossible, try next contact
						$stop = true;
						break;						
					}
				}
			}
	}//#############################################################
}
?>