<?php

/**
 * Form for a forgotten user password.
 * 
 * @extends Zend_Form
 */
class Application_Form_Forgotpassword extends Zend_Form {
    
    public function init() {
        $email = $this->addElement('text', 'email', array(
            'filters'    => array('StringTrim', 'StringToLower'),
            'required'   => true,
            'label'      => 'Email:',
        ));

        $reset = $this->addElement('submit', 'reset', array(
            'required' => false,
            'ignore'   => true,
            'label'    => 'Reset Password',
        ));
        
        // LOL at Decorators... wtf. How are you supposed to use these?
        /*
        $this->clearDecorators();
        
        $this->setElementDecorators(array(
            'viewHelper',
            'label',
            array('htmlTag', array('tag' => 'div', 'class' => 'field'))
            //array(array('data' => 'HtmlTag'), array('tag' => 'span', 'class' => 'field')),
        ));
        
        $this->addDecorator('formElements');
        
        */
    }
}

