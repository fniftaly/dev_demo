<?php

class FormController extends AuthorizedController {

    /**
     * Redirector - defined for code completion
     *
     * @var Zend_Controller_Action_Helper_Redirector
     */
    protected $_redirector = null;

    public function init() {
        $this->_redirector = $this->_helper->getHelper('Redirector');
    }

    public function indexAction() {
        /* $tinyurl = new Zend_Service_ShortUrl_TinyUrlCom();
          echo $short = $tinyurl->shorten('http://www.textm.us/form/link/url/testurltext786'); echo "<br>";

          $tinyurl1 = new Zend_Service_ShortUrl_JdemCz();
          echo $short1 = $tinyurl1->shorten('http://www.textm.us/form/link/url/testurltext786'); echo "<br>";

          $tinyurl2 = new Zend_Service_ShortUrl_MetamarkNet();
          echo $short2 = $tinyurl2->shorten('http://www.textm.us/form/link/url/testurltext786'); echo "<br>";

          $tinyurl3 = new Zend_Service_ShortUrl_IsGd();
          echo $short3 = $tinyurl3->shorten('http://www.textm.us/form/link/url/testurltext786'); echo "<br>";

          exit; */
    }

    public function createAction() {
        $error = null;
        $message = null;
        $userid = $this->user->getId();
        $username = $this->user->username();
        $formObj = new Application_Model_Form();
        $reportObj = new Application_Model_Report();
        $webformListSubAccount = array();
        if ($reportObj->checkAdminUser($userid)) {
            $subUsersArray = $reportObj->findChildEntityList($userid, 5, "");  //echo "<pre>"; print_r($subUsersArray); exit;            
            foreach ($subUsersArray as $subuser) {
                $webformlistmerge = $formObj->webformList($subuser['childuserid']);
                if (!empty($webformlistmerge) AND is_array($webformlistmerge))
                    $webformListSubAccount = array_merge($webformListSubAccount, $webformlistmerge);
            }        //echo "<pre>"; print_r($webformListSubAccount); exit;     
        }

        if ($this->request->isPost()) { //echo "<pre>"; print_r($this->_request->getParams()); exit;
            $postdataValue = $this->_request->getParams(); //echo '<pre>'; print_r($postdataValue);exit;
            $this->view->postedValues = $postdataValue;
            if ($this->request->getParam('formurl') == "") {
                $message['type'] = "error";
                $message['body'] = "Atleast Unique URL is required";
            } else {
                $createformfor = "";
                if ($this->request->getParam('subacc') == "on") {
                    $folderidArray = "subacc";
                } else {
                    $folderidArray = $this->request->getParam('folderid');
                }
                // echo '<pre>'; print_r($folderidArray); exit;

                if (!empty($folderidArray)) {

                    $webformUrlArray = array();
                    $weburl = trim($this->request->getParam('formurl'));
                    $weburlfilter = preg_replace('/[^a-z0-9]/', '-', $weburl);

                    if ($folderidArray == "subacc") {
                        foreach ($subUsersArray as $subuser) {
                            $userid = $subuser['childuserid'];
                            $folderids = $this->user->getFolderElseCreate("weboptin", $userid);
                            $webformUrlArray = Array(
                                "entityid" => $userid,
                                "urlname" => addslashes($weburlfilter),
                                "thankumsg" => addslashes($this->request->getParam('thanku_message')),
                                "descmsg" => addslashes($this->request->getParam('formdesc')),
                                "folderid" => $folderids,
                                "dobstatus" => addslashes($this->request->getParam('dobstatus')),
                                "alertmessage" => addslashes($this->request->getParam('alertmessage')),
                                "notifybysms" =>$this->request->getParam('notifybysms'),
                                "notifybyemail" => addslashes($this->request->getParam('notifybyemail'))
                            );
                            $webformAttbArray = $this->request->getParam('chkname'); //echo "<pre>"; print_r($webformAttbArray); 
                            $webformAttbTypeArray = $this->request->getParam('chktype'); //echo "<pre>"; print_r($webformAttbTypeArray); 
                            $webformAttbWeightage = $this->request->getParam('weightage'); //echo "<pre>"; print_r($webformAttbTypeArray);
                            //echo "<pre>"; print_r($this->request->getParam('chk')); 
                            foreach ($this->request->getParam('chk') as $key => $checked) {
                                $webformAttbArrayToinsert[$key]['weightage'] = addslashes($webformAttbWeightage[$checked]);
                                $webformAttbArrayToinsert[$key]['attb'] = addslashes($webformAttbArray[$checked]);
                                $webformAttbArrayToinsert[$key]['attbtype'] = addslashes($webformAttbTypeArray[$checked]);
                                $webformAttbOptionArray = $this->request->getParam('chkvalue' . $checked); //echo "<pre>"; print_r($webformAttbOptionArray);
                                if (!empty($webformAttbOptionArray)) {
                                    $webformAttbOptionString = implode(",", $webformAttbOptionArray);
                                } else {
                                    $webformAttbOptionString = "";
                                }

                                $webformAttbArrayToinsert[$key]['attboption'] = addslashes($webformAttbOptionString);
                            }      //echo "<pre>"; print_r($webformAttbArrayToinsert);   exit; 
                            if (empty($webformAttbArrayToinsert)) {
                                $message['type'] = "error";
                                $message['body'] = "Please check atleast one information field";
                            } elseif (empty($folderidArray)) {
                                $message['type'] = "error";
                                $message['body'] = "Please choose atleast one folder";
                            } else {
                                $dataInserted = $formObj->insertFormDetails($webformUrlArray, $webformAttbArrayToinsert);
                                if ($dataInserted) {
                                    $message['type'] = "success";
                                    $message['body'] = "Form created successfully";
                                } else {
                                    $message['type'] = "error";
                                    $message['body'] = $formObj->getError();
                                }
                            }
                        }
                        $this->_redirector->gotoUrl('/form/create/');
                    } else {
                        $folderids = implode($folderidArray, ',');
                        $webformUrlArray = Array(
                            "entityid" => $userid,
                            "urlname" => addslashes($weburlfilter),
                            "thankumsg" => addslashes($this->request->getParam('thanku_message')),
                            "descmsg" => addslashes($this->request->getParam('formdesc')),
                            "folderid" => $folderids,
                            "dobstatus" => addslashes($this->request->getParam('dobstatus')),
                            "alertmessage" => addslashes($this->request->getParam('alertmessage')),
                            "notifybysms" =>$this->request->getParam('notifybysms'),
                            "notifybyemail" => addslashes($this->request->getParam('notifybyemail'))
                        );
                        $webformAttbArray = $this->request->getParam('chkname'); //echo "<pre>"; print_r($webformAttbArray); 
                        $webformAttbTypeArray = $this->request->getParam('chktype'); //echo "<pre>"; print_r($webformAttbTypeArray); 
                        $webformAttbWeightage = $this->request->getParam('weightage'); //echo "<pre>"; print_r($webformAttbTypeArray);
                        //echo "<pre>"; print_r($this->request->getParam('chk')); 
                        foreach ($this->request->getParam('chk') as $key => $checked) {
                            $webformAttbArrayToinsert[$key]['weightage'] = addslashes($webformAttbWeightage[$checked]);
                            $webformAttbArrayToinsert[$key]['attb'] = addslashes($webformAttbArray[$checked]);
                            $webformAttbArrayToinsert[$key]['attbtype'] = addslashes($webformAttbTypeArray[$checked]);
                            $webformAttbOptionArray = $this->request->getParam('chkvalue' . $checked); //echo "<pre>"; print_r($webformAttbOptionArray);
                            if (!empty($webformAttbOptionArray)) {
                                $webformAttbOptionString = implode(",", $webformAttbOptionArray);
                            } else {
                                $webformAttbOptionString = "";
                            }

                            $webformAttbArrayToinsert[$key]['attboption'] = addslashes($webformAttbOptionString);
                        }      //echo "<pre>"; print_r($webformAttbArrayToinsert);   exit;
                        if (empty($webformAttbArrayToinsert)) {
                            $message['type'] = "error";
                            $message['body'] = "Please check atleast one information field";
                        } elseif (empty($folderidArray)) {
                            $message['type'] = "error";
                            $message['body'] = "Please choose atleast one folder";
                        } else {
                            $dataInserted = $formObj->insertFormDetails($webformUrlArray, $webformAttbArrayToinsert);
                            if ($dataInserted) {
                                $message['type'] = "success";
                                $message['body'] = "Form created successfully";
                            } else {
                                $message['type'] = "error";
                                $message['body'] = $formObj->getError();
                            }
                        }
                    }
                } else {
                    $message['type'] = "error";
                    $message['body'] = "Please select atleast one folder from dropdown.";
                }
            }
        }
        $user = new Application_Model_User((int) $userid);
        $this->view->message = $message;
        $this->view->folders = $this->user->getFolders();
        $this->view->webformlist = $formObj->webformList($userid);
        $this->view->webformlistSubaccount = $webformListSubAccount;
        $this->view->business = $user->businessname;
        $this->view->ainfo = $user->reward;
        $this->view->userid = $userid;
    }

