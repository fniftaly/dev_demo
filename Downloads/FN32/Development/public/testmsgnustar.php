<?php
  include("SMS.php");
  $smsObj = new SMS(); echo "<pre>"; print_r($smsObj);
  $sentArray = $smsObj->sendSMS('16506900414','NuStar Test from SDN for Textmunication.'); echo "<pre>"; print_r($sentArray);
?>