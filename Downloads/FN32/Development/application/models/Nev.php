<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Nev
 *
 * @author farad
 */
class Application_Model_Nev extends Application_Model_Abstract  {
   
    /**
     *  Just collecting all phone numbers 
     *  from spendgo comes to db
     */
    public function insertNevusage($clubid,$phonenumber){
        
        $sql = "INSERT into nevusage (`clubid`,`phonenumber`) value($clubid,$phonenumber)";
        
        $this->query($sql);
    }
}

?>
