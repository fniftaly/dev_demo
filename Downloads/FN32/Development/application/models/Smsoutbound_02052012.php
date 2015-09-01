<?php
class Application_Model_Smsoutbound extends Application_Model_Smsabstract {
	/**
	 * The log file for model logging
	 * 
	 * @var string
	 */
	public $logFile = '/tmp/messagelog.log';
	
	/**
	 * Message Object.
	 * 
	 * TODO: I FEEL LIKE WE SHOULD PASS AN ENTIRE MESSAGE OBJECT TO THIS
	 * CLASS INSTEAD OF JUST PASSING PARAMS. WHAT DO YOU THINK?
	 * 
	 * @access public
	 * @var Application_Model_Message
	 */
	public $message;
	
	/**
	 * The message to send
	 * 
	 * @access protected
	 * @var string
	 */
	protected $_message = "";
	
	/**
	 * The recipients list
	 * 
	 * @access protected
	 * @var Array
	 */
	protected $_recipients = array();
	
	/**
	 * The time to schedule this send for
	 * 
	 * @access protected
	 * @var string
	 */
	protected $_sendtime = null;
	
	/**
	 * The maximum number of recipients that can be sent a message at one time.
	 *
	 * @access protected
	 * @var int
	 */
	protected $_queueLimit = 100;
	
	/**
	 * Object constructor, sets the recipient list and message if there are one
	 *
	 * @access public
	 * @param Application_Model_Message $message The message object to send
	 */
	public function __construct($message) {
		$this->_getDbh();
        
        // Leaving these for now in hopes to not break anything...
		$this->setMessage($message->body);
		$this->setRecipients($message->recipients);
		$this->setSendtime($message->sendtime);
		
		// Set the message object for access from all methods.
		$this->message = $message;
	}
	
	/**
	 * Sets a timestamp as a DB readable timestamp
	 * 
	 * @access public
	 * @param int|string $sendtime
	 */
	public function setSendtime($sendtime) {
		// Only set a send time if there is one to set
		if (!empty($sendtime)) {
			// If it isn't a number, assume a date string and timestamp it
			if (!is_numeric($sendtime)) {
				$sendtime = strtotime($sendtime);
			}
			
			// Make it database ready
			$this->_sendtime = date('Y-m-d H:i:s', $sendtime);
		} 
	}
	
	/**
	 * Gets the timestamp for sending
	 * 
	 * @access public
	 * @return string
	 */
	public function getSendtime() {
		return $this->_sendtime;
	}
	
	/**
	 * Gets the message to send
	 * 
	 * @access public
	 * @return string The message to send
	 */
	public function getMessage() {
		return $this->_message;
	}

	/**
	 * Sets the message to send
	 * 
	 * @access public
	 * @param string $message The message to send
	 */
	public function setMessage($message) {
		$this->_message = $message;
	}
	
	/**
	 * Gets the recipient list
	 * 
	 * @access public
	 * @return array List of recipients for this message
	 */
	public function getRecipients() {
		return $this->_recipients;
	}
	
	/**
	 * Sets the recipients list
	 *
	 * To add recipients to an existing list, use the {@see addRecipient()} method
	 * 
	 * @access public
	 * @param array $recipients List of recipients
	 */
	public function setRecipients(Array $recipients) {
		$this->_recipients = $recipients;
	}
	
	/**
	 * Adds a recipient to the recipient list
	 *
	 * @access public
	 * @param string $recipient A recipient phone number
	 */
	public function addRecipient($recipient, $subscriberid = 0) {
		if (is_array($recipient)) {
			$this->_recipients = array_merge($this->_recipients, $recipient);
		} else {
			if ($subscriberid) {
				$this->_recipients[$subscriberid] = $recipient;
			}
		}
	}
	
	/**
	 * Removes a recipient from the recipient list
	 *
	 * @access public
	 * @param string $recipient A recipient phone number
	 */
	public function removeRecipient($recipient) {
		foreach (array_keys($this->recipients, $recipient) as $key) {
			unset($this->_recipients[$key]);
		}
	}
	
	/**
	 * Gets the current queue limit
	 * 
	 * @access public
	 * @return int The current queue limit
	 */
	public function getQueueLimit() {
		return $this->_queueLimit;
	}
	
