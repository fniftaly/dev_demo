<?php
class Application_Model_KeywordStop extends Application_Model_KeywordAbstract {
	public function handle() {
		$this->optOut();
		$this->_inbound->optedOut = true;
	}
}