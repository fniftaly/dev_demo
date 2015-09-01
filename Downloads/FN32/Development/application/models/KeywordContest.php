<?php
class Application_Model_KeywordContest extends Application_Model_KeywordAbstract {
	
	/**
	 * Subscriber object for the mobile number that texted in.
	 * 
	 * @var stdClass
	 * @access public
	 */
	public $subscriber;
	
	/**
	 * Message that was sent by the subscriber
	 * 
	 * @var mixed
	 * @access private
	 */
	private $_message;
	
	/**
	 * Contest Model this keyword is using.
	 * 
	 * @var Application_Model_Contest
	 * @access private
	 */
	private $_contest;
	
	/**
	 * Loads the contest model this keyword is using.
	 * Plays the contest.
	 * 
	 * @access public
	 * @return void
	 */
	public function handle() {
		// message that was sent by the subscriber
		$this->_message = $this->_inbound->message;
		
		// Make sure our keyword loaded
		if ($this->_keyword instanceof Application_Model_Keyword) {
			$this->_writeLog('Keyword: ',print_r($this->_keyword, 1));
			
			// Get the contest attached to this keyword
			$this->_contest = new Application_Model_Contest($this->_keyword->contestid);
			
			// Make sure our contest loaded
			if ($this->_contest instanceof Application_Model_Contest && $this->_contest->getId()) {
				$this->_writeLog('Contest: '.print_r($this->_contest, 1));
				// Check if there is a contest action on the keyword and, if 
				// there is, handle that
				if ($this->_keyword->hasContestAction()) {
					$action = $this->_keyword->getContestAction();
					if (is_numeric($action)) {
						//Pick some winners
						$winners = $this->_contest->pickWinners($action);
						if ($winners) {
							$this->_keyword->response = null;
							
							$first = true;
							
							foreach ($winners as $winner) {
								if ($first) {
									$message = new Application_Model_Message(new Application_Model_User($winner->userid));
									$message->body = $winner->response;
									$message->keywordid  = $winner->keywordid;
									$message->folderid = $winner->folderid;
								}
								
								$message->recipients[$winner->id] = $winner->phonenumber;
							}
							
							if (isset($message) && $message instanceof Application_Model_Message) {
								$out = new Application_Model_Smsoutbound($message);
								return $out->sendNow();
							}
						}
					} else {
						// Either start or stop
						if ($action == 'start') {
							if ($this->_contest->startContest()) {
								$this->_setResponse(str_replace('{{KEYWORD}}', $this->_keyword->keyword, $this->_getActionKeywordReplyText('conteststarted')));
							} else {
								$this->_setResponse($this->_contest->error);
							}
						} else {
							if ($this->_contest->endContest()) {
								$this->_setResponse(str_replace('{{KEYWORD}}', $this->_keyword->keyword, $this->_getActionKeywordReplyText('contestended')));
							} else {
								$this->_setResponse($this->_contest->error);
							}
						}
					}
				} else {
					
					// Not going to user the standard autoresponder
					$this->_keyword->usecustomresponse = true;
					// Default the standard autoresponder
					$this->_keyword->response = null;
					
					// See if this mobile number has already played the contest
					// Returns a subscriber object or false
					$this->subscriber = $this->_keyword->hasSubscriberPhone($this->_inbound->device_address);
					
					// If we don't have a subscriber id for this mobile number, create one
					if ($this->subscriber == false) {
						$this->_keyword->addSubscriber($this->_inbound->device_address);
						// Now get the newly created subscriber object
						$this->subscriber = $this->_keyword->hasSubscriberPhone($this->_inbound->device_address);
					}
					
					// Play the contest
					$result = $this->_contest->play($this->subscriber->id, $this->_keyword->id);
					
					// Play method will handle autoresponse, we are all done!
					$this->_setResponse($result);
	                $this->_writeLog('Contest Response is: '.$result);
				}
			} else {
				$this->_writeLog('Could not load contest model for contest: '.$this->_keyword->contestid.', Keyword: '.$this->_keyword->id);
			}
		}
	}
	
	
}