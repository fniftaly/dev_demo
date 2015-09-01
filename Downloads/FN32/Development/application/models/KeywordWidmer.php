<?php
class Application_Model_KeywordWidmer extends Application_Model_KeywordAbstract {
	
	/**
	 * App Text Label to load
	 * 
	 * @var string
	 * @access private
	 */
	private $replymessage;
	
	/**
	 * handle function.
	 * 
	 * @access public
	 */
	public function handle() {
		if ($this->_keyword instanceof Application_Model_Keyword) {
			$this->_keyword->usecustomresponse = true;
			$this->_keyword->response = null;
			
			// message that was sent
			$message = $this->_inbound->message;
			
			// See what Widmer keyword this is and set the appropriate settings
			if ($this->_keyword->keyword == 'kona') {
				$this->kona();
			}
			
			if ($this->_keyword->keyword == 'aloha') {
				$this->aloha();
			}
			
			if ($this->_keyword->keyword == 'redhook') {
				$this->redhook();
			}
			
			if ($this->_keyword->keyword == 'redhook30') {
				$this->redhook30();
			}
			
			// if they are already opted in
			if ($this->_keyword->hasSubscriberPhone($this->_inbound->device_address)) {
				// Now that the return messages are set for this keyword, we can parse the message body
				$replymessage = $this->checkValidReponse($message);
				
				// Get the replytext
				$replymessage = $this->_getActionKeywordReplyText($replymessage);
				
				// If we are on a kona keyword
				if ($this->_keyword->keyword == 'kona') {
					// inject a Kona Code into the replytext
					$code = $this->getKonaCode();
					// TODO: NEED TO HANDLE WHEN CODES RUN OUT!!!
					$replymessage = str_replace('%CODE%', $code, $replymessage);
					$this->_writeLog("Kona full reply message: $replymessage code: $code");
				}
				
				// Now set the replymessage after it has been constructed
				$this->_keyword->response = $replymessage;
			} else {
				// if they are not opted in
				$this->_keyword->response = $this->_getActionKeywordReplyText($this->auto);
			}
		}
	}
	
	private function kona() {
		$this->auto    = 'konaauto';
		$this->invalid = 'konanounderstand';
		$this->notold  = 'konanotoldenough';
		$this->nation  = 'konanationwide';
		$this->cali    = 'konacalres';		
	}	
	
	private function aloha() {
		$this->auto    = 'konaauto';
		$this->invalid = 'konanounderstand';
		$this->notold  = 'konanotoldenough';
		$this->nation  = 'alohanationwide';
		$this->cali    = 'alohacalres';
	}
	
	private function redhook() {
		$this->auto    = 'konaauto';
		$this->invalid = 'konanounderstand';
		$this->notold  = 'redhooknotoldenough';
		$this->nation  = 'redhooknationwide';
		$this->cali    = 'redhookcalres';
	}
	
	private function redhook30() {
		$this->auto    = 'konaauto';
		$this->invalid = 'konanounderstand';
		$this->notold  = 'redhooknotoldenough';
		$this->nation  = 'redhooknationwide';
		$this->cali    = 'redhookcalres';
	}
	
	private function checkValidReponse($message) {
		// Get the message parts
		$zipcode  = substr($message,0,5);
		$birthday = substr($message,6,10);
		$email    = substr($message,6,10);
		
		// check zip code
		if (!is_numeric($zipcode)) {
			return $this->invalid;
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
				return $this->notold;
			}
		} else {
			return $this->invalid;
		}
		
		if ($ResidentType == 'Non-CA') {
		    return $this->nation;
		}

		if ($ResidentType != 'Non-CA') {
		    return $this->cali;
		}
		
		return $replymessage;
	}
	
	/**
	 * Get a Kona Code and mark it as used in the database.
	 * 
	 * @access private
	 * @return string
	 */
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