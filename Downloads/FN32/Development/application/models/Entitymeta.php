<?php
/**
 * Application_Model_Entitymeta class.
 * 
 * Base structure for an entity metadata.
 */
class Application_Model_Entitymeta {
	/**
     * ID of the entitymeta row.
     * 
     * @access public
     * @var int
     */
    public $id;
    
    /**
     * ID of the entity this piece of metadata belongs to.
     * 
     * @access public
     * @var int
     */
    public $entityid;
    
    /**
     * Profile id that this metadata value links to.
     * 
     * @access public
     * @var int
     */
    public $profileid;
    
    /**
     * ID of the entitymeta row
     * 
     * @access public
     * @var string
     */
    public $value;
    
    /**
     * ID of the user who last edited this metadata.
     * 
     * @access public
     * @var int
     */
    public $edituser;
    
    /**
     * Datetime this metadata was last edited.
     * 
     * @access public
     * @var datetime
     */
    public $edittime;
    
    /**
     * __construct function.
     * 
     * @access public
     * @param array $attribs (default: array())
     * @return void
     */
    public function __construct($attribs = array()) {
        foreach ($attribs as $label => $value) {
            $this->$label = $value;
        }
    }
}