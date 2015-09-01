<?php

class Application_Model_Smsinbound extends Application_Model_Abstract {

    /**
     * A message ID, for getting an existing message
     * 
     * @access public
     * @var int
     */
    public $id = 0;

    /**
     * PROPDESCRIPTION
     * 
     * @access public
     * @var PROPTYPE
     */
    public $subscriberid = 0;

    /**
     * PROPDESCRIPTION
     * 
     * @access public
     * @var PROPTYPE
     */
    public $createtime;

    /**
     * PROPDESCRIPTION
     * 
     * @access public
     * @var PROPTYPE
     */
    public $carrier;

    /**
     * PROPDESCRIPTION
     * 
     * @access public
     * @var PROPTYPE
     */
    public $channel;

    /**
     * PROPDESCRIPTION
     * 
     * @access public
     * @var PROPTYPE
     */
    public $device_address;

    /**
     * PROPDESCRIPTION
     * 
     * @access public
     * @var PROPTYPE
     */
    public $inbound_address;

    /**
     * PROPDESCRIPTION
     * 
     * @access public
     * @var PROPTYPE
     */
    public $message;

    /**
     * PROPDESCRIPTION
     * 
     * @access public
     * @var PROPTYPE
     */
    public $message_id;

    /**
     * PROPDESCRIPTION
     * 
     * @access public
     * @var PROPTYPE
     */
    public $message_orig;

    /**
     * PROPDESCRIPTION
     * 
     * @access public
     * @var PROPTYPE
     */
    public $router;

    /**
     * PROPDESCRIPTION
     * 
     * @access public
     * @var PROPTYPE
     */
    public $status;

    /**
     * ID of the originating keyword for this message, if there is one.
     * 
     * @access public
     * @var int
     */
    public $keywordid;

    /**
     * PROPDESCRIPTION
     * 
     * @access public
     * @var PROPTYPE
     */
    public $status_code;

    /**
     * Message depth, for conversation tracking
     * 
     * @access public
     * @var int
     */
    public $depth = 0;

    public function __construct($id = 0) {
        $this->_getDbh();
        $this->_load($id);
    }

    /**
     * METHODDESCRIPTION
     * 
     * @access public
     * @return RETURNTYPE RETURNDESCRIPTION
     */
    public function getSubscriberId() {
        return $this->subscriberid;
    }

    /**
     * METHODDESCRIPTION
     * 
     * @access public
     * @param ARGTYPE $subscriberId ARGDESCRIPTION
     */
    public function setSubscriberId($subscriberId) {
        $this->subscriberid = $subscriberId;
    }

    /**
     * METHODDESCRIPTION
     * 
     * @access public
     * @return RETURNTYPE RETURNDESCRIPTION
     */
    public function getCreatetime() {
        return $this->createtime;
    }

    /**
     * METHODDESCRIPTION
     * 
     * @access public
     * @param ARGTYPE $createtime ARGDESCRIPTION
     */
    public function setCreatetime($createtime) {
        $this->createtime = $createtime;
    }

    /**
     * METHODDESCRIPTION
     * 
     * @access public
     * @return RETURNTYPE RETURNDESCRIPTION
     */
    public function getCarrier() {
        return $this->carrier;
    }

    /**
     * METHODDESCRIPTION
     * 
     * @access public
     * @param ARGTYPE $carrier ARGDESCRIPTION
     */
    public function setCarrier($carrier) {
        $this->carrier = $carrier;
    }

    /**
     * METHODDESCRIPTION
     * 
     * @access public
     * @return RETURNTYPE RETURNDESCRIPTION
     */
    public function getChannel() {
        return $this->channel;
    }

    /**
     * METHODDESCRIPTION
     * 
     * @access public
     * @param ARGTYPE $channel ARGDESCRIPTION
     */
    public function setChannel($channel) {
        $this->channel = $channel;
    }

    /**
     * METHODDESCRIPTION
     * 
     * @access public
     * @return RETURNTYPE RETURNDESCRIPTION
     */
    public function getDeviceAddress() {
        return $this->device_address;
    }

    /**
     * METHODDESCRIPTION
     * 
     * @access public
     * @param ARGTYPE $deviceAddress ARGDESCRIPTION
     */
    public function setDeviceAddress($deviceAddress) {
        $this->device_address = $deviceAddress;
    }

    /**
     * METHODDESCRIPTION
     * 
     * @access public
     * @return RETURNTYPE RETURNDESCRIPTION
     */
    public function getInboundAddress() {
        return $this->inbound_address;
    }

    /**
     * METHODDESCRIPTION
     * 
     * @access public
     * @param ARGTYPE $inboundAddress ARGDESCRIPTION
     */
    public function setInboundAddress($inboundAddress) {
        $this->inbound_address = $inboundAddress;
    }

    /**
     * METHODDESCRIPTION
     * 
     * @access public
     * @return RETURNTYPE RETURNDESCRIPTION
     */
    public function getMessage() {
        return $this->message;
    }

    /**
     * METHODDESCRIPTION
     * 
     * @access public
     * @param ARGTYPE $message ARGDESCRIPTION
     */
    public function setMessage($message) {
        $this->message = $message;
    }

    /**
     * METHODDESCRIPTION
     * 
     * @access public
     * @return RETURNTYPE RETURNDESCRIPTION
     */
    public function getMessageId() {
        return $this->message_id;
    }

    /**
     * METHODDESCRIPTION
     * 
     * @access public
     * @param ARGTYPE $messageId ARGDESCRIPTION
     */
    public function setMessageId($messageId) {
        $this->message_id = $messageId;
    }

    /**
     * METHODDESCRIPTION
     * 
     * @access public
     * @return RETURNTYPE RETURNDESCRIPTION
     */
    public function getMessageOrig() {
        return $this->message_orig;
    }

    /**
     * METHODDESCRIPTION
     * 
     * @access public
     * @param ARGTYPE $messageRaw ARGDESCRIPTION
     */
    public function setMessageOrig($messageOrig) {
        $this->message_orig = $messageOrig;
    }

    /**
     * METHODDESCRIPTION
     * 
     * @access public
     * @return RETURNTYPE RETURNDESCRIPTION
     */
    public function getRouter() {
        return $this->router;
    }

    /**
     * METHODDESCRIPTION
     * 
     * @access public
     * @param ARGTYPE $router ARGDESCRIPTION
     */
    public function setRouter($router) {
        $this->router = $router;
    }

    /**
     * METHODDESCRIPTION
     * 
     * @access public
     * @return RETURNTYPE RETURNDESCRIPTION
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * METHODDESCRIPTION
     * 
     * @access public
     * @param ARGTYPE $status ARGDESCRIPTION
     */
    public function setStatus($status) {
        $this->status = $status;
    }

    /**
     * METHODDESCRIPTION
     * 
     * @access public
     * @return RETURNTYPE RETURNDESCRIPTION
     */
    public function getStatusCode() {
        return $this->status_code;
    }

    /**
     * METHODDESCRIPTION
     * 
     * @access public
     * @param ARGTYPE $statusCode ARGDESCRIPTION
     */
    public function setStatusCode($statusCode) {
        $this->status_code = $statusCode;
    }

    public function getfolderid1() {
        //$sql = "select folderid from subscribers where phonenumber='$this->device_address' order by id desc";
        $sql = "CALL message_get_lastoutboundid('$this->device_address')";
        $rs = $this->query($sql);
        if ($rs && $rs->num_rows) {
            $row = $rs->fetchObject();
            if ($row->messageid) {
                return $row->messageid;
            }
        }
    }

