<?php
/**
 * I'm sure there's a way to make this work without this but the truth is this 
 * is just easier
 * 
 * Include the abstract controller that all API controllers extend
 */
require_once 'api/controllers/ControllerAbstract.php';
require_once 'controllers/AbstractController.php';
require_once 'controllers/AuthorizedController.php';
require_once 'models/Abstract.php';
require_once 'models/Entityabstract.php';

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {
	/**
	 * Register the configuration file
	 */
	protected function _initConfig() {
    	// This will register the config as a Zend_Config Object
    	$config = new Zend_Config($this->getOptions(), true);
    	Zend_Registry::set('config', $config);
	}
	
	/**
	 * Add the API Controllers
	 */
	protected function _initFrontControllers() {
		$this->bootstrap('frontController');
		$front = $this->getResource('frontController');
		$front->addControllerDirectory(APPLICATION_PATH . '/api/controllers', 'api');
	}
	
	/**
	 * Configure the Rest Router for API requests
	 */
	protected function _initRestRoute() {
		// Configure the Hostname Route for the api subdomain
		$op = $this->getOptions();
		$hostnameRoute = new Zend_Controller_Router_Route_Hostname($op['api']['domain']);
		
		// Configure the REST Route to use the api module
		$front     = Zend_Controller_Front::getInstance();
		$pathRoute = new Zend_Rest_Route($front, array('module' => 'api'));
		
		// Chain the REST and Hostname routes
		$chainedRoute = new Zend_Controller_Router_Route_Chain();
		$chainedRoute->chain($hostnameRoute)
		             ->chain($pathRoute);
		
		// Add the route to the REST controller using a Hostname Router
		$front->getRouter()->addRoute('api', $chainedRoute);
	}
    
    protected function _initSettings() {
        $settings = Application_Model_Settings::getInstance();
        Zend_Registry::set('settings', $settings);
    }
}

