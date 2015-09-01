<?php

/**
 * TODO: I MODIFED THIS CLASS TO WORK WITH THE TEXTMUNICATION CODEBASE. NOT ENTIRELY SURE I 
 * DID IT VERY WELL, BUT IT SEEMS TO BE WORKING. SOME CLEANUP IS MOST LIKELY NEEDED.
 * -ONE THING I CHANGED WAS CREATING A STANDARD SINGLETON INSTANCE GETTER METHOD.
 * -ALSO CHANGED IT TO NOT REQUIRE A USER MODEL UNLESS THERE IS A LOGGED IN USER. THIS IS WHERE
 *  THINGS MAY HAVE BEEN MESSED UP.
 */

class Application_Model_Session extends Application_Model_Abstract {
	/**
	 * Session ID random string generator length constant
	 * 
	 * @constant
	 */
	const IDLENGTH = 32;
	
	/**
	 * Session ID salt
	 * 
	 * @constant
	 */
	const IDSALT = '^td5*43cd$2q09?d3@u7-09+jd=2chr5';
	
	/**
	 * Session ID passed by cookie type
	 * 
	 * @constant
	 */
	const TYPECOOKIE = 1;
	
	/**
	 * Session ID passed by URL type
	 * 
	 * @constant
	 */
	const TYPEURI = 2;
	
	/**
	 * String to append to URLs to ensure proper passing of session data 
	 * 
	 * @access private
	 * @var string
	 */
	private $appendSessionId = '';
	
	/**
	 * Type of session handling to user
	 * 
	 * @access private
	 * @var int
	 */
	private $type = self::TYPECOOKIE;
	
	/**
	 * Singleton instance holder
	 * 
	 * This doesn't operate like a normal singleton pattern in that the abstract
	 * model class constructor is public visibility. Because of that, this 
	 * instance var holds this object in it after instantiation the first time 
	 * around and, on subsequent calls, is checked against its value to ensure
	 * single instance use. No, this does not prevent double instantiation since
	 * the constructor will have already been called thereby resulting in two
	 * instances before the exception. But since the real work of this model is 
	 * handled after the instance value check, no harm is done in this construction.
	 * 
	 * @access private
	 * @var Application_Model_Session
	 */
	private static $instance = null;
	
	/**
	 * Array of cookie names used throughout this model
	 * 
	 * @access protected
	 * @var array
	 */
	protected $cookiename = array();
	
	/**
	 * The settings model
	 * 
	 * @access protected
	 * @var Application_Model_Settings
	 */
	protected $settings;
	
	/**
	 * The user attached to this session represented by a Application_Model_User object
	 * 
	 * @access protected
	 * @var Application_Model_User
	 */
	protected $user = null;
	
	/**
	 * The session data array
	 * 
	 * This is populated from serialized data that lives in the datastore property
	 * 
	 * @access protected
	 * @var array
	 */
	protected $sessiondata = array();
	
	/**
	 * The ip address of the user from the request
	 * 
	 * @access protected
	 * @var string
	 */
	protected $requestip;
	
	/**
	 * The time of this request
	 * 
	 * @access protected
	 * @var int
	 */
	protected $requesttime;
	
	/**
	 * The page requested
	 * 
	 * @access protected
	 * @var string
	 */
	protected $requestpage;
	
	/**
	 * The action requested
	 * 
	 * @access protected
	 * @var string
	 */
	protected $requestaction;
	
	/**
	 * The session id
	 * 
	 * @access protected
	 * @var string
	 */
	protected $id;
	
	/**
	 * Session user id, as it comes from a found session in the database
	 * 
	 * @access protected
	 * @var int
	 */
	protected $userid = -1;
	
	/**
	 * The starttime (UNIX timestamp) of this session as found in the database
	 * 
	 * @access protected
	 * @var int
	 */
	protected $starttime;
	
	/**
	 * UNIX Timestamp of this request as found for this session in the database
	 * 
	 * @access protected
	 * @var int
	 */
	protected $time;
	
	/**
	 * The IP address of the user making this request, as found in the database
	 * 
	 * @access protected
	 * @var string
	 */
	protected $ip;
	
