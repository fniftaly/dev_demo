<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Rewards
 *
 * @author farad
 */
class Application_Model_Rewards extends Application_Model_Abstract {

    public function __construct() {
        ;
    }

    /**
     *  Collecting info for rewards members
     *  @name $insertrewards
     *  @param int $busid, bigint $rphone
     *  @access public
     *  @return boolean 
     */
    public function insertrewards($busid, $rphone,$rewardcode, $rewardstatus,$msgsend) {
        $sql = "insert into rewards (businessid, phonenumber,rewardcode, rewardstatus, msgsend ) values($busid, $rphone,'$rewardcode', $rewardstatus,$msgsend)";
        $rs = $this->query($sql);
        return $rs;
    }

    /**
     *  Riiwards data collection
     *  @name $insertriiwards
     *  @param str $subject, bigint $rphone, datetime $sendtime
     *  @access public
     *  @return boolean 
     */
    public function insertriiwards($clubid,$subject, $rphone, $sendtime) {
        $sql = "insert into riiwards (clubid,subject, phonenumber, sendtime ) values($clubid,'$subject', $rphone,'$sendtime')";
        $rs = $this->query($sql);
        return $rs;
    }

    public function riiwardsOptedout() {
        $optedout = array();
        $sql = "select clubid, device_address from messages_inbound where device_address 
        in(select distinct phonenumber from riiwards where createtime like CONCAT('%', curdate(), '%')) and message in('stop', 'cancel', 'quit') order by createtime desc";
//        $sql="CALL riiwardsOptedout()";
        $rs = $this->query($sql);
        if ($rs->hasResults()) {
            foreach ($rs->fetchAll() as $number) {
                $optedout[] = $number['device_address'];
            }
        }
        return json_encode($optedout);
    }

    /**
     *  Selecting reworders visit 
     *  for each business place
     * 
     */
    public function rewordVisit($businesid, $phone) {
        $sql = "select count(id) as rvisit from rewards where businessid=$businesid and phonenumber=$phone";
        $rs = $this->query($sql);
        return $rs->rvisit;
    }
    /**
     *  Returns all visitors from client account
     *  @param int $businesid Description
     *  @access public
     * @return int total visitors
     */
    public function totalRewardVisitor($businesid) {
        $sql = "select count(id) as total from rewards where businessid=$businesid";
        $rs = $this->query($sql);
        return $rs->total;
    }
   
    /**
     * 
     */
    public function getRewardablemsg($accountid,$visit) {
        $msg = array();
        $sql = "select message, rewardable  from rewardsmessages where accountid=$accountid and visit = $visit";
        $rs = $this->query($sql);
         if ($rs->hasResults()) {
            foreach ($rs->fetchAll() as $data) {
                $msg[] = $data['message'];
                $msg[] = $data['rewardable'];
            }
        }
        return $msg;
    }
//   SELECT rewardcode, createtime FROM `rewards` WHERE businessid=999 and phonenumber=19253050723 and `rewardcode` !='' order by `createtime` desc limit 1
    /**
     * 
     */
    public function verifyReward($accountid, $phone){
//      $sql =   "SELECT rewardcode FROM `rewards` WHERE businessid=$accountid and phonenumber=$phone and `rewardcode` !='' and `rewardstatus`=0 order by `createtime` desc limit 1";
//      $rs = $this->query($sql);
//        return $rs->rewardcode;
         $rst = array();
      $sql =   "SELECT m.message as msg, r.rewardcode as code FROM `rewardsmessages` m, rewards r  WHERE r.businessid=$accountid and r.phonenumber=$phone and r.businessid = m.accountid and 
          m.`rewardable`=1 and r.`rewardstatus`=0 and m.row = r.msgsend order by r.`createtime` desc limit 1";
      $rs = $this->query($sql);
       if ($rs->hasResults()) {
            foreach ($rs->fetchAll() as $k=>$v) {
                $rst[] = $v['msg'];
                $rst[] =$v['code'];
            }
        }
        return $rst;  
    }
    /** User reward option
     *  @name insertRewardmsg
     *  @param type Class object
     *  @access public
     *  @return bool 
     */
    public function insertRewardmsg($accountid, $message,$visit, $rewardable, $row){
      $sql =   "insert into  rewardsmessages (accountid, message, visit, rewardable, row) 
          values($accountid, '$message',$visit, $rewardable, $row)";
      $rs = $this->query($sql);
        return $rs;
    }
    /** Verifys if reward msg already in the system
     *  @name verifayRewardmsg
     *  @param type int $accountid, $row
     *  @access public
     *  @return bool 
     */
    public function verifyRewardmsg($accountid, $row){
      $sql =   "Select count(*) as rows from  rewardsmessages where accountid=$accountid and row = $row";
      $rs = $this->query($sql);
        return $rs->rows;
    }
     /**
     *   gets cycle number of reward 
     *   from account
     */
    public function rewordCycle($accountid) {
//        $sql = "select cycle from rewardscycle where accountid=$accountid";
//        $sql = "select count(*) as cycle from rewardsmessages where accountid=$accountid";
        $sql = "select max(row) as cycle from rewardsmessages where accountid=$accountid and row not in(0, 777)";
        $rs = $this->query($sql);
        return $rs->cycle;
    }
    /**
     * 
     * 
     */
    public function getAllRewards($accountid){
      $sql =   "Select* from  rewardsmessages where accountid=$accountid";
      $rs = $this->query($sql);
        return $rs->fetchAll();
    }
    
