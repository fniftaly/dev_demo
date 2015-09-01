<?php

/**
 * Application_Model_User class.
 * 
 * @extends Application_Model_Entityabstract
 */
class Application_Model_User extends Application_Model_Abstract {

    /**
     * User username
     * 
     * @access private
     * @var string
     */
    private $username;

    /**
     * Is this a valid user account?
     * 
     * @access private
     * @var bool
     */
    private $valid = false;

    /**
     * Is this user a system admin?
     * 
     * @var bool
     * @access private
     */
    private $admin = false;

    /**
     * User Status
     * 
     * Current status of the user account, is it pending, active, inactive...
     * 
     * Going to default this to 1 (pending) just in case it doesn't exist in the
     * db for some reason. In that case, the user will always be pending and
     * most likely prompt some action to activate it.
     * 
     * @access protected
     * @var bool
     */
    protected $status = 1;

    /**
     * Valid Status Types
     * 
     * @var mixed
     * @access public
     */
    public $status_types;

    /**
     * Internal User ID
     * 
     * @var int
     * @access private
     */
    private $id = 0;

    /**
     * Internal CreateUser
     * 
     * @var int
     * @access private
     */
    private $createuser = 0;

    /**
     * Timezone for this user.
     * 
     * @var mixed
     * @access private
     */
    private $timezone = null;

    /**
     * Model Constructor loads a user profile if a username is passed.
     *
     * @access public
     * @param string $username Pass a user to load the user profile upon model instatiation
     */
    public function __construct($user = null) {
        // If a user identifier was passed, try and load said user
        if ($user) {
            if (is_int($user)) {
                $this->loadById($user);
            }

            if (is_string($user)) {
                $this->loadByUsername($user);
            }
        }
    }

    /**
     * Is the email passed a valid email in our system?
     * 
     * @access public
     * @param mixed $email
     * @return void
     */
    public function validEmail($email) {
        $sql = "CALL user_valid_email('{$email}')";
        $rs = $this->query($sql);

        return $rs->valid;
    }

    /**
     * Calls the load method passing the id. This is really
     * just here to keep consitant naming with loadByUsername().
     * Plus, I am contemplating using a loadEntity method, with
     * the type of entity you want to get, and by which field,
     * in which case this method could pass the required params.
     * 
     * @access public
     * @param int $userid
     * @return bool Whether the user was loaded or not
     */
    public function loadById($userid) {
        return $this->load($userid);
    }

    /**
     * Gets the user ID for the passed username, then calls
     * load with the found user ID.
     * 
     * @access public
     * @param string $username
     * @return bool Whether the user was loaded or not
     */
    public function loadByUsername($username) {
        if (($userid = $this->getUserId($username)) !== false) {
            return $this->load($userid);
        }

        return false;
    }

    /**
     * Get a users entity ID by passing their username.
     * 
     * @access public
     * @param string $username
     * @return int The user ID of the passed username
     */
    public function getUserId($username) {
        $sql = "CALL user_get_id('$username')";
        $rs = $this->query($sql);

        if ($rs->hasRecords()) {
            // Not sure if there is an easier way to get this, but this works...
            $user = $rs->getResults(0);
            $userid = $user[0]['id'];

            return $userid;
        }

        $this->error = 'Username not found.';
        return false;
    }

    /**
     * Loads a users data into this model by user ID
     *
     * @access public
     * @param string $userid ID of the User entity being loaded.
     */
    public function load($userid) {
        $this->id = $userid;
        $sql = "CALL user_get($userid)";

        $rs = $this->query($sql);

        if ($rs->hasRecords()) {
            $attribs = $rs->getResults(0);
            // Load each attribute intot he model
            foreach ($attribs as $attrib) {
                // If the value field is null, use the default value
                //$this->$attrib['fieldname'] = $attrib['value'] === null ? $attrib['defaultvalue'] : $attrib['value'];
                $this->$attrib['fieldname'] = $attrib['fieldvalue'];
                // Now save each attribs meta data as a meta object
                // This is useless until I figure out how to return the entity data from the db properly
                //$this->entitymeta[$attrib['fieldname']] = new Application_Model_Entitymeta($attrib);
            }
            // We just loaded this user ourselves, so obviously it should be valid.
            $this->valid = true;

            return true;
        }

        $this->setError('User does not exist');
        return false;
    }

