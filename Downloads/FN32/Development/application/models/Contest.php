<?php
class Application_Model_Contest extends Application_Model_Abstract {
	/**
	 * Contest id
	 * 
	 * @access public
	 * @var int
	 */
	public $id;
	
	/**
	 * Name of this contest
	 * 
	 * @access public
	 * @var string
	 */
	public $name;
	
	/**
	 * Date Time stamp when this contest begins
	 * 
	 * @access public
	 * @var string 
	 */
	public $startdate;
	
	/**
	 * Date Time stamp when this contest ends
	 * 
	 * @access public
	 * @var string 
	 */
	public $enddate;
    
    /**
	 * Contest Type
	 * 
	 * @access public
	 * @var string 
	 */
	public $type;
	
	/**
	 * Link to the rules page for this contest
	 * 
	 * @var string
	 * @access private
	 */
	private $rules_link = '';
	
	/**
	 * User ID of the contest owner.
	 * 
	 * @var int
	 * @access public
	 */
	public $userid;
	
	/**
	 * User entity model of the contest owner
	 * 
	 * @access protected
	 * @var Application_Model_User
	 */
	protected $_user;
    
    /**
     * The result of a played contest
     * 
     * @access private
     * @return string The response message for the contest.
     */
    private $__result;
	
	/**
	 * Class constructor loads a contest if an id is passed
	 *
	 * @access public
	 * @param int $id A contest id 
	 */
	public function __construct($id = 0) {
		if ($id) {
			$this->id = $id;
			$this->_load();
		}
        
        // if no user was loaded, try and load the registered user if there is one.
		if (!$this->_user) {
			$this->_user = Zend_Registry::isRegistered('user') ? Zend_Registry::get('user') : null;
		}
	}
	
	/**
	 * Gets the contest id
	 * 
	 * @access public
	 * @return int The contest id
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * Sets a contest ID and loads it
	 * 
	 * @access public
	 * @param int $id The contest id
	 */
	public function setId($id) {
		$this->id = $id;
		$this->_load();
	}
	
	/**
	 * Tells whether this contest is active
	 * 
	 * @access public
	 * @return boolean
	 */
	public function isActive() {
		return strtotime($this->startdate) >= time() && strtotime($this->enddate) <= time();
	}
	
	/**
	 * Saves this contest as new or as an update to a currently loaded contest
	 *
	 * @access public
	 * @return int The contest id
	 */
	public function save() {
		// Force numeric value
		$this->interval           = intval($this->interval);
		$this->winneratend        = intval($this->winneratend);
		$this->wininterval        = intval($this->wininterval);
		$this->winintervaltiming  = intval($this->winintervaltiming);
		$this->winnercount        = intval($this->winnercount);
		
		// Handle types too
		if ($this->type == 2) {
			if (!$this->winnercount) {
				$this->setError('A winner count is required for automated raffles');
				return false;
			}
			
			// If this is a pick a winner at end raffle, we clear out interval
			if ($this->winneratend) {
				$this->wininterval       = 0;
				$this->winintervaltiming = 0;
			} else {
				$this->wininterval       = intval($this->wininterval);
				$this->winintervaltiming = intval($this->winintervaltiming);
				
				// Validate these
				if (!$this->wininterval || !$this->winintervaltiming) {
					$this->setError('A winner selection interval is required for automated raffles');
					return false;
				}
			}
		}
		
		// If an id is set, we are Editing the current contest
		if ($this->id) {
			// Edit
			if ($this->_isValid()) {
                
                $sql = sprintf("CALL contest_update({$this->id}, '%s', {$this->_user->getId()}, '$this->startdate', '$this->enddate', $this->type, $this->interval, '%s', '%s', '%s', '%s', $this->winnercount, $this->wininterval, $this->winintervaltiming, $this->winneratend)",
                    $this->escape($this->name),
                    $this->escape($this->already_played_msg),
                    $this->escape($this->before_contest_msg),
                    $this->escape($this->after_contest_msg),
                    $this->escape($this->admin_phone)
                );
                
                $rs = $this->query($sql);
				
				if ($this->hasError()) {
	                $this->setError('An error occurred and the contest could not be saved.<br />' . $sql, $sql.': '.$this->getError());
	                return false;
	            }
	            
	            return $rs->status;
			}
		} else {
			// No id is set we are Adding a contest
			if ($this->_isValid()) {
				
                $sql = sprintf("CALL contest_add('%s', {$this->_user->getId()}, '$this->startdate', '$this->enddate', $this->type, $this->interval, '%s', '%s', '%s', '%s', $this->winnercount, $this->wininterval, $this->winintervaltiming, $this->winneratend)",
                    $this->escape($this->name),
                    $this->escape($this->already_played_msg),
                    $this->escape($this->before_contest_msg),
                    $this->escape($this->after_contest_msg),
                    $this->escape($this->admin_phone)
                );
                
                $rs = $this->query($sql);
				
				if ($this->hasError()) {
	                $this->setError('An error occurred and the contest could not be added.<br />' . $sql, $sql.': '.$this->getError());
	                return false;
	            }
	            
	            return $rs->id;
			}
		}
	}
    
