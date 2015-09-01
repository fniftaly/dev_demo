<?php

class ReoccurringController extends AuthorizedController {
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
    
    public function historyAction() { 
        $this->view->campaigns = $this->user->getCampaignHistory();
        //$this->view->currsend = date("Y-m-d H:i:s",(time(date("Y-m-d H:i:s")) - 60*60));
        $this->view->userid = $this->user->getId();
        $this->view->userobj = $this->user;
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
    
    /*                REOCCURRANCE                  */
     public function campaignAction() {
        	$success     = null;
    	$message     = null;
    	$error       = null;
    	$recipients  = array();
    	$sentto      = array();
    	$description = null;
    	$sendtime    = null;
    	$timezone    = null;
    	$status      = null;
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
           $this->view->folders = $user_folders;
	// When the form is submit
	if ($this->request->isPost()) { 
    		
    	}
//          $msg = new Application_Model_Message();
//          $arr = $msg->selectStatusSend();
//          $ids = $msg->verifyifcampaignSend($arr);
//          $msg->updateSendNowcampaign($ids);
//          echo '<pre>'; print_r($arr);
//          echo '<pre>'; print_r();
//           exit;
             $campid = date('YmdHis').$this->user->getId();;
             $userusage = $rpt->dashboard_totalMessagesSentOut($userid,$yearmonth);
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
              $this->view->campid = $campid;
    }
    /**
     * 
     * 
     */
    public function reoccuringweeklyAction(){
         if ($this->request->isPost()) { 
            $userid = $this->user->getId();
            // see if a folder was selected
            $weekday = $this->request->getParam('weekday');
            $folders         = $this->request->getParam('folders');
            $description    = trim($this->request->getParam('name'));
            $this->msg_head = $this->request->getParam('msghead');
            $this->msg_body = $this->request->getParam('msgbody');
            $this->msg_foot = $this->request->getParam('msgfoot');
            $this->user->setTimeZone($this->request->getParam('timezone'));
            $timezone = $this->user->getTimeZone();
             $shortcode = $this->request->getParam('shortcode');
             $datetime = date_create($this->request->getParam('sendtime').":00");
             $sendtime = date_format($datetime, 'Y-m-d H:i:s');
             $campaignid = trim($this->request->getParam('campid'));
             $week = $this->request->getParam('week');
             $status = $this->request->getParam('status');
//             echo "\nWeekday: ".$weekday."\nFold: ".$folders."\nDes: ".$description."\nHead: ".$this->msg_head."\nMsgbody: ".$this->msg_body.
//                     "\nTz: ".$timezone."\nSc: ".$shortcode."\nSt: ".$sendtime; 
//             
             if($folders !="" || $description !="" || $this->msg_head !="" || $this->msg_body !="" || $shortcode !=""){
//                 $folders = explode(',',$sendto);
                  $obj = new stdClass();
                    $obj->weekday = $weekday;
                    $obj->week = $week;
                    $obj->campaingid = $campaignid;
                    $obj->createuser = $userid;
                    $obj->folder = $folders;
                    $obj->name = $description;
                    $obj->msghead = $this->msg_head;
                    $obj->message = $this->msg_body;
                    $obj->msgfoot = $this->msg_foot;
                    $obj->timezone = $timezone;
                    $obj->shortcode = $shortcode;
                    $obj->sendtime = $sendtime;
                    $obj->scheduled = NULL;
                    $obj->status = $status;

                    $msg = new Application_Model_Message();
                   $rs = $msg->weeklycampaigns($obj);
             }
             
//             echo '<pre>'; print_r($totalsubscribers);
             if($rs){echo "TRUE";}else{echo "FALSE";}
             exit;
        }
    }
     /**
     * 
     * 
     */
    public function sendnowAction(){
        if ($this->request->isPost()) { 
            $userid = $this->user->getId();
            // see if a folder was selected
            $sendto         = $this->request->getParam('folders');
            $description    = trim($this->request->getParam('name'));
            $this->msg_head = $this->request->getParam('msghead');
            $this->msg_body = $this->request->getParam('msgbody');
            $this->msg_foot = $this->request->getParam('msgfoot');
            $this->user->setTimeZone($this->request->getParam('timezone'));
            $timezone = $this->user->getTimeZone();
            $shortcode = $this->request->getParam('shortcode');
            $campaignid = trim($this->request->getParam('campid'));
//             $sendtime = date_format($datetime, 'Y-m-d H:i:s');
            // send to weeklymonthlty table
            $cobj = new stdClass();
            $cobj->campaingid = $campaignid;
            $cobj->name = $description;
            $cobj->msghead = $this->msg_head;
            $cobj->message = $this->msg_body;
            $cobj->msgfoot = $this->msg_foot;
            $cobj->sendtime = '00:00:00';
            $cobj->scheduled = date('Y-m-d H:i:s');
            $cobj->createuser = $userid;
            $cobj->folder = $sendto;
            $cobj->timezone = $timezone;
            $cobj->shortcode = $shortcode;
            $cobj->weekday = 0;
            $cobj->week = 0;
//            $cobj->status = 'send';
            $cobj->status = 'sched';
            //----------------
             $sendmessage = new Application_Model_Message();
             if($sendto !="" || $description !="" || $this->msg_head !="" || $this->msg_body !="" || $shortcode !=""){
                 $folders = explode(',',$sendto);
                $totalfolders = count($folders);
                $totalsubscribers = array();
                 for($sbr = 0; $sbr < $totalfolders; $sbr++ ){
//                     $folder = new Application_Model_Folder($this->user, $folders[$sbr]);
                      $subscribers = $sendmessage->subscribersByFolderid($folders[$sbr]);
                     foreach($subscribers as $usr_fid=>$v){
                         $totalsubscribers[$v['phonenumber']] = $v['phonenumber'];
                     } 
                 }
                             $msg  = $this->msg_head ? "{$this->msg_head}:" : '';
    		$msg .= "{$this->msg_body}\n{$this->msg_foot}";
                             $msg  = trim($msg);
	              
                   if (!empty($totalsubscribers)) {
                    $i_default = 0;
                    // Construct the message
                    $sendmessage->selectedfolders = $sendto;//implode(",",$selectedFolders);
                    // Set the folderid this message is being sent to
                    $sendmessage->folderid    = 0;//$folder->getId();
                    // Set the user description
                    $sendmessage->description = $description;
                    // Set a unique identifier for all messages that go out on this campaign
                    $sendmessage->campaignid  = $campaignid;
                    $sendtime ='';
//                     $cobj->scheduled = $sendtime;
                    $source = 404;
//                    $status = $sendmessage->queue($msg, $totalsubscribers, $sendtime, $timezone,$i_default,$source,$userid,$shortcode);
                    /*api tables*/
//                    $status = $sendmessage->api_msgsend($msg, $totalsubscribers, $sendtime, $timezone,$i_default,$source,$userid,$shortcode);
                    $sendmessage->weeklycampaigns($cobj);
                    if ($sendmessage) {
                        $sentto = $totalsubscribers;
                        echo "TRUE";
                     } else {
                        $error = $sendmessage->getError();
                        echo "FALSE";
                    }
                }else { 
                    $error = 'There are no subscribers in the chosen folders';
                }
             }
             exit;
        }
    }
     /**
     * 
     * 
     */
    public function schedulingAction(){
         if ($this->request->isPost()) { 
            $userid = $this->user->getId();
            // see if a folder was selected
            $sendto         = $this->request->getParam('folders');
            $description    = trim($this->request->getParam('name'));
            $this->msg_head = $this->request->getParam('msghead');
            $this->msg_body = ($this->request->getParam('msgbody'));
            $this->msg_foot = $this->request->getParam('msgfoot');
            $this->user->setTimeZone($this->request->getParam('timezone'));
            $timezone = $this->user->getTimeZone();
            $shortcode = $this->request->getParam('shortcode');
            $datetime = date_create($this->request->getParam('sendtime').":00");
            $sendtime = date_format($datetime, 'Y-m-d H:i:s');
            $campaignid = trim($this->request->getParam('campid'));
            $weekday = $this->request->getParam('weekday');
            $week = $this->request->getParam('week');
            $status = $this->request->getParam('status');
            
//             echo "\nFold: ".$sendto."\nDes: ".$description."\nHead: ".$this->msg_head."\nMsgbody: ".$this->msg_body.
//                     "\nTz: ".$timezone."\nSc: ".$shortcode."\nSt: ".$sendtime."\nWd: ".$weekday."\nMd: ".$weekday."\nSt: ".$status; 
//             exit;
             if($sendto !="" || $description !="" || $this->msg_head !="" || $this->msg_body !="" || $shortcode !=""){
//                    $folders = explode(',',$sendto);
                    $obj = new stdClass();
                    $obj->campaingid = $campaignid;
                    $obj->createuser = $userid;
                    $obj->folder = $sendto;
                    $obj->name = $description;
                    $obj->msghead = $this->msg_head;
                    $obj->message = $this->msg_body;
                    $obj->msgfoot = $this->msg_foot;
                    $obj->timezone = $timezone;
                    $obj->shortcode = $shortcode;
                    $obj->sendtime = '00:00:00';
                    $obj->scheduled = $sendtime;
                    $obj->weekday = $weekday;
                    $obj->week = $week;
                    $obj->status = $status;
                    $msg = new Application_Model_Message();
                    $rs = $msg->weeklycampaigns($obj);
             }
             
             if($rs){echo "TRUE";}else{echo "FALSE";}
             
             exit;
        }
    }
      /**
     * 
     * 
     */
    public function campaigncancelAction() {
         if ($this->request->isPost()) {
              $msg = new Application_Model_Message();
              $campid = $this->request->getParam('campid');
              $rs = $msg->deleteScheduledCampaign($campid);
          }
//           if($rs){echo 'TRUE';}
//          else{echo 'FALSE';}
          exit;
    }
    public function historyreoccurAction() {
           $msg = new Application_Model_Message();
           $this->view->campaigns = $msg->campaignHistory($this->user->getId());
        }
     public function historyinfoAction() {
          if ($this->request->isPost()) {
              $msg = new Application_Model_Message();
              $campid = $this->request->getParam('campid');
              $usrid = $this->request->getParam('usrid');
             $info = $msg->getcampaigninfo($campid,$usrid);
          }
          echo json_encode($info);
          exit;
     }
     public function historyupdateAction() {
          if ($this->request->isPost()) {
              $msg = new Application_Model_Message();
              $cobj = new stdClass();
                   $cnpnstatus = $this->request->getParam('status');
                   $cobj->msghead = $this->request->getParam('chead');
                   $cobj->createuser = $this->request->getParam('createuser');
                   $cobj->name = $this->request->getParam('name');
                   $cobj->message = $this->request->getParam('message');
                   $cobj->status = $cnpnstatus;
                   $cobj->campaignid = $this->request->getParam('cid');
               if($cnpnstatus == 'sched'){
                 $date = new DateTime($this->request->getParam('scheduled'));
                   $sendtime = $date->format('Y-m-d H:i:s');
//                   $cobj->scheduled = $this->request->getParam('scheduled').':00';
                   $cobj->scheduled = $sendtime;
               }
               elseif ($cnpnstatus == 'weekly') {
                   $cobj->weekday = $this->request->getParam('weekday');
                   $cobj->sendtime = $this->request->getParam('sendtime');
               }
               elseif ($cnpnstatus == 'monthly') {
                   $cobj->weekday = $this->request->getParam('weekday');
                   $cobj->week = $this->request->getParam('week');
                   $cobj->sendtime = $this->request->getParam('sendtime');
               }
               $rs = $msg->updateScheduledCampaign($cobj);
          }
          if($rs){echo 'TRUE';}
          else{echo 'FALSE';}
          exit;
     }
     
     public function badnumbersAction(){
          if ($this->request->isPost()) {
              $rep = new Application_Model_Report();
              $usrfolders = $this->request->getParam('folders');
                $folders = explode(',',$usrfolders);
                $totalfolders = count($folders);
                $totalsubscribers = array();
                 for($sbr = 0; $sbr < $totalfolders; $sbr++ ){
//                     $folder = new Application_Model_Folder($this->user, $folders[$sbr]);
                     $msg = new Application_Model_Message();
                      $subscribers = $msg->subscribersByFolderid($folders[$sbr]);
                     foreach($subscribers as $usr_fid=>$v){
                         $totalsubscribers[$v['phonenumber']] = $v['phonenumber'];
                     } 
                 }
                 $res = $rep->getBadNumbers($this->user->getId(),$totalsubscribers);
          }
          if($res){
              echo $res;
          }else{
              echo "Nothing is selected";
          }
          exit;
     }
}