    /**
     * Validates the current user model
     *
     * @access public
     * @return bool Is the user valid?
     */
    public function isValid() {
        // Run the user model data through some validation routines here
        //$this->valid = true;
        return $this->valid;
    }

    /**
     * Is this user an admin.
     * 
     * @access public
     * @return bool
     */
    public function isAdmin() {
        return $this->admin;
    }

    /**
     * Level above admins, mainly for testing until we set up full permission structures.
     * 
     * @access public
     * @return bool
     */
    public function isSuperAdmin() {
        return $this->superadmin;
    }

    /**
     * Can this user import subscribers to a folder?
     * 
     * TODO: This should become a permission
     * 
     * @access public
     * @return bool
     */
    public function canImport() {
        // Let admins that are acting as another user import
        if (($asUser = Zend_Registry::get('session')->canImport)) {
            $this->canimport = true;
        }

        return $this->canimport;
    }

    /**
     * Return the user id.
     * 
     * @access public
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Create a new user with the current user model
     * TODO: This uses the user table, which is different than the rest of the model
     * which uses entities.
     *
     * @access public
     * @return bool Status of the insert
     */
    /* public function create() {
      $sql = "CALL user_create('$this->username', '$this->firstname', '$this->lastname', '$this->email', '$this->password', '$this->salt', '$this->apikey', '$this->apisecret')";
      $rs = $this->query($sql);
      if ($rs && $rs->num_rows) {
      $row = $rs->fetchObject();
      return $row->id;
      }

      return false;
      } */

    /**
     * Update this user account
     *
     * @param array $data User data to update as key => value pairs
     * @return bool Status of the update
     */
    public function update($data = array()) {
        //var_dump($data);
        // Not sure how I want to update user accounts yet.
        // Update each field individually? Or require the API user
        // to send all params and just update all at once?

        return true;
    }

    /**
     * Delete this user
     *
     * @access public
     * @return bool Status of the delete
     */
    public function delete() {
        $sql = "CALL delete_user('$this->username')";
        $rs = $this->query($sql);
        if (!$rs->hasError()) {
            return true;
        }

        return false;
    }

    public function fullname() {
        return trim($this->firstname . ' ' . $this->lastname);
    }

    public function username() {
        return $this->username;
    }

    /**
     * Gets all children of this user. If an entity type
     * is passed it will only return the children of that type.
     * 
     * @access public
     * @param mixed $entitytype (default: null)
     * @return array Array of children id's
     */
    public function parentOf($entitytype = null) {
        $children = array();

        if ($entitytype === null) {
            $sql = "CALL entity_get_direct_children($this->id)";
        } else {
            $sql = "CALL entity_get_children_of_type($this->id, $entitytype)";
            //$sql = "CALL entity_get_direct_children($this->id)";
        }

        $rs = $this->query($sql);

        if ($this->hasError()) {
            error_log($this->getError());
            $this->error = "Unable to get children of $entitytype";
            return false;
        }

        if ($rs->hasRecords()) {
            // Skip the 1st record because it will always be the id of the user,
            // since the user has to have ownership of itself.
            for ($rs->startRecord(); $rs->hasRecord(); $rs->nextRecord()) {
                $children[] = $rs->id;
            }
        }

        return $children;
    }

    /**
     * Returns the valid status types available.
     * 
     * @access public
     * @return array Array of status types.
     */
    public function getStatusTypes() {
        if ($this->status_types) {
            return $this->status_types;
        }

        $sql = 'CALL user_status_types()';
        $rs = $this->query($sql);

        if ($this->hasError()) {
            $this->setError('Could not get status types.', $this->getError());
            return false;
        }

        $this->status_types = $rs->fetchAll();

        return $this->status_types;
    }

    /**
     * Return the current status of this user.
     * 
     * @access public
     * @return int Current status id of this user.
     */
    public function getStatus($verbose = true) {
        if ($verbose) {
            $types = $this->getStatusTypes();

            foreach ($types as $type) {
                if ($type['id'] == $this->status)
                    return $type['name'];
            }
        }

        return $this->status;
    }

