<?php
class Application_Model_Keyword extends Application_Model_Abstract {
	/**
	 * Keyword id
	 * 
	 * @access public
	 * @var int
	 */
	public $id;
	 /**
                * ShortCode
                * 
                * @access public
                * @var int
                */
               public $shortcode;
	/**
	 * The folder ID - required for all keywords
	 * 
	 * @access public
	 * @var int
	 */
	public $folderid;
	
	/**
	 * The actual keyword
	 * 
	 * @access public
	 * @var string
	 */
	public $keyword;

	/**
	 * The reply header to send when a new subscriber optsin
	 * 
	 * @access public
	 * @var string
	 */
	public $replyheader;
	
	/**
	 * The reply to send when a new subscriber optsin
	 * 
	 * @access public
	 * @var string
	 */
	public $replybody;

    /**
	 * An alternate reply body to send when a subscriber is 
     * already in the folder this keyword is attached to.
	 * 
	 * @access public
	 * @var string
	 */
	public $replybodyalt;
    
    /**
	 * Should the alternate reply body be used when a subscriber
     * is already in the attached folder?
	 * 
	 * @access public
	 * @var bool
	 */
	public $usealt = false;
    
	/**
	 * The reply footer to send when a new subscriber optsin
	 * 
	 * @access public
	 * @var string
	 */
	public $replyfooter = 'reply STOP 2 stop';
	
	/**
	 * The user that created this keyword
	 * 
	 * @access public
	 * @var int
	 */
	public $createuser;
	
	/**
	 * Date Time stampe of when this keyword was deactivated
	 * 
	 * @access public
	 * @var string 
	 */
	public $deactivatetime;
	
	/**
	 * Custom Keyword Model to run messages to this keyword through.
	 * 
	 * @var mixed
	 * @access public
	 */
	public $custommodel;
	
	/**
	 * Contest ID this keyword is using (if any).
	 * 
	 * @var mixed
	 * @access public
	 */
	public $contestid;
	/**
	 * Optin life range .
	 * 
	 * @var int
	 * @access public
	 */
	public $optinlife;
	/**
	 * Offer expiration date.
	 * 
	 * @var int
	 * @access public
	 */
	public $offerexp;
	/**
	 * Url for a particular form.
	 * 
	 * @var string
	 * @access public
	 */
	public $formurl;
	/**
	 * Secondary message to recipients
	 * 
	 * @var string
	 * @access public
	 */
	public $alertmessage;
        /**
	 * Location
	 * 
	 * @var string
	 * @access public
	 */
	public $location;
        /**
	 * Notifybysms
	 * 
	 * @var int
	 * @access public
	 */
	public $notifybysms;
	public $notifybyemail;
	/**
	 * A user entity model
	 * 
	 * @access protected
	 * @var Application_Model_User
	 */
	protected $_user;
	
	/**
	 * The contest action associated with this keyword
	 * 
	 * One of:
	 *  - start
	 *  - end
	 *  - pick
	 *  
	 * @var string
	 */
	protected $_contestAction;
	
	/**
	 * For 'pick' contest actions, the number of winners to pick
	 * 
	 * @var int
	 */
	protected $_contestPickCount = 0;
	
	/**
	 * Class constructor simply loads a keyword based on id or name
	 *
	 * @access public
	 * @param int|string $id A keyword or keyword id
	 */
	public function __construct($id = 0, $forceStringLoad = false) {
		if ($id) {
			$this->id = $id;
			$this->_load($forceStringLoad);
		}
		
		// if no user was loaded, try and load the registered user if there is one.
		if (!$this->_user) {
			$this->_user = Zend_Registry::isRegistered('user') ? Zend_Registry::get('user') : null;
		}
	}
	
	public function setUser(Application_Model_User $user) {
		$this->_user = $user;
	}
	
	/**
	 * Gets the keyword id
	 * 
	 * @access public
	 * @return int The keyword id
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * Sets a keyword ID
	 * 
	 * @access public
	 * @param int $id The keyword id
	 */
	public function setId($id) {
		$this->id = $id;
		$this->_load();
	}
	
	/**
	 * Gets the folder id
	 * 
	 * @access public
	 * @return int The folder id
	 */
	public function getFolderId() {
		return $this->folderid;
	}

	/**
	 * Sets a folder ID
	 * 
	 * @access public
	 * @param int $folderid The folder id
	 */
	public function setFolderId($folderid) {
		$this->folderid = $folderid;
	}
    
