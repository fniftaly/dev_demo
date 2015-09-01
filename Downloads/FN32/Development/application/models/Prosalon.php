<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Prosalon
 *
 * @author farad
 */
class Application_Model_Prosalon extends Application_Model_Abstract {

    public function insert($business,$timestamp,$campaign,$clentnumber,$phone,$appointmentDate,$sent="",$read="",$confirmed="") 
    {
        $sql = sprintf("CALL insert_prosalon('%s',$timestamp,'%s',$clentnumber,'%s',$appointmentDate,'%s','%s','%s')",
                $this->escape($business),
                $this->escape($campaign),
                $this->escape($phone),
                $this->escape($sent),
                $this->escape($read),
                $this->escape($confirmed)
        );
        $rs = $this->query($sql);
        if ($this->hasError()) {
            $this->setError("Could not edit data to prosalon tb", $this->getError());
            return false;
        }
        return $rs->id;
    }
    
    public function getList($business,$campaign, $timestamp)
    {
        $sql = sprintf("CALL get_prosalon_report('$business','$campaign',$timestamp)",
                $this->escape($campaign)
        );
        $rs = $this->query($sql);
        if ($this->hasError()) {
            $this->setError("Could not retrieve data from prosalon table", $this->getError());
            return false;
        }
        if($rs->hasRecords()){
            foreach ($rs->fetchAll() as $id){
                $rId = $id['id'];
                $this->query("CALL get_prosalon_update($rId)");
            }
        }
        return $rs->getRecords();
    }// end of getList
    
    public function update_prosalon_from_recipients(){ 
        $ids = array();
        $sgl = "Call getNotReaded()";
        $rs = $this->query($sgl);
        
        if ($this->hasError()) {
            $this->setError("Could not update prosalon table", $this->getError());
            return false;
        }
        
        if($rs->hasRecords()){
            foreach($rs->fetchAll() as $id){
                $rId = $id['id'];
                $rs1 =$this->query("Call get_update_from_recipients($rId)");
                if($rs1->pick){
                    $this->query("Call prosalon_confirm_update($rId,'$rs1->pick')");
                }
            }
            return true;
        }//;
        
    } // end of update_prosalon_from_recipients

    public function confim_update_from_inbound(){
         $sgl = "Call if_confirmed_update_prosalon()";
         $this->query($sgl);
    }
    public function not_confimed_update_from_inbound(){
         $sgl = "Call if_not_confirmed_update_prosalon()";
         $this->query($sgl);
    }
    
     /**
     * Checking status of the subscribes in messages_inbound table
     * @param $phonenumber
     * @method check_status_frominbound
     * @name   check_status_frominbound
     * @access public
     * 
     */
    public function check_status_frominbound($phonenumber){
      
        if($phonenumber){
          $this->setError("Phone # can't be empty: ", $this->getError());
          return;
        }
        $sql_out = "CALL inbound_messages_withstop($phonenumber)";
        
        $rs_out = $this->query($sql_out);
        
            $date_out = $rs_out->phonein;
        
         if($date_out){
             return TRUE;
         }else{
             return FALSE;
         }
                 
    }// end of optout_subscribers
    
    /**
     *  temporary solution for correct reporting
     *  total message send out from Prosalon
     */
     public function prosalon_usage($phones, $accountid){
       for($i = 0; $i <count($phones); $i++)
        $this->query("INSERT into prosalon_usage (phonenumber, createuser) values($phones[$i],$accountid)");
    }
    
     /**
     * 
     * 
     */
    public function newconfirmed($number){
//        $sql = "insert into newconfirmed (phonenumber) values($number)";
        $sql = "Call newconfirmed ($number)";
        $this->query($sql);
    }
    /**
     * 
     * 
     */
    public function getnewconfirmed(){
        $confirmed = array();
//        $sql = "select* from newconfirmed";
        $sql = "Call getnewconfirmed()";
        $rs = $this->query($sql);
        
         if ($rs->hasResults()) {
            foreach( $rs->fetchAll() as $phone){
                $confirmed[] = $phone['phonenumber'];
            }
            return $confirmed;
        }
    }
}

?>
