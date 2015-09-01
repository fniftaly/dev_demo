<?php
class Application_Model_Auth extends Application_Model_Abstract {
	
	/**
	 * Userid of the authorized user.
	 * 
	 * @var int
	 * @access private
	 */
	private $userid;
	
	
	/* NOT BEING USED. DELETE?
	public function apiCredentials($apikey, $apisecret) {
		$sql = "CALL user_auth_api('$apikey', '$apisecret')";
		
		return $this->_getAuthentication($sql);
	}
	
	public function apiKey($apikey) {
		$return = false;
		$sql = "CALL user_auth_api_key('$apikey')";
		$rs = $this->query($sql);
		if ($rs && $rs->num_rows) {
			$return = new stdClass;
			while ($row = $rs->fetchArray()) {
				if (!isset($return->id)) {
					$return->id = $row['id'];
				}
				$return->{$row->fieldname} = $row->fieldvalue;
			}
		}
		return $return;
		//return $this->_getAuthentication($sql);
	}
	
	protected function _getAuthentication($sql) {
		if ($sql) {
			$rs = $this->query($sql);
			if ($rs) {
				if ($rs->num_rows) {
					$row = $rs->fetchObject();
					if (!empty($row->auth)) {
						// The only case of auth
						return true;
					} else {
						$this->error = 'API key was either inactive or incorrect.';
					}
				} else {
					$this->error = 'API user not found or incorrect or inactive API key.';
				}
			} else {
				$this->error = 'Could not locate this API key.';
			}
		} else {
			$this->error = 'No search criteria presented.';
		}
		
		return false;
	}
    */
    
    /**
     * Validate an API Key
     * 
     * @access public
     * @param string $apikey
     * @return bool Whether the passed API Key is valid or not.
     */
    public function validate($apikey) {
        if ($apikey) {
            
            $this->_getDbh();
            $apikey = $this->_dbh->real_escape_string($apikey);
            
            $sql = "CALL user_auth_api('$apikey')";
            $rs  = $this->query($sql);
            
            if ($rs->hasRecords()) {
                // Set the authorized user
                $this->userid = (int) $rs->entityid;
                
                return $this->userid;
            } else {
                $this->error = 'API user not found or incorrect/inactive API key.';
                return false;
            }
        }
        
        $this->error = 'No API key provided.';
        return false;
    }
    
    /**
     * Used to get the User ID of a user who was successfully authorized.
     * 
     * @access public
     * @return int
     */
    public function getAuthorizedUserId() {
        return (int) $this->userid;
    }
    
    /**
     * Authenticate a user/password for logging into the admin panel.
     * 
     * @access public
     * @param string $username
     * @param string $password
     * @return bool Valid credentials or not
     */
    public function authenticate($username, $password) {
        $this->_getDBH();
        $username = $this->_dbh->real_escape_string($username);
        $password = $this->_dbh->real_escape_string($password);
        
        $sql = "CALL user_auth_credentials('$username', '$password')";
        $rs  = $this->query($sql);
        
        if ($rs->hasRecords()) {
            // Set the authorized user
            $this->userid = (int) $rs->id;
            
            return $this->userid;
        }
        
        $this->error = 'Username/Password combination not valid.';
        return false;
    }
}