    /**
	 * Gets the contestid id
	 * 
	 * @access public
	 * @return int The contestid id
	 */
	public function getContestId() {
		return $this->contestid;
	}

	/**
	 * Sets a contestid ID
	 * 
	 * @access public
	 * @param int $contestid The contestid id
	 */
	public function setContestId($contestid) {
		$this->contestid = $contestid;
	}
	
	/**
	 * Gets the keyword 
	 * 
	 * @access public
	 * @return string The keyword
	 */
	public function getKeyword() {
		return $this->keyword;
	}

	/**
	 * Sets the keyword
	 * 
	 * @access public
	 * @param string $keyword The keyword
	 */
	public function setKeyword($keyword) {
		$this->keyword = $keyword;
	}

	/**
	 * Gets the reply header to send to optin subscribers to this keyword
	 * 
	 * @access public
	 * @return string The reply header to send to optins
	 */
	public function getReplyheader() {
		return $this->replyheader;
	}

	/**
	 * Sets the reply header to send to optins
	 * 
	 * @access public
	 * @param string $reply The reply header to send to optins
	 */
	public function setReplyheader($replyheader) {
		$this->replyheader = $replyheader;
	}

	/**
	 * Gets the reply to send to optin subscribers to this keyword
	 * 
	 * @access public
	 * @return string The reply to send to optins
	 */
	public function getReplybody() {
		return $this->replybody;
	}
        /* need to documented ****************** PAY ATTANTION   */
	public function getOptinlife() {
		return $this->optinlife;
	}
	public function getOfferexp() {
		return $this->offerexp;
	}
	public function getFormurl() {
		return $this->formurl;
	}
	public function getAlertmessage() {
		return $this->alertmessage;
	}
	public function getNotifybysms() {
		return $this->notifybysms;
	}
	public function getNotifybyemail() {
		return $this->notifybyemail;
	}
             public function getLocation() {
		return $this->location;
	}
	public function setLocation($location) {
		return $this->location = $location;
	}
	public function setNotifybysms($notifybysms) {
		return $this->notifybysms = $notifybysms;
	}
   /**
     * 
     * @access public
     * @param int $notifybyemail
     */
    public function setNotifybyemail($notifybyemail) {
        $this->notifybyemail = $notifybyemail;
    }
              public function specialGetid(){return $this->id; }

              /**
	 * Sets the reply to send to optins
	 * 
	 * @access public
	 * @param string $reply The reply to send to optins
	 */
	public function setReplybody($replybody) {
		$this->replybody = $replybody;
	}
    
    /**
	 * Gets the alternate reply to send to subscribers already in 
     * this keywords folder.
	 * 
	 * @access public
	 * @return string The reply to send to optins
	 */
	public function getReplybodyAlt() {
		return $this->replybodyalt;
	}

	/**
	 * Sets the alternate reply to send to subscribers already in 
     * this keywords folder.
     * 
	 * @access public
	 * @param string $reply The reply to send to optins
	 */
	public function setReplybodyAlt($replybodyalt) {
		$this->replybodyalt = $replybodyalt;
	}
    
    /**
	 * Get whether this keyword uses an alternate response body
     * when a subscriber is already in a folder.
	 * 
	 * @access public
	 * @return string The reply to send to optins
	 */
	public function getUseAlt() {
		return $this->usealt;
	}

	/**
	 * Sets whether this keyword uses an alternate response body
     * when a subscriber is already in a folder.
     * 
	 * @access public
	 * @param string $reply The reply to send to optins
	 */
	public function setUseAlt($usealt = 0) {
		$this->usealt = (int) $usealt;
	}

	/**
	 * Gets the reply to send to optin subscribers to this keyword
	 * 
	 * @access public
	 * @return string The reply to send to optins
	 */
	public function getReplyfooter() {
		return $this->replyfooter;
	}

	/**
	 * Sets the reply to send to optins
	 * 
	 * @access public
	 * @param string $reply The reply to send to optins
	 */
	public function setReplyfooter($replyfooter) {
		$this->replyfooter = $replyfooter;
	}

	/**
	 * Gets the user id that created this keyword
	 * 
	 * @access public
	 * @return int The user id that created this keyword
	 */
	public function getCreateuser() {
		return $this->createuser;
	}