    public function linkAction() {

        $this->_helper->layout->setLayout('formlayout');
        $formObj = new Application_Model_Form();
        $error = array();
        $success = array();
        $this->request->getParam('url');
        $clubreadyid  = 0;
        $urlWithUserId = strrev($this->request->getParam('url'));
        $breakUrlInParts = explode('txet', $urlWithUserId, 2);

        $userid = strrev($breakUrlInParts[0]);

        $formurl = strrev($breakUrlInParts[1]);
        
        $isClubready = FALSE;
        $user = new Application_Model_User((int)$userid);
        if($user->additionalinfo == "CR"){
            $clubreadyid = $user->accountstatus;
            $isClubready = TRUE;
        }
        
        $webformattb = $formObj->webformDetail($userid, addslashes($formurl));
        if (empty($webformattb)) {
            $message['type'] = "error";
            $message['body'] = "Form doesn't exist";
        }else
            $this->view->webformattb = $webformattb;

        if ($this->request->isPost()) {

            $postdataValue = $this->_request->getParams();
//echo '<pre>'; print_r($postdataValue);exit;
//echo "<pre>"; print_r($this->_request->getParams()); 
            $webform_url_id = $webformattb['0']['webform_url_id'];
            $collectAttributesOnTheBasisOfDropdownAndCheckboxValues = $formObj->collectAttributesOnTheBasisOfDropdownAndCheckboxValues($webform_url_id);
//          echo "<pre>";  print_r($collectAttributesOnTheBasisOfDropdownAndCheckboxValues); 
//          echo $webform_url_id;
//          exit;
            $phonenumber = $postdataValue[0];
            $firstname = "";
            $lastname = "";
            $email = "";
            $birthday = "";
            $language = "";
            $attr_folder_ids_array = array();
            if (!empty($collectAttributesOnTheBasisOfDropdownAndCheckboxValues)) {
                foreach ($collectAttributesOnTheBasisOfDropdownAndCheckboxValues as $key => $value) {
                    foreach ($value as $k => $val) {
                        $folderid_attr = $postdataValue[$val]; //echo '<pre>'; print_r($folderid_attr);
                        if (is_array($folderid_attr)) {

                            foreach ($folderid_attr as $key => $v) {
                                $folder_id_individual = $v;
                                array_push($attr_folder_ids_array, $folder_id_individual);
                            }
                        } else {
                            $folder_id_individual = $folderid_attr;
                            array_push($attr_folder_ids_array, $folder_id_individual);
                        }
                    }
                }
            }
            $newfolderarray = substr($webformattb['0']['folderid'], 0, 1);
            if ($newfolderarray == ',') {
                $webformattb['0']['folderid'] = substr($webformattb['0']['folderid'], 1);
            }

            $folderidArraySelectedFolder = explode(",", $webformattb['0']['folderid']);

            $folderidArray = array_unique(array_merge($folderidArraySelectedFolder, $attr_folder_ids_array));
//echo '<pre>'; print_r($folderidArray);


            unset($postdataValue['controller'], $postdataValue['action'], $postdataValue['url'], $postdataValue['module'], $postdataValue['accept']);
            $this->view->postedValues = $postdataValue;
            if ($this->request->getParam('accept') == 1) {

                if (!empty($postdataValue[0])) {
                    $phonenumber = $formObj->cleanPhone($phonenumber);
                    // echo $phonenumber; exit;
                    if (strlen($phonenumber) == 10) {
                        $phonenumber = "1" . $phonenumber;
                        unset($postdataValue['0']);
                        foreach ($postdataValue as $attbid => $attbvalue) {
                            $attbnameArray = $formObj->getAttributeName($attbid);
                            $attbnam = $attbnameArray['0']['attribute'];

                            if ($attbnam == "Email") {
                                $email = addslashes($postdataValue[$attbid]);
                                unset($postdataValue[$attbid]);
                            } elseif ($attbnam == "First Name") {
                                $firstname = addslashes($postdataValue[$attbid]);
                                unset($postdataValue[$attbid]);
                            } elseif ($attbnam == "Last Name") {
                                $lastname = addslashes($postdataValue[$attbid]);
                                unset($postdataValue[$attbid]);
                            } elseif ($attbnam == "Birthday") {
                                $attbvalue = implode("-", $postdataValue[$attbid]);
                                $birthday = $attbvalue;
                                unset($postdataValue[$attbid]);
                            } elseif ($attbnam == "Language") {
                                $language = addslashes($postdataValue[$attbid]);
                                unset($postdataValue[$attbid]);
                            }
                        }

                        $subs_array = array();
                        $a = 0;
                        $attbidArray = array();
                        $status_flag = '';
                        $b_ifnewsubscriber = TRUE;
                        $subscriberintheaccount = FALSE;
                       $tmp_alternate = "";
                        foreach ($folderidArray as $folderid) {
                            if ($formObj->checksubscriber($phonenumber, $folderid) && $formObj->checksubscriber($phonenumber, $folderid) != '') { //update
                                $subscriberid = $formObj->checksubscriber($phonenumber, $folderid);
                                $status_flag = 1;
                                $formObj->updateSubscriberAllDetails($subscriberid, $folderid, $phonenumber, $firstname, $lastname, $email, $birthday, $language, $webform_url_id);
                               $tmp_alternate = "Thank you for checking in with us again, we see you have redeemed this offer already. Please stay tuned for other VIP deals and promotions.";
                                array_push($subs_array, $subscriberid);
                            } else { //insert
                                $info_msg = $webformattb[0]['alertmessage'];
                                $subscriberid = $formObj->addSubscriberAllDetails($folderid, $phonenumber, $firstname, $lastname, $email, $birthday, $language, $webform_url_id);
                                if($isClubready)
                                 {
                                   $dobformat = date('Y-').$birthday; 
                                   $bool = false;
                                  $formObj->postLeadsClubready($firstname, $lastname, $phonenumber, $dobformat, $email, $clubreadyid,$bool);
                                }
                                /* here the new subcriber or opted out gets second info sms */
                                array_push($subs_array, $subscriberid);
                               $tmp_alternate = $webformattb['0']['thankumsg'];
                               $b_ifnewsubscriber = FALSE;
                               if($formObj->getStatusOfSubscriberFromUserAccount($phonenumber, $userid)){
                                   $subscriberintheaccount = TRUE;
                               }
                            }
                        }
                        foreach ($postdataValue as $attbid => $attbvalue) {
                            if (is_array($attbvalue)) {
                                $attbvalueins[$a] = implode(",", $attbvalue);
                                $attbvalueins[$a] = addslashes($attbvalueins[$a]);
                                $attbidArray[$a] = $attbid;
                                $a++;
                            } else {
                                $attbvalueins[$a] = addslashes($attbvalue);
                                $attbidArray[$a] = $attbid;
                                $a++;
                            }
                        }
                      $sendtime = date('Y-m-d H:i:s');
                        for ($i = 0; $i < count($subs_array); $i++) {
                            for ($j = 0; $j < $a; $j++) {
                                if ($status_flag == 1) { //update values
                                    $formObj->updateFormValues($subs_array[$i], $attbidArray[$j], $attbvalueins[$j]);
                                } else {
                                    $formObj->insertFormValues($subs_array[$i], $attbidArray[$j], $attbvalueins[$j]);
                                }
                            }
                        }
                        $success[0] = $webformattb['0']['thankumsg'];
                        // the code
                        $msgObj = new Application_Model_Message();
                        $timezone = null;
                        $source = 202;
                       //queue($body, $recipients, $sendtime = null, $timezone = null, $confirmid = 0, $depth = 0, $createuser = 0, $shortcode=87365)
                        $status = $msgObj->queue($tmp_alternate, $phonenumber, $sendtime, $timezone,0,$source,$userid);
                        if(!$b_ifnewsubscriber && !$subscriberintheaccount){
                            $msgObj->queue($info_msg, $phonenumber, $sendtime, $timezone,0,$source,$userid);
                              if($webformattb['0']['notifybyemail']){
                                $confirm = "A new subscriber optin through webform  on $sendtime:".PHP_EOL."First: ".$firstname.PHP_EOL.'Last: '.$lastname
                                        .PHP_EOL."Phone: ".$phonenumber.PHP_EOL."Email: ".$email;
                                $subject = "Lead notification";
                                $formObj->alertnewlead($webformattb['0']['notifybyemail'], $subject, $confirm);
                              }
                              if($webformattb['0']['notifybysms']){
                                  if(strlen($webformattb['0']['notifybysms']) < 11 ){
                                    $phone = "1".$webformattb['0']['notifybysms'];
                                  }else{
                                      $phone = $webformattb['0']['notifybysms'];
                                  }
                                   $confirm = "A new lead optin $sendtime".PHP_EOL."First: ".$firstname.PHP_EOL.'Last: '.$lastname
                                        .PHP_EOL."Phone: ".$phonenumber.PHP_EOL."Email: ".$email;
                                    $formObj->alertMessage($phone, $confirm);
                              }
                        }
                    } else {
                        $error[1] = "Please enter 10 digit Phone number.";
                    }
                } else {
                    $error[2] = "Phone number is required.";
                }
            } else {
                $error[3] = "Please accept the terms & conditions";
            }
        }
        $this->view->success = $success;
        $this->view->error = $error;
    }

