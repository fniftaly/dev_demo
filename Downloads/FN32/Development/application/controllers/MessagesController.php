<?php

class MessagesController extends AuthorizedController {
    // TODO: THIS DEFAULT MESSAGE STUFF SHOULD PROBABLY BE MOVED TO THE MODEL.
    
    /**
     * Default outgoing header
     * 
     * @var string
     * @access private
     */
    private $msg_head;
    
    /**
     * Optin is valid during selcted
     * amount of days. 
     * @var array
     * @access private
     */
//     private $optin_life;
    /**
     * Default outgoing body
     * 
     * @var string
     * @access private
     */
    private $msg_body;
    
    /**
     * Default outgoing footer.
     * 
     * Only editable by admins
     * 
     * @var string
     * @access private
     */
    private $msg_foot;
    
    public function init()
    { 
        $this->_redirector = $this->_helper->getHelper('Redirector');              
    }       
    
    /**
     * Defaults the models message component values.
     * 
     * @access private
     */
    private function defaultValues() {
    	$this->msg_head = "";
        $this->msg_body = "";
   	$this->msg_foot = "reply STOP 2 stop";
    }
    
    public function indexAction() {
        
    }
//    public function historyAction() {
//        $userid = $this->user->getId();
////        $this->view->campaigns = $this->user->getCampaignHistory();
//        $folderObj = new Application_Model_Folder($this->user);
//        $messagessent = $this->user->getCampaignHistory();
//        $messagessent_= array();
//        $names = array();
//        $fIds = array();
//        if(isset($messagessent)){
//            foreach($messagessent as $message){
//                 $fIds = array();
////                $campaignid = $message['campaignid'];
////                $fIds = $folderObj->getFoldersidBycampainid($campaignid);
//                $selectedfolders = $message['slctfolders'];
//                if($selectedfolders != ""){
//                $fIds = explode(',',$selectedfolders);    
//                foreach($fIds as $id){
//                   foreach($folderObj->getFolderName($id) as $fName);
//                    $names[] = $fName;
//                }}
//                $message['folders']=$names;
//                $messagessent_[] = $message;
//          $names = array(); 
//        }
//        } 
//        $this->view->campaigns = $messagessent_;
////        echo '<pre>'; print_r($this->view->campaigns); exit;
//        $this->view->userid = $userid;
//    }
    
    public function historyAction() { 
        $this->view->campaigns = $this->user->getCampaignHistory();
        //$this->view->currsend = date("Y-m-d H:i:s",(time(date("Y-m-d H:i:s")) - 60*60));
        $this->view->userid = $this->user->getId();
        $this->view->userobj = $this->user;
    }
    public function verifykeywordAction(){
        $keywordObj    = new Application_Model_Keyword;
        if ($this->getRequest()->isPost()) {
           $keyword = $this->request->getParam('keyword');
           $shortcode = $this->request->getParam('shortcode');
//           echo count($keywordObj->verifyKeyWord($keyword,$shortcode));
           echo $keywordObj->verifyKeyWord($keyword,$shortcode);
//            echo $keyword.'  '.$shortcode;
        exit;
        }
    }
    public function keywordAction() {
        $nb = new Application_Model_Smsinbound();
//        echo '<pre>'; print_r($nb->getKewordObject('mac', 87365, 19253050723)); exit;
    	// Default values
        $userid = $this->user->getId();
    	$keyword    = new Application_Model_Keyword;
        $formObj = new Application_Model_Form();
    	$canedit    = false;
        $newkeyword = false;
        $error      = false;
    	$this->view->optin_life = array(1, 3, 7,15,30,60,180,360);
    	$message = array(
    		'type' => '',
    		'body' => ''
    	);
    	
    	// See if we are editing a keyword
        $id = $this->request->getParam('id');
        
        if ($id) {
        	// Load the keyword
	        $keyword->setId($id);
	        
	        // Get the folder for this keyword
	        $folderid = $keyword->folderid;
	        
	        // Make sure this user owns this keyword
	        if ($this->user->getId() == $keyword->createuser) {
	        	$canedit = true;
	        } else {
	            // Get a fresh keyword model to load in the view
                $keyword = new Application_Model_Keyword;
	            $message['type'] = 'error';
	            $message['body'] = 'Keyword being edited does not belong to this user. Can not edit.';
	        }
        } else {
        	// No keyword is being loaded, so let them edit a new empty one
        	$canedit    = true;
		$newkeyword = true;
        }
        
        // if we posted sustain keyword form values
        if ($this->request->isPost() && $canedit) {

            $shortcode     = trim($this->request->getParam('shortcode'));
            $inactive     = trim($this->request->getParam('inactive')); 
            $word         = trim($this->request->getParam('keyword')); 
            $msg_head     = trim($this->request->getParam('msg_head'));
            $msg_body     = trim($this->request->getParam('msg_body'));
            
            $optinlife    = trim($this->request->getParam('optin_life'));
            $offerexp     = trim($this->request->getParam('optinlifeexp'));
//            $formurl      = trim($this->request->getParam('formurl'));
            $notifybysms      = trim($this->request->getParam('notifybysms'));
            $notifybyemail      = trim($this->request->getParam('notifybyemail'));
            $alertmessage = trim($this->request->getParam('alertmessage'));
            
            $msg_body_alt = trim($this->request->getParam('msg_body_alt'));
            $folderid     = trim($this->request->getParam('folder'));
            $contestid    = intval($this->request->getParam('contest'));
            $usealt       = trim($this->request->getParam('usealt'));
            $location     = trim($this->request->getParam('location'));

            // to deactivate keyword	   
	    if($inactive == '1')
	    { 		
                    $keyword = new Application_Model_Keyword;
                    if($keyword->delete($id,$word)){		    
                          $message['type'] = 'success';
                          $message['body'] = 'Keyword '.$word.' Deleted!';
                          $this->view->error = $error;
                          $this->view->message = $message;
                         //$this->_redirector->gotoUrl('/messages/keyword');
                    }
	    }
	   else { // add/edit keyword

	    // validation to check for the unique keyword name
		if(empty($id)){
		$varb =$keyword->exists($word,$shortcode);
		if($varb)
		  {
		      $error = 'Keyword '.$word.' is already exists in our system.';
		  }
		}
            // Only admins can edit the footer
            if ($this->user->isAdmin()) {
                $msg_foot = trim($this->request->getParam('msg_foot'));
            } else {
            	if ($id) {
            		$msg_foot = $keyword->getReplyfooter();
            	} else {
            		$msg_foot = 'reply STOP 2 stop';
            	}
            }
            
            // See if they are creating a new folder for this keyword
            if ($folderid == '0') {
                // now make sure they named the new folder
                $new_folder = trim($this->request->getParam('new_folder'));
                
                if ($new_folder) {
                    $folder = new Application_Model_Folder($this->user);
                    
                    // Set up the Folder meta data
                    $meta         = array();
                    $meta['name'] = $new_folder;
                    
                    $success = $folder->addWithMeta($meta);
                    
                    if ($success) {
                        $folderid = $folder->getId();
                    } else {
                        $error = $folder->getError();
                    }
                } else {
                    $error = 'A folder name must be provided to add a new folder.';
                }
            }
           
            // if there was an error don't try and create the keyword
            if ($error) {
                $message['type'] = 'error';
                $message['body'] = $error;
            } else {
                $keyword->setKeyword($word);
                $keyword->setFolderId($folderid);
                $keyword->setContestId($contestid);
                $keyword->setReplyheader($msg_head);
                $keyword->setReplybody($msg_body);
                $keyword->setOptinlife($optinlife);
                $keyword->setOfferexp($offerexp);
//                $keyword->setFormurl($formurl);
                $keyword->setNotifybysms($notifybysms);
                $keyword->setNotifybyemail($notifybyemail);
                $keyword->setAlertmessage($alertmessage);
                $keyword->setReplybodyAlt($msg_body_alt);
                $keyword->setReplyfooter($msg_foot);
                $keyword->setUseAlt($usealt);
                $keyword->setLocation($location);
                $keyword->setShortcode($shortcode);
                // now try and save it
                if ($keyword->save()) {
                    $msgtype = $newkeyword ? 'You can now start to receive opt-in messages on this keyword.' : 'Your changes have been applied.';
                    
                    $message['type'] = 'success';
                    $message['body'] = 'Keyword Saved! '.$msgtype;
                    
                    if ($newkeyword) {
                        // Keyword saved, get a fresh keyword model to display an empty form
                        $keyword = new Application_Model_Keyword();
                    }
                } else {
                    $message['type'] = 'error';
                    $message['body'] = $keyword->getError();
                }
            }
	  }
        }
    	 $user = new Application_Model_User((int) $userid);
    	// Send the message values back to the view
    	$this->view->keyword = $keyword;
    	$this->view->message = $message;
    	$this->view->canedit = $canedit;
    	
    	// User data components
        $this->view->folders  = $this->user->getFolders();
        $this->view->keywords = $this->user->getKeywords();
        $this->view->contests = $this->user->getContests();
        $this->view->formobject= $formObj;
        $this->view->formobjects= $formObj->webformList($userid);
        $this->view->business = $user->businessname;
        $this->view->ainfo = $user->reward;
        $this->view->userid = $userid;
        
//       $mobj = memcache_connect('10.179.252.160', 11211);
//       $opts = $mobj->get('OPTEDOUT_INBOUND');
       
       
    } // end of keywordAction 
   