	/**
	 * Sets the user id that created this keyword
	 * 
	 * @access public
	 * @param int $createuser The user id that created this keyword
	 */
	public function setCreateuser($createuser) {
		$this->createuser = $createuser;
	}
        
	/**
	 * Subscriber can't apply again during the time perioud
	 * which defined for this keyword
	 * @access public
	 * @param int $optinlife 
	 */
	public function setOptinlife($optinlife) {
		$this->optinlife = $optinlife;
	}
        
	/**
	 * 
	 * 
	 * @access public
	 * @param int $offerexp 
	 */
	public function setOfferexp($offerexp) {
		$this->offerexp = $offerexp;
	}
                /**
                * 
                * 
                */
               public function getShortcode() {
                   return $this->shortcode;
               }
              /**
                * 
                * 
                */
               public function setShortcode($shortcode) {
                   $this->shortcode = $shortcode;
               }
	/**
	 * 
	 * 
	 * @access public
	 * @param string $formurl
	 */
	public function setFormurl($formurl) {
		$this->formurl = $formurl;
	}
	/**
	 * 
	 * 
	 * @access public
	 * @param string $alertmessage
	 */
	public function setAlertmessage($alertmessage) {
		$this->alertmessage = $alertmessage;
	}
        
	/**
	 * Gets the deactivation time of this this keyword
	 * 
	 * @access public
	 * @return string
	 */
	public function getDeactivateTime() {
		return $this->deactivatetime;
	}
	
	/**
	 * Sets the deactivation time for this keyword
	 * 
	 * @access public
	 * @param string $deactivatetime
	 */
	public function setDeactivateTime($deactivatetime) {
		$this->deactivatetime = date('Y-m-d H:i:s', strtotime($deactivatetime));
	}
	
	/**
	 * Tells whether this keyword is active
	 * 
	 * @access public
	 * @return boolean
	 */
	public function isActive() {
		return $this->deactivatetime != '' && $this->deactivatetime !== null && $this->deactivatetime != '0000-00-00 00:00:00';
	}
	
	/**
	 * Gets a folder object for the keyword
	 * 
	 * @return Application_Model_Folder
	 */
	public function getFolder() {
		return new Application_Model_Folder($this->_user, $this->folderid);
	}
	
	/**
	 * Saves this keyword
	 *
	 * @access public
	 * @return int The keyword id
	 */
	public function save() {
		if ($this->id) {
			// Edit
			if ($this->_isValid()) {
                
                $sql = sprintf("CALL keyword_update_new($this->id, '%s', $this->folderid, '%s', '%s', $this->optinlife, $this->offerexp,'%s','%s', '%s', $this->usealt, $this->contestid,'%s',$this->notifybysms,'%s',$this->shortcode)",
                    $this->escape($this->keyword),
                    $this->escape($this->replyheader),
                    $this->escape($this->replybody),
                    $this->escape($this->alertmessage),
                    $this->escape($this->replybodyalt),
                    $this->escape($this->replyfooter),
                    $this->escape($this->location),  
                    $this->escape($this->notifybyemail)    
                );
                
				$rs = $this->query($sql);
				if ($rs && $rs->num_rows) {
				    if ($this->hasError()) { 
					$this->error = 'An error occurred and the keyword changes could not be saved';
				    }else{
					return TRUE;
				    }
 				        /*if ($rs->success !== null) {
 						if ($rs->success >= 1) {
 							return true;
 						} 
 						    
 						    else {
 							$this->error = 'An error occurred and the keyword changes could not be saved';
 						    }
 					} else {
 						$this->error = 'An error occurred and the keyword could not be saved 1';
 					}*/
				}

			}
		} else {
			// Add
			if ($this->_isValidNew()) {
				$userid = $this->_user->getId();
				
                $sql = sprintf("CALL keyword_add_new('%s', $this->folderid, '%s', '%s',$this->optinlife, $this->offerexp,'%s', '%s', '%s', $this->usealt, $this->contestid, $userid,'%s',$this->notifybysms,'%s',$this->shortcode)",
                    $this->escape($this->keyword),
                    $this->escape($this->replyheader),
                    $this->escape($this->replybody),
                    $this->escape($this->alertmessage),
                    $this->escape($this->replybodyalt),
                    $this->escape($this->replyfooter),
                    $this->escape($this->location),
                    $this->escape($this->notifybyemail)    
                );
                
				$rs = $this->query($sql);
				if ($rs && $rs->num_rows) {
					if ($rs->success !== null) {
						if ($rs->success >= 1) {
							return true;
						}
						
						if ($rs->success == -1) {
							$this->error = 'KEYWORD ' . $this->keyword . ' ALREADY EXISTS IN OUR SYSTEM';
						} else {
							$this->error = 'An error occurred and the keyword could not be saved 2';
						}
					} else {
						$this->error = 'An error occurred and the keyword could not be saved 3';
					}
				}
			}
		}
		
		return false;
	}
	