	/**
	 * The page for this session, as found in the session
	 * 
	 * THIS WILL BE THE PAGE THE USER CAME FROM
	 * 
	 * @access protected
	 * @var string
	 */
	protected $page;
	
	/**
	 * The action for this session, as gound in the session
	 * 
	 * @access protected
	 * @var string
	 */
	protected $action;
	
	/**
	 * The dynamic data for this session, as a serialized array
	 * 
	 * THIS STRING, WHEN UNSERIALIZED, BECOMES THE SESSION DATA ARRAY
	 * 
	 * @access protected
	 * @var string
	 */
	protected $datastore = '';
	
	//private $asUser = array();
	
	public static function getInstance() {
        if (!isset(self::$instance)) {
            $class = __CLASS__;
            self::$instance = new $class;
        }
        
        return self::$instance;
	}
	
	/**
	 * Template method called by the constructor at construct time. This checks
	 * for the presence of a Application_Model_Session object in the instance variable
	 * and throws an exception if one is found.
	 * 
	 * @access protected
	 * @param array $params
	 */
	public function __construct($params = array()) {
		if (self::$instance instanceof Application_Model_Session) {
			die('CANNOT_INSTANTIATE_NEW_SESSION: The session object can only be created one time');
		}
		
		// Set our instance so we can flag later instantiation attempts
		self::$instance = $this;
		
		// Set our request time
		$this->requesttime = time();
		
		// Setup a new session id
		$this->id = $this->generateId();
		
		// Get the IP address of the requestor
		$this->requestip = $this->request()->getServer('REMOTE_ADDR');
		
		// Get the settings model - cookie data will have already been manipulated
		$this->settings = $this->settings();
		
		// Get the user model of the current user
		$this->user = new Application_Model_User;
		
		// Get our cookie names setup
		$this->cookiename = array('data' => $this->settings->cookiename . $this->settings->sessiondataname, 'id' => $this->settings->cookiename . $this->settings->sessionidname);
	}
	
	/**
	 * Sets the logged in state of the current session user, if there is one
	 * 
	 * @access protected
	 */
	protected function setLoggedInStatus() {
		// Do we have a real user?
		if ($this->user instanceof Application_Model_User && $this->user->getId() > 0) {
			$this->set('loggedin', true);
			
			if ($this->user->isAdmin()) {
				$this->set('admin', true);
			}
		}
	}
	
	/**
	 * Return if this session has a logged in user.
	 * 
	 * @access public
	 * @return void
	 */
	public function loggedIn() {
	   return $this->loggedin;
	}
	
	/**
	 * Sets the current page and current action as properties of the session
	 * 
	 * @access protected
	 */ 
	protected function setPageAndAction() {
		$this->set('currentpage', $this->requestpage);
		$this->set('currentaction', $this->requestaction);
	}
	
	/**
	 * Sets the request time minus session time value
	 * 
	 * @access protected
	 */
	protected function setTimeCheck() {
		$this->set('timecheck', $this->requesttime-$this->time);
	}
	
	/**
	 * Gets the session id from the request
	 * 
	 * @access public
	 * @return string Session id, if found
	 */
	public function getIdFromRequest() {
		$request = $this->request();
		
		// Check for the session id cookie
		if (($cookieid = $request->getCookie($this->cookiename['id'])) !== null) {
			$sessionid = $cookieid;
			$this->type = self::TYPECOOKIE;
			$this->appendSessionId = '';
		} else {
			// No cookie, so check the URI
			$sessionid  = $request->getParam($this->settings->sessionidname, '');
			$this->type = self::TYPEURI;
		}
		
		return $sessionid;
	}
	
	/**
	 * Builds a URI append for all URIs to maintain state for the user
	 * 
	 * @access protected
	 */
	protected function setAppendSessionId() {
		if ($this->passIdByUrl()) {
			$this->appendSessionId = $this->settings->sessionidname . '/' . $this->id; 
		}
	}
	
	/**
	 * Gets the session URI append if there is one
	 * 
	 * @access public
	 * @return string
	 */
	public function getAppendSessionId() {
		return $this->appendSessionId;
	}
	