	/**
	 * Sets the queue limit which controls how many recipients can be sent a
	 * message at one time
	 * 
	 * @access public
	 * @param int $queueLimit The limit to how many recipients can receive a
	 *                        message at once
	 */
	public function setQueueLimit($queueLimit) {
		$this->_queueLimit = $queueLimit;
	}
	
	/**
	 * Queues a message 
	 * 
	 * @param string $message The message to send
	 * @param array $recipients The recipient list
	 */
	public function queue($message = '', array $recipients = array()) {
		// Validate our message
		if (empty($message)) {
			if (empty($this->_message)) {
				$this->error = 'No message body found';
				return false;
			}
			
			$message = $this->_message;
		}
		
		// Make sure the message is a string
		$message = "$message";
		
		// Validate our recipient list
		if (empty($recipients)) {
			if (empty($this->_recipients)) {
				$this->error = 'No recipients found';
				return false;
			}
			
			$recipients = $this->_recipients;
		}
		
		// Handle timezones
		if (empty($this->message->timezone)) {
			$timezone = 'NULL';
		} else {
			$timezone = "'$this->message->timezone'";
		}
		
		// Make the list an array
		$recipients = (array) $recipients;
		
		// Save the message and get its id
		$gatewaymessageid = $this->saveOutbound();
		
		// Set our counters
		$queued = 0;
		$rcount = count($recipients);
		
		// Now add each of the recipients... this could take a while for large sends
		foreach ($recipients as $_r) {
			$sql = "CALL message_log_queue($gatewaymessageid, '$_r', {$this->message->reportingkey1}, '{$this->message->reportingkey2}', '$this->_sendtime', $timezone)";
			$rs = $this->query($sql);
			if (!$this->hasError()) {
				if ($rs->success) {
					$queued++;
				}
			}
		}
		
		// Send back our return
		return $queued == $rcount;
	}
	
	/**
	 * Sends a message
	 *
	 * Send a message through the API gateway
	 *
	 * @access public
	 * @param string $message The message to send
	 * @param array $recipients A list of recipients (can be one) as an array
	 * @return boolean True on success, false on failure
	 */
	public function sendQueued($message = '', Array $recipients = array()) {
		// Validate our username and password
		if (empty($this->_username) || empty($this->_password)) {
			$this->error = 'A username and password are required';
			return false;
		}
		
		// Validate our message
		if (empty($message)) {
			if (empty($this->_message)) {
				$this->error = 'No message body found';
				return false;
			}
			
			$message = $this->_message;
		}
		
		// Make sure the message is a string
		$message = "$message";
		
		// Validate our recipient list
		if (empty($recipients)) {
			if (empty($this->_recipients)) {
				$this->error = 'No recipients found';
				return false;
			}
			
			$recipients = $this->_recipients;
		}
		
		// Make the list an array
		$recipients = (array) $recipients;
		
		// Get our count of recipients
		$recipientsCount = count($recipients);
		
		// Set up our number of fetched recipients for sending
		$recipientsFetched = 0;
		
		// Set up the message to send
		$sendMessage = urlencode($message);
		
		// Get our sent count for handling success
		$sent = 0;
		
		// While we still have recipients yet to fetch, fetch and send
		while ($recipientsFetched < $recipientsCount) {
			// Set our group of people to send to
			$sendTo = array();
			
			// Set our group fetch count
			$queueFetched = 0;
			
			// Start fetching recipients as long as its within the limit and we
			// have something to fetch
			while ($queueFetched < $this->_queueLimit && ($subscriber = key($recipients)) !== null) {
				// Add to our send to list
				$sendTo[$subscriber] = $recipients[$subscriber];
				
				// Move the array pointer to the next recipient
				next($recipients);
				
				// Increment our queue counter
				$queueFetched++;
				
				// Increment our recipients counter
				$recipientsFetched++;
			}
			
			// Build up the request querystring
			$uri = $this->_apiUrl . '?reply_to=' . $this->_shortCode . '&recipient=' . implode('&recipient=', $sendTo) . '&body=' . $sendMessage . '&reporting_key1=' . $this->message->reportingkey1 . '&reporting_key2=' . $this->message->reportingkey2;
			
			// Now handle sending, start by opening a cURL handle
			$ch = curl_init($uri);
			
			// Now set some params, start with username and password
			curl_setopt($ch, CURLOPT_USERPWD, $this->_username . ':' . $this->_password);
			
			// Turn off header output in the response
			curl_setopt($ch, CURLOPT_HEADER, false);
			
			// Disable SSL peer verification
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			
			// Capture the output instead of echoing it
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			
			// Execute our request
			$rs = curl_exec($ch);
			
			// Close the cURL handle
			curl_close($ch);
			
			// Now lets inspect it and see if we have what we need
			$response = simplexml_load_string($rs);
			
			// Type cast the response code and description for use
			$code = intval($response->code);
			$description = "$response->description";
			
			//Success is simple... is the response code 100
			if ($code == 100) {
				// Create the recipient object
				$save = new stdClass;
				$save->gatewaymessageid = $this->message->id;
				$save->reportingkey1 = $this->message->reportingkey1;
				$save->reportingkey2 = $this->message->reportingkey2;
				
				// Before we return true we need to write the recipients and messageids
				foreach ($response->recipients->recipient as $recipient) {
					// props are wrapped in double quotes to cast to string
					$save->mobilenumber = "$recipient->mobileNumber";
					$save->messageid = "$recipient->messageId";
					// Log that this message was sent to this recipient
					$this->_writeStatus($save);
				}
				
				// Free up memory - this could be key for large sends
				$save = null;
				
				// Add our queued fetch count to the sent count
				$sent += $queueFetched;
			} else {
				// Something has gone awry, let's get some more information
				$this->error .= "Error code $code: " . Application_Model_Smsoutbounderror::getError($code, $description) . "\n";
			}
		}
		
		// Send back our return
		return $sent == $recipientsCount;
	}
	
