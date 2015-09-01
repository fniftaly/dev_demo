<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ProsalonController
 *
 * @author farad
 */
class Api_ProsalonController extends ApiControllerAbstract {
    
    public function indexAction() {
		$this->_notImplemented();
	}
    
    public function getAction() {
		$this->insertProsalon();
	}
	
    public function postAction() {
//        $this->insertProsalon();
        $business = $this->_requestParam('business');
		$timestamp    = $this->_requestParam('timestamp');
		$campaign   = $this->_requestParam('campaign');
		$sent    = $this->_requestParam('sent');
		$read    = $this->_requestParam('read');
		$confirmed    = $this->_requestParam('confirmed');
		$appointmentDate    = $this->_requestParam('appointmentDate');
		
                
                $authObj = new Application_Model_Auth();
                $isAuthorized = $authObj->authenticate($this->_requestParam('username'),$this->_requestParam('password'));
                if($isAuthorized){
                    $sql = sprintf("CALL insert_prosalon('%s',$timestamp,'%s','%s','%s','%s',$appointmentDate)",
                    $this->escape($business),
                    $this->escape($campaign),
                    $this->escape($sent),
                    $this->escape($read),
                    $this->escape($confirmed)
                );
                    $rs = $this->query($sql);
                }
             if ($this->hasError()) {
	        $error = 'Unable to save data to prosalon tb';
                $this->setError($error, $error.' - '.$sql.': '.$this->getError());
	    }
            exit();
    }

    public function putAction() {
        $this->_notImplemented();
    }
    public function deleteAction() {
        $this->_notImplemented();
    }
    public function insertProsalon() {
                $business = $this->_requestParam('business');
		$timestamp    = $this->_requestParam('timestamp');
		$campaign   = $this->_requestParam('campaign');
		$sent    = $this->_requestParam('sent');
		$read    = $this->_requestParam('read');
		$confirmed    = $this->_requestParam('confirmed');
		$appointmentDate    = $this->_requestParam('appointmentDate');
		
                
                $authObj = new Application_Model_Auth();
                $isAuthorized = $authObj->authenticate($this->_requestParam('username'),$this->_requestParam('password'));
                if($isAuthorized){
                    $sql = sprintf("CALL insert_prosalon('%s',$timestamp,'%s','%s','%s','%s',$appointmentDate)",
                    $this->escape($business),
                    $this->escape($campaign),
                    $this->escape($sent),
                    $this->escape($read),
                    $this->escape($confirmed)
                );
                    $rs = $this->query($sql);
                }
             if ($this->hasError()) {
	        $error = 'Unable to save data to prosalon tb';
                $this->setError($error, $error.' - '.$sql.': '.$this->getError());
	    }
    }// end of saveRrosalon
        
        
}

?>