    public function editAction() {
        $webformurlid = $this->request->getParam('id');
        $userid = $this->user->getId();
        $user = new Application_Model_User((int) $userid);
        if ($webformurlid) {
            $formObj = new Application_Model_Form();
            $webformadetails = $formObj->getWebformDeatils($webformurlid);
           
            if (count($webformadetails) == 0) {
                $this->_redirector->gotoUrl('/form/create/');
            }
            $attbArray = array();
            $userid = $webformadetails['0']['entityid'];

            foreach ($webformadetails as $key => $webArray) {
                $attbArray[$key] = $webArray['attribute'];
                $attbWeightage[$webArray['attribute']] = $webArray['weightage'];
                $attbtoArray[$key]['attbtype'] = $webArray['attbtype'];
                $attbtoArray[$key]['attboption'] = $webArray['attboption'];
            }
            $this->view->webformadetails = $webformadetails;
            $this->view->attbarray = $attbArray;
            $this->view->attbWeightage = $attbWeightage;
            $this->view->attbtoArray = $attbtoArray;
            $this->view->folders = $this->user->getFolders();
            $this->view->business = $user->businessname;
            if ($this->request->isPost()) {
                if ($this->request->getParam('formurl') == "") {
                    $message['type'] = "error";
                    $message['body'] = "Atleast Unique URL is required";
                } else {
                    $folderidArray = $this->request->getParam('folderid');
                    if (!empty($folderidArray)) {
                        $folderids = implode($folderidArray, ',');
                        $webformAttbArrayToinsert = array();

                        $webformUrlArray = Array(
                            "entityid" => $userid,
                            "urlname" => addslashes(trim($this->request->getParam('formurl'))),
                            "thankumsg" => addslashes($this->request->getParam('thanku_message')),
                            "descmsg" => addslashes(($this->request->getParam('formdesc'))),
                            "folderid" => $folderids,
                            "alertmessage" => addslashes(trim($this->request->getParam('alertmessage'))),
                            "notifybysms" =>  $this->request->getParam('notifybysms'),
                            "notifybyemail" => addslashes(trim($this->request->getParam('notifybyemail'))));
//                        echo "<pre>"; print_r($webformUrlArray); exit;
                        $webformAttbArray = $this->request->getParam('chkname'); //echo "<pre>"; print_r($webformAttbArray);
                        $webformAttbTypeArray = $this->request->getParam('chktype'); //echo "<pre>"; print_r($webformAttbTypeArray);
                        //$webformAttbOptionArray = $this->request->getParam('chkvalue');  //echo "<pre>"; print_r($webformAttbOptionArray);  
                        $webformAttbWeightage = $this->request->getParam('weightage'); //echo "<pre>"; print_r($webformAttbTypeArray);                
                        //echo "<pre>"; print_r($this->request->getParam('chk')); exit;
                        foreach ($this->request->getParam('chk') as $key => $checked) {
                            $webformAttbArrayToinsert[$key]['weightage'] = addslashes($webformAttbWeightage[$checked]);
                            $webformAttbArrayToinsert[$key]['attb'] = addslashes($webformAttbArray[$checked]);
                            $webformAttbArrayToinsert[$key]['attbtype'] = addslashes($webformAttbTypeArray[$checked]);
                            //$webformAttbArrayToinsert[$key]['attboption'] = addslashes($webformAttbOptionArray[$checked]);  
                            $webformAttbOptionArray = $this->request->getParam('chkvalue' . $checked); //echo "<pre>"; print_r($webformAttbOptionArray);
                            if (!empty($webformAttbOptionArray)) {
                                $webformAttbOptionString = implode(",", $webformAttbOptionArray);
                            } else {
                                $webformAttbOptionString = "";
                            }
                            $webformAttbArrayToinsert[$key]['attboption'] = addslashes($webformAttbOptionString);
                        }    //echo "<pre>"; print_r($webformAttbArrayToinsert); exit;        
                        if (empty($webformAttbArrayToinsert)) {
                            $message['type'] = "error";
                            $message['body'] = "Please check atleast one information field";
                        } elseif (empty($folderidArray)) {
                            $message['type'] = "error";
                            $message['body'] = "Please choose atleast one folder";
                        } else {
                            $updateformURL = $formObj->updateFormURL($webformurlid, $webformUrlArray['thankumsg'], $webformUrlArray['descmsg'], 
                                    $webformUrlArray['folderid'], $webformUrlArray['alertmessage'],$webformUrlArray['notifybysms'],$webformUrlArray['notifybyemail']);
                            $removeExistingAttb = $formObj->removeFormAttribute($webformurlid);
                            if ($removeExistingAttb) {
                                $addformURLAttribute = $formObj->addFormAttribute($webformurlid, $webformAttbArrayToinsert);
                            }
                            $this->_redirector->gotoUrl('/form/create/');
                        }
                    } else {
                        $message['type'] = "error";
                        $message['body'] = "Please select atleast one folder from dropdown.";
                    }
                }
            }
        } else {
            $this->_redirector->gotoUrl('/form/create/');
        }
    }

