<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Abcdata
 *
 * @author farad
 */
class Application_Model_Abcdata extends Application_Model_Abstract{
    public  $curdate;
    public $URL_ALL_DATA;           
    public $URL_UPDATE_INFO; 

    public function __construct() {
     }
    public function getURL($abcid) {
//         $this->curdate = date('Y-m-d');
         $this->curdate = date('2013-11-26');
         $this->URL_ALL_DATA      = "https://webservice.abcfinancial.com/ws/getMemberList/$abcid?memberStatus=active";
         $this->URL_UPDATE_INFO = "https://webservice.abcfinancial.com/ws/getMemberList/$abcid?memberStatus=active&updateStartTime=$this->curdate";
     }

     public function getURL_ALL_DATA() {
         return $this->URL_ALL_DATA;
     }

     public function setURL_ALL_DATA($URL_ALL_DATA) {
         $this->URL_ALL_DATA = $URL_ALL_DATA;
     }

     public function getURL_UPDATE_INFO() {
         return $this->URL_UPDATE_INFO;
     }

     public function setURL_UPDATE_INFO($URL_UPDATE_INFO) {
         $this->URL_UPDATE_INFO = $URL_UPDATE_INFO;
     }
    
     /**
     *  Getting data from ABC db
     *  for processing on Textm 
     *  platform. Using by ABC API
     *  @return type array object
     *  @access public
     */
      public function abcClientData($URL) {
        ini_set('memory_limit', '512M');  
        $enc = base64_encode("textmunication.com:Textmun1");
        $headr = array();
        $headr[] = 'Content-length: 0';
        $headr[] = "Accept: application/xml";
        $headr[] = 'Authorization: Basic '.$enc;

        $ch = curl_init($URL);

        curl_setopt($ch, CURLOPT_GET, 1);

        curl_setopt($ch,CURLOPT_HTTPHEADER, array('Authorization: Basic '.$enc));

        // Disable SSL peer verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_HEADER, false);
        
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // Execute our request
        $rs = curl_exec($ch);
//        echo $rs;
        $jxml = json_decode(json_encode((array) simplexml_load_string($rs)), 1);
        curl_close($ch);
        
        return $jxml['result']['members']['member'];
}// end of abcClientData

    /**
     *  Stores ABC clients data into table
     *  @access public
     *  @param type array of Abcclub object
     *  @return void
     */
    public function storeAbsdata($abcobj){
        foreach($abcobj as $kobj=>$abcclub){
         if($abcclub instanceof Application_Model_Abcclub)   {
             $memberid = $abcclub->getMemberid();
             $clubid = $abcclub->getAbcclubid();
             $firstname = $abcclub->getFirstname();
             $lastname = $abcclub->getLastname();
             $phone = $abcclub->getPhonenumber();
             $bod = $abcclub->getBirthday();
             if($bod){$dob = substr($bod, 5);}
             $email = $abcclub->getEmail();
             $gender = $abcclub->getGender();
             $dues = $abcclub->getDues();
             $duepayment = $abcclub->getDuepaymentdate();
        $sql = "Insert into abcdata (`memberid`,`clubid`, `firstname`,`lastname`,`phonenumber`,`birthday`,`email`,`gender`,`dues`,`duepaymentdate`) 
            values('$memberid',$clubid,'$firstname','$lastname','$phone','$dob', '$email', '$gender',$dues,'$duepayment')";
        }
        $this->query($sql);
    }
    }// end of storeAbsdata
    
