<?php

class Application_Model_Message extends Application_Model_Abstract {

    /**
     * Short code this is new varable 
     *    added for canada. Temp solution to send bilings for nev
     *          clubs
     * 
     * @var int
     */
    public $shortcode;

    /**
     * The log file for model logging
     * 
     * @var string
     */
    public $logFile = '/tmp/messagelog.log';

    /**
     * User Model sending the message
     * 
     * Gets set in the constructor. This model requires a user model to be instantiated.
     * 
     * @var id
     * @access public
     */
    public $user;

    /**
     * Message body to be sent.
     * 
     * @var string
     * @access public
     */
    public $body;

    /**
     * Appointment confirmation id.
     * 
     * @var int
     * @access public
     */
    public $confirmid;

    /**
     *   Type of campaigns
     * 
     * @var int
     * @access public
     */
    public $depth;

    /**
     * Recipient list for this message
     * 
     * (default value: array())
     * 
     * @var array
     * @access public
     */
    public $recipients = array();

    /**
     * Message Reply body.
     * 
     * @var string
     * @access public
     */
    public $replybody;

    /**
     * campaignid
     * 
     * @var mixed
     * @access public
     */
    public $campaignid;

    /**
     * scheduleid
     * 
     * @var mixed
     * @access public
     */
    public $scheduleid;

    /**
     * sponsorid
     * 
     * @var mixed
     * @access public
     */
    public $sponsorid;

    /**
     * linkid
     * 
     * @var mixed
     * @access public
     */
    public $linkid;

    /**
     * folderid
     * 
     * @var mixed
     * @access public
     */
    public $folderid = 0;

    /**
     * folders selected for campaign
     * 
     * @var array
     * @access public
     */
    public $selectedfolders = "";

    /**
     * keyword
     * 
     * @var mixed
     * @access public
     */
    public $keywordid = 0;

    /**
     * User assigned description of this message
     * 
     * @var string
     * @access public
     */
    public $description;

    /**
     * A cross reference key used in sending the message
     *
     * @var int
     * @access public
     */
    public $reportingkey1;

    /**
     * A cross reference key used in sending the message
     *
     * @var string
     * @access public
     */
    public $reportingkey2;

    /**
     * Constructor will require and load the user model of the user requesting this model.
     * 
     * @access public
     * @param Application_Model_User $user
     * @return void
     */
    public function __construct(Application_Model_User $user = null) {
        if ($user instanceof Application_Model_User) {
            $this->setUser($user);
        }
    }

    public function setConfirmid($confirmid) {
        $this->confirmid = $confirmid;
    }

    public function getConfirmid() {
        return $this->confirmid;
    }

    public function setSelectedfolders($folders) {
        $this->selectedfolders = $folders;
    }

    public function getSelectedfolders() {
        return $this->selectedfolders;
    }

    /**
     * Sets a user model into this model
     * 
     * @access public
     * @param Application_Model_User $user
     */
    public function setUser(Application_Model_User $user) {
        $this->user = $user;
    }

    public function getShortcode() {
        return $this->shortcode;
    }

    public function getDepth() {
        return $this->depth;
    }

    public function setDepth($depth) {
        $this->depth = $depth;
    }

    public function setShortcode($shortcode) {
        $this->shortcode = $shortcode;
    }

    /**
     * Sends a message to a collection of recipients (or one recipient)
     *
     * @access public
     * @param string $message The message to send
     * @param array $recipients An array of recipients to send to
     * @param string $sendtime A timestamp to schedule the message for
     * @return boolean
     */
    public function queueOld($body, $recipients, $sendtime = null, $timezone = null, $createuser = 0) {
        if ($sendtime) {
            $sendtime = date('Y-m-d H:i:s', strtotime($sendtime));
        }

        $this->body = $body;
        $this->recipients = (array) $recipients;
        $this->sendtime = $sendtime;
        $this->timezone = $timezone;
        // Build the reporting keys, used for cross referencing DRs
        $this->reportingkey1 = time();
        $this->reportingkey2 = md5(uniqid() . serialize($this->recipients));
        $this->createuser = $createuser;
//                             $this->campaignid = md5($this->createuser.time());
//		 Send this message to the outbound sms class to be sent.
        $sender = new Application_Model_Smsoutbound($this);
//		$sender = new Application_Model_Synsmsoutbound($this);

        if (($return = $sender->queue()) === false) {
            $this->error = $sender->error;
        }

        return $return;
    }

    public function queue($body, $recipients, $sendtime = null, $timezone = null, $confirmid = 0, $depth = 0, $createuser = 0, $shortcode = 87365) {
        if ($sendtime) {
            $sendtime = date('Y-m-d H:i:s', strtotime($sendtime));
        }

        $this->body = $body;
        $this->recipients = (array) $recipients;
        $this->sendtime = $sendtime;
        $this->timezone = $timezone;
        // Build the reporting keys, used for cross referencing DRs
        $this->reportingkey1 = time();
        $this->reportingkey2 = md5(uniqid() . serialize($this->recipients));
        $this->confirmid = $confirmid;
        $this->createuser = $createuser;
        $this->shortcode = $shortcode;
        $this->depth = $depth;
//		 Send this message to the outbound sms class to be sent.
        $sender = new Application_Model_Smsoutbound($this);
//		$sender = new Application_Model_Synsmsoutbound($this);

        if (($return = $sender->queue()) === false) {
            $this->error = $sender->error;
        }

        return $return;
    }

    public function queueapi($body, $recipients, $apikey, $sendtime = null, $timezone = null) {
        if ($sendtime) {
            $sendtime = date('Y-m-d H:i:s', strtotime($sendtime));
        }

        $this->body = $body;
        $this->recipients = (array) $recipients;
        $this->sendtime = $sendtime;
        $this->timezone = $timezone;

        // Build the reporting keys, used for cross referencing DRs
        $this->reportingkey1 = time();
        $this->reportingkey2 = $apikey;

        // Send this message to the outbound sms class to be sent.
        $sender = new Application_Model_Smsoutbound($this);
//		$sender = new Application_Model_Synsmsoutbound($this);
        if (($return = $sender->queue()) === false) {
            $this->error = $sender->error;
        }

        return $return;
    }
    
      public function api_msgsend($body, $recipients, $sendtime, $timezone = null, $confirmid = 0, $depth = 0, $createuser = 0, $shortcode = 0) {
        if ($sendtime) {
            $sendtime = date('Y-m-d H:i:s', strtotime($sendtime));
        }
        $this->body = $body;
        $this->recipients = (array) $recipients;
        $this->sendtime = $sendtime;
        $this->timezone = $timezone;
        $this->confirmid = $confirmid;
        $this->createuser = $createuser;
        $this->shortcode = $shortcode;
        $this->depth = $depth;
        // Build the reporting keys, used for cross referencing DRs
        $this->reportingkey1 = time();
        $this->reportingkey2 = md5(uniqid() . serialize($this->recipients));

        // Send this message to the outbound sms class to be sent.
        $sender = new Application_Model_Smsoutbound($this);

        if (($return = $sender->api_queue()) === false) {
            $this->error = $sender->error;
        }

        return $return;
    }

