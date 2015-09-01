<?php

class TestingController extends AbstractController {
    
    public function indexAction() {
        $k = new Application_Model_Keyword();
        $k->handleAction('me');
    }


}