    /**
     * Change the status of this user. Checks to make sure
     * a valid status is being used.
     * 
     * @access public
     * @param int $newstatus New status id to set.
     * @return int|bool False upon failure, new status id upon sucess
     */
    public function changeStatus(int $newstatus) {
        // make sure we are setting it to a valid status        
        $types = $this->getStatusTypes();

        if ($types && in_array($newstatus, $types)) {
            $this->setError('Trying to set an invalid status.');
            return false;
        }

        $sql = "CALL user_update_status({$this->id}, {$this->user->getId()}, {$newstatus})";
        $rs = $this->query($sql);

        if ($this->hasError()) {
            $this->setError('Could not update status.', $this->getError());
            return false;
        }

        return $rs->value == $newstatus;
    }

    /**
     * Pass an entity id and this will return if this user
     * is a parent of it.
     * 
     * @access public
     * @param int $id
     * @return bool
     */
    public function isParentOf($id) {
        // TODO: Better way to handle failure here?
        if (($children = $this->parentOf()) === false) {
            return false;
        }

        return in_array($id, $children);
    }

    /**
     * Get the folders this user is a parent of.
     * 
     * Also sorts them by name.
     * 
     * @access public
     * @return void
     */
    public function getFolders($as_models = false) {
        // 4 is the folder entity id
        // TODO: Better way to do specify the folder entity type?
        //$folders = $this->parentOf(4);

        $folders = array();

        $sql = "call user_get_folders({$this->id})";
        $rs = $this->query($sql);

        if ($this->hasError()) {
            $this->setError('Could not get user folders.', $sql . ': ' . $this->getError());
            return false;
        }

        if ($rs->hasResults()) {
            foreach ($rs->fetchAll() as $folder) {
                $folders[$folder['id']] = $folder;
            }
        }

        return $folders;
        /*
          if ($as_models) {
          $folder_models = array();

          foreach ($folders as $id) {
          // THIS WAS A BAD IDEA, TAKES FOREVER TO LOAD
          //$folder = new Application_Model_Folder($this, $id);
          //$folder_models[$folder->name] = $folder;


          $folder_models[$folder->name] = $folder;
          }

          // sort by array key (folder name)
          ksort($folder_models);

          $folders = $folder_models;
          }
         */

        return $folders;
    }

    public function getFolders_hjoe($as_models = false) {

        $folders = array();

        $sql = "call user_get_folders_hjoes({$this->id})";
        $rs = $this->query($sql);

        if ($this->hasError()) {
            $this->setError('Could not get user folders.', $sql . ': ' . $this->getError());
            return false;
        }

        if ($rs->hasResults()) {
            foreach ($rs->fetchAll() as $folder) {
                $folders[$folder['id']] = $folder;
            }
        }

        return $folders;
    }

    /**
     * Get the folders this user is a parent of.
     * 
     * Also sorts them by name.
     * 
     * @access public
     * @return void
     */
    public function getUsers($as_models = true) {

        $users = array();

        $sql = "call user_get_users({$this->id})";
        $rs = $this->query($sql);

        if ($this->hasError()) {
            $this->setError('Could not get users.', $sql . ': ' . $this->getError());
            return false;
        }

        if ($rs->hasResults()) {

            for ($rs->startRecord(); $rs->hasRecord(); $rs->nextRecord()) {
                $users[] = new Application_Model_User((int) $rs->id);
            }
        }

        return $users;
    }

    /**
     * Returns a formatted address string for this user.
     * 
     * @access public
     * @return string
     */
    public function address() {
        return $this->address . ' ' . $this->city . ', ' . $this->state . ' ' . $this->zip;
    }

    /**
     * Gets a collection of contest models for this user
     * 
     * Sorted by name
     * 
     * @access public
     * @return Array
     */
    public function getContests() {
        $return = array();

        if ($this->id) {
            $sql = "CALL user_get_contests($this->id)";
            $rs = $this->query($sql);
            if ($rs && $rs->num_rows) {
                while ($row = $rs->fetchArray()) {
                    $contest = new Application_Model_Contest();
                    $contest->loadFromArray($row);
                    if (isset($return[$contest->name])) {
                        $ix = 0;
                        $index = $contest->name . $ix;
                        while (isset($return[$index])) {
                            $ix++;
                            $index = $contest->name . $ix;
                        }
                        $return[$index] = $contest;
                    } else {
                        $return[$contest->name] = $contest;
                    }
                }
            }
        }
        ksort($return);
        return $return;
    }

