<?php
//require_once(CONFIG_PATH."/dropdownconfig.php");
class VideosController extends AuthorizedController {

	public function indexAction()
	{
		$videos = new Application_Model_Videos();  
        $videosListArray  = $videos->getAllListData();
		$this->view->videosListArray = $videosListArray; 
		$this->view->list = $videosListArray;   
        $this->view->totalvideos = count($videosListArray); 
        
        $faqs  = new Application_Model_Faqs();
        $ddl_category_list = $faqs->fetchCategoryNameList(2);		
		$this->view->ddl_category_list = $ddl_category_list;
		
    }

	public function editAction()
	{
		
//		$ddl_video_category = Zend_Registry::get('ddl_video_category'); 		
//		$this->view->ddl_video_category = $ddl_video_category;

 		$faqs  = new Application_Model_Faqs();		
		$ddl_video_category = $faqs->fetchCategoryItems(2);
		$this->view->ddl_video_category = $ddl_video_category;
		
	$id = $this->request->getParam('id'); 
		if($id)
			{
			$videosObj = new Application_Model_Videos();
			$videoDetail = $videosObj->editListData($id);
				if(count($videoDetail)==0)
				{
				return $this->_redirect('videos/index');
				}
			
			$userid 				  		= $videoDetail[0]['created_by'];
			$this->view->category 	  		= $videoDetail[0]['category'];
			$this->view->video_title 		= $videoDetail[0]['video_title']; 
			$this->view->video_description  = $videoDetail[0]['video_description'];
			$this->view->video_name  		= $videoDetail[0]['video_name'];
			$this->view->id  				= $videoDetail[0]['id'];
			}
			if($this->request->isPost())
			{	
			$videos = new Application_Model_Videos();
			$userid = $this->user->getId();   
			$actiontype = $this->request->getParam('actiontype');
			$error = "";	
				if ($actiontype == 'upload') {
					$target_path = '../public/videos/';
					
					//echo '<pre>'; print_r($_REQUEST);
					// echo '<pre>'; print_r($_FILES); die;
					
					
					if($_FILES['video_name']['tmp_name'] =='') {
						$filename = $this->request->getParam('video_name_hidden');
					}
					else {
						$filename = $this->request->getParam('category').'-'.$_FILES['video_name']['name'];
						$target_path = $target_path . basename($filename);
					}
					
					
					$videosArray = array();
					$videosArray = Array(
										"category"			=> $this->request->getParam('category'),
										"video_title"		=> $this->request->getParam('video_title'),
										"video_description"	=> $this->request->getParam('video_description'),
										"video_name"		=> $filename,
										"modified_by"		=> $userid,
										);
					
					if($_FILES['video_name']['tmp_name']!='') {						
						if($_FILES['video_name']['type']== 'video/mp4') {
							if(move_uploaded_file($_FILES['video_name']['tmp_name'], $target_path)) {
								 if ($videos->edit($id,$videosArray)) {
									 $message = 'Video Added.';  
		        				 }else{
								 	$error =  'Video error: '.$videos->getError(); 
	             				 }
							} 
						}
						else {
							$error = 'Video format not supported..!';  
						}	
					} else { 		$faqs  = new Application_Model_Faqs();		
		$ddl_video_category = $faqs->fetchCategoryItems(2);
		$this->view->ddl_video_category = $ddl_video_category;

						if ($videos->edit($id,$videosArray)) {
								 $message = 'Video Added.';  
	        			}else{
							 	$error =  'Video error: '.$videos->getError(); 
             			}
					}
				
				if(empty($error))
					return $this->_redirect('videos/index');			 
				else {
					$this->view->error  = $error;
					$this->view->video_title  = $this->request->getParam('video_title');
					$this->view->video_description  = $this->request->getParam('video_description');
					$this->view->category  = $this->request->getParam('category');
					
				}					
			//return $this->_redirect('videos/index');
		} /* else{
               return $this->_redirect('videos/index');
        } */
	}}
	public function addAction() 
	{
		
//		$ddl_video_category = Zend_Registry::get('ddl_video_category'); 		
//		$this->view->ddl_video_category = $ddl_video_category;

 		$faqs  = new Application_Model_Faqs();		
		$ddl_video_category = $faqs->fetchCategoryItems(2);
		$this->view->ddl_video_category = $ddl_video_category;


		if ($this->request->isPost()) {
		
			$videos = new Application_Model_Videos();
			$userid = $this->user->getId();   
			
			$actiontype = $this->request->getParam('actiontype');
				if ($actiontype == 'upload') {
				
					$target_path = '../public/videos/';
					$id = $this->user->getId();
					$filename = $this->request->getParam('category').'-'.$_FILES['video_name']['name'];
					$target_path = $target_path . basename($filename); 
					
					$videosArray = array();
					$videosArray = Array(
								"category"			=> $this->request->getParam('category'),
								"video_title"		=> $this->request->getParam('video_title'),
								"video_description"	=> $this->request->getParam('video_description'),
								"video_name"		=> $filename,
								"modified_by"		=> $userid,
								);
				$error = "";
				if($_FILES['video_name']['type']== 'video/mp4') {			
					if(move_uploaded_file($_FILES['video_name']['tmp_name'], $target_path)) {
							 if ($videos->add($userid,$videosArray)) {
	                  			 $message = 'Video Added.';
	        				 }else{
                       			 $error =  'Video error: '.$videos->getError(); 
             				 }
					} else{
							$error = 'There was an error uploading the file, please try again!';
					}
				} else {
					$error = 'Video format not supported..!';  
				}	
			}
			if(empty($error))
				return $this->_redirect('videos/index');			 
			else {
				$this->view->error  = $error;
				$this->view->video_title  = $this->request->getParam('video_title');
				$this->view->video_description  = $this->request->getParam('video_description');
				$this->view->category  = $this->request->getParam('category');
				
			}
		}	
	}
	public function deleteAction()
	{
		$error      = null;
        $message    = null;
		$delid = $this->request->getParam('id');
		$videosObj = new Application_Model_Videos(); 
        $videosid = $this->request->getParam('id');
		$fileArr = $videosObj->delfile($videosid);
		$fileCount = count($fileArr);
		for($i=0;$i<=$fileCount;$i++)
		{
		$dir = '../public/videos/';
		unlink($dir.$fileArr[$i]['video_name']);
		}
		if($videosid) { 
        	$videosObj->delete($videosid); 
        }
		$this->_redirect('videos/index');		
	} 
	public function viewAction() {
    	$videos = new Application_Model_Videos;
        $videoList = $videos->getVideos();
        $videoInfo = array();
        $videoCat = "";
        foreach($videoList as $fK => $fV) {
        	if($videoCat != $fV["category"]) {
        		$videoInfo[$fV["category"]][] = $fV;
        	}
        }
        //echo "<pre>";print_r($faqInfo);
		$this->view->videos  = $videoInfo; 

        $faqs  = new Application_Model_Faqs();
        $ddl_category_list = $faqs->fetchCategoryNameList(2);		
		$this->view->ddl_category_list = $ddl_category_list;
 
		
    }
	public function getviewAction() {
		$this->_helper->layout->disableLayout();
		$vname = $this->request->getParam('videoname');
		// $this->_helper->layout->setLayout('ajaxlayout');
		$this->view->vname = $vname;
   }

}
?>
