<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Nevdata
 *
 * @author farad
 */
class Application_Model_Nevdata extends Application_Model_Abstract{
    /**
     * 
     */
    public function updateMonitoring($obj){
        
        $clubid = $obj->getClubid();
        $bodsend = $obj->getBodsend();
        $bodmsg = $obj->getBodmsg();
        $bodconf = $obj->getBodconf();
        $blng1_send = $obj->getBlngfirstsend();
        $blng1_msg = $obj->getBlngfirstmsg();
        $blng1_conf = $obj->getBlngfirstconf();
        $blng2_send = $obj->getBlngsecondsend();
        $blng2_msg = $obj->getBlngsecondmsg();
        $blng2_conf = $obj->getBlngsecondconf();
        $blng3_send = $obj->getBlngthirdsend();
        $blng3_msg = $obj->getBlngthirdmsg();
        $blng3_conf = $obj->getBlngthirdconf();
        $blng4_send = $obj->getBlngfourthsend();
        $blng4_msg = $obj->getBlngfourthmsg();
        $blng4_conf = $obj->getBlngfourthconf();
        
        $blng5_send = $obj->getBlngfifthsend();
        $blng5_msg = $obj->getBlngfifthmsg();
        $blng5_conf = $obj->getBlngfifthconf();
        
        $blng6_send = $obj->getBlngsixthsend();
        $blng6_msg = $obj->getBlngsixthmsg();
        $blng6_conf = $obj->getBlngsixthconf();
        
        $campsend = $obj->getCampaignsend();
        $campmsg = $obj->getCampaignmsg();
        $campconf = $obj->getCampaignconf();
        
       $sql = "update club_monitoring set bodsend='$bodsend',bodmsg='$bodmsg',bodconf=$bodconf,blngfirstsend='$blng1_send',blngfirstmsg='$blng1_msg',
               blngfirstconf=$blng1_conf,blngsecondsend='$blng2_send', blngsecondmsg='$blng2_msg',blngsecondconf=$blng2_conf,
               blngthirdsend='$blng3_send',blngthirdmsg='$blng3_msg',blngthirdconf=$blng3_conf,blngfourthsend='$blng4_send',blngfourthmsg='$blng4_msg',
               blngfourthconf=$blng4_conf, 
               blngfifthsend='$blng5_send',blngfifthmsg='$blng5_msg', blngfifthconf=$blng5_conf, 
               blngsixthsend='$blng6_send',blngsixthmsg='$blng6_msg', blngsixthconf=$blng6_conf, 
                   campaignsend='$campsend',campaignmsg='$campmsg',campaignconf=$campconf where clubid=$clubid";
          $rs  = $this->query($sql);
          return $rs;
   }
   /**
    *  cleaning phone numbers from
    *  NEV database
    *  @param int $phonenumber
    *  @return int phonenumber
    *  @access public
    */
   public function cleanPhone($phonenumber) {
        return preg_replace("/[^0-9]/", "", $phonenumber);
    }
    /** Remote db access
    *  Fetches phones number from Nev db 
     * for each club location by using nev club id;
    *  @param int $nevid
    *  @return array with phone numbers
    *  @access public
    */
   public function getClubphones ($nevid){
     $mssq = new Application_Model_MSSQL();
     $mssq->connect();
    $sql="SELECT  c.ClubNo,          
        p.MobilePhone
        FROM    Member m
        JOIN Person p ON m.PersonId = p.Id
        JOIN MemberContract mc ON m.ActiveMembershipContractId = mc.Id
        JOIN Club c ON mc.RecurringRevenueClubId = c.Id and c.ClubNo=$nevid and p.MobilePhone !=''";
    
     $res =  $mssq->query($sql);
      $mssq->disconnect(); 
      $n=0;
      $phn = array();
      while ($row = mssql_fetch_array($res)){
         $num = $this->cleanPhone($row[1]);
         if(strlen($num) ==10 ){
             $num = "1".$num;
         }else{continue;}
         $phn[] = $num;
         $n++;
     }
      return $phn;
   }
   /**
    *  remote mssql conneciton
    *  selecting bod from Nev DB
    *  base on club id;
    *  @param int $nevID
    *  @access public
    *  @return array obj
    */
    public function getClubBdPhones($nevID){
        $mssq = new Application_Model_MSSQL();
        $mssq->connect();
        $day = date('d');
        $mon = date('m');
        $sql="SELECT  c.ClubNo,          
        p.MobilePhone,
        p.DateofBirth
       FROM    Member m
        JOIN Person p ON m.PersonId = p.Id
        
        JOIN MemberContract mc ON m.ActiveMembershipContractId = mc.Id
        
        JOIN Club c ON mc.RecurringRevenueClubId = c.Id and c.ClubNo=$nevID and p.DateofBirth is not null and p.MobilePhone !='' and
        
         datepart(m,p.DateofBirth) = '$mon' and 
         
          datepart(d,p.DateofBirth)='$day'";
        
       $res =  $mssq->query($sql);
        
      $mssq->disconnect(); 
      $n=0;
      $phn = array();
      while ($row = mssql_fetch_array($res)){
         $num = $this->cleanPhone($row[1]);
         if(strlen($num) ==10 ){
             $num = "1".$num;
         }else{continue;}
         $phn[] = $num;
         $n++;
     }
//     $test = array(19253211843,19252504282,19253050723,17607176619);
//        $test[19253211843] = 19253211843;
//        $test[19252504282] = 19252504282;
//        $test[19253050723] = 19253050723;
//     $jnarr = array_merge($phn, $test);
      return $phn;
    }
    /**
     * 
     * 
     */
    public function setnevstatus($obj ) {
//        echo $obj->getClubid()."\n".$obj->getBodsend()."\n".$obj->getBodmsg()."\n".$obj->getBodconf()."\n".$obj->getBlngfirstsend()."\n".$obj->getBlngfirstmsg()."\n".
//                $obj->getBlngfirstconf()."\n".$obj->getBlngsecondsend()."\n".$obj->getBlngsecondmsg()."\n".$obj->getBlngsecondconf()."\n".$obj->getBlngthirdsend()."\n".
//                $obj->getBlngthirdmsg()."\n".$obj->getBlngthirdconf()."\n".$obj->getBlngfourthsend()."\n".$obj->getBlngfourthmsg()."\n".$obj->getBlngfourthconf()."\n".
//                (($obj->getBlngfifthsend())?:"L")."\n".(($obj->getBlngfifthmsg())?:"")."\n".(($obj->getBlngfifthconf())?:0)."\n"
//                .(($obj->getBlngsixthsend())?:"")."\n".(($obj->getBlngsixthmsg())?:"")."\n".(($obj->getBlngsixthconf())?:0)."\n".
//                $obj->getCampaignsend()."\n".$obj->getCampaignmsg()."\n".$obj->getCampaignconf();
//        exit;
        $clubid = $obj->getClubid();
        $bodsend = $obj->getBodsend();
        $bodmsg = $obj->getBodmsg();
        $bodconf = $obj->getBodconf();
        $blng1_send = $obj->getBlngfirstsend();
        $blng1_msg = $obj->getBlngfirstmsg();
        $blng1_conf = $obj->getBlngfirstconf();
        $blng2_send = $obj->getBlngsecondsend();
        $blng2_msg = $obj->getBlngsecondmsg();
        $blng2_conf = $obj->getBlngsecondconf();
        $blng3_send = $obj->getBlngthirdsend();
        $blng3_msg = $obj->getBlngthirdmsg();
        $blng3_conf = $obj->getBlngthirdconf();
        $blng4_send = $obj->getBlngfourthsend();
        $blng4_msg = $obj->getBlngfourthmsg();
        $blng4_conf = $obj->getBlngfourthconf();
        
        
        $blng5_send = ($obj->getBlngfifthsend())?:"11:11:00";
        $blng5_msg = ($obj->getBlngfifthmsg())?:"Dam dam";
        $blng5_conf = ($obj->getBlngfifthconf())?:0;
        
        $blng6_send = ($obj->getBlngsixthsend())?:"11:11:00";
        $blng6_msg = ($obj->getBlngsixthmsg())?:"Dam six";
        $blng6_conf = ($obj->getBlngsixthconf())?:0;
        
//        $blng5_send = "11:11:00";
//        $blng5_msg = "Dam dam";
//        $blng5_conf =0;
//        
//        $blng6_send = "11:11:00";
//        $blng6_msg = "Dam six";
//        $blng6_conf = 0;
        
        $campsend = $obj->getCampaignsend();
        $campmsg = $obj->getCampaignmsg();
        $campconf = $obj->getCampaignconf();
        
        $continent = $obj->getContinent();
        
        $sql = "insert into club_monitoring (clubid,bodsend,bodmsg,bodconf,blngfirstsend,blngfirstmsg,blngfirstconf,blngsecondsend,
            blngsecondmsg,blngsecondconf,blngthirdsend,blngthirdmsg,blngthirdconf,blngfourthsend,blngfourthmsg,blngfourthconf,
            blngfifthsend,blngfifthmsg,blngfifthconf, blngsixthsend,blngsixthmsg,blngsixthconf,
            campaignsend,campaignmsg,campaignconf, timezone, nevid, continent) 
            values($clubid, '$bodsend','$bodmsg', $bodconf,
                
            '$blng1_send','$blng1_msg', $blng1_conf,'$blng2_send','$blng2_msg',$blng2_conf,
                
            '$blng3_send','$blng3_msg',$blng3_conf,'$blng4_send','$blng4_msg',$blng4_conf,
                
            '$blng5_send', '$blng5_msg',$blng5_conf,'$blng6_send','$blng6_msg',$blng6_conf,
                
            '$campsend','$campmsg',$campconf, 'US/Pacific', 0, $continent)";
        
