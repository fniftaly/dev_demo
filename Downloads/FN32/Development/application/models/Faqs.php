<?php
/**
 * Application_Model_Faqs class.
 * 
 * Manage Frequently Asked Questions Content
 * 
 * @extends Application_Model_Abstract
 */
class Application_Model_Faqs extends Application_Model_Abstract {
    /**
     * Retrieves all FAQs.
     * 
     * @access public
     * @return array
     */
	// protected $_name = 'Application_Model_Faqs';
	
	public function getListviewData() {
    	$sql = "SELECT f.id,f.category,f.faq_question,f.faq_answer FROM faqs f ORDER BY f.id ";
    	$rs  = $this->query($sql);
    	if ($this->hasError()) {
    		$this->setError('Could not retrieve FAQs');
    		return false;
    	}
    	return $rs->fetchAll();
    }
    
	public function getFaq() {
    	$sql = "SELECT f.id,f.category,f.faq_question,f.faq_answer, f.marked, f.accestime FROM faqs f ORDER BY f.accestime desc ";
    	$rs  = $this->query($sql);
    	if ($this->hasError()) {
    		$this->setError('Could not retrieve FAQs');
    		return false;
    	}
    	return $rs->fetchAll();
    }
    
	 public function getformDetails($faqid){
        if ($faqid) { 
            $sql = "SELECT * FROM faqs WHERE id = '".$faqid."' ";
            $rs  = $this->query($sql);
            if ($rs->hasRecords()) {
                    return $rs->fetchAll();
            }else
                return NULL;
		}
		return NULL;        
    }   
	
    public function updateFormValues($faqid,$faqArray){ 
       
		if ($faqid!=null AND $faqArray!=null) { 
            $sql = "UPDATE faqs SET category 	  = '".$faqArray['category']."',
									faq_question  = '".mysql_escape_string($faqArray['faq_question'])."',
									faq_answer    = '".mysql_escape_string($faqArray['faq_answer'])."',
									modified_date = now(),
									modified_by   = '".$faqArray['modified_by']."' WHERE id = '".$faqid."' ";
            $rs  = $this->query($sql);
            if ($rs) {
                   return true;
            }else
                return false;
		}
		return false;        
    }
    /**
     * Update a folder meta data.
     *
     * @param 
     * @return bool Status of the update
     */
    public function update($id,$title,$content,$active) {
        $title   = $this->_dbh->real_escape_string($title);
        $content = $this->_dbh->real_escape_string($content);
        
        if (empty($title)) {
            $this->setError('Title is required');
            return false;
        }
        if (empty($content)) {
            $this->setError('Content is required');
            return false;
        }
        
        $sql = "CALL faq_update({$id},'{$title}','{$content}',{$active})";
        $rs = $this->query($sql);
        
        if ($this->hasError()) {
           $this->setError("Could not update FAQ", $this->getError());
           return false;
        }
        
        return true;
    }
    
    /**
     * Insert a new FAQ entry
     *
     * @return bool
     */
    public function add($category,$questions,$answer,$userid) {
    	
    	
    	//echo $questions.'----'.$answer; die;
    	
        if (empty($category)) {
            $this->setError('Category is required');
            return false;
        }
        if (empty($questions)) {
            $this->setError('Question is required');
            return false;
        }
		if (empty($answer)) {
            $this->setError('Answer is required');
            return false;
        }
        
        $sql = "INSERT INTO faqs SET category 		= '".$category."', 
									 faq_question 	= '".mysql_escape_string($questions)."',
									 faq_answer 	= '".mysql_escape_string($answer)."',
									 created_date 	= now(),
									 modified_date 	= now(),
									 created_by 	= '".$userid."',
									 modified_by    = '".$userid."' ";
        //echo $sql; die;
		$rs = $this->query($sql);		
		if ($this->hasError()) {
           $this->setError("Could not update FAQ", $this->getError());
           return false;
        }
        return true;
    }
    public function deletefaqById($faqid){
        $sql = " DELETE FROM faqs WHERE id = '".$faqid."' ";
        $rs  = $this->query($sql);
        if ($this->hasError()) { 
            return FALSE;
        }else{
            return TRUE;
        }
    }
    
    public function fetchCategoryNameList($id)  
    {
    	$resSet = $this->fetchCategoryItems($id);
    	$catList = array();
    	foreach($resSet as $rData) {
    		$catList[$rData["value"]] = $rData["displaytext"];    		
    	}
    	return $catList;
    }
    public function fetchCategoryItems($id) 
	{
	   	$sql = "SELECT value,displaytext FROM dropdown_config_items WHERE ddconfigid = '".$id."'";
    	$rs  = $this->query($sql);
    	if ($this->hasError()) {
    		$this->setError('Could not retrieve FAQs');
    		return false;
    	}
    	return $rs->fetchAll();
    }
    
     public function faqAccessUpdate($id) 
	{
         $date = time();
	$sql = "update faqs set accestime =$date where id = $id";
    	$rs  = $this->query($sql);
    	if ($this->hasError()) {
    		$this->setError('Could not update access time FAQs');
    		return false;
    	}
    	return true;
    }
}