    /**
     * Gets a collection of keyword models for this user
     * 
     * Sorted by keyword
     * 
     * @access public
     * @return Array
     */
    public function getKeywords() {
        $return = array();

        if ($this->id) {
            $sql = "CALL user_get_keywords($this->id)";
            $rs = $this->query($sql);
            if ($rs && $rs->num_rows) {
                while ($row = $rs->fetchArray()) {
                    $keyword = new Application_Model_Keyword();
                    $keyword->loadFromArray($row);
                    $return[$keyword->keyword] = $keyword;
                }
            }
        }
        ksort($return);
        return $return;
    }

    /**
     * Gets the users keyword count
     * 
     * @access public
     * @return int
     */
    public function getKeywordsCount() {
        if ($this->id) {
            //$sql = "CALL user_get_keywordscount($this->id)";
            $sql = "SELECT COUNT(`id`) AS `count` FROM `keywords` WHERE `createuser` = '" . $this->id . "' 
					UNION 
					SELECT COUNT(`id`) AS `count` FROM `keywords` WHERE `createuser` = '" . $this->id . "'  AND `deactivatetime` != '0000-00-00 00:00:00' ";
            $rs = $this->query($sql);
            if ($rs && $rs->num_rows) {
                $row = $rs->fetchAll();
                //return array of total keywords and the deleted keywords
                return array($row[0]["count"], $row[1]["count"]);
            }
        }

        return 0;
    }

    /**
     * Gets the deleted users keyword count 
     * 
     * @access public
     * @return int
     */
    public function getDeletedKeywordsCount() {
        if ($this->id) {
            //$sql = "CALL user_get_deletedkeywordscount($this->id)";
            $sql = " SELECT COUNT(`id`) AS `count` FROM `keywords` WHERE `createuser` = '" . $this->id . "' AND `deactivatetime` != '0000-00-00 00:00:00';";
            $rs = $this->query($sql);
            if ($rs && $rs->num_rows) {
                if ($rs->count) {
                    return $rs->count;
                }
            }
        }

        return 0;
    }

    /**
     * Gets a users keyword limit
     * 
     * @access public
     * @return int
     */
    public function getKeywordLimit() {
        return $this->keywordlimit ? $this->keywordlimit : 0;
    }

    /**
     * Checks whether this user can create keywords
     * 
     * @access public
     * @return boolean
     */
    public function canCreateKeyword() {
        $limit = $this->getKeywordLimit();
        if (!$limit) {
            return false;
        }

        list($count, $count_deleted) = $this->getKeywordsCount();
        //$count_deleted = $this->getDeletedKeywordsCount();
        $count = $count - $count_deleted;
        return $count < $limit;
    }

    /**
     * Can this user create contests?
     * 
     * @access public
     * @return void
     */
    public function canCreateContest() {
        // Eventually we will have user permissions for certain actions, but not now.
        return true;
    }

    public function getMessageCount() {
        if ($this->id) {
            $sql = "CALL user_message_sent_count($this->id)";
            $rs = $this->query($sql);
            if ($rs->hasRecords()) {
                if ($rs->total) {
                    return $rs->total;
                }
            }
        }
        return 0;
    }

    public function getCampaignHistory() {
        //echo "campain history";
        if ($this->id) {
            $username = $this->escape($this->username);
            $sql = "CALL user_get_sent_messages('{$username}')";
            $rs = $this->query($sql);
            if ($rs->hasRecords()) {
                if ($rs->error) {
                    return $rs->message;
                } else {
                    //echo "<pre>".print_r($rs->fetchAll());
                    return $rs->fetchAll();
                }
            }
        }
        return 0;
    }
    
