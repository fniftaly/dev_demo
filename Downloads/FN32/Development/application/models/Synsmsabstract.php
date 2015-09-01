<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Synsmsabstract
 *
 * @author farad
 */

abstract class Application_Model_Synsmsabstract extends Application_Model_Abstract {
	/**
	 * The SMS Provider API URL
	 * 
	 * @access protected
	 * @var string
	 */
	protected $_apiUrl =  'https://text.mes.syniverse.com/SMSSend';
	
	/**
	 * The Short Code for our account
	 * 
	 * @access protected
	 * @var string
	 */
	protected $_shortCode = '87365';
	
	/**
	 * The SMS Provider API username
	 * 
	 * @access protected
	 * @var string
	 */
	protected $_username = '4400';
	
	/**
	 * The SMS Provider API password
	 * 
	 * @access protected
	 * @var string
	 */
	protected $_password = 'Fq0^Hc0^';
	
	/**
	 * Gets the shortcode for this account
	 * 
	 * @access public
	 * @return string The shortcode for our account
	 */
	public function getShortCode() {
		return $this->_shortCode;
	}

	/**
	 * Sets the shortcode for our account and for hitting the API with
	 * 
	 * @access public
	 * @param string $shortCode The API account shortcode
	 */
	public function setShortCode($shortCode) {
		$this->_shortCode = $shortCode;
	}
	
	/**
	 * Gets the username for the SMS Provider API
	 * 
	 * @access public
	 * @return string The API username
	 */
	public function getUsername() {
		return $this->_username;
	}

	/**
	 * Gets the username for the SMS Provider API
	 * 
	 * @access public
	 * @param string $username The API username
	 */
	public function setUsername($username) {
		$this->_username = $username;
	}

	/**
	 * WILL NOT GET THE PASSWORD THAT WAS SET
	 *
	 * This is only here for consistency with most models
	 * 
	 * @access public
	 * @return string Always returns null
	 */
	public function getPassword() {
		return null;
	}

	/**
	 * Sets the SMS Provider API password
	 * 
	 * @access public
	 * @param string $password The SMS Provider API password
	 */
	public function setPassword($password) {
		$this->_password = $password;
	}
}
?>