    public function optOut1() {

        //echo "I am here in keyword abstract";
        // Check if this is a single folder subscriber first...
        $subscriber = new Application_Model_Subscriber();
        $folders = $subscriber->getContainingFolders($this->device_address, false);
        //echo $folders[0]->folderid;
        //echo "count folder".count($folders);
        ######################################
        if ($folders && is_array($folders)) {
            // Unsubsribe from this folder
            foreach ($folders as $folder) {
                $this->optOutSubscriber1($folder->folderid, $this->device_address);
            }
            $this->confirmOptOut($this->device_address);
        } else {
            $outbound = $this->getReplytoMessage();
            //print_r($outbound);
            //echo "folderid".$outbound->folderid;
            //die;
            if ($outbound && $outbound->folderid) {
                //$user = Zend_Registry::isRegistered('user') ? Zend_Registry::get('user') : new Application_Model_User($outbound->createuser);
                //$folder = new Application_Model_Folder($user, $outbound->folderid);
                $this->optOutSubscriber1($outbound->folderid, $this->device_address);
                $this->confirmOptOut($this->device_address);
            }
            //$this->_keyword->deleteSubscriber($this->_sender);
        }



        ###############################################


        /* if ($folders && is_array($folders) && count($folders) == 1) {
          // Unsubsribe from this folder
          $this->optOutSubscriber1($folders[0]->folderid, $this->device_address);
          } else {
          $outbound = $this->getReplytoMessage();
          //print_r($outbound);
          //echo "folderid".$outbound->folderid;
          //die;
          if ($outbound && $outbound->folderid) {
          //$user = Zend_Registry::isRegistered('user') ? Zend_Registry::get('user') : new Application_Model_User($outbound->createuser);
          //$folder = new Application_Model_Folder($user, $outbound->folderid);
          $this->optOutSubscriber1($outbound->folderid, $this->device_address);
          }
          //$this->_keyword->deleteSubscriber($this->_sender);
          } */
    }

    public function optOut2() {
        $this->confirmOptOut1($this->device_address,$this->inbound_address);
        $this->optoutFromSystem($this->device_address);
//        $subscriber = new Application_Model_Subscriber();
//        $folders = $subscriber->getContainingFolders($this->device_address, false);
//        //echo $folders[0]->folderid;
//        //echo "count folder".count($folders);
//        ######################################
//        if ($folders && is_array($folders)) {
//            // Unsubsribe from this folder
//            foreach ($folders as $folder) {
//                $this->optOutSubscriber1($folder->folderid, $this->device_address);
//            }
//            $this->confirmOptOut1($this->device_address);
//        } else {
//            $outbound = $this->getReplytoMessage();
//            if ($outbound && $outbound->folderid) {
//                $this->optOutSubscriber1($outbound->folderid, $this->device_address);
//                $this->confirmOptOut1($this->device_address);
//                $this->optoutFromSystem($this->device_address);
//            }
//            //$this->_keyword->deleteSubscriber($this->_sender);
//        }
    }
    /**
     * Temporary solition for Prosalution
     * to avoid serching for keyword y or yes
     * 
     */
public function optOutForYes() {
        $this->confirmYes($this->device_address);
}
    public function optOut311($sms) {
        $subscriber = new Application_Model_Subscriber();
        $folders = $subscriber->getContainingFolders($this->device_address, false);
        //echo $folders[0]->folderid;
        //echo "count folder".count($folders);
        ######################################
        if ($folders && is_array($folders)) {
            // Unsubsribe from this folder
            foreach ($folders as $folder) {
                $this->optOutSubscriber1($folder->folderid, $this->device_address);
            }
            $this->confirmOptOut3($this->device_address, $sms);
        } else {
            $outbound = $this->getReplytoMessage();
            //print_r($outbound);
            //echo "folderid".$outbound->folderid;
            //die;
            if ($outbound && $outbound->folderid) {
                $this->optOutSubscriber1($outbound->folderid, $this->device_address);
                $this->confirmOptOut3($this->device_address, $sms);
            }
            //$this->_keyword->deleteSubscriber($this->_sender);
        }
    }

    /**
     * Opts a subscriber out of a folder
     * 
     * @param string $phone
     * @return boolean
     */
    public function optOutSubscriber1($folder, $phone) {
        $sql = "CALL folder_delete_subscriber($folder, $phone)";
        $this->_writeLog("Opt out: $sql");
        $rs = $this->query($sql);
        echo "finally here first";
        ///////////////////////////
    }

    public function confirmOptOut($phone) {

// $apiUrl = 'https://text.mes.syniverse.com/SMSSend';
//$shortCode = '87365';
//$sendTo = array($phone);
//$sendMessage = urlencode("La Salsa Promo Alerts YOU WILL RECEIVE NO FURTHER MESSAGES. www.textmunication.com");
//$username = '4400';
//$password = 'Fq0^Hc0^';
//
//	             $uri = $apiUrl;
//		
//                            $ch = curl_init($uri);
//                            curl_setopt ($ch, CURLOPT_POST, 1);
//                            curl_setopt ($ch, CURLOPT_POSTFIELDS, "user=$username&pass=$password&smsfrom=$shortCode&smsto=$phone&smsmsg=$sendMessage");
//			// Now set some params, start with username and password
////			curl_setopt($ch, CURLOPT_USERPWD, $username . ':' . $password);
//			
//			// Turn off header output in the response
//			curl_setopt($ch, CURLOPT_HEADER, false);
//			
//			// Disable SSL peer verification
//			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//			
//			// Capture the output instead of echoing it
//			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//			
//			// Execute our request
//			$rs = curl_exec($ch);
//			
//			// Close the cURL handle
//			curl_close($ch);
        /*         * ********************************* */
//$apiUrl = 'https://secure-mrr.air2web.com/a2w_preRouter/httpApiRouter';
        $apiUrl = 'https://208.71.240.93/a2w_preRouter/httpApiRouter';
        $shortCode = '87365';
        $sendTo = array($phone);
        $sendMessage = urlencode("STOP:You have been unsubscribed");
        $username = 'textmu';
        $password = 'textmu1';

        $reportingkey1 = time();
        $reportingkey2 = md5(uniqid() . serialize($phone));
        echo $uri = $apiUrl . '?reply_to=' . $shortCode . '&recipient=' . implode('&recipient=', $sendTo) . '&body=' . $sendMessage . '&reporting_key1=' . $reportingkey1 . '&reporting_key2=' . $reportingkey2;



        $ch = curl_init($uri);

        // Now set some params, start with username and password
        curl_setopt($ch, CURLOPT_USERPWD, $username . ':' . $password);

        // Turn off header output in the response
        curl_setopt($ch, CURLOPT_HEADER, false);

        // Disable SSL peer verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        // Capture the output instead of echoing it
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Execute our request
        $rs = curl_exec($ch);

        // Close the cURL handle
        curl_close($ch);

        // Now lets inspect it and see if we have what we need
        $response = simplexml_load_string($rs);

        // Type cast the response code and description for use
        $code = intval($response->code);
        echo $description = "$response->description";

//		$this->synsend(28776,$phone,$sendMessage); 
        /////////////////////////////////////
        return $rs->success > 0;
    }
  public function confirmYes($phone) {

        $apiUrl = 'https://text.mes.syniverse.com/SMSSend';
        $shortCode = '87365';
        $sendTo = array($phone);
        $sendMessage = urlencode("Thank you your appointment has been confirmed");
        $username = '4400';
        $password = 'Fq0^Hc0^';

        $uri = $apiUrl;

        $ch = curl_init($uri);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "user=$username&pass=$password&smsfrom=$shortCode&smsto=$phone&smsmsg=$sendMessage");
        // Now set some params, start with username and password
//			curl_setopt($ch, CURLOPT_USERPWD, $username . ':' . $password);
        // Turn off header output in the response
        curl_setopt($ch, CURLOPT_HEADER, false);

        // Disable SSL peer verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        // Capture the output instead of echoing it
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Execute our request
        echo curl_exec($ch);

