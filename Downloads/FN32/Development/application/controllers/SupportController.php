<?php
//require_once(CONFIG_PATH."/dropdownconfig.php");
class SupportController extends AuthorizedController 
{
   public function listAction()
   {
		$support = new Application_Model_Support();
		$admin = $this->user->isAdmin(); 
		if($admin == 'on') 
		{
			$value = 'admin';
		}
		else 
		{
			$value =  $this->user->getId(); 
		}
		$supportListArr  = $support->getAllListData($value);
		$this->view->supportListArr = $supportListArr;    
		$this->view->tolsupport = count($supportListArr);
		
		$faqs  = new Application_Model_Faqs();
        $ddl_category_list = $faqs->fetchCategoryNameList(3);		
		$this->view->ddl_category_list = $ddl_category_list;

	}
	public function indexAction()
	{
		return $this->_redirect('support/list');
	}
	public function viewAction()
    {
		
		$support = new Application_Model_Support();
		$viewid = $this->request->getParam('id');
		$viewArr  = $support->getviewData($viewid);
		$viewAttArr = $support->getAttData($viewid);
		$this->view->viewArr = $viewArr;  
		$this->view->viewAttArr = $viewAttArr;
		
		        $faqs  = new Application_Model_Faqs();
        $ddl_category_list = $faqs->fetchCategoryNameList(3);		
		$this->view->ddl_category_list = $ddl_category_list;

	}
	public function uploadAction()
	{
		$path = $_SERVER['DOCUMENT_ROOT']."/uploads/"; // change the path to fit your websites document structure
		$filename = $this->request->getParam('upload_file');
		$fullPath = $path.$filename;
	    if (file_exists($fullPath)) {
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename='.basename($fullPath));
			header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . filesize($fullPath));
			ob_clean();
			flush();
			readfile($fullPath);
			exit;
		}
	}	
	public function addAction()
	{

//
// 		$ddl_support_type = Zend_Registry::get('ddl_support_type'); 		
//		$this->view->ddl_support_type = $ddl_support_type;
		
 		$faqs  = new Application_Model_Faqs();		
		$ddl_support_type = $faqs->fetchCategoryItems(3);
		$this->view->ddl_support_type = $ddl_support_type;
		
		
		$error  = null;
		$status = null;
		$uname = $this->user->username();
		$this->view->uname  = $uname;
		if ($this->request->isPost())
		{
			$support = new Application_Model_Support();
			$userid = $this->user->getId();   
			$supportArray = array();
			$supportArray = Array(
							"name"				=> trim($this->request->name),
							"email"				=> trim($this->request->email),
							"contactnumber"		=> trim($this->request->contactnumber),
							"support_type"		=> trim($this->request->support_type),
							"subject"			=> trim($this->request->subject),
							"status_type"		=> trim($this->request->status_type),
							"message"			=> trim($this->request->message),
							"created_by"		=> $userid,
							);
			//echo '<pre>dgdfgdf';print_r($this->request->getParam('name'));
			//echo '<pre>';print_r($supportArray);
			//echo '<pre>';print_r($_FILES);die;
			if ($support->add($userid,$supportArray)) {
				$status = 'Support Added';
			} else {
				$error =  'Support Error: '.$support->getError().mysql_error(); 
			}
			
			$bodyText = "New Support:\n\r";
			$bodyText .= "Name: ".$this->request->name."\n\r";
			$bodyText .= "Contact Number: ".$this->request->contactnumber."\n\r";
			$bodyText .= "Support Type: ".$this->request->support_type."\n\r";
			$bodyText .= "Subject: ".$this->request->subject."\n\r";
			$bodyText .= "Status: ".$this->request->status_type."\n\r";
			$bodyText .= "Message: ".$this->request->message."\n\r";
			$bodyText .= "Regards\n\r";
			$bodyText .= "Textmunication.com\n\r";
			
			$mail = new Zend_Mail();
            $mail->setBodyText($bodyText);
            $mail->setFrom($this->request->email, $this->request->name);
            $mail->addTo('support@textmunication.com', 'Textmunication.com');
            //$mail->addTo('amudha.muralikannan@jeevantechnologies.com', 'Textmunication.com');
            //$mail->addCc('wais@textmunication.com', 'Wais Asefi');				                    
            $mail->setSubject('New Support'); 
			if(isset($_FILES['uploaded_files']))
			{
					foreach($_FILES['uploaded_files']['name'] as $key => $value)
					{
						if(is_uploaded_file($_FILES['uploaded_files']['tmp_name'][$key]) &&	$_FILES['uploaded_files']['error'][$key] == 0 && $_FILES['uploaded_files']['size'][$key] < 1048576)
						{																																							   
							$filename = $_FILES['uploaded_files']['name'][$key];
							//$id = $this->user->getId();
							//$filetype = $_FILES['uploaded_files']['size'][$key];
							$lastid = $support->supportId($userid);
							//echo '<pre>';print_r($_FILES);die;
							$lastidValue = $lastid[0]['last'] ;
							$filename = $lastidValue.'-'.$filename;  
							
							if(move_uploaded_file($_FILES['uploaded_files']['tmp_name'][$key], '../public/uploads/'. $filename))
							{
								
								$support = new Application_Model_Support();
								//$userid = $this->user->getId();
								$supportattach = $filename;
								if ($support->supportAttach($supportattach,$lastidValue)) {
                                    $at = $mail->createAttachment(file_get_contents('../public/uploads/'.$supportattach));
                            		$at->filename = $supportattach; 									
									$status = 'Support Added';
								} else {
									$error =  'Support Error: '.$support->getError().mysql_error(); 
								}
								
							}else{
								$error = 'The file was not moved.';
							}
						}else{
							$error =  'The file was not uploaded size greater then 1MB.'; 
						}
		
					}
				
		}
		
		if ($mail->send()) {
			
			$status = 'Support Added and mail Sent';
			
		}
		
		$this->view->status = $status;
		$this->view->error  = $error;
		//$action = 'list'; 
		//return $this->_forward('list',null,null,array(null));
		return $this->_redirect('support/list');
	}
   }
    public function delAction()
	{
		$error      = null;
        $message    = null;
		$delid = $this->request->getParam('id');
		$support = new Application_Model_Support(); 
        
		$fileArr = $support->getfileArr($delid);
		$fileCount = count($fileArr);
		//echo $fileCount;die;
		for($i=0;$i<=$fileCount;$i++)
		{
			$dir = '../public/uploads/';
			unlink($dir.$fileArr[$i]['file_name']);
		}
		//echo '<pre>';print_r($fileArr);die;
		
		$support->deleteById($delid);
		if($support)
		{
                 $status =  "Support Deleted successfully";
        }
		else
		{ 
                  $error = "Support Not Deleted";
                  $status = $support->getError();               
        }
		$this->view->error = $error;
		$this->view->status = $status;
		/*$helpArray = array(
								'error'  => $error,
								'status' => $status,
						  );*/
		$action = 'support/list'; 
	    //$this->_helper->redirector->gotoUrl($action,$helpArray);
		//$this->_helper->redirector($action, $error);
	    //return $this->_forward('list',null,null,array(null));
		$this->_redirect('support/list');
	}
	public function statusAction()
	{
		$error      = null;
        $message    = null;
		$stateid = $this->request->getParam('id');
		$support = new Application_Model_Support(); 
        $support->status($stateid);
		if($support)
		{
                 $status =  "Status Updated successfully";
        }
		else
		{ 
                  $error = "Status Not updated";
                  $status = $support->getError();               
        }
		$helpArray = array(
								'error'  => $error,
								'status' => $status,
						  );
		$this->view->error = $error;
		//$e = $this->view->error;
		$this->view->status = $status;
		//$action = 'list'; 
	    //$this->_helper->redirector->gotoUrl($action,$helpArray);
 		//$this->_helper->redirector($action, $e);
	    //return $this->_forward('list',null,null,array(null));
		$this->_redirect('support/list');
	}
}

