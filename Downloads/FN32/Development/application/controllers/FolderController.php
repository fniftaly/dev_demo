<?php

class FolderController extends AuthorizedController {

    /**
     * Redirector - defined for code completion
     *
     * @var Zend_Controller_Action_Helper_Redirector
     */
    protected $_redirector = null;

    public function init() {
        $this->_redirector = $this->_helper->getHelper('Redirector');
    }

    /**
     * Index will list all the users folders.
     * 
     * @access public
     */
    public function indexAction() {
        // Set any add folder errors into the view
        $this->view->adderror = $this->_getParam('adderror');
        $this->view->success = $this->_getParam('success');
        $folderObj = new Application_Model_Folder($this->user);
        if ($this->request->isPost()) {
            $iphonenumber = $this->request->getParam('phonesrch');
            if ($iphonenumber != "") {
                $folderidArray = $folderObj->getFoldersByPhoneNumber($iphonenumber);
                $folderidstr = "";
                foreach ($folderidArray as $key => $folder) {
                    if ($key != 0)
                        $folderidstr.=",";

                    $folderidstr.=$folder['folderid'];
                }
                $user_folders = $folderObj->getFoldersByfolderidstring($folderidstr);
            }else {
                $user_folders = $this->user->getFolders();
            }
        } else {
            $user_folders = $this->user->getFolders();
        }

        /*
          $folders      = array();

          foreach ($user_folders as $id) {
          $folders[] = new Application_Model_Folder($this->user, $id);
          } */
        $messageObj = new Application_Model_Report();
        $this->view->messageObj = $messageObj;
        $this->view->folders = $user_folders;
        $this->view->user = $this->user;
        $this->view->userid = $this->user->getId();
        $this->view->flist = $folderObj->getFoldersByName($this->user->getId());
    }
    
 /**
 * 
 * 
 */
    public function abcrequestAction(){
        if ($this->request->isPost()) {
            $folder = new Application_Model_Folder($this->user);
            $folderid = (trim($this->request->folderid))?:0;
            $clubid = (trim($this->request->abcid))?:0;
            if($folderid !=0 && !$clubid)
              $fcontent = $folder->getSubscriberscustom($folderid);
        }
        
          if($clubid && $folderid){
              $abc = new Application_Model_Abcdata();
              $abc->cleanupFolder($folderid);
              $abc->getURL($clubid);
              $rs = $abc->abcClientData($abc->URL_ALL_DATA);
              $absobj = $abc->setAbcclubObjects($rs);
              if($abc->abcSubscribers($absobj,$folderid,$this->user->getId())){
               echo 1; exit;
              }else{echo 0; exit;}
          }
        echo count($fcontent);
        exit;
    }
    
    /**
     * Add a new folder to this users account.
     * 
     * @access public
     */
    public function addAction() {
        if ($this->request->isPost()) {
            $name = trim($this->request->name);
            $page = trim($this->request->reqpage);
            $action = trim($this->request->reqaction);
            $meta = array();
            $meta['name'] = $name;
            $meta['nev'] = "false";
            
            if (empty($name)) {
                $adderror = 'A name must be provided for the new folder.';
                return $this->_forward($action, $page, null, array('adderror' => $adderror));
            }

            $folder = new Application_Model_Folder($this->user);
            if ($page == 'nevlocation') {
                $meta['nev'] = "true";
                $meta['nevlocation'] = $name;
                $page = 'folder';
            }
            $success = $folder->addWithMetaNev($meta);
            return $this->_forward($action, $page, null, array('success' => $success));
        }
    }

    function csv2array($input, $delimiter = ',', $enclosure = '"', $escape = '\\') {
        $fields = explode($enclosure . $delimiter . $enclosure, substr($input, 1, -1));
        foreach ($fields as $key => $value)
            $fields[$key] = str_replace($escape . $enclosure, $enclosure, $value);
        return($fields);
    }