	/**
	 * Sends a message
	 *
	 * This can be used to send a message without setting any params into the model
	 *
	 * @access public
	 * @param string $message The message to send
	 * @param array $recipients A list of recipients (can be one) as an array
	 * @return boolean True on success, false on failure
	 */
	public function send() {
		$this->_writeLog("Sender->Send() called...");
		// Validate our username and password
		if (empty($this->_username) || empty($this->_password)) {
			$this->error = 'A username and password are required';
			return false;
		}
		
		// Validate our message
		if ($this->message && $this->message instanceof Application_Model_Message && $this->message->id && $this->message->body && $this->message->recipients && $this->message->reportingkey1 && $this->message->reportingkey2) {
			$this->_writeLog("Sender message components checked out");
			// Get our count of recipients
			$m = $this->message;
			$this->_writeLog("Message fetched into var");
			$r = $m->recipients;
			$this->_writeLog("Recipients pulled from the message as " . print_r($r, 1));
			//$recipientsCount = count($this->message->recipients);
			$recipientsCount = count($r);
			$this->_writeLog("Recipient count: $recipientsCount");
			
			// Set up our number of fetched recipients for sending
			$recipientsFetched = 0;
            
            // Make sure the message is a string
            $message = "{$this->message->body}";
			
			// Set up the message to send
			$sendMessage = urlencode($message);
			
			// Get our sent count for handling success
			$sent = 0;
			$this->_writeLog("Sender about to begin send loop to $recipientsCount recipients...");
			// While we still have recipients yet to fetch, fetch and send
			while ($recipientsFetched < $recipientsCount) {
				// Set our group of people to send to
				$sendTo = array();
				
				// Set our group fetch count
				$queueFetched = 0;
				
				// Start fetching recipients as long as its within the limit and we
				// have something to fetch
				while ($queueFetched < $this->_queueLimit && ($subscriber = key($this->message->recipients)) !== null) {
					// Add to our send to list
					$sendTo[$subscriber] = $this->message->recipients[$subscriber];
					
					// Move the array pointer to the next recipient
					next($this->message->recipients);
					
					// Increment our queue counter
					$queueFetched++;
					
					// Increment our recipients counter
					$recipientsFetched++;
				}
				
				$this->_writeLog("Sender preparing API call");
				
				// Build up the request querystring
				$uri = $this->_apiUrl . '?reply_to=' . $this->_shortCode . '&recipient=' . implode('&recipient=', $sendTo) . '&body=' . $sendMessage . '&reporting_key1=' . $this->message->reportingkey1 . '&reporting_key2=' . $this->message->reportingkey2;
				
				// Now handle sending, start by opening a cURL handle
				$ch = curl_init($uri);
				
				// Now set some params, start with username and password
				curl_setopt($ch, CURLOPT_USERPWD, $this->_username . ':' . $this->_password);
				
				// Turn off header output in the response
				curl_setopt($ch, CURLOPT_HEADER, false);
				
				// Disable SSL peer verification
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				
				// Capture the output instead of echoing it
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				$this->_writeLog("Sender making API call...");
				// Execute our request
				$rs = curl_exec($ch);
				
				// Close the cURL handle
				curl_close($ch);
				
				// Now lets inspect it and see if we have what we need
				$response = simplexml_load_string($rs);
				
				// Type cast the response code and description for use
				$code = intval($response->code);
				$description = "$response->description";
				$this->_writeLog("Sender handling response with code $code");
				//Success is simple... is the response code 100
				if ($code == 100) {
					// Create the recipient object
					$save = new stdClass;
					$save->gatewaymessageid = $this->message->id;
					$save->reportingkey1 = $this->message->reportingkey1;
					$save->reportingkey2 = $this->message->reportingkey2;
					
					// Before we return true we need to write the recipients and messageids
					foreach ($response->recipients->recipient as $recipient) {
						// props are wrapped in double quotes to cast to string
						$save->subscriberid = array_search("$recipient->mobileNumber", $this->message->recipients);
						$save->mobilenumber = "$recipient->mobileNumber";
						$save->messageid = "$recipient->messageId";
						// Log that this message was sent to this recipient
						$this->markRecipientSent($save);
					}
					
					// Free up memory - this could be key for large sends
					unset($save);
					
					// Add our queued fetch count to the sent count
					$sent += $queueFetched;
				} else {
					// Something has gone awry, let's get some more information
					$this->error .= "Error code $code: " . Application_Model_Smsoutbounderror::getError($code, $description) . "\n";
				}
			}
			$this->_writeLog("Sender done");
			// Send back our return
			return $sent == $recipientsCount;
		}
		
		$this->setError('Missing information in the message');
		return false;
	}
	
