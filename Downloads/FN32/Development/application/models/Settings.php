<?php
/**
 * Settings Object that holds all site settings from the DB.
 * 
 * @extends Application_Model_Abstract
 */
class Application_Model_Settings extends Application_Model_Abstract {
	
	private static $instance;
	
	public static function getInstance() {
        if (!isset(self::$instance)) {
            $class = __CLASS__;
            self::$instance = new $class;
        }
        
        return self::$instance;
	}
	
	private function __construct() {
		// See if we are testing or not. If so, the settings_get
		// proc will grab a different column of settings values.
		$testing = (int) $this->config()->testing;
		
		$sql = "CALL settings_get($testing)";
        $rs = $this->query($sql);
        
        if ($this->hasError()) {
            error_log($this->getError());
            $this->error = "Unable to load settings";
        }
        
        if ($rs->hasRecords()) {
			while ($row = $rs->fetchArray()) {
				$this->{$row['label']} = $row['value'];
			}
			
			// Handle secure cookies
			if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']) {
				$this->cookiesecure = 1;
			}
		}
	}
}
