<?php

class UsersController extends AuthorizedController {

    /**
     * This page is only accessable by admins.
     * 
     * @access public
     */
    public function preDispatch() {
        parent::preDispatch();

        // See if we have a logged in user.
        if (isset($this->user) && $this->user instanceof Application_Model_User && !$this->user->isAdmin()) {
            $this->_redirect('/');
        }
    }

    public function testAction() {
        var_dump($this->session->getDatastore());
    }

    /**
     * Index page lists all system users.
     * 
     * @access public
     */
    public function indexAction() {

        // Get all users this user is a parent of
        $this->view->users = $this->user->getUsers();

        if ($this->request->isPost()) {
            // See if they are switching into another user
            $actiontype = $this->request->getParam('actiontype');
//            echo $actiontype;
            if ($actiontype == 'switchuser') {
                // Get the switch users id
                $switchuser = (int) $this->request->getParam('switchuser');
               
                // Build the switch user to see if it is a valid id
                $user = new Application_Model_User($switchuser);

                if ($user->isValid()) {
                    // Only switch into users the current user is a parent of
                    if ($this->user->isSuperAdmin() || $this->user->isParentOf($switchuser)) {
                        // change the session info to have the new user

                        $this->session->asUser($switchuser);
                        $this->session->lastUser($this->user->getId());
                        // This is here so admins can log into their customers accounts
                        // and import numbers for them, as regular users are not allowed
                        // to import. Probably can figure out a better way to handle this.
                        $this->session->canImport = true;
                        $this->session->isAdmin = true;
                        // direct to the dashboard
                        $this->_redirect('/');
                    }
                }
            }
        }
        $this->view->userid  = $this->user->getid();
    }

    /**
     * Add a new user.
     * 
     * @access public
     */
    public function addAction() {
        // Defaults
        $error = null;
        $status = null;

        $entity = new Application_Model_Userentity($this->user);

        $users = $entity->getAll();

        if ($this->request->isPost()) {
            // Check our post array
            $post = $this->request->getPost();

            if (!empty($post)) {
                if ($entity->exists('user', 'username', $post['username'])) {
                    $status = false;
                    $error = 'Username already taken.';
                } else {
                    // Special handling for passwords
                    if (array_key_exists('password', $post)) {
                        // Use a special method for updating passwords
                        $post['password'] = $entity->encryptPassword($post['password']);
                    }

                    // Add the entity with all of the passed info
                    $entity->addWithMeta($post);
                    /* ZOHO STUFF GOES HERE */
//                    $auth = $entity->getAuth();
                    $auth = "f1954f4dba0768315a9ecf4a0ae1b0a3";
                    $entity->postData($auth, $post['firstname'], $post['lastname'], $post['email'], $post['businessname'], $post['address'], $post['city'], $post['state'], $post['zip'], $post['cell'], $post['office']);
                    $status = $entity->isValid();
                    $error = $entity->getError();
                }
            } else {
                $error = 'No data was presented to create the user with.';
            }
        }
        $inds = $entity->getIndustry();
        $this->view->status_types = $this->user->getStatusTypes();
        $this->view->industries = $inds;
        $this->view->error = $error;
        $this->view->status = $status;
    }

    /**
     * Edit a passed user id.
     * 
     * @access public
     */
    public function editAction() {
        // Defaults
        $error = null;
        $status = null;
        $user = null;
        $userID = $this->user->getId();
        $password_default = '*****';
        $userid = (int) $this->request->getParam('id');
        $entity = new Application_Model_Userentity($this->user, $userid);
        if ($userid) {
            if ($this->request->isPost()) {
                // Build the user entity model so we can update values
//	    		$entity = new Application_Model_Userentity($this->user, $userid);

                if ($entity->isValid()) {
                    $new_values = $this->request->getPost();

                    // dont allow duplicate usernames if they are changing the username
                    if ($new_values['username'] != $entity->username && $entity->exists('user', 'username', $new_values['username'])) {
                        $status = false;
                        $error = 'Username already taken.';
                    } else {
                        if (array_key_exists('password', $new_values)) {
                            if ($new_values['password'] != $password_default) {
                                // Use a special method for updating passwords
                                $entity->updatePassword($new_values['password']);
                            }
                            // Dont send this to the update method
                            unset($new_values['password']);
                        }

                        // Handle the isadmin checkbox
                        if (!array_key_exists('admin', $new_values)) {
                            $new_values['admin'] = '';
                        }

                        // Handle the canimport checkbox
                        // REMOVED PER JJ 2011-09-14 - rgonzale
                        //if (!array_key_exists('canimport', $new_values)) {
                        //	$new_values['canimport'] = '';
                        //}
                        // Update the meta values
                        if ($entity->updateMetaValues($new_values)) {
                            $status = true;
                        } else {
                            $error = $entity->getError();
                        }
                    }
                } else {
                    $error = $entity->getError();
                }
            }

            // Get the user's model with the current field values
            $user = new Application_Model_User((int) $userid);
        } else {
            $error = 'No User select to edit.';
        }
        $inds = $entity->getIndustry();
        $encrypted = $user->password;
        $password = $user->password;
        $this->view->status_types = $this->user->getStatusTypes();
        $this->view->user = $user;
        $this->view->password = $password_default;
//    	$this->view->password = base64_decode($password);
        $this->view->error = $error;
        $this->view->status = $status;
        $this->view->industries = $inds;
        $this->view->ID = $userID;
    }