    /**
     * Set up a new campaign and send a message.
     * 
     * @access public
     */
    public function replyAction() {
	$this->_helper->layout->setLayout('nonadmin'); 
              $id = $this->request->getParam('id');
    	$inboxObjload = new Application_Model_Message($this->user); 
	$messageObjectload=$inboxObjload->loadinboxmessage($id);	
	$this->view->reply_messages = $messageObjectload;	
	//echo "<pre>"; print_r($this->view->reply_messages);
    	// Defaults
    	$success     = null;
    	$message     = null;
    	$error       = null;
    	$recipients  = array();
    	$sentto      = array();
    	$date        = null;
    	$time        = null;
    	$sendtime    = null;
    	$timezone    = null;
    	$status      = null;

    	// set the default message body values
    	$this->defaultValues();

		// When the form is submit
		if ($this->request->isPost()) { 
		    $this->device_address = $this->request->getParam('device_address');
		    $recipients = $this->device_address;		
		    $this->msg_head = $this->request->getParam('msg_head');
		    $this->msg_body = $this->request->getParam('msg_body'); 
		    // Get the sendtime
		    $sendtime = date('Y-m-d H:i:s');
		    // User's timezone
		    $timezone = $this->user->getTimeZone();
		    
		    // Only admins can edit the footer
		    if ($this->user->isAdmin()) {
			    $this->msg_foot = $this->request->getParam('msg_foot');
		    }
		    // Construct the message
		    $msg  = $this->msg_head ? "{$this->msg_head}:" : '';
		    $msg .= "{$this->msg_body}\n{$this->msg_foot}";
		    $msg  = trim($msg);
		    $sendmessage = new Application_Model_Message($this->user);

		    // Verify there was a message set
		    if ($msg) {          
//	                       $createuser = $this->user->getId();
					  if (!empty($recipients)) {					      
						    // message Queue it up for delivery
						//$recipients = 12222222222; 
//						$status = $sendmessage->queue($msg, $recipients, $sendtime, $timezone,$createuser);					  
						$status = $sendmessage->queue($msg, $recipients, $sendtime, $timezone);					  
					      if ($status) {
						  // Put these recipients into our sentto array to avoid sending duplicates
						  $sentto = array_merge($sentto, $recipients);
					      }else{
							$error = $sendmessage->getError();
						   }
					}
					else { 
					        $error = 'There are no any phone number.';
					     }
							
			    if ($status == 1) {
				$message = 'Message successfully sent to ' . $recipients;
				$success = true;
				// now set the message fields back to default
				$this->defaultValues();
			    } else {
				$error = $sendmessage->getError();
			    }

		    } else { 
			$error = 'Message content is required';
		    }
		}       
    	//Send the message values back to the view
    	$this->view->msg_head    = $this->msg_head;
    	$this->view->msg_body    = $this->msg_body;
    	$this->view->msg_foot    = $this->msg_foot;
    	
    	//Status back to the view
    	$this->view->error   = $error;
    	$this->view->success = $success;
    	$this->view->message = $message;

    }

