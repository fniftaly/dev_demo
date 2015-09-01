<?php
/**
 * Application_Model_Folder class.
 * 
 * Contains methods that are specific to the Folder Entity type.
 * 
 * @extends Application_Model_Entityabstract
 */
class Application_Model_Folder extends Application_Model_Entityabstract {
    /**
     * Folder type
     * 
     * @access protected
     * @var string
     */
    protected $_type = 'folder';
    
    /**
     * This is the field that will be checked for to already exist in entity meta for 
     * this entity type. This will prevent duplicate entity's, in a way, kinda. Need to
     * really look at this problem and figure out the best solution. TODO: read back.
     * 
     * @access protected
     * @var string
     */
    protected $_checkfield = 'name';
    
    /**
     * Subscriber list for this location.
     * 
     * (default value: array())
     * 
     * @var array
     * @access protected
     */
    protected $subscribers = array();
    
    /**
     * Parent ID of this folder, if there is one.
     * 
     * @var mixed
     * @access protected
     */
    protected $parentid;
    
    /**
     * Array of Children Folders of this folder
     * 
     * @var array
     * @access protected
     */
    protected $children = array();
    
    private $fetched_subscribers = false;
    
    /**
     * Update a folder meta data.
     *
     * @param array $meta Key=>Pair values for location meta data
     * @return bool Status of the update
     */
    public function update(array $meta) {
        // I think this will be moved to Entityabstract.
        // Update each field individually? Or require the API user
        // to send all params and just update all at once?
        
        return true;
    }
    
    /**
     * Checks if a folder already exists
     */
    public function folderExists($name) {
        return $this->loadEntityBy($this->_checkfield, $name);
    }
    
    /**
     * Return the current status of this location.
     * 
     * @access public
     * @return int Current status id of this location.
     */
    public function getStatus($verbose = true) {
        if ($verbose) {
            $types = $this->getStatusTypes();
            
            foreach ($types as $type) {
                if ($type['id'] == $this->status) return $type['name'];
            }
        }
        
        return $this->status;
    }

    /**
     * Returns an array of subscribers to this folder.
     * 
     * @access public
     * @return array Subscriber list
     */
    public function getSubscribers($forcenew = false) {
        // If we already got the subscribers, dont get them again unless forced to
        if ($forcenew || !$this->fetched_subscribers) {
	        $sql = "CALL folder_get_subscribers({$this->id})";
	        $rs = $this->query($sql);
	        
	        if ($this->hasError()) {
	           $this->setError("Could not get subscriber list for entity {$this->id}.", $this->getError());
	           return false;
	        }
	        
	        $this->fetched_subscribers = true;
	        
	        $this->subscribers = $rs->fetchAll();
        }
        
        return $this->subscribers;
    }

    public function getSubscriberscustom($id) {
        // If we already got the subscribers, dont get them again unless forced to
        if (@$forcenew || !$this->fetched_subscribers) {
	        $sql = "CALL folder_get_subscribers({$id})";
	        $rs = $this->query($sql);

	        if ($this->hasError()) {
	           $this->setError("Could not get subscriber list for entity {$id}.", $this->getError());
	           return false;
	        }

	        $this->fetched_subscribers = true;

	        $this->subscribers = $rs->fetchAll();
        }

        return $this->subscribers;
    }
    
    /**
     * Returns an array of subscribers to this folder. Both Opted In & Opted Out
     * 
     * @access public
     * @return array Subscriber list
     */
    public function getSubscribersBothInOut($forcenew = false) { 
        // If we already got the subscribers, dont get them again unless forced to
        if ($forcenew || !$this->fetched_subscribers) { 
	        $sql = "CALL folder_get_subscribers_inout({$this->id})";
	        $rs = $this->query($sql); //echo "<pre>"; print_r($rs); exit;
	        
	        if ($this->hasError()) {
	           $this->setError("Could not get subscriber list for entity {$this->id}.", $this->getError());
	           return false;
	        }
	        
	        $this->fetched_subscribers = true;
	        
	        $this->subscribers = $rs->fetchAll(); 
        }
        
        return $this->subscribers;
    }    
    
    public function getSubscribersLatest($forcenew = false) { 
        // Get the latest subscribers that had opted in to a particular folder
        if ($forcenew || !$this->fetched_subscribers) { 
	        $sql = "CALL folder_get_subscribers_latest({$this->id})";
	        $rs = $this->query($sql); //echo "<pre>"; print_r($rs); exit;
	        
	        if ($this->hasError()) {
	           $this->setError("Could not get subscriber list for entity {$this->id}.", $this->getError());
	           return false;
	        }
	        
	        $this->fetched_subscribers = true;
	        
	        $this->subscribers = $rs->fetchAll(); 
        }
        
        return $this->subscribers;
    }     
    
