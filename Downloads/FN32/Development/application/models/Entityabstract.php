<?php
/**
 * Abstract Application_Model_Entityabstract class.
 * 
 * Each Entity type will extend this class to get the core entity methods, 
 * such as getting, adding, deleting, updating. The extending entity classes
 * should only contain methods that are unique to those entity types.
 *
 * TODO: Need to go through and prefix all protected property names with "_".
 * 
 * @abstract
 * @extends Application_Model_Abstract
 */
abstract class Application_Model_Entityabstract extends Application_Model_Abstract {
    /**
     * User model of the user making the requests
     * 
     * @var Application_Model_User
     * @access protected
     */
    protected $user;
    
    /**
     * ID of this entity
     * 
     * @var int
     * @access protected
     */
    protected $id;
    
    /**
     * Type ID that defines what kind of entity this is.
     * 
     * @var int
     * @access protected
     */
    protected $_typeid;
    
    /**
     * Time this entity was created.
     * 
     * @var datetime
     * @access protected
     */
    protected $createtime;
    
    /**
     * Userid of the user who created this entity.
     * 
     * @var int
     * @access protected
     */
    protected $createuser;
    
    /**
     * Time this entity was deactivated.
     * 
     * @var datetime
     * @access protected
     */
    protected $deactivatetime;
    
    /**
     * Userid of the user who deactivated this entity.
     * 
     * @var int
     * @access protected
     */
    protected $deactivateuser;
    
    /**
     * ID of the parent entity this entity is a child of.
     * 
     * @var int
     * @access protected
     */
    protected $parententity;
    
    /**
     * Is this a valid entity?
     * 
     * (default value: false)
     * 
     * @var bool
     * @access protected
     */
    protected $valid = false;
    
    /**
     * This is the field that will be checked for to already exist in entity meta for 
     * this entity type. This will prevent duplicate entity's, in a way, kinda. Need to
     * really look at this problem and figure out the best solution. TODO: read back.
     * 
     * @access protected
     * @var string
     */
    protected $_checkfield;
    
    /**
     * Every entity must be accessed with a User model (which is also an
     * entity). This will determine whether the user requesting the entity
     * has permission to or not.
     *
     * @access public
     * @param Application_Model_User $user User model accessing this entity
     * @param int $id ID of the entity being requested [optional]
     */
    public function __construct(Application_Model_User $user, $id = null, $loadby = 'id') {
        // Get our type id up front
        $this->_setTypeId();    	
        
        // A user is required to be passed for all entity types other than the User entity.
        if (!$user->isValid()) {
            $this->error = 'A valid user model must be passed to access any entities.';
            return false;
        }
        
        $this->user = $user;
        
        // Standardize the loadby var
        $loadby = strtolower($loadby);
        
        // If an entity id was passed, load its existing data/profile
        if (!empty($loadby) && $loadby !== 'id') {
            $this->loadEntityBy($loadby, $id);
        } else {
            // Load by id
            if ($id) {
                $this->id = $id;
                
                $this->loadEntityById($this->id);
            }
        }
    }
    
    /**
     * loadEntity function.
     * 
     * @access public
     * @param mixed $id
     * @return void
     */
    public function loadEntityById($id) {
        // We can call userCan() here because we already have the ID.
        if ($this->userCan()) {
            $sql = "CALL entity_get_by_id($id)";
            $rs = $this->query($sql);
            
            if ($this->hasError()) {
                error_log($this->getError());
                $this->error = "Unable to load $id";
                return false;
            }
            
            if ($rs->hasRecords()) {
                $this->loadMeta($rs);
                
                $this->valid = true;
                
                return true;
            } else {
                $this->error = "[$id] not found";
            }
        }
        
        return false;
    }
    
    /**
     * Load an Entity by a specified fieldname and value.
     * 
     * @access public
     * @param mixed $loadby Fieldname to compare $id to.
     * @param mixed $id Value of the $loadby column to look for.
     * @return bool
     */
    public function loadEntityBy($loadby, $id) {
        $loadby = $this->_dbh->real_escape_string($loadby);
        $id     = $this->_dbh->real_escape_string($id);
        $sql    = "CALL entity_get('{$this->_type}', '$loadby', '$id')";
        $rs     = $this->query($sql);
        
        if ($this->hasError()) {
            error_log($this->getError());
            $this->error = "Unable to load $id by $loadby";
            return false;
        }
        
        if ($rs->hasRecords()) {
            $this->loadMeta($rs);
            
            $this->valid = true;
        } else {
            $this->error = "[$loadby : $id] not found.";
            return false;
        }
        
        // Now that we have the id set, we can see if this user can access this entity
        if ($this->userCan()) {
            return true;
        }
        
        return false;
    }
    