	/**
     * Returns an array of prizes associated with this contest.
     * 
     * @access public
     */
    public function getPrizes() {
        if ($this->id) {
            $sql = "CALL contest_get_prizes({$this->id})";
            $rs  = $this->query($sql);
            
            if ($this->hasError()) {
                $this->setError('An error occurred and the contest could not be added.', $sql.': '.$this->getError());
                return false;
            }
            
            return $rs->fetchAll();
        }
        
        return array();
    }
    
	/**
	 * Add a prize to a contest.
	 * 
	 * @access public
	 * @return void
	 */
	public function addPrize($name, $response, $odds, $winner = 0, $altresponse = null, $expiredays = null, $expiredate = null) {
		$odds       = (int) $odds;
		$winner     = $winner == 'yes' || $winner == 1 ? 1 : 0;
		$expiredays = $expiredays === null ? 0 : intval($expiredays);
		
		$sql = sprintf("CALL contest_add_prize($this->id, '%s', '%s', $odds, '%s', $winner, $expiredays, '$expiredate')",
			$this->escape($name),
			$this->escape($response),
			$this->escape($altresponse)
		);
        
        $rs  = $this->query($sql);
        
        if ($this->hasError()) {
            $this->setError('Could not add this prize Contest Prize. <!--' . $sql . ' ' . $this->getError() . '-->', $sql.': '.$this->getError());
            return false;
        }
        
        return $rs->id;
	}
    
	/**
	 * Edits a prize.
	 * 
	 * @access public
	 * @return void
	 */
	public function editPrize($id, $name, $response, $odds, $winner = 0, $altresponse = null, $expiredays = null, $expiredate = null) {
		$odds       = (int) $odds;
		$winner     = $winner == 'yes' || $winner == 1 ? 1 : 0;
		$expiredays = $expiredays === null ? 0 : intval($expiredays);
		
		$sql = sprintf("CALL contest_edit_prize($id, '%s', '%s', $odds, '%s', $winner, $expiredays, '$expiredate')",
			$this->escape($name),
			$this->escape($response),
			$this->escape($altresponse)
		);
        
        $rs  = $this->query($sql);
        
        if ($this->hasError()) {
            $this->setError('Could not edit this prize Contest Prize. <!--' . $sql . ' ' . $this->getError() . '-->', $sql.': '.$this->getError());
            return false;
        }
        
        if ($rs->success) {
        	return true;
        }
        
        $this->setError($rs->message);
        return false;
	}
	
    /**
     * Delete a contest prize
     * 
     * @access public
     * @return bool
     */
    public function deletePrize($prizeid) {
        $sql = "CALL contest_delete_prize($prizeid)";
        $rs  = $this->query($sql);
        
        if ($this->hasError()) {
            $this->setError('Could not delete Contest Prize.', $sql.': '.$this->getError());
            return false;
        }
        
        return true;
	}
    
	/**
	 * See if a subscriber has played this contest.
	 * 
	 * @access public
	 * @param int $subscriberid
	 * @return void
	 */
	public function hasSubscriberPhone($subscriberid) {
		if ($this->id) {
            $phone = $this->escape($phone);
            
			$sql = "CALL contest_subscriber_get($this->id, $subscriberid)";
			$rs  = $this->query($sql);
			
			if ($rs->num_rows) {
				return $rs->fetchObject();
			}
		}
		
		return false;
	}
	
	/**
	 * Log that a subscriber played this contest
	 * 
	 * @param string $subscriberid
	 * @return boolean
	 */
	public function addSubscriber($subscriberid, $response, $winner) {
		if ($this->id) {
			$winner = (int) $winner;
			
            $sql = sprintf("CALL contest_subscriber_add($subscriberid, $this->id, '%s', $winner)",
            	$this->escape($response)
            );
			$rs  = $this->query($sql);
			
			if ($this->hasError()) {
                $this->setError('Could not add subscriber to contest.', $sql.': '.$this->getError());
                return false;
            }
			
			return true;
		}
		
		return false;
	}
	
	/**
	 * Returns the rules url.
	 * 
	 * @access private
	 * @return void
	 */
	public function rules() {
		return $this->rules_link;
	}
	