	/**
	 * Deletes a keyword by simply deactivating it
	 * 
	 * @access public
	 * @return boolean
	 */
	public function delete($id,$word) {		  
		if ($id) {		
			$word = $word.'_delete';
			$sql = "CALL keyword_delete($id,'".$word."')"; 
			$rs = $this->query($sql);
			if ($rs && $rs->num_rows) {
				return $rs->success == 1;
			}
		}
		
		return false;
	}
	
	public function hasSubscriberPhone($phone) {
		if ($this->id) {
            $phone = $this->escape($phone);
			$sql = "CALL keyword_get_subscriber_by_phone($this->id, $phone)";
			$rs = $this->query($sql);
			if ($rs->num_rows) {
				return $rs->fetchObject();
			}
		}
		
		return false;
	}
	
	/**
	 * Opts in a subscriber
	 * 
	 * @param string $phone
	 * @return boolean
	 */
	public function addSubscriber($phone) {
		if ($this->id) {
			$phone = $this->escape($phone);
            $sql = "CALL keyword_add_subscriber($this->id, $phone)";
			$rs = $this->query($sql);
			return $rs->success > 0;
		}
		
		return false;
	}
	
	/**
	 * Opts a subscriber OUT of a keyword
	 * 
	 * @param string $phone
	 * @return boolean
	 *
	public function deleteSubscriber($phone) {
		if ($this->id) {
			$sql = "CALL keyword_delete_subscriber($this->id, $phone)";
			$rs = $this->query($sql);
			return $rs->success > 0;
		}
		
		return false;
	}
	*/
	
	/**
	 * Handles special actions like STOP, QUIT, HELP that are like keywords, only
	 * special in nature.
	 * 
	 * @param Application_Model_Smsinbound $inbound
	 */
	public function handleSpecialActions(Application_Model_Smsinbound $inbound) {
		if ($this->keyword || $this->custommodel) {
			// If this keyword has a contestid, use the Contest Keyword Model
			if ($this->hasContestAction() || $this->contestid) {
				$modelname = 'Contest';
			} else {
				// Use the custom model before the keyword check
				$modelname = $this->custommodel ? $this->custommodel : $this->keyword;
			}
			
			$modelpath = dirname(realpath(__FILE__));
			$file = $modelpath . '/Keyword' .ucfirst(strtolower($modelname)) . '.php';
			if (is_readable($file)) {
				require_once $modelpath . '/KeywordAbstract.php';
				require_once $file;
				$class = 'Application_Model_Keyword' . ucfirst(strtolower($modelname));
				if (class_exists($class, false)) {
					$obj = new $class($inbound);
					$obj->setKeyword($this);
					$obj->handle();
					$obj->shutdown();
				}
			}
		}
	}
	
	/**
	 * Loads this model with data from an array, like a result set row
	 * 
	 * @param array $array
	 */
	public function loadFromArray(array $array = array()) {
		foreach ($array as $k => $v) {
			if (property_exists($this, $k)) {
				$this->{$k} = $v;
			}
		}
	}
	
	 /**
     * Tells whether this keyword exists already
     * 
     * @param string $keyword
     * @return boolean
     *  modified -- added short code on 01/31/14
     */
    public function exists($keyword = null,$shortcode =0) {
//        echo $keyword."  ".$shortcode;
//        exit;
        if (!$keyword) {
            if (!$this->keyword) {
                return false;
            }

            $keyword = $this->keyword;
//            $shortcode = $this->shortcode;
        }
        $keyword = $this->escape($keyword);
        $sql = "CALL keyword_exists('$keyword',$shortcode)";
        $rs = $this->query($sql);
        if ($rs && $rs->num_rows) {
            if ($rs->exists != 0) {
                return true;
            }
        }

        return false;
    }

	
	/**
	 * Tells whether there is a contest action for this keyword
	 * 
	 * @return boolean
	 */
	public function hasContestAction() {
		return !empty($this->_contestAction);
	}
	