        // Close the cURL handle
        curl_close($ch);
    }
    public function confirmOptOut1($phone,$sc) {

        $apiUrl = 'https://text.mes.syniverse.com/SMSSend';
//        $shortCode = '87365';
        $shortCode = $sc;
        $sendTo = array($phone);
        if($shortCode == '87365'){
           $sendMessage = urlencode("Textmunications Promo Alerts YOU WILL RECEIVE NO FURTHER MESSAGES. www.textmunication.com");
        }
        if($shortCode == '88988'){
           $sendMessage = urlencode("Textmunication Promo Alerts: Unsubscribed, you will receive no further messages. Call 8006777003 Pwrd by Textmunication");
        }
        $username = '4400';
        $password = 'Fq0^Hc0^';

        $uri = $apiUrl;

        $ch = curl_init($uri);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "user=$username&pass=$password&smsfrom=$shortCode&smsto=$phone&smsmsg=$sendMessage");
        // Now set some params, start with username and password
//			curl_setopt($ch, CURLOPT_USERPWD, $username . ':' . $password);
        // Turn off header output in the response
        curl_setopt($ch, CURLOPT_HEADER, false);

        // Disable SSL peer verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        // Capture the output instead of echoing it
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Execute our request
        echo curl_exec($ch);

        // Close the cURL handle
        curl_close($ch);
    }

    public function alertMessage($phone, $alert) {
        $apiUrl = 'https://text.mes.syniverse.com/SMSSend';
        $shortCode = '87365';
        $sendMessage = urlencode($alert);
        $username = '4400';
        $password = 'Fq0^Hc0^';

        $uri = $apiUrl;

        $ch = curl_init($uri);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "user=$username&pass=$password&smsfrom=$shortCode&smsto=$phone&smsmsg=$sendMessage");
        // Now set some params, start with username and password
//			curl_setopt($ch, CURLOPT_USERPWD, $username . ':' . $password);
        // Turn off header output in the response
        curl_setopt($ch, CURLOPT_HEADER, false);

        // Disable SSL peer verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        // Capture the output instead of echoing it
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Execute our request
        $rs = curl_exec($ch);

        // Close the cURL handle
        curl_close($ch);
    }