    public function deleteAction() {
        //echo "<pre>"; print_r($this->_request->getParams()); exit;   
        $formObj = new Application_Model_Form();
        $deleteidArray = $this->request->getParam('deleteid');
        foreach ($deleteidArray as $formid) { //echo $formid; exit;
            $formObj->deleteWebformById($formid);
        }
        $this->_redirector->gotoUrl('/form/create/');
    }
    
    public function webformAction() {
            if ($this->request->isPost()) {
             $id = $this->request->getParam('id');
             $mobile = $this->request->getParam('phone');
             $first = $this->request->getParam('first');
             $last = $this->request->getParam('last');
             $month = $this->request->getParam('month');
             $day = $this->request->getParam('day');
         }
         echo $id." :: ".$mobile." :: ".$first." :: ".$last." :: ".$month." :: ".$day;
//         echo 'PIZDA';
         exit;
    }
    
      public function webformsAction(){
         if ($this->request->isPost()) {
             $mobile = $this->request->getParam('mobile');
             $first = $this->request->getParam('first');
             $last = $this->request->getParam('last');
             $month = $this->request->getParam('bodmonth');
             $day = $this->request->getParam('bodday');
         }
//         echo $mobile." :: ".$first." :: ".$last." :: ".$month." :: ".$day;
//         echo 'PIZDA';
         exit;
    }
}