    /**
     * Returns the # of subscribers in this folder.
     * 
     * @access public
     * @return void
     */
    public function subscriberCount() {
    	return count($this->getSubscribers());
    }
    
    /**
     * Add a subscriber to this folder.
     * 
     * @access public
     * @param mixed $subscriberid
     * @return void
     */
     public function addSubscriber($phonenumber,$firstname=null, $lastname=null, $birthday=null) {
        /*$sql = "CALL folder_add_subscriber({$this->id}, {$subscriberid})";
        $rs = $this->query($sql);
        
        if ($this->hasError()) {
           $this->setError('Could not add subscriber to location.', $this->getError());
           return false;
        }
        
        return true;*/
        
        // Reset the error holder. Not sure if this is the best way to do this, but I don't want 
        // the model thinking it has an error on this add when there was an error on the last add.
        $this->error = false;
        
        // Will be populated with any subscriber phonenumbers already in this folder
        //$existing = array();
        
        // save the original input
        $orig_input = $phonenumber;
        
        // This will get the current subscriber list, which will not return opted out
        // subscribers. This way if a subscriber opts out, then opts back in, they will
        // get a new entry in the subscriber list for this folder. I think this logic
        // works well, but maybe we need to discuss. TODO: discuss
        /*foreach ($this->getSubscribers(true) as $sub) {
       	    $existing[] = $sub['phonenumber'];
        }*/
        
        // Clense the phonenumber
        $phonenumber = $this->cleanPhone($phonenumber);
        
        if (empty($phonenumber)) {
            return null;
        }
        
        if (strlen($phonenumber) < 10) {
            $this->setError('Invalid phonenumber "'.$orig_input.'" not imported."');
            return false;
        }
        
        // TODO: can delete the above proc from the db once this new method is confirmed better
//         $sql = "CALL subscriber_add_to_folder({$this->id}, $phonenumber)";
//        echo '<br>'.$this->id.'<br>'.$phonenumber.'<br>'.$firstname.'<br>'.$lastname.'<br>'.$birthday;
//        exit;
         $sql = "CALL subscriber_add_to_folder_moredata({$this->id}, $phonenumber, '$firstname', '$lastname', '$birthday')";
        $rs  = $this->query($sql);
        
        if ($this->hasError()) {
            $error = 'Could not add subscriber: "'.$orig_input.'", filtered to: "'.$phonenumber.'" to Folder.';
            $this->setError($error, $error.' - '.$sql.': '.$this->getError());
           	return false;
        }
        
        if ($rs->id < 0) {
       	    $this->setError('Subscriber ['.$phonenumber.'] already in this folder.');
       	    return false;
        }
        
        return $rs->id;
    }
    
    public function cleanPhone($phonenumber) {
        return preg_replace("/[^0-9]/", "", $phonenumber);
    }
    
    /**
     * Add an array of subscribers in this folder.
     * 
     * @access public
     * @param array $subscribers
     * @return void
     */
    public function addSubscribers(array $subscribers) {
        $errors  = array();
        $success = array();
        
        foreach ($subscribers as $subscriber) {
            $add = $this->addSubscriber($subscriber);
            var_dump($add); die;
            if ($add) {
                $success = $add->id;
            } else {
                // Add to the error array the id and error
                $fail = array();
                $fail['id']    = $subscriber;
                $fail['error'] = $this->error;
                $error[] = $fail;
            }
        }
        
        if (!empty($error)) {
            $this->error = $error;
        }
        
        return $success;
    }
    
    
    public function getFolderid($foldername){
		if ($foldername) {
            
			$sql = "CALL get_folderid_from_name('$foldername')";
            $rs  = $this->query($sql);
            if ($rs->hasRecords()) {
                if ($rs->folderid) {
                    return $rs->folderid;
                }
            }

		}
		return NULL;                
    }
    
