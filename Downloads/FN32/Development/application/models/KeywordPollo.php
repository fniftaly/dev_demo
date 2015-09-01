<?php
class Application_Model_KeywordPollo extends Application_Model_KeywordAbstract {
	public function handle() {
		// Prepare our reply message
		//$message = new Application_Model_Message();
		
		// For all Pollo, if this is an already optedin subscriber, send back one thing
		if ($this->_keyword instanceof Application_Model_Keyword) {
			$this->_keyword->usecustomresponse = true;
			$this->_keyword->response = null;
			$this->_writeLog("KeywordPollo Model response cleared");
			if ($this->_keyword->hasSubscriberPhone($this->_inbound->device_address)) {
				$this->_keyword->response = $this->_getActionKeywordReplyText('pollonoplay');			
				$this->_writeLog("KeywordPollo Model pollonoplay");
			} else {
				// otherwise send back the standard message
				$this->_keyword->response = $this->_getActionKeywordReplyText('pollocanplay');
				$this->_writeLog("KeywordPollo Model pollocanplay");
			}
			//$this->_writeLog("KeywordPollo Model $this->_keyword->response");
		}
		$this->_writeLog(print_r($this->_keyword, 1));
		//$message->send($bodytext, array($this->_inbound->device_address));
	}
}