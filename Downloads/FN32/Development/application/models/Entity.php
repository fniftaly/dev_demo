<?php

/**
 * Application_Model_Entity abstract class.
 * 
 * This class will be extended by each entity type.
 * 
 * @extends Application_Model_Abstract
 */
class Application_Model_Entity extends Application_Model_Abstract {
	/**
     * Array of Entitymeta objects that contain the meta data for each attribute of this model.
     * 
     * (default value: array())
     * 
     * @var array
     * @access private
     */
    private $entitymeta = array();
    
    /**
     * Abstract function to load the entity.
     * 
     * @access public
     * @abstract
     * @return void
     */
    abstract public function load();
    
    /**
     * addEntity function.
     * 
     * @access public
     * @param mixed $typeid
     * @param mixed $createuser
     * @param mixed $parententity
     * @return void
     */
    public function addEntity($typeid, $createuser, $parententity) {
        
        // typeid, createuser, parententity
        $sql = "CALL entity_add($typeid, $createuser, $parententity)";
		$rs = $this->query($sql);
        var_dump($rs); die;
        
        
        #get back new entity id and profile meta fields
        $id = $rs->id;
        $profile = array();
    }
    
    /**
     * addEntityMeta function.
     * 
     * @access public
     * @return void
     */
    public function addEntityMeta($entityid, $profileid, $value, $userid) {
        $sql = "CALL entity_meta_add($entityid, $profileid, $value, $userid)";
		$rs = $this->query($sql);
        var_dump($rs); die;
        
        return $rs->new_id;
    }
    
    /**
     * getEntityTypes function.
     * 
     * @access public
     * @return void
     */
    public function getEntityTypes() {
        $sql = "CALL entity_get_types($this->userid)";
		$rs = $this->query($sql);
        var_dump($rs); die;
        
        
    }
    
    /**
     * THIS MAY GO AWAY, NOT SURE YET. RIGHT NOW THE load() METHOD
     * IS HANDLING SETTING THE METADATA, BUT I WANT TO BREAK THAT OUT
     * AND STANDARDIZE IT.
     * Returns the Entitymeta object for the passed fieldname.
     * If a metaname is passed it only returns the value for that
     * metafield.
     * 
     * @access public
     * @param mixed $fieldname
     * @param mixed $metaname (default: null)
     * @return void
     */
    public function getEntityMeta($fieldname, $metaname = null) {
        if (isset($this->entitymeta[$fieldname])) {
            
            $entitymeta = $this->entitymeta[$fieldname];
            
            if ($metaname !== null) {
                return isset($entitymeta->$metaname) ? $entitymeta->$metaname : null;
            }
            
            return $entitymeta;
        }
    }
    
    /*--- ADD ENTITY FLOW ---
    -get entity types
      -only that user is allowed to create
    -get entity profile for selected entity
    -get input data
    -add entity
    -add entity meta data
    
    # get types
    CALL textmunication.entity_get_types(1);
    
    #add the selected entity type
    CALL textmunication.entity_add(4, 1, 0);
    
    #get back new entity id and profile meta fields
    id = int
    profileid = array()
    
    #add the metadata for this entity type
    foreach (profileid) {
        CALL textmunication.entity_add_meta(id,pid,'value',1)
    }
    
    #get back meta data id?
    */
}