// end of alert messages
    /**
     *     this added for La Salsa - SBI Enterprises stop message  :
     *    "STOP:You have been unsubscribed from La Salsa - SBI Enterprises account"
     *     just temporary		
     */

    public function confirmOptOut3($phone, $sms) {
//$apiUrl = 'https://secure-mrr.air2web.com/a2w_preRouter/httpApiRouter';
        $apiUrl = 'https://208.71.240.93/a2w_preRouter/httpApiRouter';
        $shortCode = '87365';
        $sendTo = array($phone);
//$sendMessage="";
//switch ($sms){
//    case "help":
//     $sendMessage = urlencode("La Salsa Promo Alerts 4 msgs/mo Msg&Data Rates May Apply, Reply STOP to cancel www.textmunication.com");
//     break;
//    default :
//    $sendMessage = urlencode("La Salsa Promo Alerts YOU WILL RECEIVE NO FURTHER MESSAGES. www.textmunication.com");    
//}
        if ($sms == "help") {
            $sendMessage = urlencode("La Salsa Promo Alerts 4 msgs/mo Msg&Data Rates May Apply, Reply STOP 2 stop www.textmunication.com 800.677.7003");
        } else {
            $sendMessage = urlencode("La Salsa Promo Alerts YOU WILL RECEIVE NO FURTHER MESSAGES. www.textmunication.com");
        }
        $username = 'textmu';
        $password = 'textmu1';

        $reportingkey1 = time();
        $reportingkey2 = md5(uniqid() . serialize($phone));
        echo $uri = $apiUrl . '?reply_to=' . $shortCode . '&recipient=' . implode('&recipient=', $sendTo) . '&body=' . $sendMessage . '&reporting_key1=' . $reportingkey1 . '&reporting_key2=' . $reportingkey2;

        $ch = curl_init($uri);

        // Now set some params, start with username and password
        curl_setopt($ch, CURLOPT_USERPWD, $username . ':' . $password);

        // Turn off header output in the response
        curl_setopt($ch, CURLOPT_HEADER, false);

        // Disable SSL peer verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        // Capture the output instead of echoing it
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Execute our request
        $rs = curl_exec($ch);

        // Close the cURL handle
        curl_close($ch);

        // Now lets inspect it and see if we have what we need
        $response = simplexml_load_string($rs);

        // Type cast the response code and description for use
        $code = intval($response->code);
        echo $description = "$response->description";

//		$this->synsend(28776,$phone,$sendMessage); 
        /////////////////////////////////////
        return $rs->success > 0;
    }

    /**
     * METHODDESCRIPTION
     * 
     * @access public
     * @return RETURNTYPE RETURNDESCRIPTION
     */
    public function getReplytoMessageId() {
        // If there is an inbound ID, we're reading one we've already written
        if ($this->id) {
            return $this->replyto_message_id;
        }

        // This is a new message coming in, so look backward to get the last outbound to it
        // We can only do this though if we've captured the from phone number
        if ($this->device_address) {
            $sql = "CALL message_get_lastoutboundid('$this->device_address')";
            $rs = $this->query($sql);

            if ($rs && $rs->num_rows) {
                $row = $rs->fetchObject();
                if ($row->messageid) {
                    return $row->messageid;
                }
            }
        }

        return '';
    }

    /**
     * Gets the last outbound for this inbound
     * 
     * @access public
     * @return stdClass A row from the messages outbound table and recipients table
     */
    public function getReplytoMessage() {
        // Get the last message out that we know from the phone number
        if ($this->device_address) {
            $sql = "CALL message_get_lastoutboundid('$this->device_address')";
            $rs = $this->query($sql);

            if ($rs && $rs->num_rows) {
                //echo "yes row returned";
                return $rs->fetchObject();
            }
        }

        return null;
    }

    /**
     * Finds out of the passed message is a keyword. If so, 
     * sets and returns the keyword id.
     * 
     * @access public
     * @param string $message
     * @return bool|int
     */
    public function getKeywordId($message) {
        $keyword = new Application_Model_Keyword($this->message, true);

        // If it was a real keyword it would have loaded a folder
        if ($keyword->folderid) {
            $this->_writeLog("Inbound getKeyword ID: $keyword->id");
            return $keyword->id;
        }

        // if this was not a keyword, see if there is a replyto message
        if ($this->replyto_message_id) {
            // If we have a replyto message, see if it has a keyword id
            $replytomessage = $this->getReplytoMessage();

            if ($replytomessage->keywordid) {
                return $replytomessage->keywordid;
            }
        }

        return false;
    }
    /**
     *  emergency decision to get keywordid 
     *  and folderid
     * 
     */
    public function getKewordidFolderid(Application_Model_Keyword $kwd, $message){
        $msg = trim($message);
        $sql = "select id, folderid from keywords where keyword='".$msg."'";
        $rs = $this->query($sql);
         if ($rs->hasResults()) {
            foreach ($rs->fetchAll() as $obj) {
                $kwd->setId($obj['id']);
                $kwd->setFolderId($obj['folderid']);
            }
           }
           return $kwd;
    }// end of getKewordidFolderid
    public function getKewordidFolderidTest($message){
        $msg = trim($message);
        $arinfo = array();
        $sql = "select id, folderid from keywords where keyword='$msg'";
        $rs = $this->query($sql);
         if ($rs->hasResults()) {
            foreach ($rs->fetchAll() as $obj) {
                $arinfo[] = $obj['id'];
                $arinfo[] = $obj['folderid'];
            }
           }
           return $arinfo;
    }// end of getKewordidFolderid

    /**
     * Looks for a folder id in the replytomessage to see what folder
     * the subscriber belongs to for this conversation. This is what
     * will be used to opt them out if this is an opt out message.
     * 
     * @access public
     * @return bool|int
     */
    public function getFolderId() {
        // if there was a replyto message see if it has a folder id
        if ($this->replyto_message_id) {
            // If we have a replyto message, see if it has a folder id
            $replytomessage = $this->getReplytoMessage();

            if ($replytomessage->folderid) {
                return $replytomessage->folderid;
            }
        }

        return false;
    }

    /**
     * METHODDESCRIPTION
     * 
     * @access public
     * @param ARGTYPE $replytoMessageId ARGDESCRIPTION
     */
    public function setRelpytoMessageId($replytoMessageId) {
        $this->replyto_message_id = $replytoMessageId;
    }

    public function save() {
        $message = $this->_dbh->real_escape_string($this->message);
        $message_orig = $this->_dbh->real_escape_string($this->message_orig);

        $sql = "CALL message_log_inbound(
			'$this->subscriberid', '$this->carrier', '$this->channel', '$this->device_address',
			'$this->inbound_address', '$message', '$this->message_id', '$message_orig',
			'$this->router', '$this->status', '$this->status_code', '$this->replyto_message_id', 
			'$this->keywordid', '$this->folderid', $this->depth
		)";

        $rs = $this->query($sql);

        if ($this->hasError()) {
            error_log($this->getError());
            $this->error = 'Unable to log inbound message';
            return false;
        }

        return true;
    }

    public function handleKeywordActions($replytomessage = null) {
        $bReplyToStop = true;
        // LOG IT for testing
        $this->_writeLog("ReplyToMessage is " . print_r($replytomessage, 1));

        // See if this matches a keyword and handle special cases
        $keyword = new Application_Model_Keyword($this->message, true);
        $this->_writeLog("Keyword folderid: $keyword->folderid");

        // just hacking this for now
        if (!in_array(strtolower($this->message), array('stop', 'end', 'quit', 'info', 'help', 'cancel', 'stopall'))) {

            // if it didn't load a folderid, it is probably not a valid keyword
            // Now check if there is a replytomessageid and this is a response to
            // a previous outbound
            if (empty($keyword->folderid)) {
                if ($replytomessage) {

                    $this->_writeLog("Originating Keyword ID: $replytomessage->keywordid");
                    $keyword = new Application_Model_Keyword($replytomessage->keywordid);
                }
            }
            // If there is a replytomessage, get the create user. This will be for all campaign replies, as well
            // as multi level conversations. If there is no replytomessage we should ALWAYS have a keyword, so 
            // get the user that currently owns that keyword and send the reply back as them.
            $creatorid = !empty($replytomessage->createuser) ? $replytomessage->createuser : $keyword->createuser;
        } else {
            $creatorid = 0;
        }

        // Prepare the reply messages if there is one
        $keyword->response = $keyword->replyheader ? "{$keyword->replyheader}:" : '';
        $keyword->response .= "{$keyword->replybody}\n{$keyword->replyfooter}";
        if (trim($keyword->response) === "reply STOP 2 stop") {
            $bReplyToStop = false;
        }  // 04/13/2013 tmp solution to stop double msg
        // Handle special actions
        $keyword->handleSpecialActions($this);

        // LOG IT for testing
        $this->_writeLog("Keyword ID is $keyword->id");

        // If we have an id then this is a keyword
        if ($keyword->id) {
            // LOG IT for testing
            $this->_writeLog("Opted Out Flag is $keyword->optedOut");


            // LOG IT for testing
            $this->_writeLog("Message Creator is $creatorid");

            $message = new Application_Model_Message(new Application_Model_User((int) $creatorid));
//            $smsoutbound = new Application_Model_Smsoutbound($message);
            // Set the message keyword and folder id's. These will be used for tracking a conversation as well as handling optouts
            $message->keywordid = $keyword->id;
            $message->folderid = $keyword->folderid;

            // This would only be set from a SpecialAction model
            if (!$this->optedOut) {
                $this->_writeLog("non-optout");
                // Subscribe this sender, but only if they are not already a subscriber
                if (!$keyword->hasSubscriberPhone($this->device_address)) {
                    // Depth for this message is 1 since we are an optin
                    $this->depth = 1;
                    // Sign them up for this keyword
                    $keyword->addSubscriber($this->device_address);

                    /* --------------------- */
                    if ($keyword->notifybysms) {
                        $dt = date('Y-m-d H:i:s');
                        $confirm = "87365:" . $this->device_address . " has opted in to keyword " . $keyword->keyword . " on $dt";

                        $this->alertMessage($keyword->notifybysms, $confirm);
                    }
                     if ($keyword->notifybyemail) {
                         $dt = date('Y-m-d H:i:s');
                        $confirm = "87365:" . $this->device_address . " has opted in to keyword " . $keyword->keyword . " on $dt";
                        
                        $this->alertnewaccount($keyword->notifybyemail, "Lead notification", $confirm);
                     }
                    /* --------------------- */


                    $this->_writeLog("add subscriber: $this->device_address, response: $keyword->response");
                    // If there is an autoreply, send it now
                    if ($keyword->response) {
                        if ($bReplyToStop) {
                            // update message exp time
                            if ($keyword->offerexp) {
                                $message->sendNow($this->updateExp($keyword->response, $keyword->offerexp), array($this->device_address),$this->inbound_address);
                            } else {
                                $message->sendNow($keyword->response, array($this->device_address),$this->inbound_address);
                            }
                        }
                    }
                } else {
                    if (!$keyword->usecustomresponse) {
                        // If there is an alternate autoresponder
                        if ($keyword->usealt) {
                            $keyword->response = $keyword->replyheader ? "{$keyword->replyheader}:" : '';
                            $keyword->response .= "{$keyword->replybodyalt}\n{$keyword->replyfooter}";
                        } else {
                            // Otherwise provide a default one.
                            $keyword->response = 'You are already opted in. Thanks for your message.';
                            // TODO: ACTUALLY WE WANT THE 1ST ONE TO KEEP GOING OUT, BUT ALTERNATE RESPONSES
                            // NEED TO BE SET UP FOR ALL EXISTING KEYWORDS 1ST SINCE IT IS A NEW FEATURE
                        }
                    }
                    $this->_writeLog("already opted in: $keyword->response");
                    if ($keyword->replybodyalt) {

                        $message->sendNow($keyword->replybodyalt, array($this->device_address),$this->inbound_address);
                    } else {
                        $keyword->replybodyalt = 'You are already opted in. Thanks for your message.';
                        $message->sendNow($keyword->replybodyalt, array($this->device_address),$this->inbound_address);
                    }
                }
            } else {
                /* Taking out for now so stop messages can send their default responses
                  Actually, I think this might be unnecessary, because if a custom message is set
                  it will override the reponse and be used below...
                  if (!$keyword->usecustomresponse) {
                  $keyword->response = 'Your message has been received.';
                  } */
                $this->_writeLog("OptOut $keyword->response");
                if ($bReplyToStop)
                    $message->sendNow($keyword->response, array($this->device_address),$this->inbound_address);
            }
        }
    }