    /**
     * I don't like this function, but I was doing this twice so I made a function.
     * 
     * @access private
     * @param mixed $rs Result set
     * @return void
     */
    private function loadMeta($rs) {
        // Set the base attributes for all entities
        $this->id           = $rs->id;
        $this->_typeid      = $rs->typeid;
        $this->createtime   = $rs->createtime;
        $this->createuser   = $rs->createuser;
        $this->parententity = $rs->parententity;
        
        // Load each entity attribute
        for ($rs->startRecord(); $rs->hasRecord(); $rs->nextRecord()) {
            // TODO: Use profile default value if fieldvalue is null?
            $this->{$rs->fieldname} = $rs->fieldvalue !== null ? $rs->fieldvalue : $rs->defaultvalue;
        }
    }
    
    /**
     * Get the Meta Profile of this entity type.
     * 
     * @access public
     * @return array
     */
    public function getProfile($typeid = null) {
        $typeid = $typeid === null ? $this->_typeid : $typeid;
        
        $sql = "CALL entity_get_profile({$typeid})";
        $rs = $this->query($sql);
        
        if ($this->hasError()) {
            error_log($this->getError());
            $this->error = 'Unable to get entity profile ['.$typeid.']';
            return false;
        }
        
        if ($rs->hasResults()) {
            $profile = array();
            
            for ($rs->startRecord(); $rs->hasRecord(); $rs->nextRecord()) {
                // TODO: May want to include all values here, not sure.
                $profile[$rs->fieldname]['id'] = (int) $rs->id;
                $profile[$rs->fieldname]['defaultvalue'] = $rs->defaultvalue;
            }
            //var_dump($profile); die;
            return $profile;
        }
        
        $this->error = 'No profile found for entity ['.$typeid.']';
        return false;
    }
    
    /**
     * Get all of the available Entity Types.
     * 
     * @access public
     * @return array
     */
    public function getEntityTypes() {
        $sql = "CALL entity_get_types()";
        $rs = $this->query($sql);
        
        if ($this->hasError()) {
            error_log($this->getError());
            $this->error = 'Unable to retrieve entity types.';
            return false;
        }
        
        
    }
    
    /**
     * Add a new Entity.
     * 
     * @access public
     * @param mixed $typeid Type of entity to add
     * @param mixed $parententity Parent of the new entity
     * @return int The new entities ID
     */
    public function add($typeid, $parententity = 0) {
        // 1st set this entity to have the type id of the type we want to create
        $this->typeid = $typeid;
        $parententity = $parententity == 0 ? $this->user->getId() : $parententity;
        $sql = "CALL entity_add($typeid, {$this->user->getId()}, $parententity)";
        $rs = $this->query($sql);
        
        if ($this->hasError()) {
            error_log($this->getError());
            $this->error = 'Unable to add entity.';
            return false;
        }
        
        $id = $rs->id;
        
        // Link the new entity to the adding user
        if (!$this->link($this->user->getId(), $id)) {
            return false;
        }
        
        // Return the new entity id
        return $id;
    }
    
    /**
     * Link one entity to another in a parent/child relationship.
     * 
     * @access public
     * @param mixed $parent
     * @param mixed $child
     * @return void
     */
    public function link($parent, $child) {
        $sql = "CALL entity_link($parent, $child)";
        $rs = $this->query($sql);
        
        if ($this->hasError()) {
            error_log($this->getError());
            $this->error = 'Unable to link new entity to user.';
            return false;
        }
        
        return true;
    }
    
    public function exists($entitytype, $fieldname, $value) {
    	$entitytype = $this->escape($entitytype);
    	$fieldname  = $this->escape($fieldname);
    	$value      = $this->escape($value);
    	
        $sql = "CALL entity_get('$entitytype', '$fieldname', '$value')";
        //echo $sql; die;
        $rs  = $this->query($sql);
        
        return $rs->hasRecords();
    	
    }
    