    /**
     *  get selected folders name for 
     *  campaign history page
     *  @name selectedforlders
     *  @access public
     *  @param $campaignid
     */
    public function selectedforlders($campaignid){
        $fnames = array();
        $resreturn = "";
        $sql ="SELECT en.value as folder from entitymeta en where en.entityid in( select folderid from messages_outbound where
          campaignid='$campaignid') ";
         $rs = $this->query($sql);
         if ($rs->hasRecords()) {
               foreach ($rs->fetchAll() as $folder) {
                $fnames[] = $folder['folder'];
              }
              return $resreturn = implode(',',$fnames);
           }else{
               return 0;
           }
    }// end of selectedforlders
    
    
    public function getTotalSubscriberCountByDay($range = 30, $order = 'desc') {
        $return = array();
        if ($this->id) {
            $sql = "CALL user_get_subscribers_range($this->id, $range)";
            $rs = $this->query($sql);
            if ($rs->hasRecords()) {
                while ($row = $rs->fetchObject()) {
                    $return[] = $row;
                }

                if ($order == 'asc') {
                    $return = array_reverse($return);
                }
            }
        }

        return $return;
    }

    public function getDailyOptoutsByDay($range = 30, $order = 'desc') {
        $return = array();
        if ($this->id) {
            $sql = "CALL user_get_daily_optouts_range($this->id, $range)";
            $rs = $this->query($sql);
            if ($rs->hasRecords()) {
                while ($row = $rs->fetchObject()) {
                    $return[] = $row;
                }

                if ($order == 'asc') {
                    $return = array_reverse($return);
                }
            }
        }

        return $return;
    }

    /* new added for testing */

    public function setTimeZone($timezone) {
        $this->timezone = $timezone;
    }

    public function getTimeZone() {
        return $this->timezone;
    }

    public function getFoldersBdayClub($userid) {
        $folders = array();

        $sql = "call get_folders_bdayclub($userid)";
        $rs = $this->query($sql);

        if ($this->hasError()) {
            $this->setError('Could not get user folders.', $sql . ': ' . $this->getError());
            return false;
        }

        if ($rs->hasResults()) {
            foreach ($rs->fetchAll() as $folder) {
                $folders[$folder['id']] = $folder;
            }
        }

        return $folders;
    }

    public function getFoldersBdayClubBuUniqueKey($uniquekey) {
        $folders = array();

        $sql = "call get_folders_bdayclub_byuniquekey('$uniquekey')";
        $rs = $this->query($sql);

        if ($this->hasError()) {
            $this->setError('Could not get user folders.', $sql . ': ' . $this->getError());
            return false;
        }

        if ($rs->hasResults()) {
            foreach ($rs->fetchAll() as $folder) {
                $folders[$folder['id']] = $folder;
            }
        }

        return $folders;
    }

    public function getFolderElseCreate($foldername, $userid) {
        $sql = "CALL get_folder_else_create('$foldername',$userid)";
        $rs = $this->query($sql);
        if ($rs->id) {
            return $rs->id;
        }else
            return false;
    }

    /**
     * User can add new industry if it
     * doesn't exit on the list
     * @access public
     * @return boolean
     * @name addIndustry
     */
    public function addIndustry($industryname = null, $createdat) {
        if ($industryname != "") {
            $sql = "CALL addIndustry('$industryname','$createdat')";
            $rs = $this->query($sql);

            if ($this->hasError()) {
                error_log($this->getError());
                $this->error = 'Unable to edit new idnustry.';
//            return $rs;
            }
            return $rs;
        }
    }

