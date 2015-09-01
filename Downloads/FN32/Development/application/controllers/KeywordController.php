<?php

class KeywordController extends AuthorizedController {
    
    public function indexAction() {
       
    }
    
    public function editAction() {
    	// Defaults
      $error      = null;
        $return     = array();
        $new_folder = null;
        $folder     = null;
        $canedit    = false;
        $message    = null;
        
        // Get the folder we are editing
        $id = $this->request->getParam('id');
        
        $keyword = new Application_Model_Keyword($id);
        
        $folderid = $keyword->folderid;
        
        // Make sure this user owns this keyword
        if ($this->user->getId() == $keyword->createuser) {
            $canedit = true;
            
            if ($this->request->isPost()) {
                // Get new values
                $word     = $this->request->getParam('word');
                $msg_head = $this->request->getParam('msg_head');
                $msg_body = $this->request->getParam('msg_body');
                // Only admins can edit the footer
                $msg_foot = $this->user->isAdmin() ? $this->request->getParam('msg_foot') : $keyword->replyfooter;
                $folderid = trim($this->request->folder);
                
                // See if they are creating a new folder for this keyword
                if ($folderid == '0') {
                    // now make sure they named the new folder
                    $new_folder = trim($this->request->getParam('new_folder'));
                    
                    if ($new_folder) {
                        $folder = new Application_Model_Folder($this->user);
                        
                        // Set up the Folder meta data
                        $meta         = array();
                        $meta['name'] = $new_folder;
                        
                        $success = $folder->addWithMeta($meta);
                        
                        if ($success) {
                            $folderid = $folder->getId();
                        } else {
                            $error = $folder->getError();
                        }
                    } else {
                        $error = 'A folder name must be provided to add a new folder.';
                    }
                }
                
                // if there was an error don't try and edit the keyword
                if (!$error) {
                    $keyword->setKeyword($word);
                    $keyword->setFolderId($folderid);
                    $keyword->setReplyheader($msg_head);
                    $keyword->setReplybody($msg_body);
                    $keyword->setReplyfooter($msg_foot);
                    // now try and save it
                    if ($keyword->save()) {
                        $message = 'Keyword Edited!';
                    } else {
                        $error =  'Keyword error: '.$keyword->getError(); 
                    }
                }
            }
        } else {
            $error = 'Keyword does not belong to this user. Can not edit.';
        }
    	
    	// View variables
        $this->view->canedit    = $canedit;
    	$this->view->keyword    = $keyword;
    	$this->view->error      = $error;
    	$this->view->message    = $message;
    	$this->view->new_folder = $new_folder;
    	$this->view->folderid   = $folderid;
    	$this->view->folders    = $this->user->getFolders();
    	
    	// For the sidebar
    	$this->view->keywords = $this->user->getKeywords();
         
    }
}