        $res = $this->query($sql);
    }
    // `$blng5_send`, `$blng5_msg`,$blng5_conf,`$blng6_send`,`$blng6_msg`,$blng6_conf,   blngfifthsend,blngfifthmsg,blngfifthconf, blngsixthsend,blngsixthmsg,blngsixthconf,
    /**
     *  verifying if club already has status data
     *  in the system othrewise need to add club status 
     *  to the system
     *  @param int $clubid
     *  @access public
     *  @return bool
     */
    public function verifyclubstatus($clubid){
        $sql = "Select clubid from club_monitoring where clubid=$clubid";
        $res = $this->query($sql);
        return $res->clubid;
    }
    
    public function nevsetSelect($clubid){
        $sql = "Select* from club_monitoring where clubid=$clubid";
        $rs = $this->query($sql);
         if ($rs->hasRecords()) {
                return $rs->fetchAll();
         }
    }
    /**
    *   Clubs status check
    *   @name getClubstatus
    *   @access public
    *   @return array of setting for each 
    *   club
    */
   public function getClubstatus($cont){
       $clbs = array();
       $date = date('Y-m-d');
       $sql = "select* from club_monitoring where continent=$cont";
       $rs = $this->query($sql);
       if ($rs->hasResults()) {
            foreach ($rs->fetchAll() as $row) {
                $clb = new Application_Model_NevClub();
                $clb->setClubid($row['clubid']);
               
               $bodsend = $date." ".$row['bodsend'];
               $clb->setBodsend($bodsend);
               $clb->setBodmsg($row['bodmsg']);
               $clb->setBodconf($row['bodconf']);
               
               $blng1_send = $date." ".$row['blngfirstsend'];
               $clb->setBlngfirstsend($blng1_send);
               $clb->setBlngfirstmsg($row['blngfirstmsg']);
               $clb->setBlngfirstconf($row['blngfirstconf']);
               
               $blng2_send = $date." ".$row['blngsecondsend'];
               $clb->setBlngsecondsend($blng2_send);
               $clb->setBlngsecondmsg($row['blngsecondmsg']);
               $clb->setBlngsecondconf($row['blngsecondconf']);
               
               $blng3_send = $date." ".$row['blngthirdsend'];
               $clb->setBlngthirdsend($blng3_send);
               $clb->setBlngthirdmsg($row['blngthirdmsg']);
               $clb->setBlngthirdconf($row['blngthirdconf']);
               
               $blng4_send = $date." ".$row['blngfourthsend'];
               $clb->setBlngfourthsend($blng4_send);
               $clb->setBlngfourthmsg($row['blngfourthmsg']);
               $clb->setBlngfourthconf($row['blngfourthconf']);
               
               $blng5_send = $date." ".$row['blngfifthsend'];
               $clb->setBlngfourthsend($blng5_send);
               $clb->setBlngfourthmsg($row['blngfifthmsg']);
               $clb->setBlngfourthconf($row['blngfifthconf']);
               
               $blng6_send = $date." ".$row['blngsixthsend'];
               $clb->setBlngfourthsend($blng6_send);
               $clb->setBlngfourthmsg($row['blngsixthmsg']);
               $clb->setBlngfourthconf($row['blngsixthconf']);
               
               $campsend = $date." ".$row['campaignsend'];
               $clb->setCampaignsend($campsend);
               $clb->setCampaignmsg($row['campaignmsg']);
               $clb->setCampaignconf($row['campaignconf']);
               
               $clb->setTimezone($row['timezone']);
               $clb->setNevid($row['nevid']);
               $clbs[] = $clb;
            }
           }
     return $clbs;
   }// end of getClubstatus
    /**
    *   Clubs status check
    *   @name getClubstatus
    *   @access public
    *   @return array of setting for each 
    *   club
    */
   public function getClubstatusNFC(){
       $clbs = array();
       $date = date('Y-m-d');
       $sql = "select* from club_monitoring where continent=2";
       $rs = $this->query($sql);
       if ($rs->hasResults()) {
            foreach ($rs->fetchAll() as $row) {
                $clb = new Application_Model_NevClub();
                $clb->setClubid($row['clubid']);
               
               $bodsend = $date." ".$row['bodsend'];
               $clb->setBodsend($bodsend);
               $clb->setBodmsg($row['bodmsg']);
               $clb->setBodconf($row['bodconf']);
               
               $blng1_send = $date." ".$row['blngfirstsend'];
               $clb->setBlngfirstsend($blng1_send);
               $clb->setBlngfirstmsg($row['blngfirstmsg']);
               $clb->setBlngfirstconf($row['blngfirstconf']);
               
               $blng2_send = $date." ".$row['blngsecondsend'];
               $clb->setBlngsecondsend($blng2_send);
               $clb->setBlngsecondmsg($row['blngsecondmsg']);
               $clb->setBlngsecondconf($row['blngsecondconf']);
               
               $blng3_send = $date." ".$row['blngthirdsend'];
               $clb->setBlngthirdsend($blng3_send);
               $clb->setBlngthirdmsg($row['blngthirdmsg']);
               $clb->setBlngthirdconf($row['blngthirdconf']);
               
               $blng4_send = $date." ".$row['blngfourthsend'];
               $clb->setBlngfourthsend($blng4_send);
               $clb->setBlngfourthmsg($row['blngfourthmsg']);
               $clb->setBlngfourthconf($row['blngfourthconf']);
               
               $campsend = $date." ".$row['campaignsend'];
               $clb->setCampaignsend($campsend);
               $clb->setCampaignmsg($row['campaignmsg']);
               $clb->setCampaignconf($row['campaignconf']);
               
               $clb->setTimezone($row['timezone']);
               $clb->setNevid($row['nevid']);
               $clbs[] = $clb;
            }
           }
     return $clbs;
   }// end of getClubstatus
   
   /**
    *  send campaign
    */
   public function sendCampaign($msg,$recipients, $sendtime, $timezone, $clubid ){
       $i_default = 0;
       $message     = new Application_Model_Message($this->apiuser);
       $message->queue($msg, $recipients, $sendtime, $timezone,$i_default,$clubid);
   }
   /**
    *  send campaign
    */
   public function sendCampaignSNFC($msg,$recipients, $sendtime, $timezone, $clubid){
       $i_default = 0;
       $source = 404;
       $shortcode = 88988;
       $message     = new Application_Model_Message();
       $message->queue($msg, $recipients, $sendtime, $timezone,$i_default,$source,$clubid,$shortcode);
   }
   
    public function selectingBlngJV($day){
        $mssq = new Application_Model_MSSQLJV();
        $mssq->connect();
        $sql = 'EXEC txt_Aging '.$day;
        $res = $mssq->query($sql);
        while($row[]=mssql_fetch_array($res));
        $mssq->disconnect();
        return $row;
   } // end of selectingforBlng
   
    public function selectingBlngUFC($day){
        $mssq = new Application_Model_MSSQL();
        $mssq->connect();
        $sql = 'EXEC txt_Aging '.$day;
        $res = $mssq->query($sql);
        while($row[]=mssql_fetch_array($res));
        $mssq->disconnect();
        return $row;
   } // end of selectingforBlng
   
   /**
    *   Sending billing campaign
    *   @access public
    *   @param int $nevid, $day
    *   @return array object
    *   @name selectingforBlng
    * 
    *   is not used by app
    */
   public function selectingforBlng($nevid,$day){
        $mssq = new Application_Model_MSSQL();
        $mssq->connect();
        $sql = 'EXEC txt_Aging '.$day;
        $res = $mssq->query($sql);
        if($res){
        $phones = array();
        while($row[]=mssql_fetch_array($res));
        $cnt = count($row);
        if ($cnt != 0) {
            $cnt = count($row);
            for ($t = 0; $t < $cnt; $t++) {
                if ($row[$t][0] == $nevid) {
                    if (strlen($row[$t]['Mobile']) < 10) {
                        continue;
                    } else {
                        $phone = $this->cleanPhone($row[$t]['Mobile']);
                        if(strlen($phone) == 10){
                         $phone = "1".$phone;
                        }
                        $phones[$phone]=$phone;
                    }
                }
            }
        }// end of if
//        $phones[19253211843] = 19253211843;
//        $phones[17607176619] = 17607176619;
//        $phones[19252504282] = 19252504282;
//        $phones[19253050723] = 19253050723;
        return $phones;
        }else{
            return FALSE;
        }
        $mssq->disconnect();
   } // end of selectingforBlng
   
   public function selectingBlngSNFC($day){
        $mssq = new Application_Model_MSSQLNFC();
        $mssq->connect();
        $sql = 'EXEC txt_Aging '.$day;
        $res = $mssq->query($sql);
        while($row[]=mssql_fetch_array($res));
        $mssq->disconnect();
        return $row;
   } // end of selectingforBlng
   /**
    * 
    * 
    */
   public function selectingBirthdaySNFC(){
        $mssq = new Application_Model_MSSQLNFC();
        $mssq->connect();
        $sql = 'EXEC Txt_Birthdays';
        $res = $mssq->query($sql);
        while($row[]=mssql_fetch_array($res));
        $mssq->disconnect();
        return $row;
   } // end of selectingforBlng
   /**
    *  is not active yet
    * 
    */
   public function selectingBirthdayJV(){
        $mssq = new Application_Model_MSSQLJV();
        $mssq->connect();
        $sql = 'EXEC Txt_Birthdays';
        $res = $mssq->query($sql);
        while($row[]=mssql_fetch_array($res));
        $mssq->disconnect();
        return $row;
   } // end of selectingforBlng
   
       public function selectingBirthdayUFC(){
        $mssq = new Application_Model_MSSQL();
        $mssq->connect();
        $sql = 'EXEC Txt_Birthdays';
        $res = $mssq->query($sql);
        while($row[]=mssql_fetch_array($res));
        $mssq->disconnect();
        return $row;
   } // end of selectingforBlng
   
   public function getphonelistSNFCloc($nevid,$row){
        if($row){
        $phones = array();
        $cnt = count($row);
        if ($cnt != 0) {
            $cnt = count($row);
            for ($t = 0; $t < $cnt; $t++) {
                if ($row[$t][0] == $nevid) {
                    if (strlen($row[$t]['Mobile']) < 10) {
                        continue;
                    } else {
                        $phone = $this->cleanPhone($row[$t]['Mobile']);
                        if(strlen($phone) == 10){
                         $phone = "1".$phone;
                        }
                        $phones[$phone]=$phone;
                    }
                }
            }
        }// end of if
//        $phones[19253211843] = 19253211843;
//        $phones[17607176619] = 17607176619;
//        $phones[19252504282] = 19252504282;
//        $phones[19253050723] = 19253050723;
        return $phones;
        }else{
            return FALSE;
        }
   } // end of selectingforBlng
   
   public function selectingforBlngSNFC($nevid,$day){
        $mssq = new Application_Model_MSSQLNFC();
        $mssq->connect();
        $sql = 'EXEC txt_Aging '.$day;
        $res = $mssq->query($sql);
        if($res){
        $phones = array();
        while($row[]=mssql_fetch_array($res));
        $cnt = count($row);
        if ($cnt != 0) {
            $cnt = count($row);
            for ($t = 0; $t < $cnt; $t++) {
                if ($row[$t][0] == $nevid) {
                    if (strlen($row[$t]['Mobile']) < 10) {
                        continue;
                    } else {
                        $phone = $this->cleanPhone($row[$t]['Mobile']);
                        if(strlen($phone) == 10){
                         $phone = "1".$phone;
                        }
                        $phones[$phone]=$phone;
                    }
                }
            }
        }// end of if
        return $phones;
        }else{
            return FALSE;
        }
        $mssq->disconnect();
   } // end of selectingforBlng
   
   /**
    * 
    * 
    */
  public function nevusage($accountid, $phonenumbers, $clubid,$msgtype) {
        $arphones = count($phonenumbers);
//       $this->query("insert into nevusage (clubid,phonenumber,accountid) values($clubid,$phonenumbers[$n],$accountid)");
        for ($n = 0; $n < $arphones; $n++) {
            $this->query("Call nevusage($accountid,$phonenumbers[$n],$clubid,'$msgtype')");
        }
    }
 /**
    * 
    * 
    */
    public function nevusageBlng($accountid, $phonenumbers, $clubid,$msgtype) {
//       $this->query("insert into nevusage (clubid,phonenumber,accountid) values($clubid,$phonenumbers[$n],$accountid)");
        foreach ($phonenumbers as $phns => $v) {
            $this->query("Call nevusage($accountid,$v,$clubid,'$msgtype')");
        }
    }
   
   public function getOptedoutSubcribers(){
       $list = array();
       $sql = "Call totalOptedoutsubcribers()";
       $rs = $this->query($sql);
       foreach ($rs->fetchAll() as $row) {
           $list[] = $row['device_address'];
       }
        return $list;
       }
       
       /**
     *  Selecting folders created
     *  for nev clubs location
     *  @name $getNevlocations
     *  @param int $userid 
     *  @return array object
     */
    public function getNevlocations($userid){
        $location = array();
        $sql = "Call getnevfolders($userid)";
         $rs = $this->query($sql);
         
        foreach ($rs->fetchAll() as $row) {
            $location[$row['id']] = $row['value'];
        }
        return $location;
    }
    
    /**
     * 
     * 
     */
    public function getContinent($userid){
        $sql = "SELECT n.continent as continent from nevclubs n, entity e where e.typeid=7 and e.createuser=$userid and n.textmid = e.id order by n.id limit 1";
        $rs = $this->query($sql);
        return $rs->continent;
    }
}

?>