	/**
	 * Starts a session for a request
	 * 
	 * @access public
	 * @return boolean
	 */
	public function start() {
		// Get our request data
		$sessionid = $this->getIdFromRequest();
		
		// Check our session id for validity
		if (!$this->idIsValid($sessionid)) {
			$sessionid = '';
		}
		
		// Now see if there is a session id
		if ($sessionid) {
			// Set that ID into this object
			$this->id = $sessionid;
			
			// Load up this object with any session data we have if any is found
			$this->load();
			
			// Now see if there is a user
			if (is_numeric($this->userid) && $this->userid > 0) {
				
				// see if they are trying to switch back
				if (isset($_POST['switchback'])) {
					// Make sure we have a lastUser set in the session
					if ($this->lastUser) {
						// Save who we just were
						//$this->lastUser = $this->userid;
						
						// Set the last user id
						$this->userid = $this->lastUser;
						// Remove the asUser value
						$this->asUser = null;
						
						$this->lastUser = null;
					}
				} else {
					// Now see if this session is a user acting as another user
					if (!empty($this->asUser)) {
						if ($this->lastUser == null) {
							$this->lastUser = $this->userid;
						}
						$this->userid = $this->asUser;
					}
				}
				
				// Now get the user associated with this session
				$this->user = new Application_Model_User((int) $this->userid);
				
				// Register the user to the registry so we can access it elsewhere
				Zend_Registry::set('user',$this->user);
				
				// We have a user in the session, do they exist in the user table
				if ($this->user->isValid()) {
					// Yes they do, check their IP before moving on
					if (substr($this->requestip, 0, 6) == substr($this->ip, 0, 6)) {
						// Our IPs match (mostly) so carry on
						$this->set('sendingpage', $this->page);
						$this->set('sendingaction', $this->action);
						$this->setPageAndAction();
						$this->setTimeCheck();
						$this->setLoggedInStatus();
						//die($this->loggedIn());
						// Update the users last session timestamp now
						//$this->user->lastSessionTimestamp(date('M d Y g:iA', $this->requesttime), true);
						
						// Set our cookie for fetching information next go round
						setcookie($this->cookiename['id'], $this->id, 0, $this->settings->cookiepath, $this->settings->cookiedomain, $this->settings->cookiesecure);
						
						// Set the append session id
						$this->setAppendSessionId();
						
						// error on save
						return true;
					} else {
						$this->error = 'The current IP address does not match your previous address.';
					}
				} else {
					$this->error = "User could not be verified: {$this->user->error}";
				}
			} else {
				// No user, but if there is a userid in the session data, we need to kill this session
				if (isset($this->sessiondata['userid']) && $this->sessiondata['userid'] > 0) {
					$this->end($this->sessiondata['userid']);
				}
			}
		}
		
		// If we are here we had no session id so we need to create one
		$userid = isset($this->sessiondata['userid']) ? $this->sessiondata['userid'] : -1;
		
		// Create a new session and move on
		if ($this->create($userid)) {
			$this->set('sendingpage', '');
			$this->set('sendingaction', '');
			$this->setPageAndAction();
			$this->setTimeCheck();
			
			return true;
		}
		
		return false;
	}
	
	/**
	 * Become another user in this session.
	 * 
	 * @access public
	 * @param mixed $userid
	 * @return void
	 */
	public function asUser($userid) {
		//array_push($this->asUser, $userid);
		$this->asUser = $userid;
	}
	
	public function lastUser($userid) {
		$this->lastUser = $userid;
	}
	