    /**
     * See messages sent to a particular folder.
     * 
     * @access public
     */
    public function viewAction() {
        // Defaults
        $error = null;
        $foldername = 'Not Found';
        $folderid = null;
        $subscribers = array();
        $message = null;
        $datauploaded = 0;
        $folderList = $this->user->getFolders();

        $id = $this->request->getParam('id');
        $from = $this->request->getParam('from');
        if ($from && $from == 'keyword') {
            $link = '/messages/keyword/';
            $kid = $this->request->getParam('kid');
            $keyword = $this->request->getParam('keyword');

            if ($kid && $keyword) {
                $link .= 'id/' . $kid;
                $text = 'Back to keyword ' . $keyword;
            } else {
                $text = 'Back to keywords';
            }
        } else {
            $link = '/folder/';
            $text = 'Back to folders';
        }

        $this->view->backtolink = $link;
        $this->view->backtotext = $text;

        $folder = new Application_Model_Folder($this->user, $id);
        /* all opted out subcribers from current account */
        // echo "I am here".print_r($folder); 
        if ($folder->isValid()) {

            // 1st see if the user tried to import subscribers
            if ($this->request->isPost()) {
                // See what action we are taking
                $actiontype = $this->request->getParam('actiontype');


                if ($actiontype == 'upload') {
//                    echo "<script type='text/javascript'>alert('UPLOAD')</script>";
//                 	if ($this->user->isSuperAdmin() || $this->session->canImport) {
                    // initialize the uploader
                    $uploader = new Application_Model_Upload();
                    // get the action from post
                    $upload = $this->request->getParam('upload');
                    $xls_template = trim($this->request->getParam('rbts_status'));
//                   echo "<script type='text/javascript'>alert('Line 154')</script>";
                    if ($upload == 'Upload File') {
//                        echo "<script type='text/javascript'>alert('Line 156')</script>";
                        $upload_path = '../application/imports/';
                        // set the upload directory
                        $uploader->setUploadDir($upload_path);
//                        echo "<script type='text/javascript'>alert('Line 160')</script>";
                        // give the valid file type extensions
                        $uploader->setValidExt(array('csv'));
                        // Give the filename some context
                        $filename = $this->user->getId().'-'.$id.'-'.time().'.csv';
                        // do the upload
                        if (($result = $uploader->uploadFile($filename)) === true) {
                            $isTemplatewrong = false;
                            // Now open the file and import the subscribers
                            ini_set('auto_detect_line_endings', TRUE);
                            if (($handle = fopen($upload_path . $filename, "rb")) !== false) {
                                $tmpStatus = true;
                                while (($subscriber = fgetcsv($handle, 0, "\n")) !== false) {
                                    if (trim($subscriber[0])) {
                                        $csvdata = explode(',', $subscriber[0]);
                                        $count_csv = count($csvdata);

                                        $phone = $folder->cleanPhone($csvdata[0]);

                                        if (strlen($phone) == 10) {
                                            $phone = '1' . $phone;
                                            $csvdata[0] = $phone;
                                        }
                                        if (strlen($phone) < 10) {
                                            continue;
                                        }

                                        $sbrStatus = $folder->check_status_ofsubcriber($phone);

                                        if (!$sbrStatus) {
                                            continue;
                                        } else {
                                            /* upload template type definition */
                                            if ($xls_template === 'ph' && $count_csv == 1) {
                                                if ($folder->addSubscriber($csvdata[0]) === false) {
                                                    $errors[] = $folder->getError();
                                                }
                                                $isTemplatewrong = true;
                                            }

                                            if ($xls_template === 'phbd' && $count_csv == 2) {
                                                $bd = str_replace("_", "-", "$csvdata[1]");
                                                if ($folder->addSubscriber($csvdata[0], "", "", $bd) === false) {
                                                    $errors[] = $folder->getError();
                                                }
                                                $isTemplatewrong = true;
                                            }

                                            if ($xls_template === 'pnbdfl' && $count_csv == 4) {
                                                $bd = str_replace("_", "-", "$csvdata[3]");
                                                if ($folder->addSubscriber($csvdata[0], $csvdata[1], $csvdata[2], $bd) === false) {
                                                    $errors[] = $folder->getError();
                                                }
                                                $isTemplatewrong = true;
                                            }
                                        }
                                    }
//                                    usleep(10000);
                                    $datauploaded++;
                                }// end of while loop
                                // if template type is not the same with checked radio button
                                if (!$isTemplatewrong) {
                                    $msg = '<script type="text/javascript">';
                                    $msg .= ' $(function(){';
                                    $msg .=' csscody.alert("Incorrect template is selected!")});';
                                    $msg .= '</script>';
                                    echo $msg;
                                    $tmpStatus = false;
                                }
                                fclose($handle);
                                if ($tmpStatus)
                                    if (!empty($errors)) {
                                        $error = $errors;
                                    } else {

                                        $message = 'Subscribers imported!';
                                    }
                                $act_id = $this->user->getId();
                                $email = $this->user->email;
                                $business = $this->user->businessname;
                                $phone = ($this->user->office) ? $this->user->office : $this->user->cell;
                                $msg = "Data uploaded to the Textm system by:\n\nAccountid: " . $act_id . "\nEmail: " . $email .
                                        "\nBusinessname: " . $business . "\nPhone: " . $phone . "\nSubscribers imported: " . $datauploaded;
                                $folder->sendEmail($email, "New data uploaded", $msg);
                            } else {
                                $error = 'The file uploaded but could not be imported. Please contact us.';
                            }
                        } else {
                            $error = $uploader->displayError();
                        }
                    }
//                    unset($syst_subscrbs);
//                 	} else {
//                 		$error = 'You do not have permission to carry out this operation';
//                 	}
                }

                if ($actiontype == 'movenumber') {

                    $newfolderid = $this->request->getParam('movedropdown');
                    $keywordDetails = $folder->getKeywordDetailsByFolderId($newfolderid);
                    if (!empty($keywordDetails))
                        $newkeywordid = $keywordDetails[0]['id'];
                    else
                        $newkeywordid = 0;

                    $subscriberidArray = $this->getRequest()->getParam('subscid');
                    foreach ($subscriberidArray as $subscriberid) {
                        $moveradioid = 'moveradio' . $subscriberid;
                        $moveORcopy = $this->request->getParam($moveradioid);

                        if ($moveORcopy == 'copy') {
                            $subscriberDetails = $folder->getSubscriberDetailsById($subscriberid);
                            $newsubscriberid = $folder->copyPhoneNumber($newfolderid, $subscriberDetails[0]['phonenumber']);
                            if ($newsubscriberid) {
                                $message = "Phone number(s) copied sucessfully";
                                //$this->_redirector->gotoUrl('/folder/view/id/'.$newfolderid); 
                            }
                        } elseif ($moveORcopy == 'move') {
                            $subscriberDetails = $folder->getSubscriberDetailsById($subscriberid);
                            if ($folder->numberExistsInNewFolder($subscriberDetails[0]['phonenumber'], $newfolderid)) { //if number already exists in the destination folder
                                $existedNumberSubscriberId = $folder->numberExistsInNewFolder($subscriberDetails[0]['phonenumber'], $newfolderid);
                                //id found then delete corresponding row from destination folder
                                if ($folder->deleteNumberFromNewFolder($existedNumberSubscriberId)) {
                                    //now move number onto destination folder
                                    if ($folder->movePhoneNumber($subscriberid, $newfolderid, $newkeywordid)) {
                                        $message = "Phone number(s) moved sucessfully";
                                        //$this->_redirector->gotoUrl('/folder/view/id/'.$newfolderid); 
                                    } else {
                                        $error = 'An error occurred and the phone number has not been moved';
                                    }
                                }
                            } else {   //if number doesn't exist in the destination folder
                                if ($folder->movePhoneNumber($subscriberid, $newfolderid, $newkeywordid)) {
                                    $message = "Phone number(s) moved sucessfully";
                                    //$this->_redirector->gotoUrl('/folder/view/id/'.$newfolderid); 
                                } else {
                                    $error = 'An error occurred and the phone number has not been moved';
                                }
                            }
                        }
                    }
                    //echo "<pre>"; print_r($this->request); exit;
                }
            }
            if ($this->request->getParam('actiontype') == 'optout') {
                //if ($this->user->isSuperAdmin() || $this->session->canImport) {
                $subscriber = $this->request->getParam('subscriber');

                if ($subscriber) {
                    if ($folder->optOutSubscriberById($subscriber)) {
                        $message = 'Subscriber opted out.';
                    }
                }
                //} 
                /* else {
                  $error = $error ? $error . "<br />" : '';
                  $error .= 'You do not have sufficient permission to opt out subscribers';
                  } */
            }

            $foldername = $folder->name;
            $folderid = $folder->getId();

            $subscribers = $folder->getSubscribersLatest(); //echo "<pre>"; print_r($subscribers); 
        } else {
            $error = $folder->getError();
        }

        // View variables
        $this->view->foldername = $foldername;
        $this->view->folderid = $folderid;
        $this->view->subscribers = $subscribers;
        $this->view->error = $error;
        $this->view->message = $message;
        
        $this->view->folderList = $folderList;
        $this->view->accountid = $this->user->getId();
        $this->view->user = $this->user;

        //echo "<pre>"; print_r($this->view); 
        //echo "I am here5555522";die;
        //exit;
    }