    public function getFolderid_bynameuserid($foldername,$userid){
		if ($foldername) {
            
			$sql = "CALL get_folderid_from_nameuserid('$foldername',$userid)";
            $rs  = $this->query($sql);
            if ($rs->hasRecords()) {
                if ($rs->folderid) {
                    return $rs->folderid;
                }
            }

		}
		return NULL;                
    }    
    
    
    public function addSubscriberAllDetails($folderid,$phonenumber,$fname,$lname,$emailid,$bday,$lang,$webformid=null,$through=null) {

        $this->error = false;
        $orig_input = $phonenumber;        
        
        // Clense the phonenumber
        $phonenumber = $this->cleanPhone($phonenumber);
        
        if (empty($phonenumber)) {
            return null;
        }
        
        if (strlen($phonenumber) < 10) {
            $this->setError('Invalid phonenumber "'.$orig_input.'" not added."');
            return false;
        }
            /*$this->setError($folderid.','.$phonenumber.','.$fname.','.$lname.','.$emailid.','.$bday.','.$lang.','.$webformid.','.$through);
            return false;*/

        if($webformid){
               $through = 'webform';
               $sql = "CALL subscriber_add_with_metadata_webform($folderid, '$phonenumber', '$fname', '$lname', '$emailid', '$bday', '$lang', '$through',$webformid)";
        }else{
	    $sql = "CALL subscriber_add_with_metadata($folderid, '$phonenumber', '$fname', '$lname', '$emailid', '$bday', '$lang','$through')";
         }
                
            
        $rs  = $this->query($sql);
         
        if ($this->hasError()) {
            $error = 'Could not add subscriber: "'.$orig_input.'", filtered to: "'.$phonenumber.'" to Folder.';
            $this->setError($error, $error.' - '.$sql.': '.$this->getError());
           	return false;
        }
        
        if ($rs->id < 0) {
       	    $this->setError('Subscriber ['.$phonenumber.'] already in this folder.');
       	    return false;
        }
        
        return $rs->id;
    }
    
    
    /**
     * Add another folder to this folder. This folder becomes the parent
     * and the added folder becomes a child.
     * 
     * @access public
     * @return void
     */
    /*public function add() {
    	return true;
    }*/
    
    /**
     * Retrieves messages sent for this location.
     * 
     * @access public
     * @param int $start Start record to return
     * @param int $stop Stop record to return
     * @return array
     */
    public function getMessages($start = 0, $stop = 50) {
    	$sql = "CALL folder_get_messages({$this->id},{$start},{$stop})";
    	$rs  = $this->query($sql);
    	
    	if ($this->hasError()) {
    		$this->setError('Could not retrieve location messages. Reason: '.$this->getError());
    		return false;
    	}
    	
    	return $rs->fetchAll();
    }
    
    /**
     * Opts a subscriber out of a folder by phonenumber
     * 
     * @param string $phonenumber
     * @return boolean
     */
    public function optOutSubscriber($phonenumber) {
    	if ($this->id) {
			$phonenumber = $this->cleanPhone($phonenumber);
            
            $this->_getDBH();
            $phonenumber = $this->_dbh->real_escape_string($phonenumber);
            
            $sql = "CALL folder_delete_subscriber($this->id, $phone)";
			$rs = $this->query($sql);
			return $rs->success > 0;
		}
		
		return false;
    }
    
       /*for inbox need to modfy it later*/
     public function updateinboxmessage($phonenumber,$folderid) {
//        echo $phonenumber.' // '.$folderid;
//        exit;
        if ($phonenumber) {
          $sql = "CALL update_inbox_message('$phonenumber')";
            $rs = $this->query($sql);
           if ($rs && $rs->num_rows) {
            if ($rs->success !== null){
                if ($rs->success > 0){
                    return true;
                }else{
                        $this->error = 'An error occurred and the message changes could not be saved';
                }
           }else{
                    $this->error = 'An error occurred and the message could not be saved';
            }
       }
        
        }
      
     }  // end of updateinboxmessage
     
    /*for inbox need to modfy it later*/
    
    public function optOutSubscriberInbox($phonenumber) {
        $folderid_phone = explode("_", $phonenumber);
        
    	if (isset($folderid_phone)) {
            
	    $phone = $this->cleanPhone($folderid_phone[0]);
	    $folderid = $this->cleanPhone($folderid_phone[1]);
            
            $this->_getDBH();
            $phonenumber = $this->_dbh->real_escape_string($phone);
            $folderid = $this->_dbh->real_escape_string($folderid);
            $folderList = $this->user->getFolders();
            $sql ="";
            $rs ="";
            $optedout=0;
            foreach($folderList as $key=>$val){
               $sql = "CALL folder_delete_subscriber($key, '$phonenumber')";
               $rs = $this->query($sql);
               if($rs->success > 0){
                   $optedout++;
               }
            }    
               if($optedout)
	         return true;
		}
		return false;
    }
    