    /**
     * Set up a new campaign and send a message.
     * 
     * @access public
     */
    public function inboxAction() {

	$user_id = $this->user->getId(); 
////        $folderList = $this->user->getFolders();
//        echo '<pre>'; print_r($folderList);
//        exit;
	$inboxObj = new Application_Model_Message($this->user); 
	$messageObject=$inboxObj->getinboxmessage($user_id);
	$this->view->inbox_messages = $messageObject;
        
        
    }

    public function contestAction() {
        // Default values
    	$contest      = new Application_Model_Contest;
    	$canedit      = false;
        $newcontest   = false;
        $error        = false;
    	
    	$message = array(
    		'type' => '',
    		'body' => ''
    	);
    	
    	// Set our contest types... this needs to be databased at some point
    	//$this->view->contesttypes = array(1 => 'Contest', 'Raffle - Automated', 'Raffle - Phone In');
    	$this->view->contesttypes = $contest->getTypes();
		$this->view->winintervals = array(
			(object) array('id' => 1, 'label' => 'Minutes'), 
			(object) array('id' => 60, 'label'=> 'Hours'),
    		(object) array('id' => 1440, 'label'=> 'Days'),
    	);
    	// See if we are editing a keyword
        $id = $this->request->getParam('id');
        
        if ($id) {
        	// Load the keyword
	        $contest->setId($id);
	        
	        // Make sure this user owns this keyword
	        if ($this->user->getId() == $contest->createuser) {
	        	$canedit = true;
	        } else {
	            // Get a fresh contest model to load in the view
                $contest = new Application_Model_Contest;
	            $message['type'] = 'error';
	            $message['body'] = 'Contest being edited does not belong to this user. Can not edit.';
	        }
            
            if ($canedit) {
                $do = trim($this->request->getParam('do'));
                
                // See if a response was deleted
                if ($do == 'delete') {
                    $deleteprize = trim($this->request->getParam('prize'));
                    
                    if ($contest->deletePrize($deleteprize)) {
                        $message['type'] = 'success';
                        $message['body'] = 'Prize Deleted successfully.';
                    } else {
                        $message['type'] = 'error';
                        $message['body'] = $contest->getError();
                    }
                } elseif ($do == 'edit') {
                	
                }
            }
        } else {
        	// No contest is being loaded, so let them edit a new empty one
        	$canedit    = true;
            $newcontest = true;
        }
        
        // if we posted sustain keyword form values
        if ($this->request->isPost() && $canedit) {
        	// Handle date manipulation up front
        	$sdate = trim($this->request->getParam('startdate'));
        	$stime = trim($this->request->getParam('starttime'));
        	
        	if ($sdate) {
	        	if (!$stime) {
    	    		$sdate .= ' 00:00:00 AM';
        		} else {
        			$sdate .= ' ' . $stime;
        		}
        	}
        	
	        $edate = trim($this->request->getParam('enddate'));
        	$etime = trim($this->request->getParam('endtime'));
        	
            if ($edate) {
	        	if (!$etime) {
    	    		$edate .= ' 00:00:00 AM';
        		} else {
        			$edate .= ' ' . $etime;
        		}
        	}
        	
        	// Contest Attributes
            $name      = trim($this->request->getParam('name'));
            $type      = trim($this->request->getParam('type'));
            $interval  = trim($this->request->getParam('interval'));
            //$startdate = trim($this->request->getParam('startdate'));
            $startdate = date('Y-m-d H:i:s', strtotime($sdate));
            //$enddate   = trim($this->request->getParam('enddate'));
            $enddate   = date('Y-m-d H:i:s', strtotime($edate));
            
            // Contest Messages
            $already_played_msg = trim($this->request->getParam('already_played_msg'));
            $before_contest_msg = trim($this->request->getParam('before_contest_msg'));
            $after_contest_msg  = trim($this->request->getParam('after_contest_msg'));
            
            // Administrations
            $admin_phone  = trim($this->request->getParam('adminphone'));
            
            // Winning
            $winnercount        = trim($this->request->getParam('winnercount'));
            $wininterval        = trim($this->request->getParam('wininterval'));
            $winintervaltiming  = trim($this->request->getParam('winintervaltiming'));
            $winneratend        = trim($this->request->getParam('winneratend'));
            
            // Set these into the model
            $contest->name = $name;
            $contest->type = $type;
            $contest->interval = $interval;
            $contest->startdate = $startdate;
            $contest->enddate = $enddate;
            $contest->already_played_msg = $already_played_msg;
            $contest->before_contest_msg = $before_contest_msg;
            $contest->after_contest_msg = $after_contest_msg;
            $contest->setAdminPhone($admin_phone);
            $contest->winnercount = $winnercount;
            $contest->wininterval = $wininterval;
            $contest->winintervaltiming = $winintervaltiming;
            $contest->winneratend = !empty($winneratend);
            
            // Get any Responders that have been set
            $responders = $this->request->getParam('responders');
            
            // now try and save it
            if ($contest->save()) {
                $msgtype = $newcontest ? 'You can now attach this contest to a keyword.' : 'Your changes have been applied.';
                
                $message['type'] = 'success';
                $message['body'] = 'Contest Saved! '.$msgtype;
                
                if ($newcontest) {
                    // contest saved, get a fresh contest model to display an empty form
                    $contest = new Application_Model_Contest();
                }
            } else {
                $message['type'] = 'error';
                $message['body'] = $contest->getError();
            }
            
            $contest->setDateParts();
        }
    	
    	// Send the message values back to the view
    	$this->view->contest = $contest;
    	$this->view->message = $message;
    	$this->view->canedit = $canedit;
    	
    	// User data components
        $this->view->contests = $this->user->getContests();
    }
    
    /**
     * Add a new keyword to this users account.
     * TODO: MOVE THIS INTO THE SAME CONTROLLER OR METHOD AS KEYWORD/EDIT
     * 
     * @access public
     */
    public function addAction() {
    	$error = null;
        
        if ($this->request->isPost()) {
    		
    		// send them back to where they came from with the return values
    		return $this->_forward($action,$page,null,$return);
    	}
    }
    


