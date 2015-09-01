<?php
/**
 * Form to reset a user password.
 * 
 * @extends Zend_Form
 */
class Application_Form_Resetpassword extends Zend_Form {
    
    public function init() {
        $email = $this->addElement('text', 'email', array(
            'filters'    => array('StringTrim', 'StringToLower'),
            'required'   => true,
            'label'      => 'Email:',
        ));
		
		$new_pass = $this->addElement('password', 'new_pass', array(
            'filters'    => array('StringTrim'),
            'required'   => true,
            'label'      => 'New Password:',
        ));
        
        $new_pass_confirm = $this->addElement('password', 'new_pass_confirm', array(
            'filters'    => array('StringTrim'),
            'required'   => true,
            'label'      => 'Confirm Password:',
        ));
        
        $rtoken = $this->addElement('hidden', 'rtoken', array(
            'filters'    => array('StringTrim'),
            'required'   => true,
        ));
		
        $reset = $this->addElement('submit', 'reset', array(
            'required' => false,
            'ignore'   => true,
            'label'    => 'Reset Password',
        ));
    }
}