	/**
	 * Creates a new session
	 * 
	 * @access public
	 * @param int|Application_Model_User $user Either the user id or user model to create the session for
	 * @return boolean
	 */
	public function create($user) {
		// Get our request data
		$sessionid = $this->getIdFromRequest();
		
		// Check our session id for validity
		if (!$this->idIsValid($sessionid)) {
			$sessionid = '';
		}
		
		$lastVisit = 0;
		
		if ($user instanceof Application_Model_User) {
			$this->user = $user;
		}
		/*else {
			// Get the numeric value of the user
			$user = intval($user);
			
			// Create the user model if it hasn't been done yet
			if (!$this->user instanceof Application_Model_User) {
				$this->user = new Application_Model_User;
			}
			
			// Load the user if it is a non guest user
			if ($user > 0) {
				// Load the user information
				$this->user->id($user, true);
			}
		}
		*/
		// Set our logged in and admin status
		$this->setLoggedInStatus();
		
		// See if we have a session id that matches this one
		if (!$this->exists($sessionid)) {
			// Create a new session id
			$sessionid = $this->generateId();
		}
		
		// Set the id now
		$this->id = $sessionid;
		
		// Update the users last visit
		/*if ($this->user->getId() > 0) {
			// If this is a first login, last_visit becomes now, otherwise, it is the last known session time
			$lastVisit = ($visit = $this->user->lastSessionTimestamp()) > 0 ? $visit : date('M d Y g:iA', $this->requesttime);
			
			// Set that into the user information and force auto update right now
			$this->user->lastLoginTimestamp($lastVisit, true);
			
			// Build the rest of the session data
			$this->sessiondata['userid'] = $this->user->getId();
		}
		*/
		// Set finale values into the session now
		$this->starttime = $this->requesttime; // Unix Time stamp
		
		// Set our cookie for fetching information next go round
		setcookie($this->cookiename['id'], $this->id, 0, $this->settings->cookiepath, $this->settings->cookiedomain, $this->settings->cookiesecure);
		//setcookie(name,value,expire,path,domain,secure) 
		// Set the append session id
		$this->setAppendSessionId();
		
		return true;
	}
	
	/**
	 * Ends a session for a user. If no userid is passed it will use the
	 * current session's userid.
	 * 
	 * @access public
	 * @param int $userid
	 * @return boolean
	 */
	public function end($userid = null) {
		$userid = $userid === null ? $this->userid : $userid;
		
		$sql = "call session_delete_user($userid)";
		
		if (!$this->query($sql)) {
			// Again, this is an error flag
			$this->error = 'Could not release the session.';
			return false;
		}
		
		
		// Clear out all cookies for this session
		setcookie($this->cookiename['id'], '', $this->requesttime - 31536000, $this->settings->cookiepath, $this->settings->cookiedomain, $this->settings->cookiesecure);
		
		return true;
	}
	
	/**
	 * Ends all sessions - USE THIS WITH CAUTION
	 * 
	 * @access public
	 * @return boolean
	 */
	public function endAll() {
		// This is fairly straight forward
		$sql = "call session_delete({$this->settings->sessionlength})";
		
		if (!$this->query($sql)) {
			// Again, this is an error flag
			$this->error = 'Could not release all sessions.';
			return false;
		}
		
		return true;
	}
	
	/**
	 * Saves the session data store for this session
	 * 
	 * @access public
	 * @return boolean True on success
	 */
	public function saveSessionData() {
		// Serialize the session data array
		$datastore = serialize($this->sessiondata);
		
		// Write it to table now
		$sql = "call session_update_datastore('$this->id', '$this->requestip', '$datastore')";
		
		$rs = $this->query($sql);
		if (!$this->_dbh->error) {
			if (isset($rs->fields['success'])) {
				if ($rs->fields['success'] > 0) {
					return true;
				} else {				
					$this->error = 'Could not write current session information.';
				}
			} else {			
				$this->error = 'Could not save the session.';
			}
		} else {		
			$this->error = 'There was a general error saving this session.' . $db->getError(true, true);
		}
		
		return false;
	}
	
	/**
	 * Saves the session 
	 * 
	 * @access public
	 * @return boolean True on success
	 */
	public function save() {
		// Serialize the session data array
		$datastore = serialize($this->sessiondata);
		
		$userid = $this->user ? $this->user->getId() : '-1';
		
		$sql = "call session_update('$this->id', '$this->requestip', " . $userid . ", $this->requesttime, '$this->requestpage', '$this->requestaction', '$datastore')";
		
		$rs = $this->query($sql);
		if (!$this->error) {
			if (isset($rs->fields['success']) && $rs->fields['success'] == 1) {
				return true;
			}
			
			$this->error = 'Could not save the new session.';
		}
		
		$this->error = 'There was a general error saving this session.';
		return false;
	}
	