   public function campaignNEWAction() {
        
    	// Defaults
    	$success     = null;
    	$message     = null;
    	$error       = null;
    	$recipients  = array();
    	$sentto      = array();
    	$description = null;
    	$date        = null;
    	$time        = null;
    	$sendtime    = null;
    	$timezone    = null;
    	$status      = null;
        $user_folders = 0;
    	// set the default message body values
    	$this->defaultValues();
        $userid = $this->user->getId();
        $formObj = new Application_Model_Form();
        $usrObj = new Application_Model_User($userid);
    	// Logged in users folder ID's      
        
//        if($this->user->firstname == 'Happy Joe' || $this->user->firstname == 'Happy Joes'){//getFolders_hjoe
//          $user_folders = $this->user->getFolders_hjoe();   
//        }else
        {
          $user_folders = $this->user->getFolders();
        }
        $this->view->folders = $user_folders;
		// When the form is submit
		if ($this->request->isPost()) { 
    		// see if a folder was selected
    		$sendto         = $this->request->getParam('folders');
    		$description    = trim($this->request->getParam('description'));
    		$this->msg_head = $this->request->getParam('msg_head');
    		$this->msg_body = $this->request->getParam('msg_body');
    		// Get the sendtime
    		$scheduled = false;
    		if ($this->request->getParam('sendtime') == 'later') {
    			$scheduled = true;
	    		$sendtime  = $this->request->getParam('date') . ' ' . $this->request->getParam('time');
    		}
    		
    		// User's timezone
                        $this->user->setTimeZone($this->request->getParam('t_zone'));
			$timezone = $this->user->getTimeZone();
                      
    		// Only admins can edit the footer
    		if ($this->user->isAdmin()) {
    			$this->msg_foot = $this->request->getParam('msg_foot');
    		}
    		
    		$msg  = $this->msg_head ? "{$this->msg_head}:" : '';
    		$msg .= "{$this->msg_body}\n{$this->msg_foot}";
            $msg  = trim($msg);
	    $sendmessage = new Application_Model_Message($this->user);

            // Verify the user provided a description for this message
            if ($description) {
                // Verify there was a message set
                if ($msg) {
                    // make sure we got the sendto array from POST
                    if (is_array($sendto) && !empty($sendto)) {
                        // Create a campaign id that each message that goes out for this campaign can be grouped by
                        $campaignid = md5($this->user->username.time());
                        
                        // Get the subscribers from each selected folder
     $recipients = array();
     $selectedFolders = array();
     $phone_folderid = array();
     foreach ($sendto as $folder) {
         if (array_key_exists($folder, $user_folders)) {
        // Get the folder model
        $folder = new Application_Model_Folder($this->user, $folder);
        // Verify it
        if ($folder->isValid()) {
            // Get subscribers to this folder
            $subscribers = $folder->getSubscribers();
            if(!empty($subscribers)) {
                $selectedFolders[$folder->getId()]=$folder->getId();
                foreach ($subscribers as $subscriber) {
                        $recipients[$subscriber['phonenumber']] = $subscriber['phonenumber'];
                        $phone_folderid[$subscriber['phonenumber']] = $folder->getId();
                }
            }
            else { 
                    $error = 'There are no subscribers in the chosen folder(s).';
            }
        } else {
            $error = $folder->getError();
        }
     }else { ;
               $error = 'Invalid folder selected ['.$folder.'].';
           }
     } // end of foreach
//     echo '<pre>'; print_r($recipients);
//     echo '<pre>'; print_r($selectedFolders);
//     exit;
         if (!empty($recipients)) {
                    $i_default = 0;
                    // Construct the message
                    $sendmessage->selectedfolders = implode(",",$selectedFolders);
                    // Set the folderid this message is being sent to
                    $sendmessage->folderid    = $folder->getId();
                    // Set the user description
                    $sendmessage->description = $description;
                    // Set a unique identifier for all messages that go out on this campaign
                    $sendmessage->campaignid  = $campaignid;
                    // Queue it up for delivery
                    $status = $sendmessage->queue($msg, $recipients, $sendtime, $timezone);
                    if ($status) {
                        $sentto = $recipients;
                     } else {
                        $error = $sendmessage->getError();
                    }
                }else { 
                    $error = 'There are no subscribers in the chosen folder(s).';
                }
            // If the sentto list never got any recipients, there was no one in the selected folders
            /* if (empty($sentto)) { //echo 2222;
                $error = 'There are no subscribers in the chosen folder(s).';
            }
            */
                 if ($status == 1) {
                        	$insert = $scheduled ? 'scheduled to send' : 'sent';
                            $message = 'Message successfully ' . $insert . ' to ' . count($sentto) . ' subscribers!';
                            $success = true;
                            // now set the message fields back to default
                            $this->defaultValues();
                        } else {
                            $error = $sendmessage->getError();
                        }
                    } else { 
                        $error = 'You must select at least one folder to send the message to.';
                    }
                } else { 
                    $error = 'Message content is required';
                }
            } else { 
                $error = 'Message description is required';
            }
    	}
    	
    	// Send the message values back to the view
    	$this->view->description = $description;
    	$this->view->sendtime    = $sendtime;
    	$this->view->timezone    = $timezone;
    	$this->view->msg_head    = $this->msg_head;
    	$this->view->msg_body    = $this->msg_body;
    	$this->view->msg_foot    = $this->msg_foot;
    	
    	// Status back to the view
    	$this->view->error   = $error;
    	$this->view->success = $success;
    	$this->view->message = $message;
        $this->view->formobject= $formObj;
        $this->view->formobjects= $formObj->webformList($userid);
        $this->view->messagelimit = $usrObj->messagelimit;
    }

