<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TotalDataReport
 *
 * @author farad
 */
class Application_Model_TotalDataReport extends Application_Model_Abstract{
  
    /*new reporting changes for users starts from here 11/13/2012*/ 
        
        
    public function user_active_keywords($userid){
        $sql = "Call user_active_keywords($userid)";
        $rs = $this->query($sql);
       if ($rs->hasRecord()) {
           return $rs->total;  
        }else{
          return false;
        }
    }
    public function total_subscribers_inaccount($userid,$month, $start, $end){
        $sql = "";
        if($month){
//            echo '<br>Inside1: '.$month."<>".strlen($month); exit;
            $sql = "Call total_subscribers_bymonthly($userid, '$month')";
        }else{
//               echo '<br>Inside2: '.$month; exit;
            $sql = "Call total_subscribers_onaccount($userid, '$start', '$end')";
        }
        $rs = $this->query($sql);
        if ($rs->hasRecord()) {
           return $rs->total;  
        }else{
          return false;
        }
    }// end
    /**
     * Total Subcribers from user account
     * Monthly selection
     */
    
//    public function total_subscribers_bymonthly_useraccount($userid,$month){
//        $sql = "Call total_subscribers_bymonthly($userid, '$month')";
//        $rs = $this->query($sql);
//        if ($rs->hasRecord()) {
//           return $rs->total;  
//        }else{
//          return false;
//        }
//    }// end
    
    public function total_optedout_fromaccount($userid,$month,$start, $end){
        $sql = "";
        if($month){
             $sql = "Call total_optedout_bymonthly($userid, '$month')";
        }else{
             $sql = "Call total_optedout_onaccount($userid, '$start', '$end')";
        }
         
        $rs = $this->query($sql);
        if ($rs->hasRecord()) {
           return $rs->total;  
        }else{
          return false;
        }
    } //end
    /**
     * Total opted out Subcribers from user account
     * Monthly selection
     */
//    public function total_optedout_fromaccount_bymonthly($userid,$month){
//        $sql = "Call total_optedout_bymonthly($userid, '$month')";
//        $rs = $this->query($sql);
//        if ($rs->hasRecord()) {
//           return $rs->total;  
//        }else{
//          return false;
//        }
//    } //end
    
    public function total_messages_send($userid,$month,$start, $end){
        $sql = "";
        if($month){
//            echo 'month: '.$month.'<>'.$start.'<>'.$end; exit;
            $sql = "Call total_messages_bymonthly($userid,'$month')";
        }else{
            $sql = "Call total_messages_send($userid,'$start','$end')";
        }
        
        $rs = $this->query($sql);
        if ($rs->hasRecord()) {
           return $rs->total;  
        }else{
          return false;
        }
    }// end
    /**
     * Total messages send from user account
     * Monthly selection
     */
//     public function total_messages_send_monthly($userid,$month){
//        $sql = "Call total_messages_bymonthly($userid,'$month')";
//        $rs = $this->query($sql);
//        if ($rs->hasRecord()) {
//           return $rs->total;  
//        }else{
//          return false;
//        }
//    }// end
    public function total_campaigns_send($userid,$month, $start, $end){
        $sql = "";
        if($month){
            $sql = "Call total_campaigns_bymonthly($userid,'$month')";
        }else{
           $sql = "Call total_campaigns_send($userid,'$start','$end')"; 
        }
        $rs = $this->query($sql);
        if ($rs->hasRecord()) {
           return $rs->total;  
        }else{
          return false;
        }
    }// end
    
     /**
     * Total campaigns send from user account
     * Monthly selection
     */
//     public function total_campaigns_send_monthly($userid,$month){
//        $sql = "Call total_campaigns_send($userid,'$month')";
//        $rs = $this->query($sql);
//        if ($rs->hasRecord()) {
//           return $rs->total;  
//        }else{
//          return false;
//        }
//    }// end
    public function accountids_fromadminusers($userid){
        $ids = array();
        $sql = "Call accoutns_from_adminusers($userid)";
        $rs = $this->query($sql);
        
        if ($rs->hasRecords()) {
          foreach($rs->fetchAll() as $ids_r=>$id){
              $ids[] = $id['id'];
          } 
          return $ids;
        }else{
          return false;
        }
    }// end
    
    
    
    /*new reporting changes for locations folders starts from here 11/13/2012*/
    
     public function total_optedout_infolder($userid,$start, $end){
        $sql = "Call get_optedout_fromsingle_folder($userid,'$start','$end')";
        $rs = $this->query($sql);
        if ($rs->hasRecord()) {
           return $rs->total;  
        }else{
          return false;
        }
    }// end
     public function total_optedin_infolder($userid,$start, $end){
        $sql = "Call get_subcribers_fromsingle_folder($userid,'$start','$end')";
        $rs = $this->query($sql);
        if ($rs->hasRecord()) {
           return $rs->total;  
        }else{
          return false;
        }
    }// end
    
    public function total_campaigns_sendfromfolder($userid,$folderid,$start, $end){
        $sql = "Call get_campaigns_fromsingle_folder($userid,$folderid, '$start','$end')";
        $rs = $this->query($sql);
        if ($rs->hasRecord()) {
           return $rs->total;  
        }else{
          return false;
        }
    }// end
    public function total_messages_sendfromfolder($folderid,$userid,$start, $end){
        $sql = "Call get_sendmessages_fromsingle_folder($folderid,$userid,'$start','$end')";
        $rs = $this->query($sql);
        if ($rs->hasRecord()) {
           return $rs->total;  
        }else{
          return false;
        }
    }// end
    
    /* This is horrible but right now i don't have choice to avoid this cheap solution below MONTHLY SELECTION*/
}

?>