     /**
     *  is getting loyalty folder id
     *  if it is exist in user account
     */
    public function getRewardFolderid($accountid){
      $sql =   "select e1.id as fid from entity e,entity e1, entitymeta m where e.typeid = 5 
                    and e1.createuser=e.id and e1.typeid = 4 and
                   m.entityid=e1.id and m.value like '%".$accountid."' and e.id = $accountid";
        $rs = $this->query($sql);
        return $rs->fid;
    }
     public function inserRewardVisitorIntoSubscribers($folderid,$phone){
      $sql =   "insert into `subscribers` (folderid, phonenumber) values($folderid, $phone)";
      $rs = $this->query($sql);
        return $rs;
    }
    /**
     * 
     * 
     */
      public function getTotalVisitorsReword($accountid){
      $visits = array();  
      $sql =   "Select distinct phonenumber from  rewards where businessid=$accountid";
      $rs = $this->query($sql);
       if ($rs->hasResults()) {
            foreach ($rs->fetchAll() as $data=>$v) {
                $visits[] = $v['phonenumber'];
            }
        }
        return $visits;
    }
    /**
     * 
     * 
     */
      public function getRows($accountid){
       $aids = array(); 
      $sql =   "select distinct row as row from rewardsmessages where accountid=$accountid";
      $rs = $this->query($sql);
      if($rs->hasRecords()){
          foreach($rs->fetchAll() as $kid=>$vid){
              $aids[] = $vid['row'];
          }
      }
      /*sorting row value*/
      function cmp($a, $b)
        {
            if ($a == $b) {
                return 0;
            }
            return ($a < $b) ? -1 : 1;
        }
        usort($aids, "cmp");
        return $aids;
    }
    
