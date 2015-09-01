<?php
/**
 *
 *
 */
abstract class Application_Model_Abstract {
	/**
	 * The log file for model logging
	 * 
	 * @var string
	 */
	public $logFile = '/tmp/datalog.log';
	
	/**
	 * An error string of the last query
	 * 
	 * @var string
	 */
	public $error;
	
	/**
	 * Database connection handle
	 * 
	 * @var MySQLi
	 */
	protected $_dbh;
	
	/**
	 * Framework Config Object
	 * 
	 * @var Zend_Config
	 * @access protected
	 */
	protected $_config;
	
	/**
	 * The HTTP Request Object
	 *
	 * @access protected
	 * @var Zend_Controller_Request_Http
	 */
	protected $_request;
	
	/**
	 * Application Settings Object
	 * 
	 * @var Application_Model_Settings
	 * @access protected
	 */
	protected $_settings;
	
	/**
	 * Overload data holder
	 * 
	 * @access protected
	 * @var array
	 */
	protected $_modelData = array();
	
	/**
	 * Registry key that holds our database connection handle
	 * 
	 * @var string
	 */
	private $_regKey = 'dbhandle';
	
	/**
	 * Gets the Application Config File as an object.
	 * 
	 * @access public
	 * @return void
	 */
	public function config() {
	    if (!$this->_config) {
	        $this->_config = Zend_Registry::get('config');
        }
        
        return $this->_config;
	}
	
	/**
	 * Gets the Settings Object.
	 * 
	 * @access public
	 * @return void
	 */
	public function settings() {
	    if (!$this->_settings) {
            $this->_settings = Application_Model_Settings::getInstance();
		}
		
		return $this->_settings;
	}
	
	/**
	 * Gets the HTTP request object
	 *
	 * @access public
	 * @return Zend_Controller_Request_Http
	 */
	public function request() {
		if (!$this->_request) {
			$this->_request = Zend_Controller_Front::getInstance()->getRequest();
		}
		
		return $this->_request;
	}
	
	/**
	 * Runs a query and returns a result object
	 *  
	 * @param string $query
	 * @return Application_Model_Queryresult
	 */
	public function query($query) {
		// If there is no query we can't really do anything
		if (!$query) {
			$this->error = 'No query was requested.';
			return false;
		}
		
		// If there is no connection handle, get one
		if (!$this->_dbh) {
			$this->_getDbh();
		}
		
		$return = false;
		
		// Check again to make sure we are good
		if ($this->_dbh) {
			// Start stacking
			Application_Model_Queryprofiler::setQuery($query);
			
			// Start the timer
			$start = microtime(true);
			
			// Handle processing of the result data setting
			//$rs = $this->_dbh->query($query);
			$result = new Application_Model_Queryresult();
			
			if ($this->_dbh->multi_query($query)) {
				do {
					if (($rs = $this->_dbh->use_result()) !== false) {
						$result->setResult($rs);
						$rs->free();
					} else {
						if ($this->_dbh->errno) {
							// there was an error
							$this->error .= ' ' . $this->_dbh->error;
						}
					}
                } while ($this->_dbh->more_results() && $this->_dbh->next_result());
			} else { 
				if (($rs = $this->_dbh->query($query)) !== false) {
					$result->setResult($rs);
					$rs->free();
				} else {
					if ($this->_dbh->errno) {
						$this->error = $this->_dbh->error;
					}
				}
			}
			
			// Stop the time
			$stop = microtime(true);
			
			// Handle errors
			//if ($this->_dbh->error) {
			//	$this->error = $this->_dbh->error;
			//}
			
			// Handle timer
			$totaltime = $stop - $start;
			Application_Model_Queryprofiler::setQueryTime($totaltime);
			Application_Model_Queryprofiler::setQueryError($this->error);
			
			// Set the result sets, record sets and statistics
			$result->setResultStats();
			
			//$return = $rs;
			$return = $result;
		} else {
			$this->error = 'There is no database connector.';
		}
		
		return $return;
	}
	
	/**
	 * Escapes a string for entry into the database
	 * 
	 * @param string $string
	 * @return string
	 */
	public function escape($string) {
		$this->_getDbh();
		return $this->_dbh->escape_string($string);
	}
	