	/**
	 * Checks whether a given session id exists
	 * 
	 * @access public
	 * @param string $id A session id to check, or the current ID if none is given
	 * @return boolean
	 */
	public function exists($id = null) {
		if (null === $id) {
			$id = $this->id;
		}
		
		$sql = "call session_has('$id')";
		$rs = $this->query($sql);
		return isset($rs->fields['has']) && $rs->fields['has'] == 1;
	}
	
	/**
	 * Loads session data into this model and sets the fetched flag
	 * 
	 * @access public
	 */
	public function load() {
		if (($data = $this->getRawSessionData($this->id, $this->requestip)) !== false) {
			foreach ($data as $label => $value) {
				if (property_exists($this, $label)) {
					$this->{$label} = $value;
				}
			}
			
			// Unserialize our sessiondata 
			$sessiondata = unserialize($this->datastore);
			if (!is_array($sessiondata)) {
				$sessiondata = array();
			}
			
			$this->sessiondata = $sessiondata;
			$this->fetched = true;
		}
	}
	
	/**
	 * Gets session data from the database for this session id and session ip
	 * 
	 * @access public
	 * @param string $sessionid The Session id to get data for
	 * @param string $sessionip The session ip to get data for
	 * @return array Session data on success, false on failure
	 */
	public function getRawSessionData($sessionid, $sessionip) {
		if (empty($sessionid) || empty($sessionip)) {
			$this->error = 'Both a valid session id and session IP address are required.';
			return false;
		}
		
		$sql = "call session_get('$sessionid', '$sessionip')";
		
		$rs = $this->query($sql);
		
		if (!$this->_dbh->error) {
			if ($rs->hasRecords()) {
				return $rs->fetchArray();
			} else {
				$this->error = 'Could not find information for this session.';
			}
		} else {
			$this->error = 'There was a problem searching for this session.';
		}
		
		return false;
	}
	
	
	/**
	 * Become another user.
	 * 
	 * @access public
	 */
	public function switchUser($newuserid) {
		if ($this->user()->isParentOf($newuserid)) {
			
		}
	}
	
	/**
	 * Gets the integer value of the type of session passing taking place
	 * 
	 * @access public
	 * @return integer
	 */
	public function passIdByType() {
		return $this->type;
	}
	
	/**
	 * Checks if this session passes ids as a cookie
	 * 
	 * @access public
	 * @return boolean
	 */
	public function passIdByCookie() {
		return $this->type === self::TYPECOOKIE;
	}
	
	/**
	 * Checks if this session passes ids via URL
	 * 
	 * @access public
	 * @return boolean
	 */
	public function passIdByUrl() {
		return $this->type === self::TYPEURI;
	}
	
	/**
	 * Gets the request ip
	 * 
	 * @access public
	 * @return string
	 */
	public function getRequestip() {
		return $this->requestip;
	}
	
	/**
	 * Sets the request page
	 * 
	 * @access public
	 * @param string $page The name of the page to set
	 */
	public function setRequestpage($page) {
		$this->requestpage = $page;
	}
	
	/**
	 * Gets the request page
	 * 
	 * @access public
	 * @return string
	 */
	public function getRequestpage() {
		return $this->requestpage;
	}
	
	/**
	 * Sets the request action
	 * 
	 * @access public
	 * @param string $action The name of the action to set
	 */
	public function setRequestaction($action) {
		$this->requestaction = $action;
	}
	
	/**
	 * Gets the request action
	 * 
	 * @access public
	 * @return string
	 */
	public function getRequestaction() {
		return $this->requestaction;
	}
	
	/**
	 * Gets the user model for the user currently associated with this session
	 * 
	 * @access public
	 * @return Application_Model_User
	 */
	public function getUser() {
		return $this->user;
	}
	
	/**
	 * Gets the session id
	 * 
	 * @access public
	 * @return string
	 */
	public function getId() {
		return $this->id;
	}
	