	/**
	 * Gets the contest action or, if it is a pick, the number of winners to pick
	 * 
	 * @return string|int
	 */
	public function getContestAction() {
		if ($this->hasContestAction()) {
			return $this->_contestAction == 'pick' ? $this->_contestPickCount : $this->_contestAction;
		}
		
		return null;
	}
	
	/**
	 * Checks whether this keyword is a valid NEW keyword or not... useful for 
	 * saving
	 * 
	 * REQUIRED ELEMENTS:
	 *  - User keyword create limit within range
	 *  - Keyword
	 *  - Keyword doesn't exist
	 *  - Folder id that user is parent of
	 *  - Reply body
	 *  
	 * @access protected
	 * @return boolean
	 */
	protected function _isValidNew() {
		if ($this->_user instanceof Application_Model_User) {
			if ($this->_user->getId()) {
				if ($this->_user->canCreateKeyword()) {
					if ($this->keyword) {
						if (!$this->exists()) {
							if ($this->folderid) {
								if ($this->_user->isParentOf($this->folderid)) {
									if ($this->replybody) {
										return true;
									} else {
										$this->setError('A keyword requires a auto response message');
									}
								} else {
									$this->setError('Permission denied');
								}
							} else {
								$this->setError('A keyword must be added to a folder');
							}
						} else{
							$this->setError('The keyword already exists in our system');
						}
					} else {
						$this->setError('A keyword must be supplied');
					}
				} else {
					$this->setError('Keyword creation is not allowed at this time');
				}
			} else {
				$this->setError('Invalid user');
			}
		} else {
			$this->setError('A user must be supplied to create a keyword');
		}
		
		return false;
	}
	
	/**
	 * Checks whether this keyword is a valid keyword or not... useful for 
	 * saving edits
	 * 
	 * REQUIRED ELEMENTS:
	 *  - Keyword
	 *  - Folder id that user is parent of
	 *  - Reply body
	 *  
	 * @access protected
	 * @return boolean
	 */
	protected function _isValid() {
		if ($this->folderid) {
			if ($this->_user instanceof Application_Model_User && $this->_user->getId() && $this->_user->isParentOf($this->folderid)) {
				if ($this->replybody) {
					return true;
				} else {
					$this->setError('A keyword requires a auto response message');
				}
			} else {
				$this->setError('Permission denied');
			}
		} else {
			$this->setError('A keyword must be added to a folder');
		}
		
		return false;
	}
	
	/**
	 * Loads up this model with data results from the database
	 *
	 * @access protected
	 */
	protected function _load($forceStringLoad = false) {
		if ($this->id) {
			// Handle strings with special handling attached
			if ( ($conteststart = strpos($this->id, ' start')) !== false || ($contestend = strpos($this->id, ' end')) !== false || ($contestpick = preg_match_all('/(.*) pick (\d+)/', $this->id, $matches)) > 0 ) {
				if ($conteststart) {
					$this->_contestAction = 'start';
					$this->id = str_replace(' start', '', $this->id);
				}
				
				if ($contestend) {
					$this->_contestAction = 'end';
					$this->id = str_replace(' end', '', $this->id);
				}
				
				if ($contestpick) {
					if (!empty($matches[1][0]) && !empty($matches[2][0])) {
						$this->id = $matches[1][0];
						$this->_contestAction = 'pick';
						$this->_contestPickCount = intval($matches[2][0]);
					}
				} 
				
				
			}
			
			$loadStringFlag = intval((boolean) $forceStringLoad);
                                           $id = $this->escape($this->id);
            
			$sql = "call get_keyword('$id', $loadStringFlag)";
			$rs = $this->query($sql);
			if ($rs) {
				$this->loadFromArray($rs->fields);
			}
			
			$this->_user = new Application_Model_User((int) $this->createuser);
		}
	}
        /**
         * verifing existency of a new keyword
         * in the system.
         * 
         * @name verifyKeyWord;
         * @param $keyword
         */
        public function verifyKeyWord($keyword,$sc) {
        $keywords = array();
        $sql = "SELECT `id`,`keyword`,`createuser` FROM `keywords` WHERE `keyword`='$keyword' and `shortcode`=$sc";
//        $sql = "CALL verifyKeyWord('$keyword',$sc)";
        $rs = $this->query($sql);
        if ($this->hasError()) {
            error_log($this->getError());
            $this->error = 'Unable to get keyword.';
            return $rs;
        }
        if ($rs->hasResults()) {
//            foreach ($rs->fetchAll() as $takenkey) {
//                $keywords[$takenkey['id']] = $takenkey['keyword'];
//            }
            return $rs->createuser;
        }
//        return $keywords;
        return 0;
    }
    /**
     * Modifies message body for keyword when some one opt in
     * Gets keyword expiration day and adds today date
     * sets new exp day for the current optin
     * @name updateExp
     * @param $data(message body), $expday
     */    
    public   function updateExp($data, $expday){
         if($data != ""){
          $expIndx = strrpos($data,'exp');
          if($expIndx != 0){
               $setdate = date('m/d', strtotime("+$expday days"));
               $newextp = "exp:".$setdate;
               $response = substr($data,0, $expIndx).$newextp.substr($data,$expIndx+strlen($newextp));
               return $response;
          }else {
              $response = $data;
          }
      } 
      } // end of updateExp;
      