	/**
	 * Log that an outbound message was sent to a recipient.
	 * 
	 * @access public
	 * @param stdClass $message
	 * @return bool
	 */
	public function saveOutboundRecipient(stdClass $message) {
		if (empty($message->gatewaymessageid)) {
			$message->gatewaymessageid = 0;
		}
		
	    $sql = "CALL message_log_recipient('$message->messageid',$message->subscriberid,$message->gatewaymessageid,'$message->mobilenumber', $message->reportingkey1,'$message->reportingkey2')";
	    
	    $rs = $this->query($sql);
	    
	    if ($this->hasError()) {
            $error = 'Unable to log message-sent ['.$message->messageid.'] for recipient ['.$message->subscriberid.']';
	        $this->setError($error, $error.' - '.$sql.': '.$this->getError());
	        return false;
	    }
	    
	    return true;
	}
    
    /**
	 * Sends a message
	 *
	 * This can be used to send a message without setting any params into the model
	 *
	 * @access public
	 * @param string $message The message to send
	 * @param array $recipients A list of recipients (can be one) as an array
	 * @return boolean True on success, false on failure
	 */
	public function sendNow($message = '', Array $recipients = array()) {
		// Validate our username and password
		if (empty($this->_username) || empty($this->_password)) {
			$this->setError('A username and password are required', true);
			return false;
		}
		
		// Validate our message
		if (empty($message)) {
			if (empty($this->_message)) {
				$this->setError('No message body found', true);
				return false;
			}
			
			$message = $this->_message;
		}
		
		// Make sure the message is a string
		$message = "$message";
		
		// Validate our recipient list
		if (empty($recipients)) {
			if (empty($this->_recipients)) {
				$this->setError('No recipients found', true);
				return false;
			}
			
			$recipients = $this->_recipients;
		}
		
		// Make the list an array
		$recipients = (array) $recipients;
		
		// Get our count of recipients
		$recipientsCount = count($recipients);
		
		// Set up our number of fetched recipients for sending
		$recipientsFetched = 0;
		
		// Set up the message to send
		$sendMessage = urlencode($message);
		
		// Get our sent count for handling success
		$sent = 0;
        
        // Error container
        $error = null;
		
		// While we still have recipients yet to fetch, fetch and send
		while ($recipientsFetched < $recipientsCount) {
			// Set our group of people to send to
			$sendTo = array();
			
			// Set our group fetch count
			$queueFetched = 0;
			
			// Start fetching recipients as long as its within the limit and we
			// have something to fetch
			while ($queueFetched < $this->_queueLimit && ($subscriber = key($recipients)) !== null) {
				// Add to our send to list
				$sendTo[$subscriber] = $recipients[$subscriber];
				
				// Move the array pointer to the next recipient
				next($recipients);
				
				// Increment our queue counter
				$queueFetched++;
				
				// Increment our recipients counter
				$recipientsFetched++;
			}
			
			// Build up the request querystring
			$uri = $this->_apiUrl . '?reply_to=' . $this->_shortCode . '&recipient=' . implode('&recipient=', $sendTo) . '&body=' . $sendMessage . '&reporting_key1=' . $this->message->reportingkey1 . '&reporting_key2=' . $this->message->reportingkey2;
			
			// Now handle sending, start by opening a cURL handle
			$ch = curl_init($uri);
			
			// Now set some params, start with username and password
			curl_setopt($ch, CURLOPT_USERPWD, $this->_username . ':' . $this->_password);
			
			// Turn off header output in the response
			curl_setopt($ch, CURLOPT_HEADER, false);
			
			// Disable SSL peer verification
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			
			// Capture the output instead of echoing it
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			
			// Execute our request
			$rs = curl_exec($ch);
			
			// Close the cURL handle
			curl_close($ch);
			
			// Now lets inspect it and see if we have what we need
			$response = simplexml_load_string($rs);
			
			// Type cast the response code and description for use
			$code = intval($response->code);
			$description = "$response->description";
			
			//Success is simple... is the response code 100
			if ($code == 100) {
				// Save the outbound message to the DB
				// TODO: Need some more error checking and redundancy here in case the message
				// is not saved for some reason.
				$gatewaymessageid = $this->saveOutbound();
				
				// Create the recipient object
				$save = new stdClass;
				$save->gatewaymessageid = $gatewaymessageid;
				$save->reportingkey1 = $this->message->reportingkey1;
				$save->reportingkey2 = $this->message->reportingkey2;
				
				// Before we return true we need to write the recipients and messageids
				foreach ($response->recipients->recipient as $recipient) {
					// props are wrapped in double quotes to cast to string
					$save->subscriberid = array_search("$recipient->mobileNumber", $recipients);
					$save->mobilenumber = "$recipient->mobileNumber";
					$save->messageid = "$recipient->messageId";
					// Log that this message was sent to this recipient
					$this->saveOutboundRecipient($save);
				}
				
				// Free up memory - this could be key for large sends
				unset($msg);
				unset($save);
				
				// Add our queued fetch count to the sent count
				$sent += $queueFetched;
			} else {
				// Something has gone awry, let's get some more information
				$error .= "Error code $code: " . Application_Model_Smsoutbounderror::getError($code, $description) . "\n";
			}
		}
        
        if ($error) {
            $this->setError($error, true);
        }
		
		// Send back our return
		return $sent == $recipientsCount;
	}
	
