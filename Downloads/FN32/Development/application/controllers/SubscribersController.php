<?php

class SubscribersController extends AuthorizedController {
    
    public function indexAction() {
        
    }
    
    public function dataAction() {
        $this->_helper->layout->setLayout('nonadmin'); 
        
        $userid = $this->user->getId();   
        $subscriberid = $this->request->getParam('id');
        $formObj = new Application_Model_Form(); 
        $subscriberDetails = $formObj->getSubscriberDataById($subscriberid,$userid); //echo "<pre>"; print_r($subscriberDetails); exit;  
        $subscribersDetailsForm = $formObj->getSubscriberDataValueById($subscriberid,$subscriberDetails['0']['webform_url_id']); 
        ////echo "<pre>"; print_r($subscribersDetailsForm); exit;
        $folder = new Application_Model_Folder($this->user);
        $phonenumberOptinoutDate =  $folder->getPhoneNumberOptinoutDate($subscriberDetails['0']['phonenumber'],$subscriberDetails['0']['folderid']);
        //echo "<pre>"; print_r($phonenumberOptinoutDate); exit;
        $this->view->subscriberDetails = $subscriberDetails;
        $this->view->otherSubscribersDetails = $subscribersDetailsForm;
        $this->view->phonenumberOptinoutDate = $phonenumberOptinoutDate;
    }
    
    public function listAction() {
        $subscObj = new Application_Model_Subscriber();  
        $userid = $this->user->getId();   
        $subscriberListArray  = $subscObj->getAllSubscribersByUser($userid);
        $this->view->subscriberListArray = $subscriberListArray;    
        $this->view->totalsubscriber = count($subscriberListArray);  
    }    
    
    /**
     * MOVED TO FOLDERS/INDEX
     * 
     * @access public
     */
    /*public function foldersAction() {
        $user_locs = $this->user->getFolders();
        
        foreach ($user_locs as $id) {
            $folders[] = new Application_Model_Folder($this->user, $id);
        }
        
        $this->view->folders = $folders;
    }*/
       
    
}