    private function upload() {
        
    }

    /*
      Combined folder/subscriber view logic. On hold for now.

      public function indexAction() {
      $this->_forward('view');
      }
      public function viewAction() {
      // Defaults
      $error      = null;
      $foldername = 'Not Found';
      $folders    = array();

      $folderid = $this->request->getParam('id');

      // If there is a folder id try and get that folder
      if ($folderid) {
      $folder = new Application_Model_Folder($this->user, $folderid);

      if ($folder->isValid()) {
      $foldername = $folder->name;
      $folders    = $folder->getSubscribers();
      } else {
      $error = $folder->getError();
      }
      } else {
      // otherwise show all folders
      $user_locs = $this->user->getFolders();

      foreach ($user_locs as $id) {
      $folders[] = new Application_Model_Folder($this->user, $id);
      }
      }

      // View variables
      $this->view->folderid   = $folderid;
      $this->view->folders    = $folders;
      $this->view->foldername = $foldername;
      $this->view->error      = $error;
      } */

    public function editAction() {
        // Defaults
        $error = null;
        $message = null;
        $id = $this->request->getParam('id');

        $folderObj = new Application_Model_Folder($this->user, $id);

        if ($this->request->isPost()) {
            $folderDetail['id'] = $this->request->getParam('id');
            $folderDetail['name'] = trim($this->request->getParam('foldername'));

            if ($folderObj->updateFolderDetail($folderDetail)) {
                $message = 'Folder name updated.';
            } else {
                $error = 'Folder error: ' . $folderObj->getError();
            }
            $folderObj = new Application_Model_Folder($this->user, $id);
            //$this->_redirector->gotoUrl('/folder/edit/id/'.$id);              
        }
        $this->view->foldername = $folderObj->name;
        $this->view->folderid = $id;
        $this->view->error = $error;
        $this->view->message = $message;
    }

