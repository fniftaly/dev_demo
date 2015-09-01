<?php
class Application_Model_Videos extends Application_Model_abstract {
    private $category;
	private $video_title;
	private $video_description;
	private $video_name;
	private $created_date;
	private $modified_date;
	private $created_by;
	private $modified_by;
    
	public function getAllListData() {
            $sql = " SELECT v.id,v.category,v.video_title
          			 FROM videos as v
           			 ORDER BY v.id "; 
            $rs  = $this->query($sql);
			
			if ($rs->hasRecords()) {
					return $rs->fetchAll();
            }
			 $this->videos = $rs->fetchAll();
	
    }       
    public function editListData($id)
	{
		
        if ($id) { 
            $sql = "SELECT * FROM videos WHERE id = '".$id."' ";
            $rs  = $this->query($sql);
            if ($rs->hasRecords()) {
                    return $rs->fetchAll();
            }
		else
		{
		return NULL;
		}
		}
		return NULL;        
    }
   
	public function add($userid,$videosArray) {
		
		if (empty($videosArray['video_title'])) {
            $this->setError('Content is required');
            return false;
        }
		if (empty($videosArray['video_description'])) {
            $this->setError('Title is required');
            return false;
        }
        if (empty($videosArray['video_name'])) {
            $this->setError('Content is required');
            return false;
        }
        $sql = " INSERT INTO videos SET category = '".$videosArray['category']."', 
                video_title 		= '".mysql_escape_string($videosArray['video_title'])."', 
                video_description 	= '".mysql_escape_string($videosArray['video_description'])."', 
                video_name 			= '".mysql_escape_string($videosArray['video_name'])."', 
                created_date 		= now(),
                modified_date 		= now(),
                created_by 			= '".$userid."', 
                modified_by 		= '".$userid."' ";
        $rs = $this->query($sql);
        if ($this->hasError()) {
           $this->setError("Could not Add Video", $this->getError());
           return false;
        }
        return true;
    }
	
	public function edit($id,$videosArray)
	{
		if ($id!=null AND $videosArray!=null) { 
             $sql = " UPDATE videos SET category = '".$videosArray['category']."', 
                video_title 		= '".mysql_escape_string($videosArray['video_title'])."', 
                video_description 	= '".mysql_escape_string($videosArray['video_description'])."', 
                video_name 			= '".mysql_escape_string($videosArray['video_name'])."',
                modified_date 		= now() WHERE 
                id 					= '".$id."' "; 
            $rs  = $this->query($sql);
            if ($rs) {
				return true;
            }
			else
                return false;
		}
		return false;
	}
	
	    public function delete($videosid)
		{
        $sql = " DELETE FROM videos WHERE id = '".$videosid."' ";
        $rs  = $this->query($sql);
        if ($this->hasError()) { 
            return FALSE;
        }else{
            return TRUE;
        }
    }
	public function getVideos() {
            $sql = " SELECT v.id,v.category,v.video_title,v.video_name,v.video_description 
          			 FROM videos as v
           			 ORDER BY v.category "; 
            $rs  = $this->query($sql);
			
			if ($rs->hasRecords()) {
					return $rs->fetchAll();
            }
			// $this->videos = $rs->fetchAll();
	
    }
	public function delfile($videosid)
	{
		$sql = "SELECT video_name FROM videos WHERE id = '".$videosid."' ";
		$rs = $this->query($sql);
		if ($rs->hasRecords()) 
			{
				return $rs->fetchAll();
				
			}
		$this->support = $rs->fetchAll();
	
	} 
        
        public function confirmData($business,$timestamp,$campaign,$clentnumber,$phone,$appointmentDate,$sent='n',$read='n',$confirmed='1') {
//           $sql = "CALL insert_prosalon('$business',$timestamp,'$campaign',$clentnumber,$phone,'$sent','$read','$confirmed',$appointmentDate)";
        $sql = sprintf("CALL insert_prosalon('%s',$timestamp,'%s',$clentnumber,'%s',$appointmentDate,'%s','%s','%s')",
                    $this->escape($business),
                    $this->escape($campaign),
                    $this->escape($phone),
                    $this->escape($sent),
                    $this->escape($read),
                    $this->escape($confirmed)
                );
           $rs = $this->query($sql);
        if ($this->hasError()) {
           $this->setError("Could not edit data to prosalon tb", $this->getError());
           return false;
        }
        return true;
        } // end of confirmData
        
    public function insertData($userid,$videosArray) {

        $sql = " INSERT INTO data SET category = '".$videosArray['category']."', 
                    title 		= '".mysql_escape_string($videosArray['title'])."', 
                    description 	= '".mysql_escape_string($videosArray['description'])."', 
                    name 	        = '".mysql_escape_string($videosArray['name'])."',
                    marked              = '".$videosArray['mark']."',
                    createdby 		= '".$userid."',    
                    createdat 		= now();
//                    modified_date 		= now(),
//                    created_by 			= '".$userid."', 
//                    modified_by 		= '".$userid."' ";
        $rs = $this->query($sql);
        if ($this->hasError()) {
           $this->setError("Could not Add File", $this->getError());
           return false;
        }
        return true;
    }	// end of insertData    

    public function getPdfs()
	{
		$sql = "SELECT category, title, description, name, marked FROM data WHERE category in('doc', 'pdf','xls', 'xlsx')";
		$rs = $this->query($sql);
		      if ($rs->hasRecords()) 
			{
				return $rs->fetchAll();
			}
		$this->support = $rs->fetchAll();
        }// end of getPdfs
}		