    /**
     * 
     * 
     */
    public function setAbcclubObjects($dataarray){
        $abcObj = array();
        foreach($dataarray as $kcl=>$abc){
            $abcclb = new Application_Model_Abcclub();
            $abcclb->setMemberid($abc['@attributes']['id']);
            $abcclb->setAbcclubid($abc['homeClub']);
            $abcclb->setFirstname($abc['personal']['firstName']);
            $abcclb->setLastname($abc['personal']['lastName']);
            
            if($abc['contact']['cellPhone']){
                
                $cphone = preg_replace("/[^0-9]/", "", $abc['contact']['cellPhone']);
                  if(strlen($cphone) == 10)
                    {
                      $cphone = "1".$cphone;
                      $abcclb->setPhonenumber($cphone);
                    }else{
                        if(strlen($cphone) == 11)
                            {
                         $abcclb->setPhonenumber($cphone);
                        }
                    }
            }
            else{
//            elseif($abcclb->setPhonenumber($abc['contact']['homePhone'])){
                $cphone = preg_replace("/[^0-9]/", "", $abc['contact']['homePhone']);
                    if(strlen($cphone) == 10)
                    {
                      $cphone = "1".$cphone;
                      $abcclb->setPhonenumber($cphone);
                    }else{
                        if(strlen($cphone) == 11)
                            {
                         $abcclb->setPhonenumber($cphone);
                        }
                    }
                    }
                
            $abcclb->setBirthday(date($abc['personal']['birthDate']));
            $abcclb->setEmail(($abc['contact']['email'] !=NULL)?$abc['contact']['email']:NULL);
            $abcclb->setGender($abc['personal']['gender']);
            $abcclb->setDues($abc['agreement']['payment']['dues']);
            $abcclb->setDuepaymentdate(date($abc['agreement']['payment']['nextDueDate']));
            $abcObj[] = $abcclb;
        }
        return $abcObj;
    }
    
    /**
     *  Selecting IDs abc accounts that their data
     *  is not  stored in textm db
     * 
     */
    
    public function getAccountsId(){
        $ids = array();
//        $sql2 = "Select distinct clubid as id from abcdata";
        $sql = " select nevid as id from club_monitoring where nevid not in(Select distinct clubid as id from abcdata) and continent =3";
        
        $rs = $this->query($sql);
        if($rs->hasRecords()){
            foreach($rs->fetchAll() as $kid=>$val){
                $ids[] = $val['id'];
            }
        }
        return $ids;
    }// end of getAccountsId
    
  public function getPfacnumbers(){
        $rsar= array();
        $sql="SELECT distinct s.phonenumber, e.id from entity e, entity e1, subscribers s where e.typeid = 5 and e.createuser=185 and
        e1.createuser = e.id and e1.typeid=4 and s.folderid = e1.id and s.optouttime = '0000-00-00 00:00:00'";
    $rs = $this->query($sql);
    if($rs->hasRecords($offset)){
        return $rs->fetchAll();
    }
    }
    /**
     *  Gets optouts from accounts for abc accounts
     * 
     */
  public function getOptoutsForAbcaccounts($userid){
        $rsar= array();
        $sql="SELECT distinct phonenumber FROM `subscribers` WHERE folderid in(select id from entity where 
                     createuser=$userid and typeid=4) and optouttime != '0000-00-00 00:00:00'";
    $rs = $this->query($sql);
    if($rs->hasRecords()){
     foreach($rs->fetchAll() as $obj=>$phone){
         $rsar[$phone['phonenumber']] = $phone['phonenumber'];
      }
      if(count($rsar) != 0){
          return $rsar;
      }else{ return 0;}
    }
    }
    
    public function abcSubscribers($abc,$folderid,$userid){
        $optouts = $this->getOptoutsForAbcaccounts($userid);
        foreach($abc as $kcl=>$obj){
        if($obj instanceof Application_Model_Abcclub)
        {
            if($optouts[(int)$obj->getPhonenumber()]){
                continue;
            }else{
            $phone =  (int)$obj->getPhonenumber();
            $first =     $obj->getFirstname();
            $last =     $obj->getLastname();
            $email =   $obj->getEmail();
            $dob =     $obj->getBirthday();
             if($dob){$dob = substr($dob, 5);}
            $sql = "insert into subscribers(`folderid`, `phonenumber`, `firstname`, `lastname`, `email`, `birthday`) 
            values($folderid, $phone, '$first','$last', '$email','$dob')";
        }
       $rs = $this->query($sql);
     }}
       if($rs){return 1;}else{return 0;}
    }
    
     /**
     * 
     * 
     */
    public function cleanupFolder($folderid){
        $sql = "delete from subscribers where folderid=$folderid";
        $rs = $this->query($sql);
        return $rs;
    }
}

?>
