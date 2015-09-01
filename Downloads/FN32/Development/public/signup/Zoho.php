<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Zoho
 *
 * @author farad
 */
class Zoho {

    public function getAuth() {
        $username = "wais@textmunication.com";
        $password = "txhd2014";
        $param = "SCOPE=ZohoCRM/crmapi&EMAIL_ID=" . $username . "&PASSWORD=" . $password;
        $ch = curl_init("https://accounts.zoho.com/apiauthtoken/nb/create");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
        $result = curl_exec($ch);
        /* This part of the code below will separate the Authtoken from the result.
          Remove this part if you just need only the result */
        $anArray = explode("\n", $result);
        $authToken = explode("=", $anArray['2']);
        $cmp = strcmp($authToken['0'], "AUTHTOKEN");
        echo $anArray['2'] . "";
        if ($cmp == 0) {
            echo "Created Authtoken is : " . $authToken['1'];
            return $authToken['1'];
        }
        curl_close($ch);
    }

    public function postData($auth, $first, $last, $email, $business, $address, $city, $state, $zip, $cell, $office, $title) {
        
  $xml =  '<?xml version="1.0" encoding="UTF-8"?>
            <Leads>
               <row no="1">
               <FL val="Company">' . $business . '</FL>
               <FL val="First Name">' . $first . '</FL>
               <FL val="Last Name">' . $last . '</FL>
               <FL val="Title">' . $title . '</FL>
               <FL val="Email">' . $email . '</FL>
               <FL val="Phone">' . $office . '</FL>
               <FL val="Fax">000000000</FL>
               <FL val="Mobile">' . $cell . '</FL>
               <FL val="Lead Source">External Referral</FL>
               <FL val="Lead Status">Not Contacted</FL>
               <FL val="City">' . $city . '</FL>
               <FL val="Street">' . $address . '</FL>
               <FL val="State">' . $state . '</FL>
               <FL val="Zip Code">' . $zip . '</FL>
               <FL val="Description">Sample Description.</FL>
               </row>
           </Leads>';

        $url = "https://crm.zoho.com/crm/private/xml/Leads/insertRecords";
        $query = "authtoken=" . $auth . "&scope=crmapi&newFormat=1&xmlData=" . $xml;
        $ch = curl_init();
        /* set url to send post request */
        curl_setopt($ch, CURLOPT_URL, $url);
        /* allow redirects */
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        /* return a response into a variable */
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        /* times out after 30s */
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        /* set POST method */
        curl_setopt($ch, CURLOPT_POST, 1);
        /* add POST fields parameters */
        curl_setopt($ch, CURLOPT_POSTFIELDS, $query); // Set the request as a POST FIELD for curl.
        //Execute cUrl session
        curl_exec($ch);
        curl_close($ch);
//	    echo $response;
    }

}

?>
