<?php

class TermsController extends AbstractController {
    
    public function preDispatch() {
        parent::preDispatch();
        
		$this->_helper->layout()->disableLayout();
    }
    
    public function indexAction() {
		$this->view->companyname = '{{COMPANY NAME}}';
        $this->view->contestname = '{{CONTEST NAME}}';
        $this->view->shortcode   = 87365;
        $this->view->keyword     = '{{KEYWORD}}';
        $this->view->starttime   = '{{STARTTIME}}';
        $this->view->endtime     = '{{ENDTIME}}';
    }
}