    /**
     * Opts a subscriber out of a folder by subscriber id
     * 
     * @param string $phone
     * @return boolean
     */
    public function optOutSubscriberById($id) {
    	if ($this->id) {
            $sql = "CALL folder_delete_subscriber_by_id($this->id, $id)";
			$rs = $this->query($sql);
			return $rs->success > 0;
		}
		
		return false;
    }   
    
    public function updateFolderDetail($folderDetailArray){ 
        $folderid = $folderDetailArray['id'];
        $foldername = $folderDetailArray['name'];
        $sql = "CALL folder_update_details($folderid,'$foldername')";
        $rs = $this->query($sql); 
        if ($rs && $rs->num_rows) {
            if ($rs->success !== null){
                if ($rs->success >= 1){
                    return true;
                }else{
                        $this->error = 'An error occurred and the folder name changes could not be saved';
                }
           }else{
                    $this->error = 'An error occurred and the folder name could not be saved';
            }
       }
    }
    
    public function getSubscriberDetailsById($subscriberid) {
        if ($subscriberid) { 
            $sql = "CALL folder_get_subscribers_byid($subscriberid)";
            $rs  = $this->query($sql); 
            if ($rs->hasRecords()) {
                    return $rs->fetchAll();
            }

		}
		return NULL;
    }
    
    public function getKeywordDetailsByFolderId($folderid) {
        if ($folderid) { 
            $sql = "CALL keyword_get_byfolderid($folderid)";
            $rs  = $this->query($sql);
            if ($rs->hasRecords()) {
                    return $rs->fetchAll();
            }

		}
		return NULL;
    }    
    
    public function movePhoneNumber($subscriptionid,$newfolderid,$newkeywordid){ 
        if($subscriptionid!=0 AND $subscriptionid!='')
        { 
            $sql = "CALL folder_subscriber_update_folderid_keywordid($subscriptionid,$newfolderid,$newkeywordid)";
            $rs = $this->query($sql);
                    if ($rs->success >= 1){
                        return true;
                    }else{
                            $this->error = 'An error occurred and the phone number has not been moved';
                    }            
        }
    }   
    
    public function copyPhoneNumber($newfolderid,$phonenumber){
        if($phonenumber)
        { 
            $sql = "CALL subscriber_add_to_folder($newfolderid,$phonenumber)";
            $rs  = $this->query($sql); 

            if ($this->hasError()) {
                $error = 'Could not add subscriber: "'.$phonenumber.'", filtered to: "'.$phonenumber.'" to Folder.';
                $this->setError($error, $error.' - '.$sql.': '.$this->getError());
                return false;
            }

            if ($rs->id < 0) {
                $this->setError('Subscriber ['.$phonenumber.'] already in this folder.');
                return false;
            }

            return $rs->id;       
            }
    }  
    
    public function deleteFolder($id) {
    	if ($id) {
            $sql = "CALL folder_delete_byid($id)";
			$rs = $this->query($sql);
			if($rs->success > 0)
                return true;
            else
                $this->error = "An error occurred while deleting folder";
                
		}
		
		return false;
    }  
    
    public function deleteFolderWithSubscriber($id) {
    	if ($id) {
	    $sql = "CALL delete_subscribers_and_folder_byfolderid($id)";
	      $rs = $this->query($sql);
	    if($rs->success >0) 
                return true;
            else
                $this->error = "An error occurred while deleting folder";
	      }
		
	return false;
    }
    public function getFoldersByPhoneNumber($iphonenumber){
        if($iphonenumber){
            $sql = "CALL get_folderids_byphonenumber($iphonenumber)";
            $rs  = $this->query($sql);
            if ($rs->hasRecords()) {
                    return $rs->fetchAll(); 
            }
        }
        
        return false;
    }
    