    /**
     * Set up a new campaign and send a message.
     * 
     * @access public
//     */
    public function campaignAction() {
        
    	// Defaults
    	$success     = null;
    	$message     = null;
    	$error       = null;
    	$recipients  = array();
    	$sentto      = array();
    	$description = null;
    	$date        = null;
    	$time        = null;
    	$sendtime    = null;
    	$timezone    = null;
    	$status      = null;
              $user_folders = 0;
              $shortcode = 0;
    	// set the default message body values
    	$this->defaultValues();
        $userid = $this->user->getId();
        $formObj = new Application_Model_Form();
        $user = new Application_Model_User($userid);
        $rpt = new Application_Model_Report();
        $yearmonth = date('Y-m');
    	// Logged in users folder ID's      
          $user_folders = $this->user->getFolders();
          $foldersid = array_keys($user_folders);
          $totalfolders = count($foldersid);
          $orgeachfolders = array();
          $setarray = array();
//          for($i = 0; $i < $totalfolders; $i++){
//              $orgnumbers = array();
//              $folder = new Application_Model_Folder($this->user, $foldersid[$i]);
//              $subscribers = $folder->getSubscribers();
//              foreach ($subscribers as $subscriber) {
//                        if(!in_array($subscriber['phonenumber'], $setarray)){
//                          $orgnumbers[] =$subscriber['phonenumber'];
//                        }
//                        $setarray[$subscriber['phonenumber']] = $subscriber['phonenumber'];
//                } 
//                $orgeachfolders[$foldersid[$i]] = $orgnumbers;
//          }
        $this->view->cleandata = $orgeachfolders;  
        $this->view->folders = $user_folders;
        
		// When the form is submit
		if ($this->request->isPost()) { 
    		// see if a folder was selected
    		$sendto         = $this->request->getParam('folders');
    		$description    = trim($this->request->getParam('description'));
    		$this->msg_head = $this->request->getParam('msg_head');
    		$this->msg_body = $this->request->getParam('msg_body');
    		$shortcode = $this->request->getParam('scselect');
//    		echo '<pre>'; print_r($sendto); exit;
    		// Get the sendtime
    		$scheduled = false;
    		if ($this->request->getParam('sendtime') == 'later') {
    			$scheduled = true;
	    		$sendtime  = $this->request->getParam('date') . ' ' . $this->request->getParam('time');
    		}
    		
    		// User's timezone
                        $this->user->setTimeZone($this->request->getParam('t_zone'));
			$timezone = $this->user->getTimeZone();
                      
    		// Only admins can edit the footer
    		if ($this->user->isAdmin()) {
    			$this->msg_foot = $this->request->getParam('msg_foot');
    		}
    		
    		$msg  = $this->msg_head ? "{$this->msg_head}:" : '';
    		$msg .= "{$this->msg_body}\n{$this->msg_foot}";
                     $msg  = trim($msg);
	       $sendmessage = new Application_Model_Message($this->user);

            // Verify the user provided a description for this message
            if ($description) {
                // Verify there was a message set
                if ($msg) {
                    // make sure we got the sendto array from POST
                    if (is_array($sendto) && !empty($sendto)) {
                        // Create a campaign id that each message that goes out for this campaign can be grouped by
                        $campaignid = md5($this->user->username.time());
                        
                        // Get the subscribers from each selected folder
     $recipients = array();
    foreach ($sendto as $folder) {  //0001
        // Clear out the recipients array for each folder
        $recipients = array();
        // easy check to make sure this folder is owned by this user
    if (array_key_exists($folder, $user_folders)) {
        // Get the folder model
        $folder = new Application_Model_Folder($this->user, $folder);

                    // Verify it
        if ($folder->isValid()) {
            // Get subscribers to this folder
            $subscribers = $folder->getSubscribers();
//            echo '<pre>'; print_r($subscribers);
//            echo'<br>'."DELEMMA ";
            if(!empty($subscribers)) {
                foreach ($subscribers as $subscriber) {
                    // Don't add this user if they have already received this message from being in another selected folder
                    if (!in_array($subscriber['phonenumber'], $sentto)) {
                        // key off the phone number to avoid duplicates from this folder, which should
                        // never happen, but better safe than sorry
                        $recipients[$subscriber['phonenumber']] = $subscriber['phonenumber'];
                    }
                }
                /**
                * The message needs to be sent to each folder individually so that the folderid
                * can be logged in the outbound table. That allows us to see what folder to opt
                * a user out of when they reply with an optout.
                */
                if (!empty($recipients)) {
                    // Construct the message
                   $i_default = 0;
                    // Set the folderid this message is being sent to
                    $sendmessage->folderid    = $folder->getId();
                    // Set the user description
                    $sendmessage->description = $description;
                    // Set a unique identifier for all messages that go out on this campaign
                    $sendmessage->campaignid  = $campaignid;
                    // Queue it up for delivery
//                    ($body, $recipients, $sendtime = null, $timezone = null, $confirmid = 0, $depth = 0, $createuser = 0, $shortcode=87365)
                    //queue($msg, $recipients, $sendtime, $timezone,$i_default,$source,$userid,$shortcode); dev
                    $source = 404;
                    $status = $sendmessage->queue($msg, $recipients, $sendtime, $timezone,$i_default,$source,$userid,$shortcode);
//                    $status = $sendmessage->queue($msg, $recipients, $sendtime, $timezone,$i_default,$userid);
                    if ($status) {
                        // Put these recipients into our sentto array to avoid sending duplicates
                        $sentto = array_merge($sentto, $recipients);
                    } else {
                        $error = $sendmessage->getError();
                    }
                }
            }
            else { 
                    $error = 'There are no subscribers in the chosen folder(s).';
            }
        } else {
            $error = $folder->getError();
        }
                } else { ;
                    $error = 'Invalid folder selected ['.$folder.'].';
                }
      } // end of foreach  0001
            // If the sentto list never got any recipients, there was no one in the selected folders
            /* if (empty($sentto)) { //echo 2222;
                $error = 'There are no subscribers in the chosen folder(s).';
            }
            */
                        if ($status == 1) {
                        	$insert = $scheduled ? 'scheduled to send' : 'sent';
                            $message = 'Message successfully ' . $insert . ' to ' . count($sentto) . ' subscribers!';
                            $success = true;
                            // now set the message fields back to default
                            $this->defaultValues();
                        } else {
                            $error = $sendmessage->getError();
                        }
                    } else { 
                        $error = 'You must select at least one folder to send the message to.';
                    }
                } else { 
                    $error = 'Message content is required';
                }
            } else { 
                $error = 'Message description is required';
            }
    	}
              $userusage = $rpt->dashboard_totalMessagesSentOut($userid,$yearmonth);
//              echo 'USAGE: '.$yearmonth;
              
    	// Send the message values back to the view
    	$this->view->description = $description;
    	$this->view->sendtime    = $sendtime;
    	$this->view->timezone    = $timezone;
    	$this->view->msg_head    = $this->msg_head;
    	$this->view->msg_body    = $this->msg_body;
    	$this->view->msg_foot    = $this->msg_foot;
    	
    	// Status back to the view
    	$this->view->error   = $error;
    	$this->view->success = $success;
    	$this->view->message = $message;
              $this->view->formobject= $formObj;
              $this->view->formobjects= $formObj->webformList($userid);
              $this->view->userid = $userid;
              $this->view->userObj = $user;
              $this->view->msglimit = $user->messagelimit;
              $this->view->userusage = $userusage;
    }// end of campaignAction
    
    
    public function editAction() {
    	// Defaults
    	$error      = null;
        $message    = null;
        $id = $this->request->getParam('id');
        $flag = '';
        $campaignObj = new Application_Model_Message($this->user); 
        $messageObject=$campaignObj->getcampaignhistory($id);
        
        //echo '<pre>';print_r($messageObject);echo '</pre>'; //exit; 
        if(!empty($messageObject) AND $messageObject[0]['senttime']=='')
        { 
            if ($this->request->isPost()) {
                $dateorig=$this->request->getParam('startdate');
                $new_dateorig = date("Y-m-d", strtotime($dateorig));
                $timeorig=$this->request->getParam('starttime');
                $new_timeorig = date("H:i:s", strtotime($timeorig));

                $newsend = $new_dateorig.' '.$new_timeorig; 
                $oldsend = $messageObject[0]['senddate'].' '.$messageObject[0]['sendtime'];
                //$oldsend = date("Y-m-d H:i:s",(time($oldsend) - 60*60));
                $currsend = date("Y-m-d H:i:s",(time(date("Y-m-d H:i:s")) - 15*60));
              


                if( ($currsend < $oldsend) AND ($newsend >$currsend) ){
                    $campaignDetail['senddate']=$newsend;                              
                    $campaignDetail['id'] = $this->request->getParam('id'); 
                    $campaignDetail['description'] = trim($this->request->getParam('description'));
                    $campaignDetail['body'] = trim($this->request->getParam('body'));
                    $dateorig=$this->request->getParam('startdate');

                   // echo '<pre>'; print_r($campaignDetail); exit;

                    if ($campaignObj->updateCampaignDetail($campaignDetail)) {
                        $message = 'Compaign history details updated.';
			$flag = 1;
                    }else{
                            $error =  'Folder error: '.$campaignObj->getError(); 
                                }
                    $campaignObj = new Application_Model_Message($this->user, $id);                    
                }else{
                        $error = "You have to set your campaign time 1 hour before to current time.";
                }                           
               //$this->_redirector->gotoUrl('/message/edit/id/'.$id);              
           }   
        //$this->view->foldername = $comObj->name;
	  if($flag == 1) {
	    $updatedMessageObject=$campaignObj->getcampaignhistory($id);

	    $this->view->body= $updatedMessageObject[0]['body'];
	  
	  
	    $this->view->description= $updatedMessageObject[0]['description'];
	    $this->view->id = $id;
	    
	    
	    $this->view->senddate= $updatedMessageObject[0]['senddate'];
	    $this->view->sendtime= $updatedMessageObject[0]['sendtime'];   
	    
	    $this->view->error = $error;
	    $this->view->message = $message;
	  }
	  else {
	    $this->view->body= $messageObject[0]['body'];
	    
	    
	    $this->view->description= $messageObject[0]['description'];
	    $this->view->id = $id;
	    
	    
	    $this->view->senddate= $messageObject[0]['senddate'];
	    $this->view->sendtime= $messageObject[0]['sendtime'];   
	    
	    $this->view->error = $error;
	    $this->view->message = $message;
	  }
        }else
            {
            //echo 'hi';
             $this->getResponse()->setRedirect('/messages/history', 301);
            }        
    } 
    