	public function markRecipientSent(stdClass $save) {
		if (isset($save->gatewaymessageid) && isset($save->reportingkey1) && isset($save->reportingkey2) && isset($save->mobilenumber) && isset($save->messageid)) {
			$subsciber = isset($save->subscriberid) ? $save->subscriberid : 0;
			$sql = "CALL message_log_recipient_send('$save->gatewaymessageid', '$save->mobilenumber', '$save->reportingkey1', '$save->reportingkey2', '$save->messageid')";	
			$rs = $this->query($sql);
			if (!$this->hasError()) {
				return $rs->success;
			}
		}
		
		$this->setError('Missing components of the message');
		return false;
	}
	
	/**
	 * Log an outbound message to the database
	 * 
	 * @access public
	 * @return bool Log success or failure
	 */
	public function saveOutbound() {
		if (substr($this->message->replybody, 0, 2) == ': ') {
			error_log("REPLY BODY MISSING: KEY1: $this->message->reportingkey1 KEY2: $this->message->reportingkey2");
			return false;
		}
		
	    //$user          = isset($this->message->user) ? $this->message->user->getId() : 0;
            $user          = isset($this->message->user) ? $this->message->user->getId() : 1; 
            // previously it was 0, so when we tried to queue any phone number through cron, it was not working. So I made it to 1.
	    $replybody     = $this->saveNull($this->message->replybody);
	    $body          = $this->saveNull($this->message->body);
	    $campaignid    = $this->saveNull($this->message->campaignid);
	    $scheduleid    = $this->saveNull($this->message->scheduleid);
	    $sponsorid     = $this->saveNull($this->message->sponsorid);
	    $linkid        = $this->saveNull($this->message->linkid);
	    $locationid    = empty($this->message->keywordid) ? 0 : (int) $this->message->keywordid;
		$reportingkey1 = $this->message->reportingkey1;
		$reportingkey2 = $this->message->reportingkey2;
	    $keywordid     = empty($this->message->keywordid) ? 0 : (int) $this->message->keywordid;
	    $folderid      = empty($this->message->folderid) ? 0 : (int) $this->message->folderid;
        $description   = $this->saveNull($this->message->description);
	    
        /*
        $body        = $this->_dbh->real_escape_string($body);
        $replybody   = $this->_dbh->real_escape_string($replybody);
        $description = $this->_dbh->real_escape_string($description);
        */
	    //$sql = "CALL message_log('$campaignid',$scheduleid,$sponsorid,$linkid,$locationid,\"$body\",'$replybody',$user, $reportingkey1,'$reportingkey2',$keywordid,$folderid,'$description')";
	    
        $sql = sprintf("CALL message_log('$campaignid',$scheduleid,$sponsorid,$linkid,$locationid,'%s','%s',$user, $reportingkey1,'$reportingkey2',$keywordid,$folderid,'%s')",
                    $this->escape($this->message->body),
                    $this->escape($replybody),
                    $this->escape($description)
                );
        
        
	    $rs = $this->query($sql);
	    
	    if ($this->hasError()) {
	        $error = 'Unable to log outbound message';
            $this->setError($error, $error.' - '.$sql.': '.$this->getError());
	        return false;
	    }
	    
	    // Return the Gateway Message Id
	    return $rs->id;
	}
	