    /**
     * Sends a message to a collection of recipients (or one recipient)
     * 
     * THIS ABSOLUTELY REQUIRES THAT THIS MODEL BE BUILT USING THE _load METHOD
     *
     * @access public
     * @param string $message The message to send
     * @param array $recipients An array of recipients to send to
     * @param string $sendtime A timestamp to schedule the message for
     * @return boolean
     */
    public function send($shortcode) {
        $this->_writeLog("Message->Send() called...");
        // General concept here is simple... load up a message and all of the
        // recipients based simply on the message id from the queue
        if ($this->id && $this->body && $this->recipients && $this->reportingkey1 && $this->reportingkey2) {
            $this->_writeLog("All message components checked");
            $sender = new Application_Model_Smsoutbound($this);
//         $sender = new Application_Model_Synsmsoutbound($this);
            $this->_writeLog("Sender built");
            if (($return = $sender->send($shortcode)) === false) {
                $this->error = $sender->error;
            }
            $this->_writeLog("Sender->Send() called from the message...");
            return $return;
        }
        $this->_writeLog('Missing required components of the message: ' . serialize($this));
        $this->error = 'Missing required components of the message: ' . serialize($this);
        return false;
    }
    public function api_send($shortcode) {
        $this->_writeLog("Message->Send() called...");
        // General concept here is simple... load up a message and all of the
        // recipients based simply on the message id from the queue
        if ($this->id && $this->body) {
            $this->_writeLog("All message components checked");
            $sender = new Application_Model_Smsoutbound($this);
            $this->_writeLog("Sender built");
            if (($return = $sender->api_send($shortcode)) === false) {
                $this->error = $sender->error;
            }
            $this->_writeLog("Sender->Send() called from the message...");
            return $return;
        }
        $this->_writeLog('Missing required components of the message: ' . serialize($this));
        $this->error = 'Missing required components of the message: ' . serialize($this);
        return false;
    }

    public function receive(Application_Model_Smsinbound $message) {
        
    }

    public function status() {
        
    }

    /**
     * Gets the queue of unsent messages and builds a collection of message and
     * recipient data, grouped by message row id (gatewaymessageid)
     * 
     * @return array
     */
    public function getQueue() {
        $return = array();
        $sql = "CALL message_get_queue()";
        $this->_writeLog("About to fetch queue with $sql");
        $rs = $this->query($sql);
        $this->_writeLog("Query ran...");
        $this->_writeLog(print_r($rs, 1));
        if (!$this->hasError()) {
            // Since this query only returns IDs (message and user), use those to assemble message models
            while ($row = $rs->fetchObject()) {
                if ($row->userid) {
                    $this->_writeLog("Building a new message model with user {$row->userid}");
                    $m = new Application_Model_Message(new Application_Model_User($row->userid));
                    $this->_writeLog("Message model created, about to load with message {$row->id}");
                    $m->loadFromQueueId($row->id);
                    $rcount = count($m->recipients);
                    $this->_writeLog("Message id {$row->id} loaded... adding it to the stack with $rcount recipients");
                    $return[$row->id] = $m;
                } else {
                    $this->_writeLog("There is no user id for this message.... skipping");
                }
            }
        } else {
            $this->setError($this->_dbh->error);
            $this->_writeLog("Error: $this->_dbh->error");
        }

        return $return;
    }
    /**
     *  this is code is created for api msgs send
     * 
     * 
     */
    public function  getApiQueue() {
        $return = array();
        $sql = "CALL api_message_get_queue()";
        $this->_writeLog("About to fetch queue with $sql");
        $rs = $this->query($sql);
        $this->_writeLog("Query ran...");
        $this->_writeLog(print_r($rs, 1));
        if (!$this->hasError()) {
            // Since this query only returns IDs (message and user), use those to assemble message models
            while ($row = $rs->fetchObject()) {
                if ($row->userid) {
                    $this->_writeLog("Building a new message model with user {$row->userid}");
                    $m = new Application_Model_Message(new Application_Model_User($row->userid));
                    $this->_writeLog("Message model created, about to load with message {$row->id}");
                    $m->api_loadFromQueueId($row->id);
                    $rcount = count($m->recipients);
                    $this->_writeLog("Message id {$row->id} loaded... adding it to the stack with $rcount recipients");
                    $return[$row->id] = $m;
                } else {
                    $this->_writeLog("There is no user id for this message.... skipping");
                }
            }
        } else {
            $this->setError($this->_dbh->error);
            $this->_writeLog("Error: $this->_dbh->error");
        }

        return $return;
    }

    /**
     * Sends the queue of messages, writes the reports and such
     * 
     * @access public
     * @return boolean
     */
    public function sendQueue() {
        $queue = $this->getQueue();
        $queueCount = count($queue);
        $sendCount = 0;
        foreach ($queue as $id => $message) {
            if ($message instanceof Application_Model_Message) {
                if ($message->send()) {
                    $sendCount++;
                }
            }
        }

        return $sendCount == $queueCount;
    }

    /**
     * Sends a message to a collection of recipients (or one recipient)
     *
     * @access public
     * @param string $message The message to send
     * @param array $recipients An array of recipients to send to
     * @return boolean
     */
    public function sendNow($body, $recipients, $sc) {
        $this->body = $body;
        $this->recipients = (array) $recipients;

        // Build the reporting keys, used for cross referencing DRs
        $this->reportingkey1 = time();
        $this->reportingkey2 = md5(uniqid() . serialize($this->recipients));
        $this->shortcode = $sc;
        // Send this message to the outbound sms class to be sent.
        $sender = new Application_Model_Smsoutbound($this);
//		$sender = new Application_Model_Synsmsoutbound($this);
        if (($return = $sender->sendNow()) === false) {
            $this->setError($sender->error, true);
        }

        return $return;
    }

    public function synsendNow($shortCode, $sendTo, $body) {
        $this->body = $body;
        $this->recipients = (array) $recipients;

        // Build the reporting keys, used for cross referencing DRs
        $this->reportingkey1 = time();
        $this->reportingkey2 = md5(uniqid() . serialize($this->recipients));

        // Send this message to the outbound sms class to be sent.
        $sender = new Application_Model_Smsoutbound($this);
        $sender->synsend($shortCode, $sendTo, $body);
    }

