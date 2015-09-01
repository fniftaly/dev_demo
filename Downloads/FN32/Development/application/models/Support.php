<?php
class Application_Model_Support extends Application_Model_Abstract 
{
	private $name;
	private $email;
	private $contactnumber;
	private $support_type;
	private $subject;
	private $status;
	private $message;
	private $created_date;
	private $created_by;
	private $support_attach;
	private $uid;
	
	public function getAllListData($uid) 
	{
			if($uid == 'admin') 
			{
				$where = "1";
			}else
			{
				$where = "sup.created_by = '".$uid."'";
			}
			$sql = " SELECT * FROM support as sup WHERE  $where ORDER BY sup.id ";   
			$rs  = $this->query($sql);
			if ($rs->hasRecords()) 
			{
				return $rs->fetchAll();
			}
			$this->support = $rs->fetchAll();
	} 
	public function getviewData($viewid) 
	{
			//echo $viewid;
			//echo '<pre>';print_r($viewArr);die;
			$sql = " SELECT * FROM support as sup WHERE  sup.id = '".$viewid."' ORDER BY sup.id ";  
			//echo $sql;
			//$sql_att = " SELECT FROM support_attachments WHERE support_id = '".$viewid."' ";
			//echo $sql_att;die;
			//$rsA = $this->query($sql_att);
			$rs  = $this->query($sql);
			if ($rs->hasRecords()) 
			{
				return $rs->fetchAll();
			}
			$this->support = $rs->fetchAll();
	} 
	public function getAttData($viewid) 
	{
			//echo $viewid;
			//echo '<pre>';print_r($viewArr);die;
			//echo $sql;
			$sql = " SELECT * FROM support_attachments WHERE support_id = '".$viewid."' ";
			//echo $sql_att;die;
			$rs = $this->query($sql);
			if ($rs->hasRecords()) 
			{
				return $rs->fetchAll();
				
			}
			$this->support = $rs->fetchAll();
			//echo '<pre>';print_r($rs->fetchAll());die;
	} 
	public function add($userid,$supportArray) 
	{
		$sql = " INSERT INTO support SET 
					name 			= '".$supportArray['name']."', 
					email 			= '".$supportArray['email']."', 
					contactnumber 	= '".$supportArray['contactnumber']."', 
		            support_type 	= '".$supportArray['support_type']."', 
					subject 		= '".mysql_escape_string($supportArray['subject'])."', 
					status 		    = '".$supportArray['status_type']."', 
					message 		= '".mysql_escape_string($supportArray['message'])."', 
					created_date 	= now(),
					modified_date 	= now(),
					created_by 		= '".$userid."', 
					modified_by 	= '".$userid."' ";
		$rs = $this->query($sql);
		if ($this->hasError())
		{
           $this->setError("Could not Add Support", $this->getError());
           return false;
        }
        return true;
    }
	public function supportAttach($supportattach,$lastid)
	{
		$sql_att = "INSERT INTO support_attachments  SET support_id = '".$lastid."', file_name = '".$supportattach."' ";
		
		$rs_att  = $this->query($sql_att);
        
		if ($this->hasError())
		{
           $this->setError("Could not Add Support", $this->getError());
           return false;
        }
        return true;
	}
	public function supportId($userid)
	{
		$last  = "SELECT MAX(id) as last from support where created_by='".$userid."' ";
		$lastid = $this->query($last);
		if ($lastid->hasRecords()) 
			{
				return $lastid->fetchAll();
				
			}
		$this->support = $lastid->fetchAll();
	}
	public function deleteById($delid)
	{
        $sql = " DELETE FROM support WHERE id = '".$delid."' ";
		$sql_att = " DELETE FROM support_attachments WHERE support_id = '".$delid."' ";
		$rsA = $this->query($sql_att);
       	$rs  = $this->query($sql);
        if ($this->hasError())
		{ 
            return FALSE;
        }
		else
		{
            return TRUE;
        }
    }
	public function getfileArr($delid)
	{
		$sql = "SELECT file_name FROM support_attachments WHERE support_id = '".$delid."' ";
		$rs = $this->query($sql);
		if ($rs->hasRecords()) 
			{
				return $rs->fetchAll();
				
			}
		$this->support = $rs->fetchAll();
	
	}
	public function status($stateid)
	{
		$sql = " UPDATE support SET status = 'C' WHERE id = '".$stateid."' ";
		$rs  = $this->query($sql);
        if ($this->hasError()) 
		{ 
            return FALSE;
        }
		else
		{
            return TRUE;
        }
	
	}  
}
