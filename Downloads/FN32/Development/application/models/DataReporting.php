<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DataReporting
 *
 * @author farad
 */
class Application_Model_DataReporting extends Application_Model_Abstract{
    
    /**
     *  reporting from corporate account
     *  super user can see all accounts activity
     *  
     * @name corporate_message_reporting
     * @param int userid and string month and year
     * @return array
     */
   public function corporate_message_reporting($userid,$monthyear,$start, $end){
        $sql = "";
        if($monthyear){
            $sql = "Call corporate_message_reporting_monthly($userid,'$monthyear')";
        }else{
           $sql = "Call corporate_message_reporting_weekly($userid,'$start','$end')";
        }
        $rs = $this->query($sql);
        if ($rs->hasRecords()) {
           return $rs->fetchAll();  
        }else{
          return false;
        }
    }// end
        /**
     *  reporting from user account
     *  keywords, optin, optout, campaign, messages
     * @name account_message_reporting
     * @param int userid and string month and year
     * @return array
     */
   public function account_message_reporting($userid,$monthyear){
        $sql = "";
        if($monthyear){
            $sql = "Call account_message_reporting_monthly($userid,'$monthyear')";
        }
        $rs = $this->query($sql);
        if ($rs->hasRecords()) {
           return $rs->fetchAll();  
        }else{
          return false;
        }
    }// end
    
    /**
     * updates messages outbound recipients table
     * 
     */
     public function recipients_update(){
        $sql = "Call messages_outbound_resipients_update()";
        $rs = $this->query($sql);
        if ($rs->success >0 ) {
           return $rs->success;  
        }else{
          return 0;
        }
    }// end
    /**
     *   updates allphone numbers table
     *   truncate and populates from subscribers
     * 
     */
     public function allphonenumbers(){
        $sql = "Call allphonenumbers()";
        $rs = $this->query($sql);
        if ($rs->rows >0 ) {
           return $rs->rows;  
        }else{
          return 0;
        }
    }// end
    
    
    
}

?>