  public function deleteAction() { 
    	// Defaults
    	$error      = null;
        $message    = null;                                 
        $id = $this->request->getParam('id');
        
        $campaignObj = new Application_Model_Message($this->user); 
        $messageObject=$campaignObj->getcampaignhistory($id);
/*            [senttime] => 
            [senddate] => 2012-03-30
            [sendtime] => 21:42:00*/
        if(!empty($messageObject) AND $messageObject[0]['senttime']=='')
        {
   
                $oldsend = $messageObject[0]['senddate'].' '.$messageObject[0]['sendtime'];
                //$oldsend = date("Y-m-d H:i:s",(time($oldsend) - 60*60));
                $currsend = date("Y-m-d H:i:s",(time(date("Y-m-d H:i:s")) - 15*60));

                if( ($currsend < $oldsend) ){  
                    $campaignDetail['id'] = $this->request->getParam('id'); 

                   // echo '<pre>'; print_r($campaignDetail); exit;

                    if ($campaignObj->deleteCampaign($campaignDetail['id'])) { 
                        $message = 'Campaign deleted.';
                    }else{
                            $error =  'Folder error: '.$campaignObj->getError(); 
                                }
                    $campaignObj = new Application_Model_Message($this->user, $id);                    
                }else{
                        $error = "You have to set your campaign time 1 hour before to current time.";
                }                           
            
	       $this->getResponse()->setRedirect('/messages/history');
;

        }       
    } 
      
