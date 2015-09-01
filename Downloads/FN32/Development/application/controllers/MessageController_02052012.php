<?php
class Api_MessageController extends ApiControllerAbstract {
	
	/**
	 * Use to get last message sent?
	 * 
	 * @access public
	 * @return void
	 */
	public function indexAction() {
            
		$this->_notImplemented();
	}
	
	/**
	 * Special use case here. Wiggins wants to send messages via GET.
	 * 
	 * @access public
	 * @return void
	 */
	public function getAction() {
		//$this->setOutputParam('status', true);
    	//$this->setOutputParam('message', 'Get Message sender');
		
		$this->sendMessage();
	}
	
	/**
	 * Sends a message
	 *
	 * This method accepts three parameters:
	 *  - Message
	 *  - Recipient(s)
	 *  - Folder
	 *
	 * Once all three have been validated, will attempt to send a message to the
	 * target recipients.
	 *
	 */
	public function postAction() {
		$this->sendMessage();
	}
	
	/**
	 * Is there any need to "update" a message? Maybe scheduled messages?
	 * 
	 * @access public
	 * @return void
	 */
	public function putAction() {
		$this->_notImplemented();
	}
	
	/**
	 * Don't think there will be a delete action for messages. Unless
	 * maybe we use it to deactivate campaign's or scheduled messages?
	 * 
	 * @access public
	 * @return void
	 */
	public function deleteAction() {
		$this->_notImplemented();
	}
	
	/**
	 * Created a sendMessage method that will do everything the post controller would
	 * because we have a user who wants to send a message via GET and our API does this
	 * via POST. I tried using _forward('post'), but it was doubling up the JSON response
	 * body for some reason. So now post and get just call this method to do the same thing.
	 * 
	 * @access private
	 */
	private function sendMessage() {
		$valid = true;
		
		echo $recipients  = $this->_requestParam('recipients');exit;
		$subjecttext = $this->_requestParam('subject');
		$bodytext    = $this->_requestParam('message');
		$sendtime   = $this->_requestParam('sendtime');
		$timezone    = $this->_requestParam('timezone');
		
		// Cleanup send time
		if ($sendtime) {
			$tparts = str_split($sendtime, 2);
			$sendtime = $tparts[0].$tparts[1] . '-' . $tparts[2] . '-' . $tparts[3] . ' ' . $tparts[4] . ':' . $tparts[5] . ':00';
		}
		
		// Not using this at the moment, so don't want anyone to access it
		//$folder = $this->_request->getParam('id');
		//$loadby = $this->_request->getParam('field');
		$folder = false;
		$loadby = false;
		
		// Folder is optional (at the moment...)
		if ($folder) {
		    $folder = new Application_Model_Folder($this->apiuser, $folder, $loadby);
		    
		    if ($folder->isValid()) {
		        // Get subscribers in this folder
    			$subscribers = $folder->getSubscribers();
    			
    			// TODO: Come up with a better way to pull phone #'s out of a folder's subscriber list
    			foreach ($subscribers as $subscriber) {
    			    $recipients[] = $subscriber['phonenumber'];
    			}
		    } else {
		        $valid = false;
				$this->setError($folder->getError(), 500);
		    }
		}
		
		// Validate and send
		if ($valid) {
    		if ($recipients) {
    			if ($bodytext) {
    				$message     = new Application_Model_Message($this->apiuser);
    				$bodytext    = urldecode($bodytext);
    				$subjecttext = urldecode($subjecttext);
    				
    				// Build the message with a subject if there is one.
    				$msg = $subjecttext ? $subjecttext.': '.$bodytext : $bodytext;
    				
    				// Queue it up for delivery
    				$return = $message->queue($msg, $recipients, $sendtime, $timezone);
    				
    				if ($return) {
    					$this->setOutputParam('status', true);
    					$this->setOutputParam('message', 'Message successfully sent.');
    				} else {
    					$this->setError($message->getError(), 500);
    				}
    			} else {
    				$this->setError('Message is required.', 500);
    			}
    		} else {
    			$this->setError('At least one recipient is required.', 500);
    		}
		}
	}
}