    /**
     * Add a new Entity and immediately load meta data for it.
     * 
     * @access public
     * @param Array $meta Meta data for new entity
     * @return bool Add success or failure
     */
    public function addWithMeta(array $meta) { //echo "<pre>"; print_r($this->getProfile()); echo "<pre>"; print_r($meta); exit;
        if ($this->userCan()) {
           // 1st add the entity so we can get an id
            if (($id = $this->add($this->_typeid)) !== false) {
                // Next add the meta data for this new location
                
                // Get this entity's profile so we only handle fields that are legit
                if (($profile = $this->getProfile()) !== false) {
                    // Now add the meta data for each profile field 
                    foreach ($profile as $field => $value) {
                        /**
                         * TODO: We need a way to flag which fields are user populated and which 
                         * are only system populated. Such as activatetime, deactivatetime, etc...
                         */
                        if (isset($meta[$field])) {
                           $profileid = $value['id'];
                           $value = empty($meta[$field]) ? $value['defaultvalue'] : trim($meta[$field]);
                            $value = $this->_dbh->real_escape_string($value);
                           $sql = "CALL entity_meta_add($id, $profileid, '$value', {$this->user->getId()})";
                           $rs = $this->query($sql);
                           
                           if ($this->hasError()) {
                               error_log($this->getError());
                               $this->error = "Failed to load entity meta. Could not add entity meta value [$value]";
                               return false;
                           }
                    }
                   }
                   // Set the id for the added entity as well as validate it.
                   $this->id = $id;
                   $this->valid = true;
                   
                   return $id;
            }
        }
       }
       
       return false;
    }
     public function addWithMetaNev(array $meta) {
        if ($meta['nev'] == 'true') {
            $this->_typeid = 7;
        }
//        echo "<pre>";
//        print_r($this->getProfile($this->_typeid));
//        echo "<pre>";
//        print_r($meta);
//        exit;
        if ($meta['nev'] == 'true') {
            if ($this->userCan()) {
                // 1st add the entity so we can get an id
                if (($id = $this->add($this->_typeid)) !== false) {
                    // Next add the meta data for this new location
                    // Get this entity's profile so we only handle fields that are legit
                    if (($profile = $this->getProfile($this->_typeid)) !== false) {

                        // Now add the meta data for each profile field 
                        foreach ($profile as $field => $value) {
                            /**
                             * TODO: We need a way to flag which fields are user populated and which 
                             * are only system populated. Such as activatetime, deactivatetime, etc...
                             */
                            if (isset($meta[$field])) {
                                $profileid = $value['id'];
                                    $value = empty($meta[$field]) ? $value['defaultvalue'] : trim($meta[$field]);
                                $value = $this->_dbh->real_escape_string($value);
//                                echo '<br>'.$value;
//                                exit;
                                $sql = "CALL entity_meta_add($id, $profileid, '$value', {$this->user->getId()})";
                                $rs = $this->query($sql);
                                if ($this->hasError()) {
                                    error_log($this->getError());
                                    $this->error = "Failed to load entity meta. Could not add entity meta value [$value]";
                                    return false;
                                }
                            }
                        }
                        // Set the id for the added entity as well as validate it.
                        $this->id = $id;
                        $this->valid = true;
                        return $id;
                    }
                }
            }
        } else {
            if ($this->userCan()) {
                // 1st add the entity so we can get an id
                if (($id = $this->add($this->_typeid)) !== false) {
                    // Next add the meta data for this new location
                    // Get this entity's profile so we only handle fields that are legit
                    if (($profile = $this->getProfile()) !== false) {

                        // Now add the meta data for each profile field 
                        foreach ($profile as $field => $value) {
                            /**
                             * TODO: We need a way to flag which fields are user populated and which 
                             * are only system populated. Such as activatetime, deactivatetime, etc...
                             */
                            if (isset($meta[$field])) {
                                $profileid = $value['id'];
                                  $value = empty($meta[$field]) ? $value['defaultvalue'] : trim($meta[$field]);
                                $value = $this->_dbh->real_escape_string($value);
                                $sql = "CALL entity_meta_add($id, $profileid, '$value', {$this->user->getId()})";
                                $rs = $this->query($sql);
                                if ($this->hasError()) {
                                    error_log($this->getError());
                                    $this->error = "Failed to load entity meta. Could not add entity meta value [$value]";
                                    return false;
                                }
                            }
                        }
                        // Set the id for the added entity as well as validate it.
                        $this->id = $id;
                        $this->valid = true;
                        return $id;
                    }
                }
            }
        }

        return false;
    }
    /**
     * Update a single meta value.
     * 
     * @access public
     * @return bool
     */
    public function updateMetaValue($profileid, $value) {
        $value = $this->_dbh->real_escape_string($value);
        $sql = "CALL entity_meta_update($this->id, $profileid, '$value', {$this->user->getId()})";
        $rs = $this->query($sql);
        
        if ($this->hasError()) {
            error_log($this->getError());
            $this->error = 'Unable to update entity meta information.';
            return false;
        }
        
        return true;
    }
    