    public function contestprizeAction() {
    	// No rendering of views or layouts
    	$this->_helper->viewRenderer->setNoRender(true);
		$this->_helper->layout()->disableLayout();
		
		// Set up the reply
		$reply = array('success' => 0, 'message' => '');
		
		// Handle the request
    	if ($this->_request->isPost()) {
    		$contestid = $this->_request->getPost('contestid');
    		if ($contestid) {
    			// Only build a contest model if we have a contest id
    			$contest = new Application_Model_Contest($contestid);
    			$prizeid = $this->_request->getPost('prizeid');
    			
    			// Now we can work
    			$name = $this->_request->getPost('name');
    			$action = $this->_request->getPost('action');
	    		$response = $this->_request->getPost('response');
		    	$odds = $this->_request->getPost('odds');
		    	$winner = $this->_request->getPost('winner');
		    	$expiredays = $this->_request->getPost('expire_days');
		    	$expiredate = $this->_request->getPost('expire_date');
	    		
	    		if ($action == 'add') {
	    			if ($contest->addPrize($name, $response, $odds, $winner, null, $expiredays, $expiredate)) {
	    				// Response with success
	    				$reply['success'] = 1;
	    			} else {
	    				// Response with success = 0, message = $contest->error
	    				$reply['message'] = $contest->error;
	    			}
	    		} else if ($action == 'edit') {
	    			if ($prizeid) {
		    			if ($contest->editPrize($prizeid, $name, $response, $odds, $winner, null, $expiredays, $expiredate)) {
		    				// Response with success
		    				$reply['success'] = 1;
		    			} else {
		    				// Response with success = 0, message = $contest->error
		    				$reply['message'] = $contest->error;
		    			}
	    			} else {
	    				$reply['message'] = 'Missing prize id for editing';
	    			}
	    		} else if ($action == 'delete') {
	    			// 
	    		} else {
	    			$reply['message'] = 'Incorrect request type';
	    		}
    		} else {
    			$reply['message'] = 'No contest selected';
    		}
    	} else {
    		$reply['message'] = 'Improper or malformed request';
    	}
    	
    	echo json_encode($reply);
    }
    
    public function bdayclubAction(){ 
        
        $messageObj = new Application_Model_Message($this->user);
        $userid = $this->user->getId();
        $user_folders = $this->user->getFolders(); 
        $folder_messageset = $this->user->getFoldersBdayClub($userid); 
        $folderotshow = array_diff_key($user_folders, $folder_messageset);
        $bdayMessageList = $messageObj->getBdayMessageList($userid);
        
        $this->view->msg_body = "Many Many Returns of the Day.. Happy B'day";
        $this->view->msg_foot = "reply STOP2quit";        
        $this->view->folders = $folderotshow;
        $this->view->bdaymsglist = $bdayMessageList;
        /*birthday date fixing*/
//        $bds = $messageObj->selectbd();
        $userid = $this->user->getId();
        $formObj = new Application_Model_Form();
        
        if ($this->_request->isPost()) { 
            $postdataValue = $this->_request->getParams();
          
//            echo '<pre>'; print_r($postdataValue);
//            exit;
            $tzone = $postdataValue['t_zone'];
            $uniquekey = md5($userid.time()); 
            $sendtime = implode(":",$postdataValue['sendtime']);
            if(!empty($postdataValue['folders']) AND is_array($postdataValue['folders'])){ 
                foreach($postdataValue['folders'] as $folderid){
                    $subscribersListArray = $messageObj->getSubscribersListByFolderId($folderid); //echo "<pre>"; print_r($subscribersListArray); exit;
                  
                    $bdayMessageSet = $messageObj->setBirthDayMessage($uniquekey,addslashes($userid),addslashes($folderid),addslashes($postdataValue['messagedesc']),addslashes($postdataValue['messagebody']),addslashes($postdataValue['msg_foot']),addslashes($postdataValue['sendbefore']),addslashes($sendtime), $tzone);
//                    if($bdayMessageSet){
//                        foreach($subscribersListArray as $subscribersList){
//                            if($subscribersList['birthday']!=null){
//                                $bdaydate = Date("Y")."-".$subscribersList['birthday'];
//                                $bdaymsgtobesenton = date("Y-m-d",strtotime(date("Y-m-d", strtotime($bdaydate)) . " -".$postdataValue['sendbefore']." day"));
//                                $bdaymsgtobesenton = $bdaymsgtobesenton." ".$sendtime;
//                                $messageObj->updateBdayMsgSendDate($subscribersList['id'],$bdaymsgtobesenton);
//                            }
//                        }  
//                    }
                }    
               $this->_redirector->gotoUrl('/messages/bdayclub/');  
            }else{
                $this->view->error = "Please select atleast one folder.";
            }       
        }
        $this->view->formobject= $formObj;
        $this->view->formobjects= $formObj->webformList($userid);
        $this->view->count = count($bds);
    }
    
    public function bdayclubeditAction(){         
        $uniquekey = $this->request->getParam('id');
        $messageObj = new Application_Model_Message($this->user);
        $userid = $this->user->getId();
        $user_folders = $this->user->getFolders(); 
        $folder_messageset = $this->user->getFoldersBdayClub($userid); 
        $folder_messageset_edit = $this->user->getFoldersBdayClubBuUniqueKey($uniquekey);
        $folderotshow = array_diff_key($user_folders, $folder_messageset);        
        
        $userid = $this->user->getId();
        $formObj = new Application_Model_Form();
        
        $bdaymsgedit = $messageObj->getBirthDayMessage($uniquekey); //echo "<pre>"; print_r($bdaymsgedit); exit;

        $this->view->folders = $folderotshow;
        $this->view->folder_messageset_edit = $folder_messageset_edit;
        
        $this->view->messagedesc = $bdaymsgedit['0']['messagedesc'];
        $this->view->messagebody = $bdaymsgedit['0']['messagebody'];
        $this->view->msg_foot = $bdaymsgedit['0']['messagefooter'];
        $this->view->sendbefore = $bdaymsgedit['0']['sendbefore'];
        $sendtime = $bdaymsgedit['0']['sendtime'];
        $sendtime = explode(":",$sendtime);
        $this->view->sendhour = $sendtime['0'];
        $this->view->sendmin = $sendtime['1'];
        $this->view->sendsec = $sendtime['2'];
        
        if ($this->_request->isPost()) { 
            $postdataValue = $this->_request->getParams(); //echo "<pre>"; print_r($postdataValue); exit;
            $sendtime = implode(":",$postdataValue['sendtime']);            
            
            $bdayMessageUpdate = $messageObj->updateBirthDayMessage($uniquekey,addslashes($postdataValue['messagedesc']),addslashes($postdataValue['messagebody']),addslashes($postdataValue['msg_foot']),addslashes($postdataValue['sendbefore']),addslashes($sendtime));                        
            
            if(!empty($postdataValue['foldertoremove'])){
                foreach($postdataValue['foldertoremove'] as $folderid){
                    $messageObj->deleteBirthDayMessageByFolderId($folderid);
                }
            }

            if(!empty($postdataValue['folders']) AND is_array($postdataValue['folders'])){ 
                foreach($postdataValue['folders'] as $folderid){
                    $bdayMessageSet = $messageObj->setBirthDayMessage($uniquekey,addslashes($userid),addslashes($folderid),addslashes($postdataValue['messagedesc']),addslashes($postdataValue['messagebody']),addslashes($postdataValue['msg_foot']),addslashes($postdataValue['sendbefore']),addslashes($sendtime));
                }                        
            }  
            
            $folder_messageset_edit = $this->user->getFoldersBdayClubBuUniqueKey($uniquekey); 
            foreach($folder_messageset_edit as $folderid => $foldrexist){
                $subscribersListArray = $messageObj->getSubscribersListByFolderId($folderid);

                    foreach($subscribersListArray as $subscribersList){
                        if($subscribersList['birthday']!=null){
                            $bdaydate = Date("Y")."-".$subscribersList['birthday'];
                            $bdaymsgtobesenton = date("Y-m-d",strtotime(date("Y-m-d", strtotime($bdaydate)) . " -".$postdataValue['sendbefore']." day"));
                            $bdaymsgtobesenton = $bdaymsgtobesenton." ".$sendtime;
                            $messageObj->updateBdayMsgSendDate($subscribersList['id'],$bdaymsgtobesenton);
                        }
                    }                 
            }
            
            $this->_redirector->gotoUrl('/messages/bdayclub/');     
        }
        $this->view->formobject= $formObj;
        $this->view->formobjects= $formObj->webformList($userid);
    }      