    public function getFoldersByfolderidstring($folderidstr){
        if($folderidstr){ 
            $userid = $this->user->getId(); 
            $sql = "CALL user_get_folders_byidstring($userid,'$folderidstr')";
            $rs  = $this->query($sql);
            if ($rs->hasRecords()) {
                    return $rs->fetchAll(); 
            }
        }
        
        return false;
    }    
    /**
     * gets folders id by campaignid
     * @param string $camainid
     * @method getFoldersidBycampainid
     */
    public function getFoldersidBycampainid($campainid){
            $fIds = array();
            $sql = "CALL get_folderids_outboundmessages('$campainid')";
            $rs  = $this->query($sql);
            if ($rs->hasResults()) {
            foreach ($rs->fetchAll() as $folderid) {
                $fIds[$folderid['folderid']] = $folderid['folderid'];
            }
           }
        return $fIds;
    } // end of getFoldersidBycampainid func
    /**
     * gets folder name from entitymeta by folderid
     * @param int $folderid
     * @method getFolderName
     */
    public function getFolderName($folderid){
            $names = array();
            $sql = "CALL get_foldername_entitymeta('$folderid')";
            $rs  = $this->query($sql);
//            if ($rs->hasRecords()) {
//                    return $rs->fetchAll(); 
//            }
             if ($rs->hasResults()) {
            foreach ($rs->fetchAll() as $name) {
                $names[$name['value']] = $name['value'];
            }
           }
        return $names;
    }    //end of getFolderName func
    public function getPhoneNumberOptinoutDate($phonenumber,$folderid){
            $sql = "CALL get_phonenumber_optinoutdate($phonenumber,$folderid)";
            $rs  = $this->query($sql);
            if ($rs->hasRecords()) {
                    return $rs->fetchAll(); 
            }else{
                return false;
            }
    }

  public function numberExistsInNewFolder($phoneNumber,$newfolderid)
  {
    if($phoneNumber)
    { 
      if($newfolderid) 
      {
	$sql = "CALL number_exists_in_folder($phoneNumber,$newfolderid)";
	$rs  = $this->query($sql);
	if($rs->hasRecords()) 
	{ 
	  if($rs->id) 
	  {
 	    return $rs->id;
	  }
	}
      }
    }
    return NULL;       
  }  

    public function deleteNumberFromNewFolder($subsid) {
    	if ($subsid) { //echo $subsid ;exit;
            $sql = "CALL delete_subscriber_from_folder_by_id($subsid)";
			$rs = $this->query($sql);
			if($rs->success > 0)
			{
			  return true;
			}
			else
			{
			  $this->error = "An error occurred while deleting existing number from destination folder";
			}
		}
		
		return false;
    } 
    
     /**
     * This returns all opted out subcribers from user account
     * by user id
     * @param $userid type int
     * @method optedout_subscribers
     * @name  optedout_subscribers
     */
    public function optedout_subscribers($userid){
        $subscribers = array();
        $sql = "CALL optout_numbers_fromuseraccount($userid)";
          $rs = $this->query($sql);
           if ($this->hasError()) {
            error_log($this->getError());
            $this->error = 'Unable to get optout subscribers.';
            return $rs;
           }
        if ($rs->hasResults()) {
            foreach ($rs->fetchAll() as $number) {
                $subscribers[$number['num']] = $number['num'];
            }
           }
           return $subscribers;
    }// end of optout_subscribers
    
     /**
     * This returns all opted out subcribers from the system
     * 
     * @method system_optedout_subcr
     * @name  system_optedout_subcr
     * @access public
     * @return array all phone numbers 
     */
    public function system_optedout_subcr($phone){
        $sql = "CALL ifnumber_optedin_somewhere_insystem($phone)";
          $rs = $this->query($sql);
           if ($this->hasError()) {
            error_log($this->getError());
            $this->error = 'Unable to get optout subscribers.';
            return $rs;
           }
        if ($rs->phone == -1) {
             return FALSE;
           }else{
               return TRUE;
           }
    }// end of optout_subscribers
    
    public function addSubscriber_api($id,$phonenumber,$firstname=null, $lastname=null, $birthday=null) {
       
        $this->error = false;
        
        $orig_input = $phonenumber;
              
        // Clense the phonenumber
        $phonenumber = $this->cleanPhone($phonenumber);
        
        if (empty($phonenumber)) {
            return null;
        }
        
        if (strlen($phonenumber) < 10) {
            $this->setError('Invalid phonenumber "'.$orig_input.'" not imported."');
            return false;
        }
        
        // TODO: can delete the above proc from the db once this new method is confirmed better
//         $sql = "CALL subscriber_add_to_folder({$this->id}, $phonenumber)";
//        echo '<br>'.$this->id.'<br>'.$phonenumber.'<br>'.$firstname.'<br>'.$lastname.'<br>'.$birthday;
//        exit;
//         $sql = "CALL insert_subscriberFrom_api($id, $phonenumber, '$firstname', '$lastname', '$birthday')";
         $sql = "CALL insert_subscriberFrom_api($id, $phonenumber, '$firstname', '$lastname', '$birthday')";
        $rs  = $this->query($sql);
        
        if ($this->hasError()) {
            $error = 'Could not add subscriber: "'.$orig_input.'", filtered to: "'.$phonenumber.'" to Folder.';
            $this->setError($error, $error.' - '.$sql.': '.$this->getError());
           	return false;
        }
        
        if ($rs->id < 0) {
       	    $this->setError('Subscriber ['.$phonenumber.'] already in this folder.');
       	    return false;
        }
        
        return $rs->id;
    }
    