    /**
     * Update multiple meta values at once.
     * 
     * @access public
     * @param Array $meta Meta data to update
     * @return bool Edit success or failure
     */
	public function updateMetaValues(array $meta) {
		if ($this->userCan()) {
			// Get this entity's profile so we only handle fields that are legit
			if (($profile = $this->getProfile()) !== false) {
				// Now add the meta data for each profile field
				foreach ($profile as $field => $value) {
					/**
					 * TODO: We need a way to flag which fields are user populated and which
					 * are only system populated. Such as activatetime, deactivatetime, etc...
					 */
					if (isset($meta[$field])) {
						$profileid = $value['id'];
						$value     = trim($meta[$field]);
						
						if (!$this->updateMetaValue($profileid, $value)) {
							return false;
						}
					}
				}
				
				// Confirm that this entity is still valid after the update.
				$this->valid = true;
				
				return true;
			}
		}
		
		return false;
	}
    
    /**
     * Delete this entity. It won't be "deleted" but it will be deactivated.
     * 
     * @access public
     * @return bool
     */
    public function delete() {
        // Not going to delete the entity, but will disable it.
        $sql = "CALL entity_deactivate($this->id, {$this->user->getId()})";
        $rs = $this->query($sql);
        
        if ($this->hasError()) {
            error_log($this->getError());
            $this->error = "Failed to delete entity $id";
            return false;
        }
        
        return true;
    }
    
    /**
     * Still playing around with how I want to handle permissions.
     * This is a method that can be called before each action to 
     * see if the user can do what they are asking to.
     * 
     * @access public
     * @return void
     */
    protected function userCan() {
        // User can edit themselves
        if ($this->id == $this->user->getId()) {
            return true;
        }
        
        // TODO: Need to expand upon this? Probably...
        // we can add in what the user is trying to do, and check perms
        // on top of just ownership. This will do for now.
        if ($this->id) {
            if ($this->user->isParentOf($this->id) || $this->user->isSuperAdmin()) {
                return true;
            }
        } else {
            // If there was no id already set, we are probably adding a folder. 
            // TODO: Need to handle perms somehow here.
            return true;
        }
        
        // Set this entity to not valid so we do not give anything away
        // TODO: Maybe make a default() method that we can reset a built entity to it's defaults.
        // defaults could be set from the entity profile.
        $this->valid = false;
        $this->error = 'User does not have permission to execute the request.';
        //$this->error = 'ID does not exist for this user.';
        return false;
    }
    
    /**
     * getId function.
     * 
     * @access public
     * @return int
     */
    public function getId() {
        return $this->id;
    }
    
    /**
     * getTypeid function.
     * 
     * @access public
     * @return int
     */
    public function getTypeid() {
        return $this->typeid;
    }
    
    /**
     * getCreatetime function.
     * 
     * @access public
     * @return datetime
     */
    public function getCreatetime() {
        return $this->createtime;
    }
    
    /**
     * getCreateuser function.
     * 
     * @access public
     * @return int
     */
    public function getCreateuser() {
        return $this->createuser;
    }
    
    /**
     * getParententity function.
     * 
     * @access public
     * @return int
     */
    public function getParententity() {
        return $this->parententity;
    }
    
    /**
     * Is this entity valid?
     *
     * @access public
     * @return bool yes or no
     */
    public function isValid() {
        return $this->valid;
    }
    
    /**
     * Returns whether this entity has been deactivated or not.
     * 
     * @access public
     * @return bool Active or not
     */
    public function isActive() {
        return !$this->deactivatetime > 0;
    }
    
    /**
     * Sets the type id for this entity
     */
    protected function _setTypeId() {
    	$this->_typeid = $this->_getTypeId();
    }
    
    /**
     * Gets the entity type id for this entity type
     * 
     * @access protected
     * @return int
     */
    protected function _getTypeId() {
        $slug = strtolower(str_replace('Application_Model_', '', get_class($this)));
        $sql = "CALL entity_get_typeid('$slug')";
        $rs = $this->query($sql);
        if ($rs && $rs->num_rows) {
            if ($rs->entityid) {
                return $rs->entityid;
            }
        }
        
        return 0;
    }
}