	/**
	 * Gets a database handle
	 *
	 * If there isn't one set yet this will attempt to create one if there
	 * isn't one found in the registry
	 *
	 * @return MySQLi
	 */
	protected function _getDbh() {
		// If there is no database handle...
		if (null === $this->_dbh) {
			// If there is a registered handle, get that
			if (Zend_Registry::isRegistered($this->_regKey)) {
				$this->_dbh = Zend_Registry::get($this->_regKey);
			} else {
				// Create one, register it and move on
				// Start with getting our config options
				/* TODO: Remove this old code when everything is confirmed to be working.
				I switched the Bootstrap to register the config as an object instead of array.
				$options = Zend_Registry::get('config');
				
				// Get just the database params from the config
				$creds = $options['resources']['db']['params'];
				*/
				$creds = $this->config()->resources->db->params;
				// Connect
				$this->_dbh = new mysqli($creds->host, $creds->username, $creds->password, $creds->dbname);
				
				// Register
				Zend_Registry::set($this->_regKey, $this->_dbh);
			}
		}
		
		return $this->_dbh;
	}
	
	/**
	 * Sets a database handle
	 *
	 * Not sure this will be of any value to use since our handle should be set
	 * internally by this class. Still, makes sense to have this.
	 */
	protected function _setDbh($dbh) {
		$this->_dbh = $dbh;
	}
	
	/**
	 * Sets an error message into the error property.
	 * 
	 * Can also be used to log error messages. You have the option to log the error
	 * string being set, or a seperate error string that may contain info you want
	 * to see in the log but don't want the user to see.
	 *
	 * @access public
	 * @param string $error The error message
	 * @param string|bool $log If true, will log the error message, if a string will log the string passed.
	 */
	public function setError($error, $log = false) {
		if ($log) {
		    $log = (is_string($log)) ? $log : $error;
		    error_log($log);
        }
        
		$this->error = $error;
	}
	
	/**
	 * Gets the error message
	 *
	 * This is a simple accessor. Since this is a public property you could just
	 * as easily say $model->error
	 *
	 * @return string
	 */
	public function getError() {
		return $this->error;
	}
	
	/**
	 * Is there an error set?
	 *
	 * @return bool
	 */
	public function hasError() {
		return !empty($this->error);
	}
	
	/**
	 * Gets the total number of queries ran up to this point
	 *
	 * @return int
	 */
	public function queryCount() {
		return Application_Model_Queryprofiler::getQueryCount();
	}
	
	/**
	 * Gets the current query id
	 *
	 * @return int
	 */
	public function queryId() {
		return Application_Model_Queryprofiler::getQueryId();
	}
	
	/**
	 * Gets the stack of queries run up to this point, in order, as an array
	 * keyed on query id OR, if an id is specified, gets the query for that id.
	 *
	 * @access public
	 * @param int $id The query id
	 * @return array
	 */
	public function queryStack($id = 0) {
		return Application_Model_Queryprofiler::getQueryStack($id);
	}
	
	/**
	 * Gets the current query execution time to $precision decimal places
	 *
	 * @access public
	 * @param int $precision The precision to apply to the time
	 * @return float|string
	 */
	public function queryTime($precision = 0) {
		return Application_Model_Queryprofiler::getQueryTime($precision);
	}
	
	/**
	 * Gets the total query execution time to $precision decimal places
	 *
	 * @access public
	 * @param int $precision The precision to apply to the time
	 * @return float|string
	 */
	public function queryTimeTotal($precision = 0) {
		return Application_Model_Queryprofiler::getQueryTimeTotal($precision);
	}
	
	/**
	 * Cleans up null values for entry into the database
	 * 
	 * @param string $value
	 */
	public function saveNull($value) {
	   return $value === null ? '(NULL)' : $value;
	}
	
	/**
	 * Loads this model up with overloaded data as though from a single row from
	 * a DB result set
	 * 
	 * @param array $array
	 */
	public function loadFromArray(array $array = array()) {
		if (is_array($array)) {
			foreach ($array as $k => $v) {
				if (!empty($k) && !is_numeric($k)) {
					$this->{$k} = $v;
				}
			}
		}
	}
	
	/**
	 * Overloaded setter method
	 * 
	 * @param string $label
	 * @param mixed $value
	 */
	public function __set($label, $value) {
		$this->_modelData[$label] = $value;
	}
	
	/**
	 * Overloaded getter method
	 * 
	 * @access public
	 * @param string $label
	 */
	public function __get($label) {
		return $this->__isset($label) ? $this->_modelData[$label] : null;
	}
	
	/**
	 * Overloaded issetter method
	 * 
	 * @access public
	 * @param string $label
	 */
	public function __isset($label) {
		return array_key_exists($label, $this->_modelData);
	}
	
	/**
	 * Overloaded unsetter method
	 * 
	 * @access public
	 * @param string $label
	 */
	public function __unset($label) {
		if ($this->__isset($label)) {
			unset($this->_modelData[$label]);
		}
	}
		
	protected function _writeLog($line) {
		$date = date('Y-m-d H:i:s');
		$fh = fopen($this->logFile, 'a');
		fwrite($fh, "$date $line\n");
		fclose($fh);
	}
}