    public function getFoldersByName($id) {
        // 4 is the folder entity id
        // TODO: Better way to do specify the folder entity type?
        //$folders = $this->parentOf(4);
        
        $folders = array();
        
        $sql = "call getfoldersName_fromuseraccount($id)";
        $rs  = $this->query($sql);
        
        if ($this->hasError()) {
           $this->setError('Could not get user folders.', $sql.': '.$this->getError());
           return false;
        }
        
        if ($rs->hasResults()) {
            foreach ($rs->fetchAll() as $folder) {
                $folders[$folder['name']] = $folder;
            }
        }
        
        return $folders;
    }
    
    /**
     * Checking status of the subscribes within the system
     *  it can be optedout or still optin after multiple times optout
     * @param $phonenumber
     * @method check_status_ofsubcriber
     * @name   check_status_ofsubcriber
     * @access public
     * 
     */
    public function check_status_ofsubcriber($phonenumber){
        $sql_in = "CALL ifnumber_optedin_somewhere_insystem($phonenumber)";
        $sql_out = "CALL ifnumber_optedout_somewhere_insystem($phonenumber)";
        
        $rs_in = $this->query($sql_in);
        $rs_out = $this->query($sql_out);
        
            $date_in = $rs_in->phonein;
            $date_out = $rs_out->phonein;
        
         if($date_in && !$date_out){
             return TRUE;
         }
         if(!$date_in && $date_out){
             return FALSE;
         }
         if(!$date_in && !$date_out){
             return TRUE;
         }
         
    }// end of optout_subscribers
    
       /**
     *  Upload data 
     *  saving user information
     *  to db
     *  @method insertDataUpload
     *  @access public
     *  @param $emai, $business, $phone, $accountid
     *  
     */
    public function insertDataUpload($accountid,$emai, $business, $phone){
        $sql = "insert into datauploaders_info (accountid, email, business, phonenumber) values ($accountid,'$emai','$business',$phone)";
        $rs = $this->query($sql);
        return $rs;
    }
    
     public function sendEmail($email, $subject, $message){
            $legal     = "legal@textmunication.com";
            $nick     = "nick@textmunication.com";
            $daniel  = "daniel@textmunication.com";
            $andrew   = "andrew@textmunication.com";
            $farad   = "farad@textmunication.com";
            $mail = new Zend_Mail();
            $mail->addTo($email);
            $mail->addTo($legal);
//            $mail->addTo($nick);
//            $mail->addTo($daniel);
//            $mail->addTo($andrew);
            $mail->setFrom('info@textmunication.com', 'Textmunication Admin');
            $mail->setSubject($subject);
            $mail->setBodyText($message);
            $mail->send();
        }
        
           /**
         * 
         * 
         */
        public function sethollyCampaign($msg, $sentime, $folder,$createuser){
//            echo $msg."-".$sentime."-".$folder; exit;
            $sql = "insert into hollycampaign (`message`,`sendtime`,`folder`, `createuser`) values('$msg','$sentime', $folder,$createuser)";
            $rs = $this->query($sql);
            if($rs){return 1;}else{return 0;}
        }
        /**
         * 
         * 
         */
        public function updatehollyCampaign($msg, $sentime, $folder,$id,$createuser){
            $sql = "update hollycampaign set `message`='$msg', `sendtime`='$sentime', `folder` =$folder where id =$id and createuser=$createuser";
            $rs = $this->query($sql);
            if($rs){return 1;}else{return 0;}
        }
        /**
         * 
         * 
         */
        public function selecthollyCampaign($createuser){
            $sql = "Select* from hollycampaign where createuser=$createuser order by id";
            $rs = $this->query($sql);
            if($rs->hasRecords())
            {return $rs->fetchAll();}
        }
        /**
         * 
         * 
         */
        public function canselinghollyCampaign($id){
            $sql = "delete from hollycampaign where id=$id";
            $rs = $this->query($sql);
            if($rs){return 1;}else{return 0;}
        }
}
