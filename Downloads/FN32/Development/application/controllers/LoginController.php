<?php

/**
 * LoginController class.
 * 
 * @extends AbstractController
 */
class LoginController extends AbstractController {
    
    /**
     * Name of the remember me cookie
     * 
     * @var string
     * @access private
     */
    private $remembermecookie = 'rememberme';
    
    /**
     * # of days to have the rememberme cookie live
     * 
     * @var mixed
     * @access private
     */
    private $remembermeexpire = 30;
    
    /**
     * Returns the login form object.
     * 
     * @access public
     * @return void
     */
    public function getLoginForm() {
        return new Application_Form_Login(array(
            'action' => '/login/process',
            'method' => 'post',
        ));
    }
    
    /**
     * Returns the forgot password form object.
     * 
     * @access public
     * @return Application_Form_Forgotpassword
     */
    public function getForgotPassForm() {
        return new Application_Form_Forgotpassword(array(
            'action' => '/login/forgotpassword',
            'method' => 'post',
        ));
    }
    
    /**
     * Returns the reset password form object.
     * 
     * @access public
     * @return Application_Form_Resetpassword
     */
    public function getResetPassForm() {
        return new Application_Form_Resetpassword(array(
            'action' => '/login/forgotpassword',
            'method' => 'post',
        ));
    }
    
    public function init() {
        // See if the rememberme cookie is set
        $this->view->username   = isset($_COOKIE[$this->remembermecookie]) ? $_COOKIE[$this->remembermecookie] : null;
        $this->view->rememberme = isset($_COOKIE[$this->remembermecookie]);
	}
    
    
    /**
     * Basic login page to the admin backend.
     * 
     * @access public
     */
    public function indexAction() {
        // Get the login form
        $this->view->form = $this->getLoginForm();
        // Set any login errors into the view
        $this->view->loginerror = $this->_getParam('loginerror');
    }
    
    public function maintenanceAction() {
        // If we are not in maintenance mode, send the user to login.
        if (!$this->maintenancemode) {
            $this->_redirect('/login/');
        }
    }
    
    /**
     * Send the user their password if they forgot it.
     * 
     * @access public
     */
    public function forgotpasswordAction() {
        // Defaults
    	$message = null;
	    $error   = null;
	    
    	// 1st see if the user is coming in with a reset code
    	if (($r = $this->request->getParam('r'))) {
    		//var_dump($r); die;
    		
    		$form = $this->getResetpassForm();
    		
    		
    	} else {
    		
	        $form = $this->getForgotPassForm();
	        
	        if ($this->request->isPost()) {
	            if ($form->isValid($this->request->getPost())) {
	                
	                $email = $form->getValue('email');
	                
	                // check for a valid email in our system
	                if ($this->user->validEmail($email)) {
	                    // send the reset email
	                    $mail = new Zend_Mail();
					    $mail->setBodyText('There was a request to reset your password. Our automated password reset function is currently unavailable while our server is under scheduled maintenance Please call (800) 677-7003 and we will reset your password.');
					    $mail->setFrom('no-reply@textmunication.com', 'Textmunication');
					    $mail->addTo($email);
					    $mail->setSubject('Textmunication Password Reset');
					    $sent = $mail->send();
	                    
	                    if ($sent) {
	                        // set a message to the user that is was sent
			                $message = 'A password reset email has been sent.';
	                    } else {
	                        $this->view->error = $mail->error;
	                        //$this->view->error = 'For some reason we couldn\'t send you a password reset email. Bummer.';
	                    }
	                } else {
	                    // If the email submit does not belong to us, don't do anything.
	                    $error   = 'The provided email address is not registered in our system.';
	                }
	            } else {
	                $message = $form->getMessages();
	                $error   = $form->getErrors();
	            }
	        }
        }
        
        $this->view->form    = $form;
        $this->view->message = $message;
        $this->view->error   = $error;
    }
    
    /**
     * Allow a user to reset their password.
     * 
     * @access public
     */
   public function resetpasswordAction() {
       if ($this->request->isPost()) {
           $logObj = new Application_Model_Login();
           
          $email = $this->request->getParam('email');
          $isEmail = $logObj->getUserEmail($email);
               if($isEmail)
                   {
                      $msgbody = "To reset your password please click on this link and follow the instructions http://50.57.107.160/resetpassword.php?item=$isEmail";
                        $mail = new Zend_Mail();
                         $mail->setSubject('Reset password');
                         $mail->setFrom('info@textmunication.com', 'Textmunication Admin');
                         $mail->addTo($email, '');
                         $mail->setBodyText($msgbody);
                         $mail->send();
                      echo $isEmail;   
                   }
                 else
                    {echo 0;}
        }
        exit();
    }
    
    public function updatepswdAction(){
        if ($this->request->isPost()) {
           $logObj = new Application_Model_Login();
            $email = $this->request->getParam('newemail');
            $id = $this->request->getParam('userid');
            $ml = $logObj->encryptPassword($email);
            
             if($id && $ml){
                   $rsset = $logObj->updatePassword($id,$ml);
                   if($rsset){
                       echo 'true';
                   }else{
                       echo 'false';
                   }
               }
        }
        exit();
    }// end of updatepswdAction;
    
    /**
     * Process an attempted login request.
     * 
     * @access public
     */
    public function processAction() {
        // Check if we have a POST request
        if (!$this->request->isPost()) {
            return $this->_helper->redirector('index');
        }

        // Get our form and validate it
        $form = $this->getLoginForm();
        if (!$form->isValid($this->request->getPost())) {
            // Invalid entries
            $this->view->form = $form;
            return $this->render('index'); // re-render the login form
        }
        
        // Use our Authenticate model
        $auth        = new Application_Model_Auth;
        $post        = $form->getValues();
        $username    = isset($post['username']) ? $post['username'] : null;
        $password    = isset($post['password']) ? $post['password'] : null;
        
        // Test the credentials
        $result = $auth->authenticate($username, $password);
        
        if (!$result) {
            // Invalid credentials
            $form->setDescription('Invalid username/password combination.');
            $this->view->form = $form;
            return $this->render('index'); // re-render the login form
        }
        // If they want to be remembered, set a cookie with their username
        if (isset($_POST['remember'])) {
            setcookie($this->remembermecookie,$username,strtotime('+'.$this->remembermeexpire.' days'), '/');
        } else {
            // Unset if they previously had it set
            setcookie($this->remembermecookie,null,time() - 3600, '/'); 
	}
        
        // Build the user
		$this->user = new Application_Model_User($result); //echo "<pre>"; print_r($this->user); exit;
        // now register the user model to the registry
		Zend_Registry::set('user', $this->user);
        // Start a session for this user
        $this->session->create($this->user);
        // Redirect to the landing page
        $this->_helper->redirector('index', 'index');
    }
}