//	public function handleKeywordActions($replytomessage = null) {
//                            $bReplyToStop = true;
//		// LOG IT for testing
//		$this->_writeLog("ReplyToMessage is " . print_r($replytomessage, 1));
//		
//		// See if this matches a keyword and handle special cases
//		$keyword = new Application_Model_Keyword($this->message, true);
//		$this->_writeLog("Keyword folderid: $keyword->folderid" );
//		
//		// just hacking this for now
//		if (!in_array(strtolower($this->message), array('stop','end','quit','info','help'))) {
//
//                                // if it didn't load a folderid, it is probably not a valid keyword
//                                // Now check if there is a replytomessageid and this is a response to
//                                // a previous outbound
//                                if (empty($keyword->folderid)) {
//                                        if ($replytomessage) {
//                                                $this->_writeLog("Originating Keyword ID: $replytomessage->keywordid");
//                                                $keyword = new Application_Model_Keyword($replytomessage->keywordid);
//                                        }
//                                }
//	         // If there is a replytomessage, get the create user. This will be for all campaign replies, as well
//                        // as multi level conversations. If there is no replytomessage we should ALWAYS have a keyword, so 
//                        // get the user that currently owns that keyword and send the reply back as them.
//			$creatorid = !empty($replytomessage->createuser) ? $replytomessage->createuser : $keyword->createuser;
//			
//		} else {
//			$creatorid = 0;
//		}
//		
//		// Prepare the reply messages if there is one
//		$keyword->response  = $keyword->replyheader ? "{$keyword->replyheader}:" : '';
//		$keyword->response .= "{$keyword->replybody}\n{$keyword->replyfooter}";   // commented on 4/11/13
//		
//                             if(trim($keyword->response) === "reply STOP 2 stop") {$bReplyToStop = false; }  // 04/13/2013 tmp solution to stop double msg
//                
//		// Handle special actions
//		$keyword->handleSpecialActions($this);
//		
//		// LOG IT for testing
//		$this->_writeLog("Keyword ID is $keyword->id");
//		
//		// If we have an id then this is a keyword
//		if ($keyword->id) {
//			// LOG IT for testing
//			$this->_writeLog("Opted Out Flag is $keyword->optedOut");
//			// LOG IT for testing
//			$this->_writeLog("Message Creator is $creatorid");
//			
//			$message = new Application_Model_Message(new Application_Model_User((int) $creatorid));
//			$smsoutbound = new Application_Model_Smsoutbound($message);
//			// Set the message keyword and folder id's. These will be used for tracking a conversation as well as handling optouts
//			$message->keywordid = $keyword->id;
//			$message->folderid  = $keyword->folderid;
//			// This would only be set from a SpecialAction model
//			if (!$this->optedOut) 
//                                           {
//                                                $this->_writeLog("non-optout");
//                                                // Subscribe this sender, but only if they are not already a subscriber
//                                                if (!$keyword->hasSubscriberPhone($this->device_address)) {
//                                                        // Depth for this message is 1 since we are an optin
//                                                   $this->depth = 1;
//                    //		        $smsoutbound->alertMessage($this->device_address, "Phone_added $keyword->id");
//                                                        // Sign them up for this keyword
//                                                    $optin_already = $keyword->isSubcriberGetone($keyword->id, $keyword->folderid, $this->device_address);
//                                                    $date = new DateTime($optin_already);
//                                                    $dd =  $date->format('Y-m-d');
//        //                                        $smsoutbound->alertMessage($this->device_address, $dd);
//                                                if($optin_already && $keyword->optinLife_exp($dd, $keyword->optinlife) !==0)
//                                                {
//                                                    if($keyword->replybodyalt){
//                                                     $smsoutbound->alertMessage($this->device_address, $keyword->replybodyalt);
//                                                    }else{
//                                                        $dft = 'You are already opted in. Thanks for your message.';
//                                                        $smsoutbound->alertMessage($this->device_address, $dft);
//                                                    }
//                                                }
//                                        else
//                                         {       
//                                                $keyword->addSubscriber($this->device_address);
//                                                $this->_writeLog("add subscriber: $this->device_address, response: $keyword->response");
////                                                $smsoutbound->alertMessage($this->device_address, "Default msg00");
//                                                // If there is an autoreply, send it now
//                                                if ($keyword->response) {
//                                                    if($keyword->offerexp){
//                                                       $message->sendNow($this->updateExp($keyword->response, $keyword->offerexp), array($this->device_address)); 
//                                                    }else{
//                                                        if ($keyword->keyword == 'amigo' || $keyword->keyword == 'markis' || $keyword->keyword == 'temu' || $keyword->keyword == 'buno' ){
//                                                          $message->synsendNow(28776, $this->device_address,$keyword->response);
////                                                          $smsoutbound->alertMessage($this->device_address, "Default msg");
//                                                        }else {
//                                                              if($bReplyToStop)
//                                                             $message->sendNow($keyword->response, array($this->device_address));
////                                                             $message->sendToSynNow($keyword->response, array($this->device_address));
////                                                             $smsoutbound->alertMessage($this->device_address, "Default msg");
//                                                        }
//                                                    }
//			   }
//                                         if($optin_already){
//                                             if($keyword->id !==82 || $keyword->id !==84 || $keyword->id !==85 || $keyword->id !==86 || $keyword->id !==1899 ){
//                                                  $keyword->update_freeoffer($keyword->id, $keyword->folderid, $this->device_address);
//                                                  $dft = 'You are already opted in. Thanks for your message.';
//                                                  $smsoutbound->alertMessage($this->device_address, $dft);
//                                             }
//                                         }else{
//                                              if($keyword->id !==82 || $keyword->id !==84 || $keyword->id !==85 || $keyword->id !==86 || $keyword->id !==1899 )
//                                            $keyword->getFree_offer($keyword->id, $keyword->folderid, $this->device_address); 
//                                         }
//                                         
//                                        }
//		} else {
//		if (!$keyword->usecustomresponse) {
//						// If there is an alternate autoresponder
//                                        if ($keyword->usealt) {
//                                            $keyword->response  = $keyword->replyheader ? "{$keyword->replyheader}:" : '';
////                                            $keyword->response .= "{$keyword->replybodyalt}\n{$keyword->replyfooter}"; // commented on 4/11/13
////                                            $smsoutbound->alertMessage($this->device_address, "HELLO kkkk");
//                                        } else {
//                                            // Otherwise provide a default one.
//                                            $keyword->response = 'You are already opted in. Thanks for your message.';
//                                            $dft = 'You are already opted in. Thanks for your message.';
//                                            if($keyword->replybodyalt){
//                                                if ($keyword->keyword == 'amigo' ||$keyword->keyword == 'markis' || $keyword->keyword == 'temu' || $keyword->keyword == 'buno' ){
//                                                          $message->synsendNow(28776, $this->device_address,$keyword->replybodyalt);
//                                                        } else{ 
//                                                                 $smsoutbound->alertMessage($this->device_address, $keyword->replybodyalt);
//                                                        }
//                                            }else{
//                                                $smsoutbound->alertMessage($this->device_address, $dft);
//                                            }
//                                            // TODO: ACTUALLY WE WANT THE 1ST ONE TO KEEP GOING OUT, BUT ALTERNATE RESPONSES
//                                            // NEED TO BE SET UP FOR ALL EXISTING KEYWORDS 1ST SINCE IT IS A NEW FEATURE
//                                        }
//		   }
//					$this->_writeLog("already opted in: $keyword->response");
//					$message->sendNow($keyword->replybodyalt, array($this->device_address));
//				}
//			} else {
//				/* Taking out for now so stop messages can send their default responses
//				Actually, I think this might be unnecessary, because if a custom message is set
//				it will override the reponse and be used below...
//				if (!$keyword->usecustomresponse) {
//					$keyword->response = 'Your message has been received.';
//				}*/
//				$this->_writeLog("OptOut $keyword->response");
//                                                          if($bReplyToStop)
//				$message->sendNow($keyword->response, array($this->device_address));  
//			}
////                                                $smsoutbound->alertMessage($this->device_address, "Default msg");
//		}
//                 // if no keyword this default need to send back 111233;
////                                    else{
////                                           $smsoutbound->alertMessage($this->device_address, 'Test mesages');
////                                    }
//                /// end of edition  111233 
//	}

    /**
     * Loads up this model with data from the database
     *
     * @access protected
     * @param int $id The id to load
     * @return boolean
     */
    protected function _load($id = 0) {
        if ($id) {
            $sql = "CALL message_get_inbound($id)";

            $rs = $this->query($sql);

            if ($this->hasError()) {
                $this->setError('Unable to load inbound message', $this->getError());
                return false;
            }

            foreach ($rs->fields as $key => $value) {
                if (property_exists($this, $key)) {
                    $this->{$key} = $value;
                }
            }

            return true;
        }

        return false;
    }

// end of load

    public function updateExp($data, $expday) {
        if ($data != "") {
            $expIndx = strrpos($data, 'exp');
            if ($expIndx != 0) {
                $setdate = date('m/d', strtotime("+$expday days"));
                $newextp = "exp:" . $setdate;
                $response = substr($data, 0, $expIndx) . $newextp . substr($data, $expIndx + strlen($newextp));
                return $response;
            } else {
                $response = false;
            }
        }
    }

