<?php
class Application_Model_KeywordQuit extends Application_Model_KeywordAbstract {
	public function handle() {
		$model = new Application_Model_KeywordStop($this->_keyword, $this->_sender);
		$model->handle();
	}
}