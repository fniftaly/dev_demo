<?php
/**
 * Application_Model_Subscriber class.
 * 
 * @extends Application_Model_Abstract
 */
class Application_Model_Subscriber extends Application_Model_Abstract {
	/**
     * 
     * 
     * @access private
     * @var string
     */
    protected $_id;
    
    /**
     * 
     * 
     * @access private
     * @var string
     */
    protected $_keywordid;
    
    /**
     * 
     * 
     * @access private
     * @var string
     */
    protected $_phonenumber;
    
    /**
     * 
     * 
     * @access private
     * @var string
     */
    protected $_createtime;
    
    /**
     * Not sure what this is. It was a column in the subscriber table so I included it.
     * 
     * @access private
     * @var string
     */
    protected $_uniqueid;
    
    /**
     * 
     * 
     * @access private
     * @var string
     */
    protected $_firstname;
    
    /**
     * 
     * 
     * @access private
     * @var string
     */
    protected $_lastname;
    
    /**
     * 
     * 
     * @access private
     * @var string
     */
    protected $_email;
    
    /**
     * 
     * 
     * @access private
     * @var string
     */
    protected $_optouttime;
    
    public function getId() {
        return $this->_id;
    }
    
    /**
     * Add a subscriber.
     * 
     * @access public
     * @param int $keywordid
     * @param int $phonenumber
     * @return int|bool New subscribers id upon success, false upon failure.
     */
    public function add($phonenumber, $keywordid = 0) {
        $sql = "CALL subscriber_add({$keywordid},{$phonenumber})";
        
        $rs = $this->query($sql);
        
        if ($this->hasError()) {
           $this->setError('Could not add subscriber new subscriber.', $this->getError());
           return false;
        }
        $this->_phonenumber = $phonenumber;
        $this->_id = $rs->id;
        
        return $this->_id;;
    }
    
    /**
     * Load a subscriber data by subscriber id.
     * 
     * @access public
     * @param int $id (default: null)
     * @return void
     */
    public function load($id = null) {
        $id = $id === null ? $this->_id : $id;
        
        $sql = "";
        $rs = $this->query($sql);
        
    }
        
    /**
     * Gets a listing of folders for a phone number
     * 
     * @param string $phone
     * @param boolean $all
     * @return array
     */
    public function getContainingFolders($phone, $all = true) {
    	$proc = $all ? 'subscriber_get_folders' : 'subscriber_get_folders_optedin';
    	$sql = "CALL $proc('$phone')";
    	$rs = $this->query($sql);
    	$return = array();
    	if ($rs && $rs->num_rows) {
    		while ($row = $rs->fetchObject()) {
    			$return[] = $row;
    		}
    	}
    	
    	return $return;
    }
    
    public function getAllSubscribersByUser($userid) {
        if ($userid) { 
            $sql = "CALL folder_get_subscribers_inout_byuser($userid)";
            $rs  = $this->query($sql);
            if ($rs->hasRecords()) {
                    return $rs->fetchAll();
            }

		}
		return NULL;
    }       
}