    /* Must removed after syniverse will take over of velty */

    public function sendNowtoSyn_Test() {
        $this->body = $body;
        $this->recipients = (array) $recipients;

        // Build the reporting keys, used for cross referencing DRs
        $this->reportingkey1 = time();
        $this->reportingkey2 = md5(uniqid() . serialize($this->recipients));

        // Send this message to the outbound sms class to be sent.
        $sender = new Application_Model_Smsoutbound($this);
        if (($return = $sender->sendToSynNow()) === false) {
            $this->setError($sender->error, true);
        }
    }

    /**
     * Loads a queued message and its associated recipients from a message id
     *  
     * @param int $id
     */
    public function api_loadFromQueueId($id) {

        $this->api_load($id, true);
    }
    public function loadFromQueueId($id) {

        $this->_load($id, true);
    }

    public function updateCampaignDetail($campaignDetailArray) { //echo "<pre>"; print_r($campaignDetailArray); exit;
        $campaignid = $campaignDetailArray['id'];
        $campaignbody = $campaignDetailArray['body'];
        $campaigndescription = $campaignDetailArray['description'];
        $campaignsenddate = $campaignDetailArray['senddate'];

        //exit;
        if ($campaignid) {
            // $sql = "CALL campaign_update_details($campaignid,'$campaignbody','$campaigndescription','$campaignsenddate')";
            $sql = sprintf("CALL campaign_update_details($campaignid,'%s','%s','$campaignsenddate')", $this->escape($campaignbody), $this->escape($campaigndescription)
            );

            $rs = $this->query($sql);
            //echo '<pre>';print_r($rs);echo '</pre>';exit;       

            if ($rs && $rs->num_rows) {
                if ($rs->success !== null) {

                    if ($rs->success >= 1) {
                        return true;
                    } else {
                        $this->error = 'An error occurred and the campaign details changes could not be saved';
                    }
                } else {
                    $this->error = 'An error occurred and the folder name could not be saved';
                }
            }
        } else {
            $this->error = 'An error occurred and the folder name could not be saved';
        }
    }

    public function deleteCampaign($id) {
        if ($id) {
            $sql = "CALL delete_campaign_byID($id)";
            $rs = $this->query($sql);
            if ($rs->success > 0)
                return true;
            else
                $this->error = "An error occurred while deleting folder";
        }

        return false;
    }

    public function getcampaignhistory($id) {
        if ($id) {
            $sql = "CALL get_campaign_history($id)";
            $rs = $this->query($sql);
            if ($rs->hasRecords()) {
                return $rs->fetchAll();
            }
        }
        return NULL;
    }

    public function getinboxmessage($user_id) {
        if ($user_id) {
//            $sql = "
//      (SELECT `id`,`device_address`,`folderid`,`keywordid`,`message`,`createtime` 
//        FROM `messages_inbound` WHERE folderid in 
//        (select id from entity where typeid = 4 AND  
//        parententity = ".$user_id.") and keywordid in (select id from keywords where createuser = ".$user_id."))
//        union
//        (SELECT `id`,`device_address`,`folderid`,`keywordid`,`message`,`createtime` 
//        FROM `messages_inbound` WHERE message in ('cancel', 'stop', 'quit', 'end', 'fuck') ) ORDER BY `createtime` DESC
//";
//            
            $sql = "CALL get_inbox_message($user_id)";
            $rs = $this->query($sql);
            if ($rs->hasRecords()) {
                return $rs->fetchAll();
            } else {
                return NULL;
            }
        }
    }

    public function loadinboxmessage($id) {
        if ($id) {
            $sql = "CALL message_get_inbound($id)";
            $rs = $this->query($sql);
            if ($rs->hasRecords()) {
                return $rs->fetchAll();
            } else {
                return NULL;
            }
        }
    }

    /**
     * Load up this model with data from the query
     * 
     * @param int $id
     */
    public function _load($id = 0, $queued = false) {
        if ($id) {
            $sql = 'CALL message_get';
            if ($queued) {
                $sql .= '_queueid';
            }
            $sql .= "($id)";
            // We absolutely need, at the very least:
            // id, body, recipients, reportingkey1, reportingkey2
            // All but recipients from messages outbound
            $rs = $this->query($sql);
            if (!$this->hasError()) {
                while ($row = $rs->fetchObject()) {
                    // Use the first row to build our message components
                    if ($rs->isFirst) {
                        $this->id = $row->id;
                        $this->body = $row->body;
                        $this->reportingkey1 = $row->reportingkey1;
                        $this->reportingkey2 = $row->reportingkey2;
                        $this->shortcode = $row->shortcode;
                    }

                    // Stack recipients onto this message before returning
                    $this->recipients[] = $row->mobilenumber;
                }
            }
        }
    }
    public function api_load($id = 0, $queued = false) {
        if ($id) {
            $sql = 'CALL api_message_get';
            if ($queued) {
                $sql .= '_queueid';
            }
            $sql .= "($id)";
            // We absolutely need, at the very least:
            // id, body, recipients, reportingkey1, reportingkey2
            // All but recipients from messages outbound
            $rs = $this->query($sql);
            if (!$this->hasError()) {
                while ($row = $rs->fetchObject()) {
                    // Use the first row to build our message components
                    if ($rs->isFirst) {
                        $this->id = $row->id;
                        $this->body = $row->body;
                        $this->shortcode = $row->shortcode;
                    }

                    // Stack recipients onto this message before returning
                    $this->recipients[] = $row->mobilenumber;
                }
            }
        }
    }

    public function setBirthDayMessage($uniquekey, $useid, $folderid, $messagedesc, $messagebody, $footermessage, $sendbefore, $sendtime, $timezone) {
        if ($folderid) { //echo $useid.','.$folderid.','.$messagebody.','.$footermessage.','.$sendbefore.','.$sendtime; exit;
            $sql = "CALL set_birthday_message('$uniquekey',$useid,$folderid,'$messagedesc','$messagebody','$footermessage',$sendbefore,'$sendtime','$timezone')";
            $rs = $this->query($sql);
            if ($this->hasError()) {
                $error = 'Could not set Birthday message';
                $this->setError($error, $error . ' - ' . $sql . ': ' . $this->getError());
                return false;
            } else {
                return $rs->id;
            }
        }
        return false;
    }

    public function getBdayMessageList($userid) {

        $sql = "CALL get_bday_message_list($userid)";
        $rs = $this->query($sql);
        if ($rs->hasResults()) {
            return $rs->fetchAll();
        }
        return false;
    }

    public function getBirthDayMessage($uniquekey) {

        $sql = "CALL get_bday_message('$uniquekey')";
        $rs = $this->query($sql);
        if ($rs->hasResults()) {
            return $rs->fetchAll();
        } else {
            return false;
        }
    }

