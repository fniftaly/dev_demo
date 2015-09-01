<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Login
 *
 * @author farad
 */
class Application_Model_Login extends Application_Model_Abstract{
    
    /**
     *  This function verifing user email address
     *  If it exist this function should return true
     *  other waise fase
     *  @param type  string $name usremail
     *  @return false or true
     */
    public function getUserEmail($usremail){
        $sql = "Call check_user_email('$usremail')";
        $rs = $this->query($sql);
        if($rs->usrid > 0){
            return $rs->usrid;
        }else{
            return false;
        }
     }// end of getUserEmail
     
    public function updatePassword($usrid,$password) {
              $sql = "CALL update_user_password($usrid,'$password')";
	$rs = $this->query($sql);
	if($rs->success){
              return true;
        }else{
            return false;
        }
    }// end of updatePassword;
    
     public function encryptPassword($password) {
        return md5($password);
    }
}

?>
