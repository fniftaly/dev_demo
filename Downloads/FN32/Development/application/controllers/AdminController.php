<?php

class AdminController extends AuthorizedController {
    /**
     * This page is only accessable by admins.
     * 
     * @access public
     */
    public function preDispatch() {
        parent::preDispatch();
        
        // See if we have a logged in user.
        if (!$this->user->isAdmin()) {
            $this->_redirect('/');
        }
    }
    
    public function indexAction() {
       
    }
    
    public function billingAction() {
        
    }
    
    public function importAction() {
        
    }
    
    public function FaqAction() {
        // Defaults
        $error  = null;
        $status = null;
        
        $faqs = new Application_Model_Faqs;
        
        $list = $faqs->get();
        
        if ($this->request->isPost()) {
            // see what kind of action was taken
            $action  = $this->request->action;
            $id      = trim($this->request->id);
            $title   = trim($this->request->title);
            $content = trim($this->request->content);
            $active  = (bool) trim($this->request->active);
            
            switch ($action) {
                case 'Update':
                    if ($faqs->update($id,$title,$content,$active)) {
                        $status = true;
                    } else {
                        $error = $faqs->getError();
                    }
                    break;
                case 'Add':
                    if ($faqs->add($title,$content,$active)) {
                        $status = true;
                    } else {
                        $error = $faqs->getError();
                    }
                    break;
                default:
                    $error = 'Request not recognized, please try again.';
            }
        }
        
        $this->view->error  = $error;
        $this->view->status = $status;
        $this->view->list   = $list;
    }
}

