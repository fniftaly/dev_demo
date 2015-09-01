<?php

/**
 * Basic controller to log a user out, end their session, and send them to the login page.
 * 
 * @extends AbstractController
 */
class LogoutController extends AbstractController {
    
    /**
     * Process a logout request.
     * 
     * @access public
     */
    public function indexAction() {
        // End the user session
        $this->session->end();
        // To login page
        $this->_helper->redirector('index', 'login');
    }
}
