<?php
class Application_Model_KeywordKona extends Application_Model_KeywordAbstract {
	public function handle() {
		if ($this->_keyword instanceof Application_Model_Keyword) {
			$this->_keyword->usecustomresponse = true;
			$this->_keyword->response = null;
			
			// message that was sent
			$message = $this->_inbound->message;
			
			// if they are already opted in
			if ($this->_keyword->hasSubscriberPhone($this->_inbound->device_address)) {
				// validate the users response to see what reply to send
				$replymessage = $this->checkValidReponse($message);
				
				$replymessage = $this->_getActionKeywordReplyText($replymessage);
				
				// If the response was valid inject a kona code
				if ($this->validresponse) {
					// now inject a Kona Code into the message if the correct text is found
					$code = $this->getKonaCode();
					
					// TODO: NEED TO HANDLE WHEN CODES RUN OUT!!!
					$replymessage = str_replace('%CODE%', $code, $replymessage);
					$this->_writeLog("Kona full reply message: $replymessage code: $code");
				}
				
				// Set the constructed reponse
				$this->_keyword->response = $replymessage;
				
			} else {
				// if they are not opted in
				$this->_keyword->response = $this->_getActionKeywordReplyText('konaauto');
			}
		}
	}
	
	private $validresponse = false;
	
	function checkValidReponse($message) {
		// Get the message parts
		$zipcode  = substr($message,0,5);
		$birthday = substr($message,6,10);
		$email    = substr($message,6,10);
		
		// check zip code
		if (!is_numeric($zipcode)) {
			return 'konanounderstand';
		}
		if (in_array(substr($zipcode,0,2), array('90','91','92','93','94','95','96'))) {
			$ResidentType = 'CA';
		} else {
			$ResidentType = 'Non-CA';
		}
		
		// check birth date
		$birthday = str_replace('-', '/', $birthday);
		$bparts = explode('/', $birthday);
		
        foreach ($bparts as $i => $part) {
            $bparts[$i] = (int) $part;
        }
        
		if (is_array($bparts) && count($bparts) == 3 && checkdate($bparts[0],$bparts[1],$bparts[2])) {
			// See if they are older than 21
			$birthday = strtotime($birthday); 
			$now      = time(); 
			$diff     = $now - $birthday; 
			$years    = $diff / 365 / 24 / 60 / 60;
			
			if ($years < 21) {
				return 'konanotoldenough';
			}
		} else {
			return 'konanounderstand';
		}
		
		if ($ResidentType == 'Non-CA') {
		    $this->validresponse = true;
		    return 'konanationwide';
		}

		if ($ResidentType != 'Non-CA') {
		    $this->validresponse = true;
		    return 'konacalres';
		}
		
		return $replymessage;
	}
	
	private function getKonaCode() {
		$sql = "CALL kona_get_code()";
		$rs = $this->query($sql);
		
		if ($this->hasError()) {
			$this->setError($this->getError(), true);
		}
		
		if ($rs->hasRecords()) {
			if ($rs->code) {
				$this->_writeLog("Kona Code: $rs->code");
				return $rs->code;
			}
		}
		
		return '';
	}
}