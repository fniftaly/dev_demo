<?php
class Application_Model_Apidataentry extends Application_Model_Abstract {
    /**
     *   properties
     */
    
//    public function __construct() {
//        ;
//    }
    
    /**
     * 
     * 
     */
    
    public function insertApidataentry($subscribers, $user, $shortcode=87365){
        $phones = count($subscribers);
        if($phones !=0){
             foreach($subscribers as $pn=>$p){
              $sql = "insert into apiusersdata (`phonenumber`, `createuser`, `shortcode`) values($p, $user, $shortcode)";
              $rs = $this->query($sql);
         }
        }
       return $rs;
    }
    
    /**
     * 
     * 
     * 
     */
    public function getApiusersdata($userid, $shortcode=0){
        $sql = "insert into tempapi  SELECT * FROM apiusersdata where status = 0 and createuser=$userid FOR UPDATE;
         UPDATE `apiusersdata` SET status = status + 1 where status=0 and createuser=$userid";
         $rs = $this->query($sql);
        
    }// end of getApiusersdata
    
    /**
     *  Gets api users data and then check status in
     *  two places delevery and inbound table
     *  @access public
     *  @param int userid, shortcode is optinal for now
     *  @name getDataStatus
     */
    public function getDataStatus($userid, $shortcode=0){
        $this->getApiusersdata($userid);
        $dt = date('Y-m-d');
        $phones = array();
        $je = null;
        $sql = "Select phonenumber from tempapi where status = 0 and createuser=$userid and createtime like '%$dt%'";
         $rs = $this->query($sql);
         if($rs->hasRecords()){
             foreach($rs->fetchAll() as $id=>$ph){
                 $phones[]=$ph['phonenumber'];
             }
             $rsarr = array();
             $badnumbers = $this->processingBadNumbers($userid,$phones);
             $respons  = $this->processingResponses($phones);
             
             if(count($badnumbers)==0 && count($respons) !=0){
                 $rsarr = $respons;
                 $je = json_encode($rsarr);
//                 $this->postDataClubready($je);
                 return $je;
             }
            else if(count($badnumbers)!=0 && count($respons) ==0){
                 $rsarr = $badnumbers;
                 $je = json_encode($rsarr);
//                 $this->postDataClubready($je);
                 return $je;
             }
            else if(count($badnumbers) !=0 && count($respons) !=0){
                 $rsarr = $badnumbers + $respons;
                 $je = json_encode($rsarr);
//                 $this->postDataClubready($je);
                 return $je;
             }
//             return $je;
//             $this->deleteAccessedData($userid);
         }
         return $badnumbers;
    }// end of getDataStatus
    public function requstDeliveryStatus($data, $shortcode=87365){
       
             $badnumbers = $this->requestfromDelivery($data);
             $respons  = $this->requestFromInbound($data,$shortcode);
             
             if(count($badnumbers)==0 && count($respons) !=0){
                 $rsarr = $respons;
                 $je = json_encode($rsarr);
                 return $je;
             }
            else if(count($badnumbers)!=0 && count($respons) ==0){
                 $rsarr = $badnumbers;
                 $je = json_encode($rsarr);
                 return $je;
             }
            else if(count($badnumbers) !=0 && count($respons) !=0){
                 $rsarr = $badnumbers + $respons;
                 $je = json_encode($rsarr);
                 return $je;
             }
    }// end of getDataStatus
    /**
     *   check status clients data
     * 
     */
    public function processingBadNumbers($userid,$data){
        $dt = date('Y-m-d');
        $phones = array();
        $arstr = implode(',', $data);
        $sql = "select distinct `destination`, `statuscode` from messages_drs_delivery where `destination` in($arstr) and statuscode in(90,8,24) and createtime like '%$dt%'";
         $rs = $this->query($sql);
         if($rs->hasRecords()){
             foreach($rs->fetchAll() as $id=>$ph){
                 $phones[$ph['destination']]=$ph['statuscode'];
             }
             $this->deleteAccessedNumbers($userid, $data);
             return $phones;
         }
//         return json_encode($phones);
    } // end of processingBadNumbers
    public function requestfromDelivery($data){
//        $jdt = json_decode($data);
//        print_r($data);
        $dt = date('Y-m-d');
        $phones = array();
        $arstr = implode(',', $data);
//        echo $arstr;
//        exit;
        $sql = "select distinct `destination`, `statuscode` from messages_drs_delivery where `destination` in($arstr) and statuscode in(90,8,24) and createtime like '%$dt%'";
         $rs = $this->query($sql);
         if($rs->hasRecords()){
             foreach($rs->fetchAll() as $id=>$ph){
                 $phones[$ph['destination']]=$ph['statuscode'];
             }
             return $phones;
         }
//         return json_encode($phones);
    } // end of processingBadNumbers
    
