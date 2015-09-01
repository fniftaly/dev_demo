<?php
class Application_Model_KeywordJoegurt extends Application_Model_KeywordAbstract {
	public function handle() {
		if ($this->_keyword instanceof Application_Model_Keyword) {
			$this->_keyword->usecustomresponse = true;
            
			// if they are already opted in
			if ($this->_keyword->hasSubscriberPhone($this->_inbound->device_address)) {
				/*
				if (strtolower($this->_inbound->message) == 'hj26') {
					$this->_keyword->response = "50th Street HJ: You have already registered for HJ Mobile Club. Stay tuned for special offers coming your way! reply STOP 2quit";
				} else {
					$this->_keyword->response = "Bettendorf HJ: You have already registered for HJ Mobile Club. Stay tuned for special offers coming your way! reply STOP 2quit";
				}
				*/
				if ($this->_keyword->usealt) {
					$this->_keyword->response  = $this->_keyword->replyheader ? "{$this->_keyword->replyheader}:" : '';
					$this->_keyword->response .= "{$this->_keyword->replybodyalt}\n{$this->_keyword->replyfooter}";
					$this->_keyword->response = $this->injectRandomDate($this->_keyword->response);
				}
			} else {
                // Inject the random date into the message
				$this->_keyword->response = $this->injectRandomDate($this->_keyword->response);
			}
		}
	}
    
    public function getRandomDate($range = array(1,10), $increment = 'days') {
        $range = rand($range[0], $range[1]);
        echo "today + $range $increment";
        return date('m/d', strtotime("today + $range $increment"));
    }
    
    public function injectRandomDate($string) {
        return str_replace('%DATE%', $this->getRandomDate(), $string);
    }
}