     public function returnSubscriber($folderid, $keywordid, $phonenumber){
           $optedin = array();
             $sql = "CALL return_subscriber($folderid,$keywordid,$phonenumber)";
             $rs = $this->query($sql);
           if ($this->hasError()) {
            error_log($this->getError());
            $this->error = 'Unable to get keyword.';
            return $rs;
           }
          if ($rs->hasResults()) {
            foreach ($rs->fetchAll() as $takenkey) {
                $optedin[$takenkey['keywordid']] = $takenkey['keywordid'];
            }
           }
        return $optedin;
     }
     
        public function alertMessage($resp,$alert){
         $alertmsgbody = urlencode($alert); 
         $shortCode = 87365;
         $url = 'https://208.71.240.93/a2w_preRouter/httpApiRouter';
         $username = 'textmu';
         $password = 'textmu1';
         $uri = $url . '?reply_to=' . $shortCode . '&recipient='. $resp . '&body='.$alertmsgbody;
                       $ch = curl_init($uri);
			
			// Now set some params, start with username and password
			curl_setopt($ch, CURLOPT_USERPWD, $username . ':' . $password);
			
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
     }// end of alert messages
     
     /**
        * Registiring subcribers for free offer
        * it  basically controls having a free offer
        * once only optin life time
        *  @name getFreeOffer
        *  @param keywordid, folderid, phonenumber
        */
        public function getFree_offer($keywordid, $folderid, $phonenumber){
            $sql = "CALL getfree_offer($keywordid, $folderid, '$phonenumber')";
            $rs = $this->query($sql);
            return $rs->success > 0;
        }// end of getfreeOffer
        
        /**
        * Verifying if subscriber get free offer already
        * 
        *  @name getFreeOffer
        *  @param keywordid, folderid, phonenumber
        */
        public function isSubcriberGetone($keywordid, $folderid, $phonenumber){
            $sql = "CALL check_forfree_offer($keywordid, $folderid, '$phonenumber')";
            $rs = $this->query($sql);
        if ($rs->hasRecord()) {
           return $rs->reg_time;  
        }else{
          return false;
        }
        }// end of isSubcriberGetone
        
        public function optinLife_exp($reqtime, $optinlife){
            $dif =  (strtotime(date('Y-m-d')) - strtotime($reqtime))/(60 * 60 * 24);
            
            if($dif >= $optinlife)
            {
                return 1;
            }else{
                return 0;
            }
        }
        
        public function update_freeoffer($keywordid, $folderid, $phonenumber){
            $sql = "CALL update_forfree_offer($keywordid, $folderid, '$phonenumber')";
            $rs = $this->query($sql);
        }
        
        /**
         *  Keywords for clubready 
         * 
         */
        public function create_crkewords($keyword,$textmid,$crid,$shortcode, $body=''){
            $sql = "insert into crkeywords (`keyword`,`body`,`textmid`,`crid`,`shortcode`) values('$keyword','$body', $textmid, $crid, $shortcode )";
            $this->query($sql);
        }
        public function verify_crkewords($keyword,$shortcode){
            $sql = "select `keyword` from crkeywords where `keyword`='$keyword' and `shortcode`=$shortcode";
            
            $rs = $this->query($sql);
            
            if($rs->hasRecords()){
                return 1;
            }else{
                return 0;
            }
        }
} 