    public function processingResponses($data){
         $dt = date('Y-m-d');
        $phones = array();
        $arstr = implode(',', $data);
        $sql = "select `device_address`, `message` from messages_inbound where `device_address` in($arstr)  and createtime like '%$dt%'";
         $rs = $this->query($sql);
         if($rs->hasRecords()){
             foreach($rs->fetchAll() as $id=>$ph){
                 $phones[$ph['device_address']]=$ph['message'];
             }
             
             return $phones;
         }
//         return json_encode($phones);
    } // end of processingResponces
    public function requestFromInbound($data,$shortcode){
         $dt = date('Y-m-d');
        $phones = array();
        $arstr = implode(',', $data);
        $sql = "select `device_address`, `message` from messages_inbound where 
                 `device_address` in($arstr)  and createtime like '%$dt%' and inbound_address=$shortcode";
         $rs = $this->query($sql);
         if($rs->hasRecords()){
             foreach($rs->fetchAll() as $id=>$ph){
                 $phones[$ph['device_address']]=$ph['message'];
             }
             
             return $phones;
         }
//         return json_encode($phones);
    } // end of processingResponces

    /**
     *   deletes clients data after it was processed
     *   from tempapi table
     * 
     */
    public function deleteAccessedNumbers($userid,$data){
        $arlong = count($data);
        if($arlong != 0){
            foreach($data as $dkey=>$v){
             $sql = "delete from tempapi where createuser=$userid and phonenumber=$dkey";
              $rs = $this->query($sql);
            }
        }
       
        return $rs;
    }// end of deleteAccessedData
    public function AccessedNumbers($userid){
        $sql = "delete from tempapi where createuser=$userid";
        $rs = $this->query($sql);
        return $rs;
    }// end of deleteAccessedData
    /**  
     *  posting data for club ready
     * 
     */
 public  function postDataClubready($items) {
    $apiUrl = "www.clubreadystage.com/services/ss/sms/textmunication/event";
    
//traverse array and prepare data for posting (key1=value1)
//    foreach ($items as $key => $value) {
//        $post_items[] = $key . '=' . $value;
//    }
//create the final string to be posted using implode()
//    $post_string = implode('&', $post_items);
//create cURL connection
    $curl_connection = curl_init($apiUrl);
    curl_setopt($curl_connection, CURLOPT_CONNECTTIMEOUT, 30);
    curl_setopt($curl_connection, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl_connection, CURLOPT_HTTPHEADER, array("Content-type: application/json"));
    curl_setopt($curl_connection, CURLOPT_POST, true);
    curl_setopt($curl_connection, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl_connection, CURLOPT_FOLLOWLOCATION, 1);
//set data to be posted
    curl_setopt($curl_connection, CURLOPT_POSTFIELDS, $items);
    
//perform our request
    $result = curl_exec($curl_connection);
//show information regarding the request
    print_r(curl_getinfo($curl_connection));
    echo curl_errno($curl_connection) . '-' . curl_error($curl_connection);
//close the connection
    curl_close($curl_connection);
}

} 