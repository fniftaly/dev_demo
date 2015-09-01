<?php
//require_once(CONFIG_PATH."/dropdownconfig.php");
class FaqController extends AuthorizedController {
   
    public function addAction()
	{
 		//$ddl_faq_category = Zend_Registry::get('ddl_faq_category'); 		
 		
 		$faqs  = new Application_Model_Faqs();		
		$ddl_faq_category = $faqs->fetchCategoryItems(1);
		$this->view->ddl_faq_category = $ddl_faq_category;

		if ($this->request->isPost()) {
    		$category   = trim($this->request->category);
    		$questions  = trim($this->request->questions); 
			$answer   	= trim($this->request->answer);
    		$action 	= trim($this->request->reqaction);
    		//echo $questions.'----'.$answer; die;
    		if (empty($category)) {
    			$adderror = 'A name must be provided for the new folder.';
    			return $this->_forward($action,$page,null,array('adderror' => $adderror));
    		}
			$faqs  = new Application_Model_Faqs();
			$userid = $this->user->getId();
			$faqs->add($category,$questions,$answer,$userid);
			return $this->_redirect('faq/index');			
		}			
	}
    
    public function indexAction() 
	{
		$faqs = new Application_Model_Faqs;
        $list = $faqs->getListviewData();        
        $this->view->list = $list;
		$this->view->total = count($list); 
		
		$ddl_category_list = $faqs->fetchCategoryNameList(1);		
		$this->view->ddl_category_list = $ddl_category_list;

	}
	public function editAction()
    {
// 		$ddl_faq_category = Zend_Registry::get('ddl_faq_category'); 		
//		$this->view->ddl_faq_category = $ddl_faq_category;

 		$faqs  = new Application_Model_Faqs();		
		$ddl_faq_category = $faqs->fetchCategoryItems(1);
		$this->view->ddl_faq_category = $ddl_faq_category;


        $faqid = $this->request->getParam('id');
        if($faqid){
            $faqObj = new Application_Model_Faqs();
            $faqdetails = $faqObj->getformDetails($faqid);
            if(count($faqdetails)==0){
                $this->_redirector->gotoUrl('/faq/index/');
            }
			$this->view->category 	  = $faqdetails[0]['category'];
            $this->view->faq_question = $faqdetails[0]['faq_question']; 
            $this->view->faq_answer   = $faqdetails[0]['faq_answer'];
            if($this->request->isPost()){
                        $faqid = $this->request->getParam('id'); 
                        if(!empty($faqid)){
                        $userid = $this->user->getId();
                        $faqArray = array();
							$faqArray = Array(
								"category" 	   => $this->request->getParam('category'),
								"faq_question" => $this->request->getParam('questions'),
								"faq_answer"   => $this->request->getParam('answer'),
								"modified_by"  => $userid,
							);
						$faqUpdate = $faqObj->updateFormValues($faqid,$faqArray);
						$this->_redirect('faq/');		
						}else{
                            $message['type'] = "error";
                            $message['body'] = "Please Enter all Values.";                         
                        }            
                }             
        }else{
                $this->_redirector->gotoUrl('/faq/'); 
        }
    }
     public function deleteAction(){
        $faqid = $this->request->getParam('id');
		if($faqid) { 
			$faqObj = new Application_Model_Faqs();
        	$faqObj->deletefaqById($faqid); 
        }
		$this->_redirect('faq/');		
    }
    public function viewAction() {
    	$faqs = new Application_Model_Faqs;
    	$user = new Application_Model_User($this->user);
    	$ddl_category_list = $faqs->fetchCategoryNameList(1);		
		$this->view->ddl_category_list = $ddl_category_list;
		
        $faqList = $faqs->getFaq();
        $faqInfo = array();
        $fCat = "";
        foreach($faqList as $fK => $fV) {
        	if($fCat != $fV["category"]) {
        		$faqInfo[$fV["category"]][] = $fV;
        	}
        }
        //echo "<pre>";print_r($faqInfo);
        $this->view->faqs  = $faqInfo;
        $this->view->userID = $this->user->getId();
	$this->view->userObj = $user;	
    }
    
   public function fileuploadAction(){
    if ($this->getRequest()->isPost()) {
       $userid = $this->user->getId();
       
       $upload = new Zend_File_Transfer_Adapter_Http();
       $videos = new Application_Model_Videos();
      $docs = array("doc", "pdf", "txt", "img","xls","xlsx");
       $category = $this->request->getParam('category');
       if(in_array($category, $docs)){
           $upload->setDestination($_SERVER['DOCUMENT_ROOT']."/data");
            $fname = $this->request->getParam('ftitle');
            $fextension = "";
            $files = $upload->getFileInfo();
                foreach($files as $fieldname=>$fileinfo)
                {
                if (($upload->isUploaded($fileinfo['name']))&& ($upload->isValid($fileinfo['name'])))
                    {
                    $upload->receive($fileinfo['name']);
                    $fname = $fileinfo['name'];
                    $fextension = pathinfo($fname, PATHINFO_EXTENSION);;
                    }
                }
                $videosArray = Array(
                    "category"			=> $this->request->getParam('category'),
                    "title"		        => $this->request->getParam('ftitle'),
                    "description"	        => $this->request->getParam('fdescription'),
                    "name"		        => $fname,
                    "mark"                      => $this->request->getParam('fmark')
                );
            if ($upload->receive()) { 
                $videos->insertData($userid, $videosArray);
                echo "The file has been uploaded!<>".$fname."===".$fextension;
            }
       } 
       else
           {
            $upload->setDestination($_SERVER['DOCUMENT_ROOT']."/videos");
            $fname = $this->request->getParam('ftitle');
            $fextension = "";
            $files = $upload->getFileInfo();
                foreach($files as $fieldname=>$fileinfo)
                {
                if (($upload->isUploaded($fileinfo['name']))&& ($upload->isValid($fileinfo['name'])))
                    {
                    $upload->receive($fileinfo['name']);
                    $fname = $fileinfo['name'];
                    $fextension = pathinfo($fname, PATHINFO_EXTENSION);;
                    }
                }
                $videosArray = Array(
                    "category"			=> $this->request->getParam('category'),
                    "video_title"		=> $this->request->getParam('ftitle'),
                    "video_description"	        => $this->request->getParam('fdescription'),
                    "video_name"		=> $fname,
                    "modified_by"		=> $userid,
                );
            if ($upload->receive()) { 
                $videos->add($userid, $videosArray);
                echo "The file has been uploaded!<>".$fname."===".$fextension;
            }
       } //end of else  
     }
/*  
 * how to reaname uploaded file name
 * $rename = new Zend_Filter_File_Rename(array(
        'target'    =>$upf_name,
        'overwrite' => true
      ));
     $upload->addFilter($rename);*/
     
    }// end of marketingandtoolsAction
    
     public function datalistAction()
    {
        $user = new Application_Model_User($this->user);
//        $dickSubaccounts = $user->getUserSubaccounts(1174);
        $videos = new Application_Model_Videos();
        $this->view->data = $videos->getPdfs();
        $this->view->userID = $this->user->getId();
//        $this->view->dickSubusers = $dickSubaccounts;
//        $this->view->prUsers = array('1174');
        $this->view->userObj = $user;
    }// end of datalistAction
    
    public function faqaccesAction(){
        $faqs = new Application_Model_Faqs();
        if ($this->getRequest()->isPost()) {
           $faqid = $this->request->getParam('faq');
          $faqs->faqAccessUpdate($faqid);
        }
        exit;
    }
   
}