    public function deleteAction() {
        $folderObj = new Application_Model_Folder($this->user);
        $folderid = $this->request->getParam('id');
        //if ($this->user->isSuperAdmin() || $this->session->canImport) {
        $reportObj = new Application_Model_Report();

        if ($reportObj->getTotalSubscriberByFolder($folderid) == 0) {
            if ($folderObj->deleteFolder($folderid)) {
                $message = 'Folder Deleted.';
            } else {
                $error = 'Folder error: ' . $folderObj->getError();
            }
        } else {
            if ($folderObj->deleteFolderWithSubscriber($folderid)) {
                $message = 'Folder Deleted.';
            } else {
                $error = 'Folder error: ' . $folderObj->getError();
            }
        }
        //}

        $this->view->error = $error;
        $this->view->message = $message;
        $this->_redirector->gotoUrl('/folder/');
    }

    public function subcriberoptoutAction() {
        $folderObj = new Application_Model_Folder($this->user);
        if ($this->request->isPost()) {
            $phone = $this->request->getParam('phone');
            $folderid_phone = explode("_", $phone);
            $folderObj->updateinboxmessage($folderid_phone[0], (int) $folderid_phone[1]);
            if ($folderObj->optOutSubscriberInbox($phone)) {
                echo 'This subscriber ' . $folderid_phone[0] . ' has been opted out';
            } else {
                echo 'This subcraiber ' . $folderid_phone[0] . ' is not in the subcribers folder';
            }
            exit;
        }
    }

    /**
     * 
     *  Data upload action
     *  Capturing info from account who is
     *  attended to upload data to the system
     *  
     */
    public function datauploadAction() {
        $folderObj = new Application_Model_Folder($this->user);
        if ($this->request->isPost()) {
            $act_id = $this->request->getParam('accountid');
            $email = $this->request->getParam('email');
            $business = $this->request->getParam('business');
            $phone = $this->request->getParam('phone');

            $rs = $folderObj->insertDataUpload($act_id, $email, $business, $phone);
            if ($rs) {
//                   $msg = "Data uploaded to the Textm system by:\n\nAccountid: ".$act_id."\nEmail: ".$email."\nBusinessname: ".$business."\nPhone: ".$phone;
//                   $folderObj->sendEmail($email, "New data upload", $msg);
                echo 'TRUE';
            } else {
                echo 'FALSE';
            }
        }
        exit();
    }

}