    public function updateBirthDayMessage($uniquekey, $messagedesc, $messagebody, $footermessage, $sendbefore, $sendtime) {
        //echo $uniquekey.','.$messagebody.','.$messagedesc.','.$footermessage.','.$sendbefore.','.$sendtime; exit;
        $sql = "CALL update_birthday_message('$uniquekey','$messagedesc','$messagebody','$footermessage',$sendbefore,'$sendtime')";
        $rs = $this->query($sql);
        if ($rs && $rs->num_rows) {
            if ($rs->success !== null) {
                if ($rs->success >= 1) {
                    return true;
                } else {
                    $this->error = 'Could not update Birthday message';
                }
            } else {
                $this->error = 'Could not update Birthday message';
            }
        }
    }

    public function deleteBirthDayMessageByFolderId($folderid) {
        $sql = "CALL delete_birthday_message_byfolderid($folderid)";
        $rs = $this->query($sql);
        if ($rs && $rs->num_rows) {
            if ($rs->success !== null) {
                if ($rs->success >= 1) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
    }

    public function deleteBirthDayMessageByUniqueId($uniqueid) {
        $sql = "CALL delete_birthday_message_uniquekey('$uniqueid')";
        $rs = $this->query($sql);
        if ($rs && $rs->num_rows) {
            if ($rs->success !== null) {
                if ($rs->success >= 1) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
    }

    public function getSubscribersListByFolderId($folderid) {

        $sql = "CALL get_subscribers_list_folderid($folderid)";
        $rs = $this->query($sql);
        if ($rs->hasResults()) {
            return $rs->fetchAll();
        } else {
            return false;
        }
    }

    public function updateBdayMsgSendDate($subscriberid, $bdaymsgdate) {
        //echo $subscriberid.",".$bdaymsgdate; exit;
        $sql = "CALL update_birthday_message_senddate($subscriberid,'$bdaymsgdate')";
        $rs = $this->query($sql);
        if ($rs && $rs->num_rows) {
            if ($rs->success !== null) {
                if ($rs->success >= 1) {
                    return true;
                } else {
                    $this->error = 'Could not update Birthday message send date';
                }
            } else {
                $this->error = 'Could not update Birthday message send date';
            }
        }
    }

    /** Old bdayclubs info set into
     *  memcache server under createuser key 
     *  @access public
     *   @return void 
     */
    public function addMemcahceBdclub() {
        $mobj = new Application_Model_CacheMemcache();
        $sql = "SELECT distinct userid, messagebody FROM bdayclub";
        $rs = $this->query($sql);
        if ($rs->hasResults()) {
            foreach ($rs->fetchAll() as $val) {
                $obj = new stdClass();
                $obj->createuser = $val['userid'];
                $obj->messagebody = $val['messagebody'];
//                $mobj->delData($val['userid']);
                $mobj->setData($val['userid'], $obj);
            }
            return $rs->fetchAll();
        }
    }

    public function getBdayMsgSendDate($curdate) {

//        $sql = "CALL get_bdaymsg_send_date('$curdate')";
        $sql = "SELECT distinct s.phonenumber, e.createuser, m.value as messagebody FROM `subscribers`s,

        entity e, entitymeta m,entitymeta m1 WHERE s.`birthday` like '%" . $curdate . "%' and e.typeid=4 and

        s.folderid= e.id and s.optouttime = '0000-00-00 00:00:00' and m.entityid=e.createuser and m.profileid=38 and 

       m1.entityid = e.createuser and m1.profileid= 8 and m1.value=1";
        $rs = $this->query($sql);
        if ($rs->hasResults()) {
            return $rs->fetchAll();
        } else {
            return false;
        }
    }

    public function queueBirthDayMessage() {
//        $mobj = memcache_connect('10.210.65.119', 11211);
        $mobj = memcache_connect('10.179.252.160', 11211);
        $currDate = date("Y-m-d");
        $tomorrowDate = date("Y-m-d", strtotime(date("Y-m-d", strtotime($currDate)) . " +1 day"));

        $md = explode("-", $tomorrowDate);

        $bd = $md[1] . "-" . $md[2];

        $subscriberlist = $this->getBdayMsgSendDate($bd);

//        echo "<pre>"; print_r($subscriberlist); exit;
        // Construct the message
        $sendmessage = new Application_Model_Message($this->user);
        if (!empty($subscriberlist)) {
            foreach ($subscriberlist as $subscriber) {
                $messagebody = $subscriber['messagebody'];
                $phonenumber = $subscriber['phonenumber'];
                $createuser = $subscriber['createuser'];
                if (strpos($messagebody, ",")) {
                    $firstpart = strstr($messagebody, ',', true);
                } else {
                    $firstpart = $messagebody;
                }
                /* tmp solution for handiling bd messages send out from entire system */
                $msgbody = $mobj->get("$createuser");
                if ($msgbody instanceof stdClass) {
                    $fmsg = $msgbody->messagebody;
                } else {
                    $fmsg = "Happy Birthday from your $firstpart friends, today is a special day in your life so enjoy it to the fullest, live long & prosperous";
                }
//                $datesend = date("Y-" . $bd . " " . $sendtime);
                $source = 303;
                $datesend = date("Y-" . $bd . " " . "11:30:00");
                $status = $sendmessage->queue($fmsg, $phonenumber, $datesend, "US/Pacific", 0, $source, $createuser);
//                queue($body, $recipients, $sendtime = null, $timezone = null, $confirmid = 0, $createuser = 0, $shortcode=87365)
//                $status = $sendmessage->queue($fmsg, $phonenumber, $datesend, "US/Pacific",0,$source,$createuser,87365); Dev server
            }
        }
        // Queue it up for delivery
//        if(!empty($subscriberlist)){
//            foreach($subscriberlist as $subscriber){
//                $messagebody = $subscriber['messagebody'];
//                $phonenumber = $subscriber['phonenumber'];
//                $sendtime = $subscriber['sendtime'];
//                $timezone = $subscriber['timezone'];
//                 
//                $datesend =  date("Y-".$bd." ".$sendtime);
////                echo $datesend.'<br>';
//		$sendmessage->folderid    = $subscriber['folderid'];
//		$sendmessage->description = $subscriber['messagedesc'];
//		$sendmessage->campaignid  = null; //it will be null as here we are not sending any campaign
//
//                $status = $sendmessage->queue($messagebody, $phonenumber, $datesend, $timezone);
//            }            
//        }
    }

    /**
     *   international sms sending
     *   Twilio API is involved
     *   this has no schedule option
     */
    public function internationalSms($id, $phone, $messageid, $message) {
        $sql = "Call internationalsms($id,$phone,'$messageid','$message') ";
        $rs = $this->query($sql);
    }

// end of internationalSms

    public function getphonesForIntrSms($foldersid) {
        $fids = array();
        $sql = "Call phonefor_intr_sms('$foldersid')";
        $rs = $this->query($sql);

        if ($rs->hasResults()) {
            foreach ($rs->fetchAll() as $phone) {
                $fids[] = $phone['phonenumber'];
            }
            return $fids;
        } else {
            return false;
        }
    }

// end of getphonesForIntrSms

    public function selectbd() {
        $bd = array();
//        echo 'Bassan';
        $sql = "Select id, birthday from subscribers where length(birthday) =4";
        $upd = "update subscribers set birthday=";
        $rs = $this->query($sql);
        $f = 0;
        $l = 0;
        if ($rs->hasResults()) {
            foreach ($rs->fetchAll() as $sub) {
                $bd[$sub['id']] = $sub['birthday'];
                $bcv = explode('-', $sub['birthday']);
                if (strlen($bcv[0]) < 2) {
                    $bcv[0] = '0' . $bcv[0];
                }
                if (strlen($bcv[1]) < 2) {
                    $bcv[1] = '0' . $bcv[1];
                }
                $bdbo = implode('-', $bcv);
                $id = $sub['id'];
                $this->query("update subscribers set birthday='$bdbo' where id=$id");
//                echo '<pre>'; print_r($bcv);
            }

//            echo 'count: '.count($bd);
//              echo '<pre>'; print_r($bd);
        }
    }

    /**
     * 
     * 
     */
    public function selectAllsubcribers($user) {
        $phones = array();
        $sql = "SELECT distinct `phonenumber` as phone FROM `subscribers` 
            WHERE folderid in(select id from entity where createuser=$user and typeid=4) and optouttime='0000-00-00 00:00:00'";
        $rs = $this->query($sql);
        if ($rs->hasRecords()) {
            foreach ($rs->fetchAll() as $pn => $pv) {
                $phones[$pv['phone']] = $pv['phone'];
            }
            return $phones;
        }
    }

// end of selectAllsubcribers

    /**
     * 
     * 
     * 
     */
    public function hollyFolderSubscribers($folderid) {
        $phones = array();
        $sql = "CALL get_subscribers_list_folderid($folderid)";
        $rs = $this->query($sql);
        if ($rs->hasResults()) {
            foreach ($rs->fetchAll() as $in => $arv) {
                $phones[] = $arv['phonenumber'];
            }
            return $phones;
        } else {
            return false;
        }
    }

    /**
     * 
     * 
     */
    public function intoOnefolderAllsubcribers($phones, $folderid) {
//        $this->cleanUpTotalfolder($folderid);

        if (count($phones) != 0 && $folderid) {
            foreach ($phones as $pk => $pv)
//             $sql="insert into subscribers (`folderid`, `phonenumber` ) values($folderid,$pv)";
                $this->query("insert into subscribers (`folderid`, `phonenumber` ) values($folderid,$pv)");
        }
    }

// end of intoOnefolderAllsubcribers
    /**
     * 
     * 
     */

    public function cleanUpTotalfolder($folderid) {
        $sql = "delete from subscribers where folderid=$folderid";
        $this->query($sql);
    }

// end of cleanUpTotalfolder

    /**
     *  selecting all holly campaigns that are ready to send out 
     *  based on send time
     * 
     */
    public function hollyCampaignSend() {
        $objarr = array();
        $sql = "select* from hollycampaign where sendtime < now() and status = 0";
        $rs = $this->query($sql);
        if ($rs->hasRecords()) {
            foreach ($rs->fetchAll() as $obj => $ov) {
                $std = new stdClass();
                $std->id = $ov['id'];
                $std->message = $ov['message'];
                $std->folderid = $ov['folder'];
                $std->createuser = $ov['createuser'];
                $objarr[] = $std;
            }
        }
        return $objarr;
    }

    /**
     *  updating holly campaigns where
     *  sendtime is equal to current time 
     * 
     */
    public function hollyCampaignUpdate($id, $createuser) {
        $sql = "update hollycampaign set status = 1 where id=$id and createuser = $createuser";
        $this->query($sql);
    }

    /**
     *  pushing holly campaigns to queue 
     * 
     */
    public function hollyCampaignToqueue($arr) {
        $sendtime = date('Y-m-d H:i:s');
        if (count($arr) != 0) {
            foreach ($arr as $ob => $std) {
                if ($std instanceof stdClass) {
                    $this->hollyCampaignUpdate($std->id, $std->createuser);
                    $subcr = $this->hollyFolderSubscribers($std->folderid);
                    $this->queue($std->message, $subcr, $sendtime, "US/Pacific", 0, $std->createuser);
                }
            }
        }
    }

    /*        REOCCURRING     */

    /**
     *  selecting all scheduled campaigns that are ready to send out 
     *  based on send time
     * 
     */
    public function scheduledCampaignsSend() {
        $objarr = array();
        $sql = "select* from wklymonthlyreoccurance where scheduled < now() and status = 'sched' and sended=0 and messages=0";
        $rs = $this->query($sql);
        if ($rs->hasRecords()) {
            foreach ($rs->fetchAll() as $obj => $ov) {
                $std = new stdClass();
                $std->id = $ov['id'];
                $std->campaignid = $ov['campaignid'];
                $std->name = $ov['name'];
                $std->msghead = $ov['msghead'];
                $std->message = $ov['message'];
                $std->msgfoot = $ov['msgfoot'];
                $std->sendtime = $ov['scheduled'];
                $std->createuser = $ov['createuser'];
                $std->folder = $ov['folder'];
                $std->timezone = $ov['timezone'];
                $std->shortcode = $ov['shortcode'];
                $objarr[] = $std;
                $this->weeklycampaignUpdate($std->id,$std->createuser,'first');
            }
        }
        return $objarr;
    }

    /**
     * 
     * 
     */
    public function schedulingcampaigns($cobj) {
        if ($cobj instanceof stdClass) {
            $campid = $cobj->campaingid;
            $name = $cobj->name;
            $msghead = $cobj->msghead;
            $message = $cobj->message;
            $msgfoot = $cobj->msgfoot;
            $sendtime = $cobj->sendtime;
            $createuser = $cobj->createuser;
            $folder = $cobj->folder;
            $timezone = $cobj->timezone;
            $shortcode = $cobj->shortcode;
            $sql = "insert into scheduledcampains (campaignid, name, msghead,message,msgfoot, 
                       sendtime, createuser,folder,timezone, shortcode) values('$campid','$name','$msghead','$message','$msgfoot',
                           '$sendtime',$createuser,'$folder','$timezone',$shortcode)";
            $rs = $this->query($sql);
        }
        return $rs;
    }

    /**
     * 
     * 
     */
    public function weeklycampaigns($cobj) {
        if ($cobj instanceof stdClass) {
            $campid = $cobj->campaingid;
            $name = addslashes($cobj->name);
            $msghead = addslashes($cobj->msghead);
            $message = addslashes($cobj->message);
            $msgfoot = $cobj->msgfoot;
            $sendtime = $cobj->sendtime;
            $scheduled = $cobj->scheduled;
            $createuser = $cobj->createuser;
            $folder = $cobj->folder;
            $timezone = $cobj->timezone;
            $shortcode = $cobj->shortcode;
            $weekday = $cobj->weekday;
            $week = $cobj->week;
            $status = $cobj->status;
            $sql = "insert into wklymonthlyreoccurance (campaignid, name, msghead,message,msgfoot, 
                       sendtime, createuser,folder,timezone, shortcode, weekday, week, status,scheduled) values('$campid','$name','$msghead','$message','$msgfoot',
                           '$sendtime',$createuser,'$folder','$timezone',$shortcode, $weekday, $week, '$status',CONVERT_TZ('$scheduled', '$timezone', 'US/Pacific'))";
            $rs = $this->query($sql);
            //CONVERT_TZ(iSENDTIME, iTIMEZONE, 'US/Pacific');
        }
        return $rs;
    }

    /**
     *  updating weekly scheduled campaigns based
     *  on day of the week
     * 
     */
    public function weeklycampaignUpdate($id,  $createuser, $call, $msg=0) {
        if($call =="first"){
         $sql = "update wklymonthlyreoccurance set sended = sended + 1 where id=$id and createuser = $createuser";
        }else{
         $sql = "update wklymonthlyreoccurance set messages =$msg  where id=$id and createuser = $createuser";
        }
        $this->query($sql);
    }

    /**
     * 
     * 
     */
    public function selectWeeklyCampaignSend() {
        $objarr = array();
        $sql = "select* from wklymonthlyreoccurance where weekday =  DATE_FORMAT(NOW(),'%w') and status = 'weekly'";
        $rs = $this->query($sql);
        if ($rs->hasRecords()) {
            foreach ($rs->fetchAll() as $obj => $ov) {
                $std = new stdClass();
                $std->id = $ov['id'];
                $std->campaignid = $ov['campaignid'];
                $std->name = $ov['name'];
                $std->msghead = $ov['msghead'];
                $std->message = $ov['message'];
                $std->msgfoot = $ov['msgfoot'];
                $std->sendtime = $ov['sendtime'];
                $std->createuser = $ov['createuser'];
                $std->folder = $ov['folder'];
                $std->timezone = $ov['timezone'];
                $std->shortcode = $ov['shortcode'];
                $objarr[] = $std;
            }
        }
        return $objarr;
    }
/**
 *  Verifying if scheduled campaign already
 *  pushed into messages_outbound
 *  @param str $camaignid 
 *  @access public
 *  @name checkScheduled
 */
    public function checkScheduled($type, $campaignid){
//        $sql = "select campaignid from api_outbound where campaignid='$campaignid'";
         if($type=="api"){
             $sql = "Call api_check_for_scheduled('$campaignid')";
         }else{
             $sql = "Call check_for_scheduled('$campaignid')";
         }
//        $sql = sprintf("Call check_for_scheduled('%s','%s')",
//                $this->escape($table),$this->escape($campaignid)
//                );
        $rs = $this->query($sql);
        if ($rs->hasRecords()){
            return 1; 
        }else{return 0; }
    }
/**
 *  Verifying if scheduled campaign already
 *  pushed into messages_outbound
 *  @param str $camaignid 
 *  @access public
 *  @name directcheckScheduled
 */
    public function directcheckScheduled($campaignid){
             $sql = "Call dir_api_check_for_scheduled('$campaignid')";
        $rs = $this->query($sql);
        if ($rs->hasRecords()){
            return 1; 
        }else{return 0; }
    }

    /**
     *  pushing weekly scheduled campaigns to queue 
     * 
     */
    public function weeklycampToqueue($arr) {
        $arc = count($arr);
         $sendmessage = new Application_Model_Message();
        if ($arc != 0) {
            $sendtime = date('Y-m-d H:i:s');
            foreach ($arr as $cind => $cobj) {
                if ($cobj instanceof stdClass) { // read the object
                    if(!$sendmessage->checkScheduled("msg",$cobj->campaignid)){
                        echo $cobj->campaignid;  
                    $folders = explode(',', $cobj->folder);
                    $totalfolders = count($folders);
                    $totalsubscribers = array();
                    for ($sbr = 0; $sbr < $totalfolders; $sbr++) {
                        $subscribers = $this->subscribersByFolderid($folders[$sbr]);
                        foreach ($subscribers as $usr_fid => $v) {
                            $totalsubscribers[$v['phonenumber']] = $v['phonenumber'];
                        }
                    }
                    $msg = $cobj->msghead ? "{$cobj->msghead}:" : '';
                    $msg .= "{$cobj->message}\n{$cobj->msgfoot}";
                    $msg = trim($msg);
//                    $sendmessage = new Application_Model_Message();
                    if (!empty($totalsubscribers)) {
                        $i_default = 0;
                        // Construct the message
                        $sendmessage->selectedfolders = $cobj->folder;
                        // Set the folderid this message is being sent to
                        $sendmessage->folderid = 1;
                        // Set the user description
                        $sendmessage->description = $cobj->name;
                        // Set a unique identifier for all messages that go out on this campaign
                        $sendmessage->campaignid = $cobj->campaignid;
//                     echo $sendtime = date('Y-m-d').' '.$cobj->sendtime;
                        $sendtime = date($cobj->sendtime);
                        $timezone = $cobj->timezone;
                        $userid = $cobj->createuser;
                        $shortcode = $cobj->shortcode;
                        $source = 404;
                        $totalmsg = count($totalsubscribers);
                         $status = $sendmessage->queue($msg, $totalsubscribers, $sendtime, $timezone,$i_default,$source,$userid,$shortcode);
                        /*api sms senders table*/
//                           $status = $sendmessage->api_msgsend($msg, $totalsubscribers, $sendtime, $timezone,$i_default,$source,$userid,$shortcode);
                            $sendmessage->weeklycampaignUpdate($cobj->id,$totalmsg,$cobj->createuser);
                    } else {
                        $error = 'There are no subscribers in the chosen folder(s).';
                    }
                    }
                } // end of object
                  sleep(2);
            }
        }
    }
    /**
     *  Selecting data from dir_send_collection 
     *  through procedure save_api_recipients_collection
     *  creates temp table then push data out to aggregator
     *  
     */
    public function push_data_to_aggregator(){
        $sql ="Call select_data_to_send_out()";
        $rs = $this->query($sql);
        if($rs->hasRecords()){
            return $rs->fetchAll();
        }else{
            return null;
        }
    }// end push_data_to_aggregator
    
    public function weeklycampDirectSend($arr) {
        $arc = count($arr);
         $sendmessage = new Application_Model_Message();
        if ($arc != 0) {
            $sendtime = date('Y-m-d H:i:s');
            $cn=0;
            foreach ($arr as $cind => $cobj) {
                $cn++;
                if ($cobj instanceof stdClass) { // read the object
                    if(!$sendmessage->directcheckScheduled($cobj->campaignid)){
//                        echo $cobj->campaignid;  
                    $folders = explode(',', $cobj->folder);
                    $totalfolders = count($folders);
                    $totalsubscribers = array();
                    ini_set('memory_limit', '512M');
                    for ($sbr = 0; $sbr < $totalfolders; $sbr++) {
                        $subscribers = $this->subscribersByFolderid($folders[$sbr]);
                        foreach ($subscribers as $usr_fid => $v) {
                            $totalsubscribers[$v['phonenumber']] = $v['phonenumber'];
                        }
                    }
                    $msg = $cobj->msghead ? "{$cobj->msghead}:" : '';
                    $msg .= "{$cobj->message}\n{$cobj->msgfoot}";
                    $msg = trim($msg);
//                    $sendmessage = new Application_Model_Message();
                    if (!empty($totalsubscribers)) {
                        $i_default = 0;
                        // Construct the message
                        $sendmessage->selectedfolders = $cobj->folder;
                        // Set the folderid this message is being sent to
                        $sendmessage->folderid = 1;
                        // Set the user description
                        $sendmessage->description = $cobj->name;
                        // Set a unique identifier for all messages that go out on this campaign
                        $sendmessage->campaignid = $cobj->campaignid;
//                     echo $sendtime = date('Y-m-d').' '.$cobj->sendtime;
                        $sendtime = date($cobj->sendtime);
//                        $timezone = $cobj->timezone;
//                        $userid = $cobj->createuser;
                        $shortcode = $cobj->shortcode;
                        $source = 404;
                        $totalmsg = count($totalsubscribers);
                        $sendmessage->weeklycampaignUpdate($cobj->id,$cobj->createuser,"second",$totalmsg);
                        $regidforcampaign = $this->save_api_outbound($cobj->campaignid,$cobj->createuser);
//                        $this->save_api_recipients($regidforcampaign,$totalsubscribers,$source,$cobj->createuser);
//                            echo $cobj->createuser; exit;
                        $this->save_api_recipients_collection($msg,$totalsubscribers,$source,$shortcode,$cobj->createuser);
//                        $send = $this->direct_send($shortcode,$totalsubscribers,$msg,$regidforcampaign);
                    } else {
                        $this->_writeLog("There are no subscribers in the chosen folder(s)");
                    }
                    }
                    unset($totalsubscribers);
//                    unset($cobj);
                    echo date('Y-m-d H:i:s').' C# '.$cn.' sbcr: '.$totalmsg.'<br>Failed: '.$send.'<br>';
                    if($send=="success"){
                       sleep(20);
                    }
                } // end of object
            }
            echo date('Y-m-d H:i:s').'<br>';
        }
    }
    
    
    public function save_api_outbound($campid,$userid){
        $sql = "Call save_api_outbound('$campid',$userid)";
        $rs = $this->query($sql);
        if($rs->id){
           return  $rs->id;
        }else{return 0;}
    }//
    
    public function save_api_recipients($gateway,$subscribers,$source,$createuser){
         $subscrb = count($subscribers);
//         echo '<pre>'; print_r($subscribers);
         if($subscrb !=0 ){
          foreach($subscribers as $ob=>$v){   
         $sql = "Call save_api_recipients($gateway,$v,$source,$createuser)";
          $rs = $this->query($sql);
          }
         }
    }
    public function save_api_recipients_collection($gateway,$subscribers,$source,$shortcode,$createuser){
         $subscrb = count($subscribers);
//         echo $gateway.'<br>';
//         echo $source.'<br>';
//         echo $createuser.'<br>';
//         echo $subscrb.'<br>';
////         echo '<pre>'; print_r($subscribers);
//         exit;
         if($subscrb !=0 ){
          foreach($subscribers as $ob=>$v){   
         $sql = "Call save_api_recipients_collection('$gateway',$v,$source,$shortcode,$createuser)";
          $rs = $this->query($sql);
          }
         }
    }
    /***********************/
    public function direct_send($array) {
        $apiUrl = 'https://text.mes.syniverse.com/SMSSend';
        $username = '4400';
        $password = 'Fq0^Hc0^';
//        $this->_writeLog("Sender->Send() called...");
        $rcpt = count($array);
        // Validate our message
        if ($rcpt !=0) {
//                $nscrp = 0;
                foreach ($array as $ind =>$nv) {
                    $phone=$nv['mobilenumber'];
                    $sendMessage = urlencode($nv['message']);
                    $shortcode =$nv['shortcode'];
                    $uri = $apiUrl;
                    $ch = curl_init($uri);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, "user=$username&pass=$password&smsfrom=$shortcode&smsto=$phone&smsmsg=$sendMessage");
                    curl_setopt($ch, CURLOPT_HEADER, false);
                    // Disable SSL peer verification
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

                    // Capture the output instead of echoing it
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//                    $this->_writeLog("Sender making API call...");
                    // Execute our request
                    curl_exec($ch);
                    // Close the cURL handle
                    curl_close($ch);
                }
            $this->_writeLog("Sender done");
            // Send back our return
//            if(count($array) == $nscrp)
//            {
//                $this->_writeLog("\nDIRPASS success $nscrp");
////                sleep(15);
//                return "<br>success ";}
//             else{
//                 $this->_writeLog("\nDIRPASS failed $nscrp");
//                 return "<br>failed ";
//                 }  
//            return $sent == $recipientsCount;
        }

        $this->setError('Missing information in the message');
        return false;
    }

// end of send --87365
        /**
         *   Updating updateing trakcing id 
         *   confirmed by 
         * 
         */
        public function api_markRecipientSent($gateway, $mobnum, $msgid) {
        if ($gateway) {
            $sql = "CALL update_dir_recipients('$gateway', '$mobnum','$msgid')";
            $rs = $this->query($sql);
            if (!$this->hasError()) {
                return $rs->success;
            }
        }

        $this->setError('Missing components of the message');
        return false;
    }
       /**
        *   Getting tracking ids from syniverse
        * 
        */
        public function confirmed_trackingid($mobnum, $msgid) {
           $sql="insert into trackingid_from_aggregator(`phonenumber`,`messageid`) values($mobnum, '$msgid')";
          $this->query($sql);
    }
    //monthly scheduling start here
    /**
     * 
     * 
     */
    public function selectMonthlCampaignSend($week) {
        $objarr = array();
        $sql = "select* from wklymonthlyreoccurance where weekday =  DATE_FORMAT(NOW(),'%w') and status = 'monthly' and week = $week";
        $rs = $this->query($sql);
        if ($rs->hasRecords()) {
            foreach ($rs->fetchAll() as $obj => $ov) {
                $std = new stdClass();
                $std->id = $ov['id'];
                $std->campaignid = $ov['campaignid'];
                $std->name = $ov['name'];
                $std->msghead = $ov['msghead'];
                $std->message = $ov['message'];
                $std->msgfoot = $ov['msgfoot'];
                $std->sendtime = $ov['sendtime'];
                $std->createuser = $ov['createuser'];
                $std->folder = $ov['folder'];
                $std->timezone = $ov['timezone'];
                $std->shortcode = $ov['shortcode'];
                $objarr[] = $std;
            }
        }
        return $objarr;
    }

    /**
     * 
     */
    public function updateScheduledCampaign($obj) {
//        echo '<pre>'; print_r($obj); exit;
        $sql = "";
        if ($obj instanceof stdClass) {
            if ($obj->status == 'sched') {
                $sql = "Update wklymonthlyreoccurance set name ='$obj->name', msghead='$obj->msghead', message='$obj->message', scheduled='$obj->scheduled' 
                    where campaignid='$obj->campaignid' and createuser=$obj->createuser and status='sched'";
            } elseif ($obj->status == 'weekly') {
                $sql = "Update wklymonthlyreoccurance set name ='$obj->name', msghead='$obj->msghead', message='$obj->message', 
                sendtime='$obj->sendtime', weekday='$obj->weekday'  where campaignid='$obj->campaignid' and createuser=$obj->createuser and status='weekly'";
            } elseif ($obj->status == 'monthly') {
                $sql = "Update wklymonthlyreoccurance set name ='$obj->name', msghead='$obj->msghead', message='$obj->message', 
                       sendtime='$obj->sendtime', weekday='$obj->weekday', week='$obj->week'
                      where campaignid='$obj->campaignid' and createuser=$obj->createuser and status='monthly'";
            }
        }
        $rs = $this->query($sql);
        return $rs;
    }

    /**
     *  getting send now campaignids from wklymonthlyreoccurance
     * 
     */
    public function selectStatusSend() {
        $ids = array();
        $sql = "select campaignid from wklymonthlyreoccurance where scheduled < now() and status='send' and sended=0";
        $rs = $this->query($sql);
        if ($rs->hasRecords()) {
            foreach ($rs->fetchAll() as $camp => $cv) {
                $ids[$cv['campaignid']] = $cv['campaignid'];
            }
        }
        if (count($ids) != 0) {
            return $ids;
        } else {
            return 0;
        }
    }

    /**
     * verifying if campaign send out then update history
     */
    public function verifyifcampaignSend($sendid) {
        $arrsent = array();
        $ids = array();
//        if($sendid instanceof ArrayObject)
        if (count($sendid) != 0) {
            $ids = array_keys($sendid);
        } else {
            return NULL;
        }
        for ($k = 0; $k < count($ids); $k++) {
            $id = $ids[$k];
            $rs = $this->query("SELECT o.campaignid, count(r.`gatewaymessageid`) as subscr FROM `messages_outbound_recipients` r, 
                    messages_outbound o WHERE o.campaignid='$id' and o.id = r. gatewaymessageid and r.senttime !='0000-00-00 00:00:00'");
            if ($rs->hasRecords()) {
                foreach ($rs->fetchAll() as $camp => $cv) {
                    $arrsent[$cv['campaignid']] = $cv['subscr'];
                }
            }
        }
        if (count($arrsent) != 0) {
            return $arrsent;
        } else {
            return 0;
        }
    }

    /**
     *  updates wklymonthlyreoccurance table after 
     *  campaign completely send out
     * 
     */
    public function updateSendNowcampaign($data) {
//        if($data instanceof ArrayObject){
        if (count($data) == 0) {
            return NULL;
        }
        $rs = null;
        foreach ($data as $id => $msgs) {
            $rs = $this->query("update wklymonthlyreoccurance set sended=1, messages=$msgs where campaignid='$id'");
        }
        return $rs;
//        }
//        return false;
    }

    /**
     *  delete campaign from scheduled list
     * 
     */
    public function deleteScheduledCampaign($campid) {
        $sql = "delete from wklymonthlyreoccurance where campaignid='$campid'";
        $rs = $this->query($sql);
        return $rs;
    }

    /**
     * Campaign history by user id
     * 
     */
    public function campaignHistory($userid) {
        $sql = "select* from wklymonthlyreoccurance where createuser=$userid";
        $rs = $this->query($sql);
        if ($rs->hasRecords()) {
            return $rs->fetchAll();
        } else {
            return 0;
        }
    }

    /**
     *  
     * 
     */
    public function getcampaigninfo($campid, $userid) {
        $sql = "select* from wklymonthlyreoccurance where createuser=$userid and campaignid = '$campid'";
        $rs = $this->query($sql);
        if ($rs->hasRecords()) {
            return $rs->fetchAll();
        } else {
            return 0;
        }
    }

    /**
     * 
     * 
     * 
     */
    public function getWeeks($date, $rollover) {
        $cut = substr($date, 0, 8);
        $daylen = 86400;

        $timestamp = strtotime($date);
        $first = strtotime($cut . "00");
        $elapsed = ($timestamp - $first) / $daylen;

        $i = 1;
        $weeks = 1;

        for ($i; $i <= $elapsed; $i++) {
            $dayfind = $cut . (strlen($i) < 2 ? '0' . $i : $i);
            $daytimestamp = strtotime($dayfind);

            $day = strtolower(date("l", $daytimestamp));

            if ($day == strtolower($rollover))
                $weeks++;
        }

        return $weeks;
    }
    
       /**
     * Returns an array of subscribers to this folder.
     * 
     * @access public
     * @return array Subscriber list
     */
   public function subscribersByFolderid($fid) {
//               $sql = "Select distinct phonenumber from subscribers where folderid=$fid and optouttime = '0000-00-00 00:00:00'";
               $sql = "Call getnumbersforcampaign($fid)";
               $rs = $this->query($sql);
               if($rs->hasRecords()){
	   return $rs->fetchAll();
               }
        return 0;
    }

}
