<?php
class Application_Model_KeywordInfo extends Application_Model_KeywordAbstract {
	public function handle() {
		// Prepare our reply message
		$message = new Application_Model_Message();
		$bodytext = $this->_getActionKeywordReplyText('info');
		$message->send($bodytext, array($this->_inbound->device_address));
	}
}