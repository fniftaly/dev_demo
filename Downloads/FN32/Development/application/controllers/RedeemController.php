<?php
class RedeemController extends AbstractController {
	/**
	 * Logged in flag tells the view which template to render
	 * 
	 * @var boolean
	 */
	protected $_isLoggedInHere = false;
	
	/**
	 * The redemption code
	 * 
	 * @var string
	 */
	protected $_code;
	
	/**
	 * The phone number
	 * 
	 * @var string
	 */
	protected $_phone; 
	
	protected $_isLoggedInHereCookie = '_trmu';
	protected $_username;
	protected $_loginError = null;
	
	public function indexAction() {
		// Tell the layout to not show the header, nav, footer, etc...
	    $this->_layout->loginpage = true;
	    // Login specific styles
	    $this->view->headLink()->appendStylesheet('/css/login.css');
	    
	    $this->_doLogin();
		
		if (!$this->_isLoggedInHere) {
			$this->view->username = $this->_request->getPost('username');
			$this->view->loginerror = $this->_loginError;
		} else {
			$this->_doRedemption();
		}
    }
    
    /**
     * Handles loggin in of this particular module
     */
    protected function _doLogin() {
    	if (isset($_COOKIE[$this->_isLoggedInHereCookie])) {
    		$this->_username = $_COOKIE[$this->_isLoggedInHereCookie];
    		$this->_isLoggedInHere = true;
    	} else {
    		if ($this->_request->isPost()) {
    			$user = strtolower($this->_request->getPost('username'));
    			$pass = $this->_request->getPost('password');
    			
    			if ($user == 'blackjack' && $pass == 'Black Jack') {
    				setcookie($this->_isLoggedInHereCookie, $user);
    				$this->_username = $user;
    				$this->_isLoggedInHere = true;
    				$this->_redirect('/redeem');
    			} else {
    				$this->_loginError = 'Incorrect username or password.';
    			}
    		}
    	}
    }
    
    /**
     * Does the redemption and sets the view properties
     */
	protected function _doRedemption() {
		// Get our form values
		$this->_code  = $this->_request->getPost('code');
		$this->_phone = $this->_request->getPost('phone');
		
		if ($this->_request->getPost('redeem')) {
			// Redeem them
		    $redeemer = new Application_Model_Redeem();
		    $redeemer->redeem($this->_username);
			
		    // Read the redemption into the view
		    $this->view->redeemsuccess = $redeemer->success;
			$this->view->redeemmessage = $redeemer->message;
    	}
    	
		// Hand it back off
		$this->view->code  = $this->_code;
		$this->view->phone = $this->_phone;
    	
		$this->render('redeem');
    }
}