	/**
	 * Writes a status message to a log file, where it will be picked up later 
	 * and saved to the database
	 * 
	 * @param stdClass $sent
	 */
	protected function _writeStatus($sent) {
		// Get the log file name
		$logfile = $this->settings()->sendstatuslogfile;
		if (!$logfile) {
			$logfile = 'outboundstatus.log';
		}
		
		// Build the full path to the file
		$logpath = realpath(dirname(__FILE__) . '/..') . '/logs/status';
		if (!file_exists($logpath)) {
			mkdir($logpath);
		}
		$logpath .= '/' . $logfile;
		
		// Check and/or create 
		if (!file_exists($logpath)) {
			touch($logpath);
		}
		
		if (!is_writable($logpath)) {
			chmod($logpath, 0777);
		}
		
		// Build out line to write
		$line  = str_pad($sent->gatewaymessageid, 20, ' ', STR_PAD_RIGHT);
		$line .= str_pad($sent->reportingkey1, 20, ' ', STR_PAD_RIGHT);
		$line .= str_pad($sent->reportingkey2, 40, ' ', STR_PAD_RIGHT);
		$line .= str_pad($sent->mobilenumber, 20, ' ', STR_PAD_RIGHT);
		$line .= $sent->messageid;
		
		// Open the file and write
		$fh = fopen($logpath, 'a');
		fwrite($fh, "$line\n");
		fclose($fh);
		
		return true;
	}
}