<?php
abstract class Application_Model_KeywordAbstract extends Application_Model_Abstract {
	/**
	 * The log file for model logging
	 * 
	 * @var string
	 */
	public $logFile = '/tmp/keywordhandler.log';
	
	/**
	 * The SMS Inbound model
	 * 
	 * @var Application_Model_Smsinbound
	 */
	protected $_inbound;
	
	/**
	 * The keyword model that called this model
	 * 
	 * @var Application_Model_Keyword
	 */
	protected $_keyword;
	
	/**
	 * Constructor, simply sets the keyword model and the sender into this model
	 * 
	 * @param Application_Model_Smsinbound $inbound
	 */
	public function __construct(Application_Model_Smsinbound $inbound) {
		$this->_inbound = $inbound;
	}
	
	/**
	 * Sets the keyword model into this model
	 * 
	 * @param Application_Model_Keyword $keyword
	 */
	public function setKeyword(Application_Model_Keyword $keyword) {
		$this->_keyword = $keyword;
	}
	
	/**
	 * Opts a subscriber out, used by STOP, QUIT and END
	 * 
	 */
	public function optOut() {
	
	//echo "I am here in keyword abstract";
		// Check if this is a single folder subscriber first...
		$subscriber = new Application_Model_Subscriber();
		$folders = $subscriber->getContainingFolders($this->_inbound->device_address, false);
		
		if ($folders && is_array($folders) && count($folders) == 1) {
			// Unsubsribe from this folder
			$this->optOutSubscriber($folders[0]->folderid, $this->_inbound->device_address);
		} else {
			$outbound = $this->_inbound->getReplytoMessage();
			//echo $outbound->folderid;
			//die;
			if ($outbound && $outbound->folderid) {
				//$user = Zend_Registry::isRegistered('user') ? Zend_Registry::get('user') : new Application_Model_User($outbound->createuser);
				//$folder = new Application_Model_Folder($user, $outbound->folderid);
				$this->optOutSubscriber($outbound->folderid, $this->_inbound->device_address);
			}
			//$this->_keyword->deleteSubscriber($this->_sender);
		}
	} 
	
	/**
     * Opts a subscriber out of a folder
     * 
     * @param string $phone
     * @return boolean
     */
    public function optOutSubscriber($folder, $phone) {
		 $sql = "CALL folder_delete_subscriber($folder, $phone)";
		$this->_writeLog("Opt out: $sql");
		$rs = $this->query($sql);
		return $rs->success > 0;
    } 
    
    /**
	 * Set the response to send back to the user.
	 * 
	 * @access protected
	 * @param string $response
	 * @return void
	 */
	protected function _setResponse($response) {
		$this->_keyword->response = $response;
	}
	
	/**
	 * Abstract method defined by the children that will actually do something
	 */
	abstract public function handle();
	
	/**
	 * Gets the reply message for action keywords like HELP and INFO
	 * 
	 * @access protected
	 * @return string
	 */
	protected function _getActionKeywordReplyText($action = null) {
		if ($action === null) {
			$action = strtolower(str_replace('Application_Model_Keyword', '', get_class($this)));
		}
		$sql = "CALL keyword_get_actionreply('$action')";
		$rs = $this->query($sql);
		
		if ($this->hasError()) {
			$this->setError($this->getError(), true);
			$this->_writeLog("Keyword Abstract actionreply error: $this->getError()");
		}
		
		if ($rs->hasRecords()) {
			//$row = $rs->fetchObject();
			$this->_writeLog("Keyword Abstract actionreply: $rs->replytext");
			if ($rs->replytext) {
				return $rs->replytext;
			}
		}
		$this->_writeLog("Keyword Abstract actionreply was null");
		return null;
	}
	
	public function shutdown() {
		$this->_writeLog("Model Name: " . get_class($this) . "\nModel Data:\n" . print_r($this->_modelData, 1));
	}
}