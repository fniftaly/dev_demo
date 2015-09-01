<?php
/** 
 * 
 * Abstract controller for all Admin panel controllers to extend.
 * 
 * Sets up the layout theme, session, and hooks into the layout.
 */
abstract class AbstractController extends Zend_Controller_Action {
	/**
	 * Layout Theme to use for this action
	 * 
	 * @var string
	 * @access protected
	 */
	protected $theme = 'default';
	
	/**
	 * Layout object for controllers to set Layout variables
	 * 
	 * @var Zend_Layout
	 * @access protected
	 */
	protected $_layout;
	
	/**
	 * Current page name
	 * 
	 * @var string
	 * @access public
	 */
	public $page;
	
	/**
	 * Current page action
	 * 
	 * @var string
	 * @access public
	 */
	public $action;
	
	/**
	 * Session object
	 * 
	 * @var Application_Model_Session
	 * @access protected
	 */
	protected $session;
	
	/**
	 * The request object for easy access to the controllers.
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $request;
	
	/**
	 * Current session user model
	 * 
	 * @var Application_Model_User
	 * @access public
	 */
	public $user;
    
    /**
	 * Settings model
	 * 
	 * @var Application_Model_Settings
	 * @access protected
	 */
	protected $_settings;
	
	/**
	 * Are we in maintenace mode? This flag is set in the settings table.
	 * 
	 * @var bool
	 * @access public
	 */
	public $maintenancemode;
	
	/**
	 * A few things happen here:
	 *     -Layout is set
	 *     -Page and action vars are set
	 *     -Session Object is started
	 */
	public function preDispatch() {
		// Get the settings from the db.
		// TODO: Expand upon this and use it for theme and whatnot...
		$this->_settings = Zend_Registry::get('settings');
		$this->maintenancemode = $this->_settings->maintenancemode;
        
        // Set the layout
		$this->_helper->layout->setLayout($this->theme);
		
		// Get the Layout object so controllers can easily set variables into it.
		$this->_layout = Zend_Layout::getMvcInstance();
		
		// If this is the login page, set a few things
		if ('login' == strtolower($this->getRequest()->getControllerName())) {
		    // Tell the layout to not show the header, nav, footer, etc...
		    $this->_layout->loginpage = true;
		    // Login specific styles
		    $this->view->headLink()->appendStylesheet('/css/login.css');
        }
        
        // Easy access to where we are.
        $this->page   = strtolower($this->getRequest()->getControllerName());
        $this->action = strtolower($this->getRequest()->getActionName());
        
        // Send the page/action to the layout
        // TODO: Right now I am doing this to include page specific javascript, 
        // but I know ZF offers a way to do this in the controllers, just gotta
        // figure it out.
        $this->_layout->page   = $this->page;
        $this->_layout->action = $this->action;
        
        // Easy access to the request object
        $this->request = $this->getRequest();
        
        // Set the baseurl meta tag value
        $this->_layout->baseUrl = $this->baseUrl();
        
        // Set the company this is running as
        $this->setCompany();
        
        // Fire up the session object.
        $this->startSession();
        
        // Get the current user from the session (if there is one)
        $this->user = $this->session->getUser();
        
        // Set the session user into the layout
        $this->_layout->user = $this->user;
        
        // 
        $this->setDefaultViewValues();
        
        if ($this->maintenancemode && 'maintenance' != $this->action) {
            $this->_redirect('/login/maintenance');
        }
        
	    // If the user is already logged in, make sure they don't see the login page again.
	    $this->noLoginForLoggedIn();
	}
	
	/**
	 * Sets the company variables for the system. This handles the use of 
	 * whitelabels, configuring the site to be branded by another company.
	 * 
	 * TODO: Maybe create a company model that maintians some structure
	 * and organization to the company vars. Right now there are only 3, 
	 * name, logo and email, so I didn't really find it necessary. But as we 
	 * expand that might be a nice thing to have.
	 * 
	 * @access public
	 */
	public function setCompany() {
		$whitelabels = array();
		$whitelabel_config = CONFIG_PATH.'/whitelabels.ini';
		
		// Get the whitelabel ini file
		if (file_exists($whitelabel_config)) {
			$whitelabels = parse_ini_file($whitelabel_config,true);
		}
		
		// 1st set default values
		$this->company = new stdClass;
        $this->company->whitelabel = false;
		$this->company->logo       = 'textmunication.png';
		$this->company->logowidth  = '';
		$this->company->name       = 'Textmunication';
		$this->company->email      = 'support@textmunication.com';
		
		// See if there are company specific variables to handle Whitelabels.
		if (!empty($whitelabels)) {
			// Check the hostname for settings
			$label = $_SERVER['HTTP_HOST'];
			if (array_key_exists($label, $whitelabels)) {
				$this->company->whitelabel = isset($whitelabels[$label]['whitelabel']) ? $whitelabels[$label]['whitelabel'] : true;
				
                if (isset($whitelabels[$label]['logo'])) {
                    $this->company->logo = $whitelabels[$label]['logo'];
                }
                if (isset($whitelabels[$label]['logowidth'])) {
                    $this->company->logowidth = $whitelabels[$label]['logowidth'];
                }
                if (isset($whitelabels[$label]['name'])) {
                    $this->company->name = $whitelabels[$label]['name'];
                }
                if (isset($whitelabels[$label]['email'])) {
                    $this->company->email = $whitelabels[$label]['email'];
                }
			}
			
			// Change the session cookie for this domain
			// TODO: This needs to be looked at, maybe handle setting this differently or just use one domain
			$this->_settings->cookiedomain = $label;
		}
		// Now path the logo file to the proper directory
		$this->company->logo = '/images/logo/' . $this->company->logo;
		
		// Set into layout
		$this->_layout->company = $this->company;
		// Set into view
		$this->view->company = $this->company;
	}
    
    /**
     * Set variables for all views to use here.
     */
    protected function setDefaultViewValues() {
        $this->view->contactphonenumber = $this->_settings->contactphonenumber;
    }
	
	private function isSSL() {
		return !empty($_SERVER['HTTPS']);
	}
	
	private function baseUrl() {
		$return  = $this->isSSL() ? 'https://' : 'http://';
		$return .= $_SERVER['HTTP_HOST'];
		
		return $return;
	}
	
	/**
	 * Get the session going
	 * 
	 * @access private
	 * @return void
	 */
	private function startSession() {
        // Get the session object
        $this->session = Application_Model_Session::getInstance();
        $this->session->setRequestpage($this->page);
		$this->session->setRequestaction($this->action);
		
		// Start it up
		if (!$this->session->start()) {
			die("Session error: {$this->session->error}");
		}
		Zend_Registry::set('session',$this->session);
	}
	
	/**
	 * Prevent a user from seeing the login form if they are already logged in.
	 * 
	 * @access protected
	 * @return void
	 */
	protected function noLoginForLoggedIn() {
		if ($this->session->loggedIn()) {
            // If they are logged in and on the login page, send them to the homepage
            if ('login' == strtolower($this->getRequest()->getControllerName())) {
                $this->_redirect('/');
            }
	    }
	}
}