	/**
	 * Checks whether this contest ready to be saved.
	 *  
	 * @access protected
	 * @return boolean
	 */
	protected function _isValid() {
		if ($this->_user instanceof Application_Model_User) {
			if ($this->_user->getId()) {
				if ($this->_user->canCreateContest()) {
					if ($this->name) {
						if (($this->startdate && $this->enddate) || $this->type == 3) {
							if ($this->type) {
                                if ($this->already_played_msg && $this->before_contest_msg && $this->after_contest_msg) {
                                    return true;
                                } else {
                                    $this->setError('A contest requires a messages for before the contest, after the contest and already played subscibers.');
                                }
							} else {
								$this->setError('A contest type must be selected.');
							}
						} else{
							$this->setError('You must enter a start and end date for this contest to run.');
						}
					} else {
						$this->setError('You must name the contest.');
					}
				} else {
					$this->setError('User can not create Contests, upgrade to a Premium user for this feature.');
				}
			} else {
				$this->setError('Invalid user');
			}
		} else {
			$this->setError('A user must be supplied to create a contest');
		}
		
		return false;
	}
	
	/**
	 * Loads up this model with data results from the database
	 *
	 * @access protected
	 */
	protected function _load() {
		if ($this->id) {
			$sql = "CALL contest_get($this->id)";
			$rs  = $this->query($sql);
			
			if ($this->hasError()) {
                $this->setError('Could not load contest.', $sql.': '.$this->getError());
                return false;
            }
			
			if ($rs->hasResults()) {
				$this->loadFromArray($rs->fields);
				$this->setDateParts();
			}
            
            $this->_user = new Application_Model_User((int) $this->createuser);
		}
	}
    
	public function setDateParts() {
		if ($this->startdate) {
			$this->startdatepart = date('m/d/Y', strtotime($this->startdate));
			$this->starttimepart = date('h:i a', strtotime($this->startdate));
		}
		
		if ($this->enddate) {
			$this->enddatepart = date('m/d/Y', strtotime($this->enddate));
			$this->endtimepart = date('h:i a', strtotime($this->enddate));
		}
	}
	
    /**
     * Set the result of a played contest.
     * 
     * @access private
     */
    private function setResult($result) {
        $this->__result = $result;
    }
    
    /**
     * Get the result of a played contest.
     * 
     * @access private
     */
    public function getResult() {
        return $this->__result;
    }
    
    /**
	 * See if a subscriber can play in the contest.
	 * 
	 * @access public
	 * @return bool
	 */
	public function canPlay($subscriberid) {
		// See if this subscriber can play the contest
		$sql = "CALL contest_subscriber_can_play({$this->id}, {$subscriberid})";
        $rs  = $this->query($sql);
        
        if ($this->hasError()) {
            $this->setError('Could not see if subscriber can play.', $sql.': '.$this->getError());
            return false;
        }
        
        if ($rs->canplay) {
        	return true;
        }
        
        // if they can't play, see if there is an interval and when they can play again
        $sql = "CALL contest_subscriber_get_next_play({$this->id}, {$subscriberid})";
        $rs  = $this->query($sql);
        
        if ($this->hasError()) {
            $this->setError('Could not get the next day the subscriber can play.', $sql.': '.$this->getError());
            return false;
        }
        
        // This should always be populated, unless I guess there is no already_played_msg for some reason.
        if ($rs->already_played_msg) {
            $this->setResult($rs->already_played_msg);
        } else {
        	// Need a default message if for some reason we could not get the already played msg
            // This should technically NEVER happen, but just in case...
            $this->setResult('You have already played this contest. Thank you for playing.');
        }
        
        return false;
	}
	
	/**
	 * Plays the contest and sets the result message, whether it be a win or a loss.
	 * 
	 * @access public
	 * @return string
	 */
	public function play($subscriberid, $keywordid) {
		// Check to see if there is no start date
		if (!$this->startdate || (strtotime($this->startdate) > time())) {
			// Send back a can't play
			$this->setResult($this->before_contest_msg);
			return $this->getResult();
		}
		
		// Check to see if the end date has passed
		if ($this->enddate && strtotime($this->enddate) < time()) {
			// Send back a can't play
			$this->setResult($this->after_contest_msg);
			return $this->getResult();
		}
		
		// See if this subscriber can play, if not, the result message will be set by canPlay()
		if ($this->canPlay($subscriberid)) {
			$sql = "CALL contest_get_response({$this->id}, $keywordid)";
			$rs  = $this->query($sql);
			
			if ($this->hasError()) {
                $this->setError('Could not play contest for subscriber.', $sql.': '.$this->getError());
                return false;
            }
			
			// Set the result
			$this->setResult($rs->response);
			
			// Log that the subscriber played, and whether they won or not.
			// But only if this isn't a type 3 contest
			if ($this->type != 3) {
				$played = $this->addSubscriber($subscriberid, $rs->response, $rs->winner);
			}
		}
        
        return $this->getResult();
	}
	
