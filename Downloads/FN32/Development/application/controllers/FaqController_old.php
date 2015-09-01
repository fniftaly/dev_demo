<?php

class FaqController extends AuthorizedController {
    
    public function indexAction() {
        $faqs = new Application_Model_Faqs;
        
        $list = $faqs->get();
        
        $this->view->list = $list;
    }
    public function datalistAction()
    {
        $videos = new Application_Model_Videos();
    //    echo '<pre>'; print_r($videos->getPdfs());
    //    exit();
        $this->view->data = $videos->getPdfs();
    }// end of datalistAction
}