// end of updateExp;

    /**
     *  optout subscriber from entire system
     *  @access public
     *  @name optoutFromSystem
     *  @parm int $phonenumber
     *  @return bool
     */
    public function optoutFromSystem($phonenumber) {
        $sql = "Call optoutfromsystem($phonenumber)";
        $rs = $this->query($sql);
        if ($rs->success == -1) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     *  If sms id is in the system 
     *  then no response will be send out
     */
    public function checksmsId($smsid) {
        // 103110377721374142231851
        $sql = "Select message_id from messages_inbound where message_id='.$smsid.' limit 1";
        $rs = $this->query($sql);
        if ($rs) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
  /**
   *   client gets notification of 
   *   a  new member opted in 
   *   by keyword
   */  
 public function alertnewaccount($email, $subject, $message){
            $mail = new Zend_Mail();
            $mail->addTo($email);
            $mail->setFrom('info@textmunication.com', 'Textmunication Admin');
            $mail->setSubject($subject);
            $mail->setBodyText($message);
            $mail->send();
        }
        
     /**
     *   is not used yet
     * 
     */
    public function addMemcahceKeword() {
        $sql = "SELECT id,createuser, keyword, folderid,replyheader, replybody, replyfooter, replybodyalt, notifybysms, notifybyemail FROM keywords";
        $keywords = array();
        $rs = $this->query($sql);
        if ($rs->hasResults()) {
            foreach ($rs->fetchAll() as $val) {
                $obj = new stdClass();
                $obj->id = $val['id'];
                $obj->createuser = $val['createuser'];
                $obj->keyword = $val['keyword'];
                $obj->folderid = $val['folderid'];
                $obj->replyheader = $val['replyheader'];
                $obj->replybody = $val['replybody'];
                $obj->replyfooter = $val['replyfooter'];
                $obj->replybodyalt = $val['replybodyalt'];
                $obj->notifybysms = $val['notifybysms'];
                $obj->notifybyemail = $val['notifybyemail'];
                $keywords[$val['keyword']] = $obj;
            }
            return $keywords;
        }
    }
   
    /**
     * 
     * 
     */
    public function getoptedoutFromsystem(){
        $sql = "select distinct phonenumber from allphonenumbers where optouttime ='0000-00-00 00:00:00' group by phonenumber asc";
         $phones = array();
         $rs = $this->query($sql);
         if ($rs->hasResults()) {
            foreach ($rs->fetchAll() as $val) {
                $phones[] = $val['phonenumber'];
            }
         }
         return $phones;
    }
    /**
     *   updating phone numbers in messages_inbound table
     *   just make sure all phone numbers are 11 digits 
     *  
     */
    public function updatePhonenumber(){
         $sql = "update `messages_inbound` set `device_address`= concat('1',`device_address`) WHERE length(`device_address`) = 10";
         $rs = $this->query($sql);
    }
    /**
     * 
     * 
     */
    public function getoptedoutFromInbound(){
        //update `messages_inbound` set `device_address`= concat('1',`device_address`) WHERE length(`device_address`) = 10
        $sql = "select distinct device_address from messages_inbound where message in('stop','fuck','cancel') group by device_address asc";
         $phones = array();
         $rs = $this->query($sql);
         if ($rs->hasResults()) {
            foreach ($rs->fetchAll() as $val) {
                $phones[] = $val['device_address'];
            }
         }
         return $phones;
    }
    
        public function delivery_recipies_insert($obj) {
          $phone   = preg_replace("/[^0-9]/", "", $obj['destination']);
          $trid = $obj['trackingid'];
          $scode = $obj['statuscode'];
          $sinfo = $obj['statusinfo'];
           $this->_writeLog("INFO:\n".$phone."\n".$trid."\n".$scode."\n".$sinfo);
            $sql = "INSERT INTO messages_drs_delivery (`destination`, `trackingid`,`statuscode`,`statusinfo`) VALUES ($phone,$trid,$scode,'$sinfo')";
            $this->query($sql);
            if ($this->hasError()) {
                error_log($this->getError());
               return $this->error = 'Unable to log delivery messages';
//                return false;
            }
    }
     public function delivery_recipies_insert222($obj) {
        if ($obj instanceof stdClass) {
          $phone   = preg_replace("/[^0-9]/", "", $obj->destination);
            
            $sql = "INSERT INTO messages_drs_delivery (`destination`, `trackingid`,`statuscode`,`statusinfo`) 
                     VALUES ($phone,'$obj->trackingid',$obj->statuscode,'$obj->statusinfo')";
            $this->query($sql);
            if ($this->hasError()) {
                error_log($this->getError());
               return $this->error = 'Unable to log delivery messages';
                return false;
            }

            return true;
        }
    }
    
    /**
     * Optouts subscribers from inbound table
     *  stores in the memcache for stoping sending
     *  campaign to them from API users
     *  
     */
    public function optoutsListFromInboundToMemcache() {
        $this->updatePhonenumber();
        $mobj = new Application_Model_CacheMemcache(86400);
        $optin_key_list = "OPTIN_ALL_KEYS";
        
       $get_all_keys = $mobj->getData($optin_key_list);
 
        foreach($get_all_keys as $key13=>$val13){
            $mobj->delData($val13);
        }  
        $mobj->delData($optin_key_list);
        
        $optarr = $this->getoptedoutFromInbound();
        $optinarrkeys = array();
        $sortphone = array();
        $cnt = count($optarr);
        $data_in_array = 15000;

        foreach ($optarr as $key => $val) {
            if ($key % $data_in_array == 0) {
                if ($key != 0) {
                    $optinKeys = $val;
                    $optinarrkeys[] = $optinKeys;
                    $mobj->setData($optinKeys, $sortphone);
                    unset($sortphone);
                }
            }
            if ($key == ($cnt - 1) && count($sortphone) < $data_in_array) {
                $optinKeys = $val;
                $optinarrkeys[] = $optinKeys;
                $mobj->setData($optinKeys, $sortphone);
                unset($sortphone);
            }
            $sortphone[$val] = $val;
        }
        $mobj->setData($optin_key_list, $optinarrkeys);

    }
    /**
     *  Controls API action if
     *  some one already optedout then no messages will send
     *  to that phone number. Data selects from inbound table
     */
    public function statusofSubscriber($phone){
        
//        $mobj = memcache_connect('10.210.65.119', 11211);
        $mobj = memcache_connect('10.179.252.160', 11211);
        
        $keys = $mobj ->get('OPTIN_ALL_KEYS');
       
        for($n = 0; $n< count($keys); $n++){
            
            if($keys[$n] >= $phone){
                
             $phk = $keys[$n];
              
              $rarr = $mobj->get((int)$phk);
              
              if($rarr[$phone]){
                  
                  return 1;
                  
              }else{ return 0; }
            }
        }
    }// end of statusofSubscriber
    
    /*NEW MO PROCESSING */
    
     /**
     * 
     * 
     */
    public function handleOptout($kobj, $phone) {
        if ($kobj->id && $kobj->createuser == 0) {
            if ($this->getStatusofMoresponderbyPhone($phone)) {
                $this->confirmOptOut_stop($phone, $kobj->replybody, $kobj->shortcode);
                $this->optoutFromSystembyStop($phone);
                $this->_writeLog("\nHandleOptout func $phone $kobj->replybody: " . $kobj->shortcode.'\n');
            } else {
                // send alternate msg body
                $this->confirmOptOut_stop($phone, $kobj->replybodyalt, $kobj->shortcode);
                $this->_writeLog("\nHandleOptout func alter: $phone $kobj->replybodyalt: " . $kobj->shortcode.'\n');
            }
        }
    }
    
     /**
     *   Creates keyword object. This is very
     *   fast way to handle current problems with MO
     *  @name getKewordObject
     *  @access public
     *  @param type int $keyword, $shortcode, $phonenumber
     *  @return type Object
     */
    public function getKewordObject($keyword, $shortcode, $phone) {
        $sql = "SELECT id,createuser, keyword, folderid,replyheader, 
                             replybody, offerexp, alertmessage, replyfooter, replybodyalt,
                             notifybysms, notifybyemail, shortcode FROM keywords
                              where keyword='$keyword' and shortcode=$shortcode";
        $rs = $this->query($sql);
        $obj = new stdClass();
        if ($rs->hasResults()) {
            foreach ($rs->fetchAll() as $val) {
                $obj->id = $val['id'];
                $obj->createuser = $val['createuser'];
                $obj->keyword = $val['keyword'];
                $obj->folderid = $val['folderid'];
                $obj->replyheader = $val['replyheader'];
                $obj->replybody = $val['replybody'];
                $obj->offerexp = $val['offerexp'];
                $obj->alertmessage = $val['alertmessage'];
                $obj->replyfooter = $val['replyfooter'];
                $obj->replybodyalt = $val['replybodyalt'];
                $obj->notifybysms = $val['notifybysms'];
                $obj->notifybyemail = $val['notifybyemail'];
                $obj->shortcode = $val['shortcode'];
            }
            $obj->subscriber = $phone;
        } else {
            $obj->id = -1;
            $obj->keyword = $keyword;
            $obj->shortcode = $shortcode;
            $obj->subscriber = $phone;
        }
        return $obj;
    }
    
 /**   
   *  Handles in coming MO messages
   *  @name keywordProcess
   *  @param type object Keyword
   *  @access public
   *  @return type void
   */
    public function keywordProcess($kobj) {
   //$phone, $sms, $createuser, $keywordid, $folderid, $sc
        if ($kobj instanceof stdClass) {
            if ($kobj->id !=-1 && $kobj->createuser !=0) {
                $phone = $kobj->subscriber;
                $this->_writeLog("\n Message Creator is $kobj->createuser \n");
               /* Subscriber is not in the system new */
                if (!$this->getStatusofMoresponderbyPhone($kobj->subscriber)) {
                    $response = $kobj->replyheader ? "{$kobj->replyheader}:" : '';
                    $response .= "{$kobj->replybody}\n{$kobj->replyfooter}";

                    $this->addSubscriber($kobj->id, $phone);
                    if($kobj->offerexp > 0)
                    {
                        $response = $this->updateExp($response, $kobj->offerexp);
                    }    
                    $this->confirmOptOut_mo($phone, $response, $kobj->createuser, $kobj->id,$kobj->folderid,$kobj->shortcode);
                     if($kobj->alertmessage ==""){
                         $kobj->alertmessage="Standard msg & data may apply. Msg sent 4/month. Reply HELP for HELP. Reply STOP to optout";
                         }
                    $this->confirmOptOut_mo($phone, $kobj->alertmessage, $kobj->createuser, $kobj->id,$kobj->folderid,$kobj->shortcode);
                    /* ----------------------------------------------- */
                       $this->optinConfirmationSmsEmai($kobj);
                    /* ----------------------------------------------- */
                    $this->_writeLog($kobj->subscriber." is not in the system \n"."$kobj->keyword \n $response \n Subscriber: $kobj->subscriber ");
                } else {
                        /* Subscriber is in the system but is not in the current account */
                        if (!$this->subcriberStatus($kobj->createuser, $kobj->subscriber)) {
                            $this->_writeLog("\n KeywordProcess Subscriber is not in the account:  \n");
                            $this->addSubscriber($kobj->id, $phone);
                            $response = $kobj->replyheader ? "{$kobj->replyheader}:" : '';
                            $response .= "{$kobj->replybody}\n{$kobj->replyfooter}";
                            $this->_writeLog("\n KeywordProcess $kobj->keyword \n $response \n Subscriber: $kobj->subscriber ");
                            
                            $this->confirmOptOut_mo($phone, $response, $kobj->createuser, $kobj->id,$kobj->folderid,$kobj->shortcode);
                             /* ----------------------------------------------- */
                                $this->optinConfirmationSmsEmai($kobj);
                            /* ----------------------------------------------- */
                                if($kobj->alertmessage ==""){
                                  $kobj->alertmessage="Standard msg & data may apply. Msg sent 4/month. Reply HELP for HELP. Reply STOP to optout";
                                }
                                $this->confirmOptOut_mo($phone, $kobj->alertmessage,$kobj->createuser, $kobj->id,$kobj->folderid,$kobj->shortcode);
                        }
                        else{
                            /* Subscriber is in the account but optins with a new keyword */
                            if(!$this->getStatusofMoresponder($kobj->folderid, $kobj->id, $kobj->subscriber)){
                                $this->_writeLog("\n KeywordProcess Subscriber is in the account new keyword \n");
                               $this->addSubscriber($kobj->id, $phone);
                               $response = $kobj->replyheader ? "{$kobj->replyheader}:" : '';
                               $response .= "{$kobj->replybody}\n{$kobj->replyfooter}";
                               
                               $this->_writeLog("\n KeywordProcess $kobj->keyword \n $response \n Subscriber: $kobj->subscriber");
                               $this->confirmOptOut_mo($phone, $response, $kobj->createuser, $kobj->id,$kobj->folderid,$kobj->shortcode);
                                /* ----------------------------------------------- */
                                   $this->optinConfirmationSmsEmai($kobj);
                               /* ----------------------------------------------- */
                        }
                        else{
                            $response = $kobj->replyheader ? "{$kobj->replyheader}:" : '';
                            $response .= "{$kobj->replybodyalt}\n{$kobj->replyfooter}";
                            $this->_writeLog("\n KeywordProcess Auto reply $kobj->keyword \n $response \n Subscriber: $kobj->subscriber ");
                            // default alternate message
                            if($kobj->replybodyalt ==""){
                              $response="Thank you for texting, we see you have already opted-in";
                             }
                            $this->confirmOptOut_mo($phone, $response, $kobj->createuser, $kobj->id,$kobj->folderid,$kobj->shortcode);
                        }
                     }
                }
            }// end of if
            else if($kobj->id !=-1 && $kobj->createuser == 0){
                      $this->confirmOptOut_stop($kobj->subscriber, $kobj->replybody, $kobj->shortcode);
                 }
            else {
                $msg = strtolower($kobj->keyword);
                if ($msg == 'y' || $msg == 'yes' || $msg == 'n' || $msg == 'no') {
                    $response = "Thank you for sending confirmation";
                } else {
                    $response = "Your message has been delivered";
//                    $response = "This keyword - $msg - is not recognized by the system thank you for response";
//                    $response = "Thank you for your reply we will get back to you as soon as possible";
                }
                $this->_writeLog("\n KeywordProcess Response: $response \n Subscriber: $kobj->subscriber ");
//                $this->confirmOptOut1($kobj->subscriber, $response, $kobj->createuser, $kobj->id,$kobj->folderid,$kobj->shortcode);
                $this->confirmOptOut_stop($kobj->subscriber, $response, $kobj->shortcode);
            }
            $objData = serialize($kobj);
            $this->_writeLog("\n Kobject: ".$objData);
        }
    }
    /**
     *  optin cofirmation by sms or by email
     * @access public
     * @param type $kobj keyword object
     * @return type void
     */
    public function optinConfirmationSmsEmai($kobj){
             /* ----------------------------------------------- */
                    if ($kobj->notifybysms != 0) {
                        $dt = date('Y-m-d H:i:s');
                        $confirm = "$kobj->shortcode:" . $kobj->subscriber . " has opted in to keyword " . $kobj->keyword . " on $dt";
                        $this->confirmOptOut_mo($kobj->notifybysms, $confirm,$kobj->createuser, $kobj->id,$kobj->folderid, $kobj->shortcode);
                         $this->_writeLog(" \n OptConf func notification by $kobj->notifybysms \n");
                    }
                    if ($kobj->notifybyemail != "") {
                        $dt = date('Y-m-d H:i:s');
                        $confirm = "$kobj->shortcode:" . $kobj->subscriber . " has opted in to keyword " . $kobj->keyword . " on $dt";
                        $this->alertnewaccount($kobj->notifybyemail, "Lead notification",$confirm);
                        $this->_writeLog(" \n OptConf func notification by email $kobj->notifybyemail \n");
                    }
                    /* ----------------------------------------------- */
    }
    
      public function confirmOptOut_mo($phone, $sms, $createuser, $keywordid, $folderid, $sc) {
        $trackingid = "";
         $reportingkey1 = time();
         $reportingkey2 = md5(uniqid() . serialize($phone));
        //($phone, $response, $kobj->createuser, $kobj->id,$kobj->folderid,$kobj->shortcode)
        $sendtime = date('Y-m-d H:i:s');
        $source = 101;
//        $gatewayid =  $this->outbound_message_log(addslashes($sms), $createuser, $reportingkey1, $reportingkey2, $keywordid, $folderid, $sc);
//        $this->mtsto_outbound_formorequest($trackingid,$gatewayid,$phone,$reportingkey1,$reportingkey2,$sendtime,'',$source,$createuser);
        $this->mt_for_mo_request($phone, $sms, $createuser, $keywordid, $sc);
         $this->_writeLog("\n Confirmoptout_mo func Trackingid ".$trackingid.  " for response \n Gatewayid $gatewayid");
    }
      public function confirmOptOut_stop($phone, $sms, $sc) {

        $apiUrl = 'https://text.mes.syniverse.com/SMSSend';
        $shortCode = $sc;
        $sendMessage = urlencode($sms);
        $username = '4400';
        $password = 'Fq0^Hc0^';
        $uri = $apiUrl;

        $ch = curl_init($uri);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "user=$username&pass=$password&smsfrom=$shortCode&smsto=$phone&smsmsg=$sendMessage");
        // Now set some params, start with username and password
//			curl_setopt($ch, CURLOPT_USERPWD, $username . ':' . $password);
        // Turn off header output in the response
        curl_setopt($ch, CURLOPT_HEADER, false);

        // Disable SSL peer verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        // Capture the output instead of echoing it
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Execute our request
        curl_exec($ch);
//        $response = simplexml_load_string($rs);
//        $trackingid = $response->trackingid;
        // Close the cURL handle
        curl_close($ch);
    }
      public function mt_for_mo_request($phone, $sms, $createuser,$keywordid, $sc) {

        $apiUrl = 'https://text.mes.syniverse.com/SMSSend';
        $shortCode = $sc;
        $sendMessage = urlencode($sms);
        $username = '4400';
        $password = 'Fq0^Hc0^';
        $uri = $apiUrl;

        $ch = curl_init($uri);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "user=$username&pass=$password&smsfrom=$shortCode&smsto=$phone&smsmsg=$sendMessage");
        // Now set some params, start with username and password
//			curl_setopt($ch, CURLOPT_USERPWD, $username . ':' . $password);
        // Turn off header output in the response
        curl_setopt($ch, CURLOPT_HEADER, false);

        // Disable SSL peer verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        // Capture the output instead of echoing it
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Execute our request
        $rs = curl_exec($ch);
        $response = simplexml_load_string($rs);
        $trackingid = $response->trackingid;
        $this->mt_collector_forMos($trackingid, $createuser, $sms, $phone, $keywordid, $sc);
        // Close the cURL handle
        curl_close($ch);
    }
    
    /**
     * 
     * 
     */
    public function mt_collector_forMos($trackingid,$createuser,$body,$phonenumber,$keywordid,$shortcode){
        $sql = "insert into mt_for_mo_request (trackingid,createuser,body,phonenumber,keywordid,shortcode) 
            values('$trackingid',$createuser,'$body',$phonenumber,$keywordid,$shortcode)";
         $rs = $this->query($sql);
    }

    /**
     * 
     * 
     */
    public function getStatusofMoresponder($fid, $kid, $phone) {
        $sql = "SELECT * FROM `subscribers` WHERE `folderid`= $fid AND keywordid=$kid AND
            `phonenumber` = $phone  and `optouttime` = '0000-00-00 00:00:00'";
        $rs = $this->query($sql);
        if ($rs->hasRecords()) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    /**
     * 
     * 
     */
    public function subcriberStatus($createuser, $phone) {
        $sql = "SELECT `id`, `keywordid` FROM `subscribers` WHERE `phonenumber`=$phone and `folderid` in(select id 
	from entity where createuser=$createuser and typeid=4) and `optouttime`='0000-00-00 00:00:00'";
        $rs = $this->query($sql);
        if ($rs->hasRecords()) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * 
     * 
     */
    public function getStatusofMoresponderbyPhone($phone) {
        $sql = "SELECT * FROM `subscribers` WHERE `phonenumber` = $phone AND `optouttime` = '0000-00-00 00:00:00'";
        $rs = $this->query($sql);
        if ($rs->hasRecords()) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * 
     * 
     */
    public function optoutFromSystembyStop($phone) {
        $sql = "update subscribers set optouttime=now() WHERE `phonenumber` = $phone AND `optouttime` = '0000-00-00 00:00:00'";
        $rs = $this->query($sql);
    }

     /**
     * Opts in a subscriber
     * 
     * @param string $phone
     * @return boolean
     */
    public function addSubscriber($kid, $phone) {
        if ($kid) {
            $phone = $this->escape($phone);
            $sql = "CALL keyword_add_subscriber($kid, $phone)";
            $rs = $this->query($sql);
            return $rs->success > 0;
        }
        return false;
    }
    
    /**
 *  Mts for mo request saving into messages_outbound
 * 
 */
    public function outbound_message_log($body, $createuser, $reportingkey1, $reportingkey2, $keywordid, $folderid, $shortcode){
        $sql = "Call message_log_outbound('(NULL)','$body', $createuser,$reportingkey1,'$reportingkey2',$keywordid,$folderid,$shortcode)";
        $this->_writeLog(" \nSaving into messages_outbound\nBody: ".$body."\nUsrid: ".$createuser."\nRp2: ".$reportingkey1."\nRp2: ".$reportingkey2.
                   "\nKid: ".$keywordid."\nFid: ".$folderid."\nSC: ".$shortcode);
//        $sql = sprintf("CALL message_log_outbound(%s',$createuser, $reportingkey1,'$reportingkey2',$keywordid,$folderid,$shortcode)",
//                    $this->escape($body)
//                );
	    $rs = $this->query($sql);
	    if ($this->hasError()) {
	        $error = 'Unable to log outbound message';
                      $this->setError($error, $error.' - '.$sql.': '.$this->getError());
	        return false;
	    }
	    // Return the Gateway Message Id
	    return $rs->id;
    }
    /**
     *  MO mts inserts int to messages_outbound_recipients
     *  It is not done by great way but for now it is ok
     *  @param  $msgid,$gateid,$phone,$rpkey1,$rpkey2,$sendtime,$timezone,$createuser
     *  @return last insearted line id
     *  @access public
     */
    public function mtsto_outbound_formorequest($msgid,$gateid,$phone,$rpkey1,$rpkey2,$sendtime,$timezone,$depth,$createuser){
        $sql = "Call messagelogmo_queue(
            '$msgid',
             $gateid,
             $phone,
             $rpkey1,
             '$rpkey2',
             '$sendtime',
             '$timezone',
              $depth,    
             $createuser    
        )";
         $rs = $this->query($sql);
	    if ($this->hasError()) {
	        $error = 'Unable to log outbound message';
                      $this->setError($error, $error.' - '.$sql.': '.$this->getError());
	        return false;
	    }
	    // Return the Gateway Message Id
	    return $rs->id;
    }
    /*END OF NEW MO PROCESSING */
}