	/**
	 * Gets a listing of active raffles that are UI type
	 * 
	 * @access public
	 * @return array
	 */
	public function getRaffles() {
		$sql = "CALL contests_get_raffles()";
		$rs = $this->query($sql);
		
		if ($this->hasError()) {
			$this->setError('Could not get raffles', $sql.': '.$this->getError());
			return array();
		}
		$return = array();
		while ($row = $rs->fetchObject()) {
			$return[] = $row;
		}
		
		return $return;
	}
	
	public function getRaffleWinner($raffle) {
		
	}
	
	public function notifyRaffleWinner($winner) {
		
	}
	
	/**
	 * Starts a contest 
	 * 
	 * @return boolean
	 */
	public function startContest() {
		if ($this->id && $this->admin_phone) {
			$sql = "CALL contest_add_start($this->id, '$this->admin_phone')";
			$rs = $this->query($sql);
			if ($this->hasError()) {
				$this->setError('Could not start this contest', $sql . ': ' . $this->getError());
				return false;
			}
			
			if ($rs->success) {
				return true;
			} else {
				$this->error = $rs->message ? $rs->message : 'Could not start the contest';
				return false;
			}
			
		}
		
		$this->error = 'No contest ID / Admin Phone Number found';
		return false;
	}
	
	/**
	 * Ends a contest
	 * 
	 * @return boolean
	 */
	public function endContest() {
		if ($this->id && $this->admin_phone) {
			$sql = "CALL contest_add_end($this->id, '$this->admin_phone')";
			$rs = $this->query($sql);
			if ($this->hasError()) {
				$this->setError('Could not end this contest', $sql . ': ' . $this->getError());
				return false;
			}
			
			if ($rs->success) {
				return true;
			} else {
				$this->error = $rs->message ? $rs->message : 'Could not end the contest';
				return false;
			}
			
		}
		
		$this->error = 'No contest ID / Admin Phone Number found';
		return false;
	}
	
	/**
	 * Picks winners for a contest
	 * 
	 * THIS IS SET UP FOR A RAFFLE RIGHT NOW
	 * 
	 * @param int $count
	 * @return array
	 */
	public function pickWinners($count = 1, $id = 0) {
		if (!$id) {
			if (!$this->id) {
				return array();
			}
			
			$id = $this->id;
		}
		
		$count = intval($count);
		if ($count) {
			$sql = "CALL contest_raffle_get_winner($id, $count)";
			$rs = $this->query($sql);
			if ($this->hasError()) {
				$this->setError('Could not get raffle winners', $sql . ': ' . $this->getError());
				return false;
			}
			
			// If there is a problem this proc returns a 0 success
			if (isset($rs->fields['success'])) {
				return array();
			}
			
			// If we're here we should have something to fetch or nothing at all
			$return = array();
			while ($row = $rs->fetchObject()) {
				$return[] = $row;
			}
			
			return $return;
		}
		
		return false;
	}
	
	/**
	 * Sets the admin phone
	 * 
	 * @param string $phone
	 */
	public function setAdminPhone($phone) {
		if ($phone) {
			$phone = Application_Model_Utility::cleanPhone($phone);
			if ($phone[0] != 1) {
				$phone = '1' . $phone;
			}
		}
		
		$this->admin_phone = $phone;
	}
	
	public function getTypes() {
		$sql = "CALL contests_get_types()";
		$rs = $this->query($sql);
		$return = array();
		if ($this->hasError()) {
			$this->setError('Could not get contest types', $sql . ': ' . $this->getError());
			return $return;
		}
		
		while ($row = $rs->fetchObject()) {
			$return[$row->id] = $row;
		}
		
		return $return;
	}
	
	/**
	 * Checks to see if a contest is ready to pick a winner and if it is, returns
	 * the number of winners to pick
	 * 
	 * @param int $id
	 * @return int|false The number of winners to pick if there are any, false otherwise
	 */
	public function hasWinnersToPick($id = 0) {
		if (!$id) {
			if (!$this->id) {
				return false;
			}
			
			$id = $this->id;
		}
		
		$sql = "CALL contest_has_winners_to_pick($id)";
		$rs = $this->query($sql);
		if ($rs && $rs->num_rows) {
			return $rs->pickcount;
		}
		
		return false;
	}
	
	/**
	 * Gets all active auto raffle type contests ids
	 * 
	 * @return array
	 */
	public function getAllAutoRaffles() {
		$sql = 'CALL contests_get_all_active_auto_raffles()';
		$rs = $this->query($sql);
		$return = array();
		if ($this->hasError()) {
			$this->setError('Could not get contest raffles', $sql . ': ' . $this->getError());
			return $return;
		}
		
		while ($row = $rs->fetchObject()) {
			$return[] = $row;
		}
		
		return $return;
	}
}