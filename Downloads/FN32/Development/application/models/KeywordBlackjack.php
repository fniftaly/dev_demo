<?php
class Application_Model_KeywordBlackjack extends Application_Model_KeywordAbstract {
	public function handle() {
		if ($this->_keyword instanceof Application_Model_Keyword) {
			$this->_keyword->usecustomresponse = true;
			$this->_keyword->response = null;
			
			// message that was sent
			$message = $this->_inbound->message;
			
			// if they are already opted in
			if (!$this->_keyword->hasSubscriberPhone($this->_inbound->device_address)) {
				// Opt them in first
				$this->_keyword->addSubscriber($this->_inbound->device_address);
				
				// Now get the newly created subscriber object because we need an id
				$this->subscriber = $this->_keyword->hasSubscriberPhone($this->_inbound->device_address);
				
				// Then check if there are codes 
				if ($this->_hasCodes()) {
					// Then get their code and build their message
					$replymessage = $this->_getResponseMessage();
				} else {
					$replymessage = $this->_getActionKeywordReplyText('blackjacknomorecodes');
				}
				
				// Set the constructed reponse
				$this->_keyword->response = $replymessage;
				
			} else {
				// if they are not opted in
				$this->_keyword->response = $this->_getActionKeywordReplyText('blackjackalreadyplayed');
			}
		}
	}
	
	/**
	 * Gets the proper response message to send back to the opt in
	 * 
	 * @access protected
	 * @return string
	 */
	protected function _getResponseMessage() {
		if ($this->subscriber) {
			$subscriberid = $this->subscriber->id;
			// This proc gets a code, assigns it and marks it used
			$sql = "CALL luckyderby_add_code_to_subscriber($subscriberid)";
			$rs = $this->query($sql);
			
			if ($this->hasError()) {
				$this->setError($this->getError(), true);
				return $this->_getActionKeywordReplyText('blackjacknomorecodes');
			}
			
			if ($rs->hasRecords()) {
				if ($rs->code && $rs->prize) {
					$this->_writeLog("Black Jack Code: $rs->code");
					$this->_writeLog("Black Jack Prize: $rs->prize");
					$this->_writeLog("Black Jack Mobile: $rs->phonenumber");
					
					$replymessage = $this->_getActionKeywordReplyText('blackjack');
					$replymessage = str_replace(array('{{CODE}}', '{{PRIZE}}'), array($rs->code, $rs->prize), $replymessage);
					$this->_writeLog("Black Jack full reply message: $replymessage");
					return $replymessage;
				}
			}
		}
		
		return $this->_getActionKeywordReplyText('blackjacknomorecodes');
	}
	
	/**
	 * Checks to see if there are any usable codes
	 * 
	 * @access protected
	 * @return boolean
	 */
	protected function _hasCodes() {
		$sql = "CALL luckyderby_has_codes()";
		$rs = $this->query($sql);
		
		if ($this->hasError()) {
			$this->setError($this->getError(), true);
			return false;
		}
		
		if ($rs->hasRecords()) {
			return $rs->hascodes;
		}
		
		return false;
	}
}