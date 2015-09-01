<?php

class Application_Form_Login extends Zend_Form
{
    // TODO: MODIFY THE FORM ELEMENTS TO FIT THE LAYOUTS CSS, OR... 
    // I SHOULD PROBABLY DO IT THE RIGHT WAY AND MODIFY THE CSS TO 
    // FIT THE FORM ELEMENTS...
    public function init()
    {
        $username = $this->addElement('text', 'username', array(
            'filters'    => array('StringTrim', 'StringToLower'),
            'required'   => true,
            'label'      => 'Username:',
        ));

        $password = $this->addElement('password', 'password', array(
            'filters'    => array('StringTrim'),
            'required'   => true,
            'label'      => 'Password:',
        ));

        $login = $this->addElement('submit', 'login', array(
            'required' => false,
            'ignore'   => true,
            'label'    => 'Login',
        ));

        // We want to display a 'failed authentication' message if necessary;
        // we'll do that with the form 'description', so we need to add that
        // decorator.
        $this->setDecorators(array(
            'FormElements',
            array('HtmlTag', array('tag' => 'dl', 'class' => 'zend_form')),
            array('Description', array('placement' => 'prepend')),
            'Form'
        ));
    }


}

