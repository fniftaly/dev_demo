<?php
/** 
 * @author Jason Memory
 * 
 * Any controller that wants to be behind a logged in session can extend this class.
 */
abstract class AuthorizedController extends AbstractController {
	
	
	/**
	 * Before the page dispatch happens, check for a logged in user.
	 * 
	 * @access public
	 */
	public function preDispatch() {
		parent::preDispatch();
        
        // See if we have a logged in user.
	    $this->checkLogin();
	}
	
	
	
	/**
	 * Check for a logged in session.
	 * 
	 * Non-logged in users will be redirected to the login page.
	 * 
	 * @access private
	 * @return void
	 */
	private function checkLogin() { 
	    // Check for a logged in session
	    /*if ($this->session->loggedIn()) {
            // If they are logged in and on the login page, send them to the homepage
            if ('login' == strtolower($this->getRequest()->getControllerName())) {
                $this->_forward('index', 'index');
            }
	    } else {
	    */
	    // If the user is already logged in, make sure they don't see the login page again.
	    //$this->noLoginForLoggedIn();
        
        if($this->getRequest()->getControllerName()=='form' AND $this->getRequest()->getActionName()=='link')  //this is a hack to allow non-user to see webform
             $isLoggedin = true;   
        else
             $isLoggedin = $this->session->loggedIn();
         
	    if (!$isLoggedin) {
    	    // They are not logged in, send them to the login page.
    	    if ('login' != strtolower($this->getRequest()->getControllerName())) {
                $this->_forward('index', 'login', null, array('loginerror' => 'You must be logged in to view this page.'));
            }
        }
	}
	
}