      /**
     * 
     * 
     */
    public function getrewardMsgbody($accountid, $row){
        $msg_rwd = array();
        $sql = "select* from rewardsmessages where accountid = $accountid and row = $row";
        $rs = $this->query($sql);
        
        if($rs->hasRecords()){
          foreach($rs->fetchAll() as $k=>$v){
              $msg_rwd[] = $v['message'];
              $msg_rwd[] = $v['rewardable'];
              $msg_rwd[] = $v['row'];
          }
      }
      return json_encode($msg_rwd);;
    }
     /**
     * 
     * 
     */
    public function getRewardsByvisit($accountid,$visit){
      $visits = array();  
      $sql =   "Select phonenumber from  rewards where businessid=$accountid and msgsend=$visit";
      $rs = $this->query($sql);
       if ($rs->hasResults()) {
            foreach ($rs->fetchAll() as $data) {
                $visits[] = $data['phonenumber'];
            }
        }
        return $visits;
    }
      /**
     * 
     * 
     */
    public function getVisitorLastvisit($accountid,$phone){
      $visits = array();  
      $sql =   "SELECT * FROM `rewards` WHERE businessid=$accountid and phonenumber=$phone order by `createtime` desc limit 1";
      $rs = $this->query($sql);
       if ($rs->hasResults()) {
            foreach ($rs->fetchAll() as $data) {
                $visits[] = $data['createtime'];
            }
        }
        return $visits;
    }
     /**
     * 
     * 
     */
    public function getVisitorsrange($accountid){
       $range = array(); 
      $sql =   "select `accountid`,`range` from `rewardvisitorsrange` where `accountid`=$accountid";
       $rs = $this->query($sql);
       if ($rs->hasResults()) {
            foreach ($rs->fetchAll() as $data) {
                $range[] = $data['accountid'];
                $range[] = $data['range'];
            }
        }
        return $range;
    }
     /**
     * 
     * 
     */
    public function updateVisitorsrange($accountid,$range){
      $sql =   "update `rewardvisitorsrange` set `range`=$range where `accountid`=$accountid";
       $rs = $this->query($sql);
    }
     /**
     * 
     * 
     */
    public function insertVisitorsrange($accountid,$range){
      $sql =   "insert into `rewardvisitorsrange` (`accountid`,`range`) values($accountid,$range)";
      $rs = $this->query($sql);
    }
     /**
     * 
     * 
     */
   public function getRewardMsg($accountid,$phone){
      $sql =   "SELECT m.message, r.rewardcode, r.rewardstatus, r.createtime FROM `rewardsmessages` m, rewards r 
                   WHERE m.accountid = r.businessid and m.visit = r.msgsend and r.businessid = $accountid and r.phonenumber=$phone order by createtime asc";
      $rs = $this->query($sql);
        return $rs->fetchAll();
    }
    /** Update rewards option
     *  @name updateReward
     *  @param type int $accountid,$visit, $rewardable, $row
     *  @param type str $message
     *  @access public
     *  @return bool 
     */
    public function updateReward($accountid, $message,$visit, $rewardable, $row){
      $sql =   "update  rewardsmessages set message='$message', visit = $visit, rewardable= $rewardable where accountid=$accountid and row = $row";
      $rs = $this->query($sql);
        return $rs;
    }
    /**
     * 
     */
    public function redeamReward($rwcode){
      $sql =   "update `rewards` set  rewardstatus = 1 WHERE rewardcode= '$rwcode'";
      $rs = $this->query($sql);
    }
    public function riiwardoptedout($phones) {
        $apiUrl = 'https://riiwards.com/optedout';
        $sendMessage = urlencode($alert);
        $username = 'username';
        $password = 'pwd';

        $uri = $apiUrl;

        $ch = curl_init($uri);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "user=$username&pass=$password&optedout=$phones");
        // Now set some params, start with username and password
//			curl_setopt($ch, CURLOPT_USERPWD, $username . ':' . $password);
        // Turn off header output in the response
        curl_setopt($ch, CURLOPT_HEADER, false);

        // Disable SSL peer verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        // Capture the output instead of echoing it
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Execute our request
        $rs = curl_exec($ch);

        // Close the cURL handle
        curl_close($ch);
    }

public function rewardMessage($phone, $alert) {
        $apiUrl = 'https://text.mes.syniverse.com/SMSSend';
        $shortCode = '87365';
        $sendMessage = urlencode($alert);
        $username = '4400';
        $password = 'Fq0^Hc0^';

        $uri = $apiUrl;

        $ch = curl_init($uri);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "user=$username&pass=$password&smsfrom=$shortCode&smsto=$phone&smsmsg=$sendMessage");
        // Now set some params, start with username and password
//			curl_setopt($ch, CURLOPT_USERPWD, $username . ':' . $password);
        // Turn off header output in the response
        curl_setopt($ch, CURLOPT_HEADER, false);

        // Disable SSL peer verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        // Capture the output instead of echoing it
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Execute our request
        $rs = curl_exec($ch);

        // Close the cURL handle
        curl_close($ch);
    }
}

?>
