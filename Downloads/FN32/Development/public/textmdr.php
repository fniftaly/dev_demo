<?php
$sbrs = array(19259563438,19093799004,14157162443,15037172558);

// for($n =0; $n< 300; $n++)
//           {
//                $random = '100'.substr(number_format(time() * rand(),0,'',''),0,8);
//                 $sbrs[$random]=$random;
//           } 
 $jr = json_encode($sbrs); 
 
 sendMessage(15037172558, 87365);
 
function sendMessage($data, $shortcode) {
    
     $apiUrl = "https://api.textmunication.com/textmdr/";
    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_GET, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "username=farad&password=0099&data=$data&shortcode=$shortcode");
    // Turn off header output in the response
    curl_setopt($ch, CURLOPT_HEADER, false);
    // Disable SSL peer verification
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    // Capture the output instead of echoing it
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Execute our request
    echo curl_exec($ch);

    // Close the cURL handle
    curl_close($ch);
}
// end of alert messages 
?>