    /**
     * Register user with new field.
     * This action will replace add once completed
     * @access public
     */
    public function registerAction() {
        // Defaults
        $error = null;
        $status = null;

        $entity = new Application_Model_Userentity($this->user);

        $users = $entity->getAll();

        if ($this->request->isPost()) {
            // Check our post array
            $post = $this->request->getPost();

            if (!empty($post)) {
                if ($entity->exists('user', 'username', $post['username'])) {
                    $status = false;
                    $error = 'Username already taken.';
                } else {
                    // Special handling for passwords
                    if (array_key_exists('password', $post)) {
                        // Use a special method for updating passwords
                        $post['password'] = $entity->encryptPassword($post['password']);
                    }

                    // Add the entity with all of the passed info
                    $entity->addWithMeta($post);

                    $status = $entity->isValid();
                    $error = $entity->getError();
                }
            } else {
                $error = 'No data was presented to create the user with.';
            }
        }

        $this->view->status_types = $this->user->getStatusTypes();

        $this->view->error = $error;
        $this->view->status = $status;
    }

    /*     * Creates a new industry and saves it into industry table
     * this action also retruns all industries 
     * from industrie tables
     * when a new industies enters
     * 
     * @name allIndustryAction
     * @return json object
     */

    public function addindustryAction() {
        if ($this->getRequest()->isXmlHttpRequest()) {
            if ($this->getRequest()->isPost()) {
                $this->_helper->layout()->disableLayout();
                $this->_helper->viewRenderer->setNoRender(true);
                $industryname = $this->request->getParam('name');
                $user = new Application_Model_User();
                $createdat = date('Y-m-d H:i:s');
                if ($user->addIndustry($industryname, $createdat)) {
                    $entity = new Application_Model_Userentity($this->user);
                    $indstruies = $entity->getIndustry();

                    if (isset($indstruies)) {
                        echo json_encode($indstruies);
                    }
                    exit;
                }
            }
        }
    }

// end of addindustryAction;

    public function categoryAction() {
        $user = new Application_Model_User();
        $userListArr = $user->getAllListData();
        $FAQCnt = $user->FAQCntData();
        $SupportCnt = $user->SupportCntData();
        $VideosCnt = $user->VideosCntData();
        $this->view->userListArr = $userListArr;
        $this->view->FAQCnt = $FAQCnt;
        $this->view->SupportCnt = $SupportCnt;
        $this->view->VideosCnt = $VideosCnt;
    }

    public function categoryeditAction() {
        $error = null;
        $status = null;
        $ddconfigid = $this->request->getParam('id');
        $this->view->id = $ddconfigid;
        $user = new Application_Model_User();
        $Lid = $this->request->getParam('id');
        $ListArr = $user->getListData($Lid);
        $this->view->lid = $ListArr;
        if ($this->request->isPost()) {
            $user = new Application_Model_User();
            $post = $this->request->getPost();
            $cnt = count($post['cnt']);
            $ddid = $post['gid'];
            $user->dele($ddid);
            for ($i = 0; $i <= $cnt; $i++) {
                if (!isset($post['chk_box' . $i])) {
                    $value = $post['value' . $i];
                    if ($value != '') {
                        $gettext = $post['displaytext' . $i];
                        if ($gettext != '') {
                            if ($user->add($value, $gettext, $ddid)) {
                                $status = 'Category Added';
                            } else {
                                $error = 'Category Error: ' . $support->getError() . mysql_error();
                            }
                        }
                    }
                }
            }
            $this->view->status = $status;
            $this->view->error = $error;
            return $this->_redirect('users/category');
        }
    }

public function newaccountsAction() {
        $entity = new Application_Model_User($this->user);
        $date = date('Y-m');
        $newacc = $entity->selectNewAccounts($date);

        $this->view->accounts = count($newacc);

        $this->view->accountsCreated = $newacc;
    }
    
    public function ajaxnewaccountAction() {
        $entity = new Application_Model_User($this->user);
        
        if ($this->getRequest()->isPost()) {
            
            $yearmonth = $this->request->getParam('yearmonth');
            
            $datein = date($yearmonth);
            
            $newacc1 = $entity->selectNewAccounts($datein);
            
            $count = count($newacc1);
            
            echo json_encode($newacc1);
//             echo $datein;
        }
        exit;
    }

    public function sendemainewuserAction() {
        if ($this->getRequest()->isXmlHttpRequest()) {
            if ($this->getRequest()->isPost()) {
                $to = $this->request->getParam('to');
                $subject = $this->request->getParam('subject');
                $from = $this->request->getParam('from');
                $msgbody = $this->request->getParam('msgbody');

                if ($to != "" && $from != "" && $msgbody != "")
                    $mail = new Zend_Mail();
                $mail->setSubject($subject);
                $mail->setFrom($from, 'Textmunication Admin');
                $mail->addTo($to, '');
                $mail->setBodyText($msgbody);
                $mail->send();
                echo "true";
                exit;
            }
        }
    }

}