	/**
	 * Gets the user id of the user associated with this session
	 * 
	 * @access public
	 * @return int
	 */
	public function getUserid() {
		return $this->userid;
	}
	
	/**
	 * Gets the start time (UNIX TIMESTAMP) of this session
	 * 
	 * @access public
	 * @return int
	 */
	public function getStarttime() {
		return $this->starttime();
	}
	
	/**
	 * Gets the time (UNIX TIMESTAMP) of this request
	 * 
	 * @access public
	 * @return int
	 */
	public function getTime() {
		return $this->time;
	}
	
	/**
	 * Gets the IP address associated with this request in dot quad format
	 * 
	 * @access public
	 * @return string
	 */
	public function getIp() {
		return $this->ip;
	}
	
	/**
	 * Gets the page name of this request
	 * 
	 * @access public
	 * @return string
	 */
	public function getPage() {
		return $this->page;
	}
	
	/**
	 * Gets the action associated with this request
	 * 
	 * @access public
	 * @return string
	 */
	public function getAction() {
		return $this->action;
	}
	
	/**
	 * Gets the session data string as a serialized array
	 * 
	 * @access public
	 * @return string
	 */
	public function getDatastore() {
		return $this->datastore;
	}
	
	/**
	 * Generates a session id
	 * 
	 * @access public
	 * @param int $length Length to use for the random seed string to build the session id from
	 * @param boolean $all Flag that tells the id maker to user upper and lower case
	 * @return string $length long string of ascii chars
	 */
	public function generateId($length = self::IDLENGTH, $all = true) {
		// Get a random string then salt it and MD5 it
		return md5(uniqid() . Application_Model_Utility::getRandomString($length, $all) . self::IDSALT);
	}
	
	/**
	 * Checks validity of a session if for format only
	 * 
	 * @access public
	 * @param string $id A session id to check validity of
	 * @return boolean
	 */
	public function idIsValid($id) {
		// Checks to make sure the session id is alphanumeric only and 32 characters
		return strlen($id) == 32 && preg_match('/^[A-Za-z0-9]*$/', $id);
	}
	
	/**
	 * Accessor method - simply sets data into the session model using the 
	 * overloader methods
	 * 
	 * @access public
	 * @param string $label The property to set
	 * @param mixed $value Any scalar value that can be serialized
	 */
	public function set($label, $value) {
		$this->__set($label, $value);
	}
	
	/**
	 * Accessor method - simply gets data from the session model using the 
	 * overloader methods
	 * 
	 * @access public 
	 * @param string $label The property to get data for
	 * @return mixed The value stored in this label or null, if not found
	 */
	public function get($label = null) {
		return null === $label ? $this->sessiondata : $this->__get($label);
	}
	
	/**
	 * Overloaded setter method, sets information into the session data array
	 * 
	 * @access public
	 * @param string $label The property to set
	 * @param mixed $value Any scalar value that can be serialized
	 */
	public function __set($label, $value) {
		$this->sessiondata[$label] = $value;
	}
	
	/**
	 * Overloaded getter method, gets information from the sessiondata array for
	 * the label requested
	 * 
	 * @access public 
	 * @param string $label The property to get data for
	 * @return mixed The value stored in this label or null, if not found
	 */
	public function __get($label) {
		return $this->__isset($label) ? $this->sessiondata[$label] : null;
	}
	
	/**
	 * Checks whether the given property is found in this model
	 * 
	 * @access public 
	 * @param string $label The property to get data for
	 * @return boolean True if set
	 */
	public function __isset($label) {
		return array_key_exists($label, $this->sessiondata);
	}
	
	/**
	 * Unsets a member of the session data array if found
	 * 
	 * @access public
	 * @param string $label The property to delete the value of
	 */
	public function __unset($label) {
		if ($this->__isset($label)) {
			unset($this->sessiondata[$label]);
		}
	}
	
	/**
	 * Object desctuctor, causes a session data store write to the database
	 * 
	 * @access public
	 */
	public function __destruct() {
		$this->save();
	}
}