  public function deletebdaymsgAction(){   
    $this->_helper->viewRenderer->setNoRender(true);
    $uniquekeyArray = $this->request->getParam('deleteid');
    $messageObj = new Application_Model_Message($this->user);   
    foreach($uniquekeyArray as $uniquekValue)
    {
      $messageObj->deleteBirthDayMessageByUniqueId($uniquekValue);
    }
     $this->_redirector->gotoUrl('/messages/bdayclub/'); 
  }

  public function createshortenedurlAction(){
          $frmObj = new Application_Model_Form();
      if ($this->getRequest()->isPost()) {
          
           $url = $this->request->getParam('url');
           $shrUrl = $frmObj->makeShortUrl($url);
           echo $shrUrl;
        }
        exit;
  }
  
     public function sendsmsnewuserAction(){
      $campaignObj = new Application_Model_Message($this->user);
      $smsOut = new Application_Model_Smsoutbound($campaignObj);
      if ($this->getRequest()->isPost()) {
           $phone = $this->request->getParam('phone');
           $smsBody = $this->request->getParam('smsbody');
           if($phone !="" && $smsBody){
            $smsOut->alertMessage($phone, $smsBody);
            echo "true";
           }
        }
        exit;
  }
 
   /**
     *    action for sending international messages
     *    this done just for one account 
     */
  public function intrsmsAction(){
      $msgObj = new Application_Model_Message($this->user);
      $ID = $this->user->getId();
     if ($this->getRequest()->isPost()) {
         
         $sid = "ACbf5f17a1be48a06f94eb500d22c0b8f8"; // Your Account SID from www.twilio.com/user/account
         $token = "d7f61830172db0b42d3c6531e9707238";//"f@r@d2013"; // Your Auth Token from www.twilio.com/user/account
          require '/var/www/zendfw/zend/library/Zend/Service/Twilio.php';
         $client = new Services_Twilio($sid, $token);
           // processing folders ids send by ajax
           $jarr = json_decode($this->request->getParam('data'), true);
           // processing message body
           $msg = $this->request->getParam('msg');
           // fetching phonenumbers 
           $phones = $msgObj->getphonesForIntrSms(implode(',', $jarr));
           $totalPhonenumbers = count($phones);
           
           for($pnr= 0; $pnr < $totalPhonenumbers; $pnr++){
              $phn = $phones[$pnr];
              $intrpn = '+'.$phn;
              $message = $client->account->sms_messages->create('+17786530459', $intrpn,$msg);
              $msgObj->internationalSms($ID,$phn, $message->sid, $msg);
           }
//           echo print_r($phones);
           echo 'SEND SUCCESSFULLY';
       }
      exit();
  }
  
    /**
    * 
    * 
    */
    public function hollydayAction(){
        $folderObj = new Application_Model_Folder($this->user);
         $this->view->flist = $folderObj->getFoldersByName($this->user->getId());
         $this->view->msglist = $folderObj->selecthollyCampaign($this->user->getId());

    }
   /**
    * 
    * 
    */
    public function hollyentryAction(){
        if ($this->getRequest()->isPost()) {
            $userid = $this->user->getId();
            $folderObj = new Application_Model_Folder($this->user);
            $h_cid = (int)(trim($this->request->getParam("h_cid")))?:0;
            $h_msg = trim($this->request->getParam("h_msg"));
            $h_sendtime = trim($this->request->getParam("h_sendtime"));
            $h_msgid = (int)trim($this->request->getParam('h_msgid'));
            $h_folder = trim($this->request->getParam('h_folder'));
            
            if($h_cid !=0 && $folderObj->canselinghollyCampaign($h_cid)){
                echo 'Campaign has been canceled'; exit;
            }
            
            if($h_msgid ==0 && $folderObj->sethollyCampaign($h_msg,$h_sendtime,$h_folder,$userid)==1)
            {
                echo 'Data has been saved';
            }
            elseif($h_msgid !=0 && $folderObj->updatehollyCampaign($h_msg, $h_sendtime, $h_folder,$h_msgid,$userid)==1){
                echo 'Changes has been saved';
            }else{
                 echo 'No data action';
            }
//            echo  $h_msg."\n".$h_sendtime."\n".$h_folder."\n".$h_msgid;
        }
        exit;
    }
    /**
     * 
     * 
     */
    public function toonefolderAction(){
        if ($this->getRequest()->isPost()) {
            $msg = new Application_Model_Message($this->user);
            $folderid = trim($this->request->getParam("folderid"));
            $userid = $this->user->getId();
            $phones = $msg->selectAllsubcribers($userid);
//            echo '<pre>'; print_r($phones); exit;
            $msg->cleanUpTotalfolder($folderid);
            if(count($phones) != 0){
                $msg->intoOnefolderAllsubcribers($phones, $folderid);
                echo "Folder is populated";
            }
        }
        exit;
    }// end of toonefolderAction
    
}

