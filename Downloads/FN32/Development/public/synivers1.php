<?php	

$shortCode = '87365';
ini_set('memory_limit', '512M');
$sbrs = array();
 for($n =0; $n< 11234; $n++)
           {
                $random = '100'.substr(number_format(time() * rand(),0,'',''),0,8);
                $sbrs[$random]=$random;
           }  
echo date('Y-m-d H:i:s').'<br>';
//for($i = 0; $i <count($sbrs); $i++){
//    echo $i.'  '.$sbrs[$i].' Message '.test().'<br>';
   $n=0;
   $t = 0;
    foreach($sbrs as $piz=>$v){
    echo $n++.'  '.sendSMS($shortCode,$v,"THIS IS JUST TEST!");
    if($n % 1000 ==0){
      sleep(60);
      echo '<br> Passed 1000  '.$t++.'<br>';
    }
}
echo '<br>'.date('Y-m-d H:i:s');

function sendSMS($SC,$phone,$sendMessage1){
 $sendMessage = urlencode("$sendMessage1");   
$apiUrl = 'https://text.mes.syniverse.com/SMSSend';    
$username = '4400';
$password = 'Fq0^Hc0^';

			$uri = $apiUrl;
		               $ch = curl_init($uri);
			 curl_setopt ($ch, CURLOPT_POST, 1);
                                            curl_setopt ($ch, CURLOPT_POSTFIELDS, "user=$username&pass=$password&smsfrom=$SC&smsto=$phone&smsmsg=$sendMessage");
			// Now set some params, start with username and password
//			curl_setopt($ch, CURLOPT_USERPWD, $username . ':' . $password);
			
			// Turn off header output in the response
			curl_setopt($ch, CURLOPT_HEADER, false);
			
//			curl_setopt($ch,CURLOPT_HTTPHEADER,array('X-Api-Key: 7663a9d863ee4dfbac8c42affd51f149'));
			
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
//			echo '<br>'.$response;
			// Type cast the response code and description for use
			return $code = $response->trackingid.'  Phone: '.$phone.'<br>';
//			echo $description = "$response->description";
}   

function test(){
    return md5(time());
}

?>