// end of addIndustry;

    public function __toString() {
        ;
    }

    //Added by Jeevan Technologies for Dashboard
    public function getKeywordsCountNew() {
        if ($this->id) {
            $sql = "SELECT COUNT(`id`) AS `count` FROM `keywords` WHERE `createuser` = '" . $this->id . "' 
					UNION 
					SELECT COUNT(`id`) AS `count` FROM `keywords` WHERE `createuser` = '" . $this->id . "'  AND `deactivatetime` != '0000-00-00 00:00:00' ";
            $rs = $this->query($sql);
            if ($rs && $rs->num_rows) {
                $row = $rs->fetchAll();
                //return array of total keywords and the deleted keywords
                return array($row[0]["count"], $row[1]["count"]);
            }
        }

        return 0;
    }

    public function getListData($Lid) {
        $sql = "SELECT * FROM dropdown_config_items WHERE ddconfigid = '" . $Lid . "'";
        $rs = $this->query($sql);

        if ($rs->hasRecords()) {
            return $rs->fetchAll();
        }
        $this->user = $rs->fetchAll();
    }

    public function getAllListData() {
        $sql = "SELECT * FROM dropdown_config";
        $rs = $this->query($sql);
        if ($rs->hasRecords()) {
            return $rs->fetchAll();
        }
        $this->user = $rs->fetchAll();
    }

    public function FAQCntData() {
        $sql = "SELECT count(*) as FAQ FROM dropdown_config_items WHERE ddconfigid = '1' ";
        $rs = $this->query($sql);
        if ($rs->hasRecords()) {
            return $rs->fetchAll();
        }
        $this->user = $rs->fetchAll();
    }

    public function SupportCntData() {
        $sql = "SELECT count(*) as Support FROM dropdown_config_items WHERE ddconfigid = '2' ";
        $rs = $this->query($sql);
        if ($rs->hasRecords()) {
            return $rs->fetchAll();
        }
        $this->user = $rs->fetchAll();
    }

    public function VideosCntData() {
        $sql = "SELECT count(*) as Videos FROM dropdown_config_items WHERE ddconfigid = '3' ";
        $rs = $this->query($sql);
        if ($rs->hasRecords()) {
            return $rs->fetchAll();
        }
        $this->user = $rs->fetchAll();
    }

    public function dele($ddid) {
        $sql = "DELETE FROM dropdown_config_items WHERE ddconfigid = '" . $ddid . "'";
        $rs = $this->query($sql);
    }

    public function add($value, $gettext, $ddid) {
        $sql = "INSERT INTO dropdown_config_items SET 
					ddconfigid		= '" . $ddid . "',
					value 			= '" . $value . "', 
					displaytext		= '" . $gettext . "' ";
        $rs = $this->query($sql);
        if ($this->hasError()) {
            $this->setError("Could not Add Support", $this->getError());
            return false;
        }
        return true;
    }

    /**
     * Get user subaccounts
     * 
     * @access public
     * @return void
     */
    public function getUserSubaccounts($userid) {
        $accounts = array();

        $sql = "CALL subuser_accounts($userid)";
        $rs = $this->query($sql);

        foreach ($rs->fetchAll() as $id) {

            $accounts[] = $id['id'];
        }

        return $accounts;
    }

    /**
     * this function returns user 
     *  parententity id by user id
     *  @method user_parententity
     *  @param  int $userid
     *  @return int parententityid
     */
    public function user_parententity($userid) {
        $sql = "Call get_user_parent_id($userid)";
        $rs = $this->query($sql);
        if ($rs->hasRecord()) {
            return $rs->pr_id;
        } else {
            return false;
        }
    }

    /**
     * need to documented this func
     */
    public function getTxtmuEmployee() {
        $empids = array();

        $sql = "CALL txtm_employee()";
        $rs = $this->query($sql);

        foreach ($rs->fetchAll() as $id) {

            $empids[$id['empid']] = $id['empid'];
        }

        return $empids;
    }

    /**
     *  Get new accounts from table 
     *  which created during last 20 days
     *  @name $selectNewAccounts
     *  @return Array all new accounts
     * 
     */
   public function selectNewAccounts($yearmonth){
//        $sql = "CALL get_newaccounts_lastmonth('$yearmonth')";
         $sql = "SELECT e.id, e.createtime as createtime, m.value as firstname, m1.value as lastname, m2.value as email, m3.value as cell, m4.value as business, m5.value as rate

            FROM entity e, entitymeta m, entitymeta m1, entitymeta m2, entitymeta m3,entitymeta m4,entitymeta m5

            WHERE e.typeid = 5 

            and m.entityid = e.id and m.profileid=22

            and m1.entityid = e.id and m1.profileid=23

            and m2.entityid = e.id and m2.profileid=24

            and m3.entityid = e.id and m3.profileid=40

            and m4.entityid = e.id and m4.profileid=38 and m5.entityid = e.id and m5.profileid=53 and

            e.createtime like CONCAT('%', '$yearmonth', '%')";
        $rs  = $this->query($sql);
        
        if($rs->hasRecords()){
           return $rs->fetchAll();
          }else{
              return false;
          }
        }// ent of selectNewAccounts
        
        
         /**
         *  Gets prosalons campaigns for each account
         *  using accaountid:
         *  @access public
         *  @param int $accaountid
         *  @name $prosalonUsage
         *  @return int amount ouf campaigns
         */
        public function prosalonUsage($accountid){
            $yearmon = date('Y-m');
//            $sql = "Select count(phonenumber) as campaign from prosalon_usage where createuser=".$accountid." and createtime like CONCAT('%', $yearmon, '%')";
            $sql = "Select count(phonenumber) as campaign from prosalon_usage where createuser=".$accountid." and createtime like ('%$yearmon%')";
            $rs = $this->query($sql);
            return $rs->campaign;
        }
}
