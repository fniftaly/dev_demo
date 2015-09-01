<?php
/**
 * Application_Model_User class.
 * 
 * @extends Application_Model_Entityabstract
 */
class Application_Model_Form extends Application_Model_Abstract {
    
    
     /*bit.ly.com url shortnen api info*/
     protected $_apiUri = 'http://api.bit.ly/v3/shorten';
     protected $login = 'textmuser';
     protected $apiKey = 'R_75e3ca609fad9fe76a26e86bda9a7540';
	/**
	 * Can this user create contests?
	 * 
	 * @access public
	 * @return void
	 */    
	public function getMessageCountList($userid) {  
		if ($userid) {
			$sql = "CALL user_message_sent_count($userid)";
            $rs  = $this->query($sql);
            if ($rs->hasRecords()) {
                if ($rs->total) {
                    return $rs->total;
                }
            }

		}
		return 0;        
	}
    
	public function getMessageCountListMonthYear($userid,$rmonth,$ryear) {  
		if ($userid) {
            $monthStartDate = date("Y-m-d H:i:s", strtotime($ryear.'-'.$rmonth.'-01'.' 00:00:00'));
            $monthEndDate = date("Y-m-d H:i:s", strtotime('-1 second',strtotime('+1 month',strtotime($monthStartDate))));
            
			$sql = "CALL user_message_sent_count_my($userid,'$monthStartDate','$monthEndDate')";
            $rs  = $this->query($sql);
            if ($rs->hasRecords()) {
                if ($rs->total) {
                    return $rs->total;
                }
            }

		}
		return 0;        
	}

    	public function getMessageCountListLastPeriod($userid,$period) {  
		if ($userid) {
             $monthEndDate = date("Y-m-d H:i:s");
             $monthStartDate = date('Y-m-d H:i:s', strtotime('-'.$period.' days'));
            
			$sql = "CALL user_message_sent_count_my($userid,'$monthStartDate','$monthEndDate')";
            $rs  = $this->query($sql);
            if ($rs->hasRecords()) {
                if ($rs->total) {
                    return $rs->total;
                }
            }

		}
		return 0;        
	}
 
	public function getTotalOptIns($userid) {  
		if ($userid) {
			$sql = "CALL total_opt_ins($userid)";
            $rs  = $this->query($sql); 
            if ($rs->hasRecords()) {
                if ($rs->total) {
                    return $rs->total;
                }
            }

		}
		return 0;        
	}  
    
	public function getTotalOptOuts($userid) {  
		if ($userid) {
			$sql = "CALL total_opt_outs($userid)";
            $rs  = $this->query($sql);
            if ($rs->hasRecords()) {
                if ($rs->total) {
                    return $rs->total;
                }
            }

		}
		return 0;        
	}    
 
    	public function getTotalOptInsPeriod($userid,$period) {  
		if ($userid) {
             $monthEndDate = date("Y-m-d H:i:s");
             $monthStartDate = date('Y-m-d H:i:s', strtotime('-'.$period.' days'));
            
			$sql = "CALL total_opt_ins_period($userid,'$monthStartDate','$monthEndDate')";
            $rs  = $this->query($sql);
            if ($rs->hasRecords()) {
                if ($rs->total) {
                    return $rs->total;
                }
            }

		}
		return 0;        
	}
    
    	public function getTotalOptOutsPeriod($userid,$period) {  
		if ($userid) {
             $monthEndDate = date("Y-m-d H:i:s");
             $monthStartDate = date('Y-m-d H:i:s', strtotime('-'.$period.' days'));
            
			$sql = "CALL total_opt_outs_period($userid,'$monthStartDate','$monthEndDate')";
            $rs  = $this->query($sql);
            if ($rs->hasRecords()) {
                if ($rs->total) {
                    return $rs->total;
                }
            }

		}
		return 0;        
	}   
    
    public function reportCampaignHistory($userid) {
        if ($userid) {
            $sql = "CALL campaign_history_report($userid)";
            $rs  = $this->query($sql); 
            if ($rs->hasRecords()) {
                    return $rs->fetchAll();
            }

		}
		return NULL;
    }  
    
    public function reportCampaignMessageStatus($messageid) {
        if ($messageid) { 
            $sql = "CALL campaign_message_status($messageid)";
            $rs  = $this->query($sql); 
            if ($rs->hasRecords()) {
                    return $rs->fetchAll();
            }

		}
		return NULL;
    } 
    
    public function listTopthreeFolders($userid) {
        if ($userid) { 
            $sql = "CALL top_three_folder_optins($userid)";
            $rs  = $this->query($sql); 
            if ($rs->hasRecords()) {
                    return $rs->fetchAll();
            }

		}
		return NULL;
    }    
    
    public function totalOptinsByFolder($folderid) {
        if ($folderid) { 
            $sql = "CALL count_total_optins_byfolder($folderid)";
            $rs  = $this->query($sql); 
            if ($rs->hasRecords()) {
                if ($rs->total) {
                    return $rs->total;
                }
            }

		}
		return 0;
    }  
    
    public function totalOptoutsByFolder($folderid) {
        if ($folderid) { 
            $sql = "CALL count_total_optouts_byfolder($folderid)";
            $rs  = $this->query($sql); 
            if ($rs->hasRecords()) {
                if ($rs->total) {
                    return $rs->total;
                }
            }

		}
		return 0;
    }     
 
    public function totalCampaignByFolder($folderid) {
        if ($folderid) { 
            $sql = "CALL count_total_campaign_byfolder($folderid)";
            $rs  = $this->query($sql); 
            if ($rs->hasRecords()) {
                if ($rs->total) {
                    return $rs->total;
                }
            }

		}
		return 0;
    }     
    
    public function insertFormDetails($webformUrlArray, $webformAttbArray) {
            $sql1 = "CALL add_webform_url($webformUrlArray[entityid], 
                    $this->escape('$webformUrlArray[urlname]'),
                    $this->escape('$webformUrlArray[thankumsg]'),
                    $this->escape('$webformUrlArray[descmsg]'),
                    '$webformUrlArray[folderid]',
                    $this->escape('$webformUrlArray[dobstatus]'),
                    $this->escape('$webformUrlArray[alertmessage]'))";
            
//                    $webformUrlArray[notifybysms],
//                    $this->escape('$webformUrlArray[notifybyemail]'))";
            $rs1  = $this->query($sql1);
            if ($this->hasError()) { 
                $this->setError('An error occurred and the webform could not be added.<br />' . $this->getError(), $sql1.': '.$this->getError());
                return false;
            }else{
                    $webformUrlId = $rs1->id;
                    foreach($webformAttbArray as $webformAttb)
                    {
                        $sql2 = "CALL add_webform_attributes($webformUrlId,'$webformAttb[attb]','$webformAttb[attbtype]','$webformAttb[attboption]',$webformAttb[weightage])";
                        $rs2  = $this->query($sql2);	
                    } 
                    return true;
            }
    }  
    
    public function webformList($userid){
        if ($userid) { 
            $sql = "CALL webform_list($userid)";
            $rs  = $this->query($sql);
            if ($rs->hasRecords()) {
                    return $rs->fetchAll();
            }else
                return NULL;
		}
		return NULL;        
    }
    
    public function webformDetail($userid,$formurl){
        if ($userid!=null AND $formurl!=null) { 
            $sql = "CALL webform_detail($userid,'$formurl')";
            $rs  = $this->query($sql);
            if ($rs->hasRecords()) { 
                    return $rs->fetchAll();
            }else
                return NULL;
		}
		return NULL;        
    }    
    
        public function insertFormValues($subscriberid,$formattbid,$formattbvalue){ 
        if ($formattbid!=null AND $formattbvalue!=null) { 
            $sql = "CALL add_webform_attribute_values($subscriberid,$formattbid,'$formattbvalue')";
            $rs  = $this->query($sql);
            if ($rs) {
                    return $rs->id;
            }else
                return false;
		}
		return false;        
    }

        public function updateFormValues($subscriberid,$formattbid,$formattbvalue){ 
        if ($formattbid!=null AND $formattbvalue!=null) { 
            $sql = "CALL update_webform_attribute_values($subscriberid,$formattbid,'$formattbvalue')";
            $rs  = $this->query($sql);
            if ($rs) {
                   return true;
            }else
                return false;
		}
		return false;        
    }
    
        public function getAttributeName($attributeid){
        if ($attributeid) { 
            $sql = "CALL get_attribute_name($attributeid)";
            $rs  = $this->query($sql);
            if ($rs->hasRecords()) {
                    return $rs->fetchAll();
            }else
                return NULL;
		}
		return NULL;        
    } 
    
        public function getSubscriber($phonenumber,$folderid){
            if ($phonenumber) { 
                $sql = "CALL get_subscriber($phonenumber,$folderid)";  //not opted out
                $rs  = $this->query($sql);
                if ($rs->hasRecords()) {
                        return $rs->fetchAll();
                }else
                    return NULL;
            }
            return NULL;        
       }   
       
        public function checksubscriber($phonenumber,$folderid){ //echo $phonenumber.'<br>'.$folderid; exit;
            if ($phonenumber) { 

                $sql = "CALL check_subscriber($phonenumber,$folderid)";  //not opted out
		$rs  = $this->query($sql);
	      if($rs->hasResults()){
		//if ($rs->hasResults()) {
		    $result = $rs->fetchAll();
		    return  $result[0]['id'];
		}   
		//else{
		//return "teststing";
	      }
	           
	return NULL;           
       } 
    public function addSubscriberAllDetails($folderid,$phonenumber,$fname,$lname,$emailid,$bday,$lang,$webformid=null) { 
        //echo $folderid."<br>".$phonenumber."<br>".$fname."<br>".$lname."<br>".$emailid."<br>".$bday."<br>".$lang."<br>".$webformid;
       //echo $folderid.','.$phonenumber.','.$fname.','.$lname.','.$emailid.','.$bday.','.$lang.','.$webformid; exit;
        $this->error = false;
        $orig_input = $phonenumber;        
        
        // Clense the phonenumber
        $phonenumber = $this->cleanPhone($phonenumber);
        
      /*  if (empty($phonenumber)) {
            return null;
        }
        
        if (strlen($phonenumber) < 10) {
            $this->setError('Invalid phonenumber "'.$orig_input.'" not added."');
            return false;
        }*/
        
        /*if ($this->checkEmailFormat($emailid)==0) {
            $this->setError('Invalid Email ID: "'.$emailid.'"."');
            return false;
        }*/        
        
        if($webformid)
               $sql = "CALL subscriber_add_with_metadata_webform($folderid, $phonenumber, '$fname', '$lname', '$emailid', '$bday', '$lang','webform',$webformid)";
            else
                $sql = "CALL subscriber_add_with_metadata($folderid, '$phonenumber', '$fname', '$lname', '$emailid', '$bday', '$lang')";
        $rs  = $this->query($sql);
         
        if ($this->hasError()) {
            $error = 'Could not add subscriber: "'.$orig_input.'", filtered to: "'.$phonenumber.'" to Folder.';
            $this->setError($error, $error.' - '.$sql.': '.$this->getError());
           	return false;
        }
        if ($rs->id < 0) {
       	    $this->setError('Subscriber ['.$phonenumber.'] already in this folder.');
       	    return false;
        }
        
        return $rs->id;
    }       


    public function updateSubscriberAllDetails($subscriberid,$folderid,$phonenumber,$fname,$lname,$emailid,$bday,$lang,$webformid=null) { 
        //echo $folderid."<br>".$phonenumber."<br>".$fname."<br>".$lname."<br>".$emailid."<br>".$bday."<br>".$lang."<br>".$webformid;
       //echo $folderid.','.$phonenumber.','.$fname.','.$lname.','.$emailid.','.$bday.','.$lang.','.$webformid; exit;
        $this->error = false;
        $orig_input = $phonenumber;        
        
        // Clense the phonenumber
        $phonenumber = $this->cleanPhone($phonenumber);
        
      /*  if (empty($phonenumber)) {
            return null;
        }
        
        if (strlen($phonenumber) < 10) {
            $this->setError('Invalid phonenumber "'.$orig_input.'" not added."');
            return false;
        }*/
        
        /*if ($this->checkEmailFormat($emailid)==0) {
            $this->setError('Invalid Email ID: "'.$emailid.'"."');
            return false;
        }*/        
        
        if($webformid)
               $sql = "CALL subscriber_update_with_metadata_webform($subscriberid,$folderid, $phonenumber, '$fname', '$lname', '$emailid', '$bday', '$lang','webform',$webformid)";
            else
                $sql = "CALL subscriber_update_with_metadata($subscriberid,$folderid, '$phonenumber', '$fname', '$lname', '$emailid', '$bday', '$lang', 'NULL')";
        $rs  = $this->query($sql);
         
        if ($this->hasError()) {
            $error = 'Could not add subscriber: "'.$orig_input.'", filtered to: "'.$phonenumber.'" to Folder.';
            $this->setError($error, $error.' - '.$sql.': '.$this->getError());
           	return false;
        }

        
        return $rs->id;
    }

    public function cleanPhone($phonenumber) {
        return preg_replace("/[^0-9]/", "", $phonenumber);
    }  
    
    public function checkEmailFormat($emailid) {
        if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email))
             return 1;
        else
             return 0;
    } 
    
    public function getWebformDeatils($webformid){
        if ($webformid) { 
            $sql = "CALL get_webform_details($webformid)";
            $rs  = $this->query($sql);
            if ($rs->hasRecords()) {
                    return $rs->fetchAll();
            }else
                return NULL;
		}
		return NULL;        
    }   
    
    public function updateFormURL($webformid,$thankyoumsg,$descmsg,$folderids,$alertmessage,$notifybysms,$notifybyemail){ 
        if ($webformid) { 
            $sql = "CALL update_webform_url($webformid,'$thankyoumsg','$descmsg','$folderids','$alertmessage',$notifybysms,'$notifybyemail')";
            $rs  = $this->query($sql);
            if ($rs->hasRecords()) {
                    return $rs->fetchAll();
            }else
                return NULL;
		}
		return NULL;        
    }  
    
    public function removeFormAttribute($webformid){
        if ($webformid) { 
            $sql = "CALL remove_form_attribute($webformid)";
            $rs  = $this->query($sql);
            if (!$this->hasError()) {
                return true;
            }
		}
		return false;        
    }   
                    
    public function addFormAttribute($webformid,$webformAttbArray){ 
        if ($webformid) {             
            foreach($webformAttbArray as $webformAttb)
            {
                $sql = "CALL add_webform_attributes($webformid,'$webformAttb[attb]','$webformAttb[attbtype]','$webformAttb[attboption]',$webformAttb[weightage])";
                $rs  = $this->query($sql);
            }     
            return true;   
		}
		return false;        
    }                      
    
       public function makeShortUrl($url){ 
//       $tinyurl = new Zend_Service_ShortUrl_MetamarkNet();
       return $this->shorten($url);     
    }
    
    public function shorten($url,$format='txt') {
	$connectURL = $this->_apiUri.'?login='.$this->login.'&apiKey='.$this->apiKey.'&uri='.urlencode($url).'&format='.$format;
	return $this->curl_get_result($connectURL);
    }
    public function curl_get_result($url) {
	$ch = curl_init();
	$timeout = 5;
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
  }
    
    public function getSubscriberDataById($subscid,$userid){ 
        if ($subscid) { 
            $sql = "CALL get_subscriber_data_byid($subscid,$userid)";
            $rs  = $this->query($sql);
            if ($rs->hasRecords()) {
                    return $rs->fetchAll();
            }else
                return NULL;
		}
		return NULL;     
    }   
    
    public function getSubscriberDataValueById($subscid,$formurlid){ 
        if ($subscid) { 
            $sql = "CALL get_subscriber_datavalue_byid($subscid,$formurlid)";
            $rs  = $this->query($sql);
            if ($rs->hasRecords()) {
                    return $rs->fetchAll();
            }else
                return NULL;
		}
		return NULL;     
    }     
    
    public function multiArraySearch($srchvalue,$sercharray){
        foreach($sercharray as $srchkey => $srch){
            if(in_array($srchvalue,$srch)){
                return $srchkey;
                break;
            }else{
                    return false;
            }
        }
    }  
    
    public function monthList(){
        $monthArray = Array(
            "01" => "Jan",
            "02" => "Feb",
            "03" => "Mar",
            "04" => "Apr",
            "05" => "May",
            "06" => "Jun",
            "07" => "Jul",
            "08" => "Aug",
            "09" => "Sep",
            "10" => "Oct",
            "11" => "Nov",
            "12" => "Dec"
        );
        return $monthArray;
    }
    
    public function dateList(){
        $dateArray = Array();
        for($i=1;$i<=31;$i++){
            $dateArray[$i] = $i;
        }
        return $dateArray;
    }    
    
    public function weightage(){
        $weightageList = array();
        for($i=0;$i<30;$i++){
            $weightageList[$i] = $i;
        }
        return $weightageList;
    }
    
    public function getFolderName($folderid){
		if ($folderid) {            
			$sql = "CALL get_foldername_from_entityid($folderid)";
            $rs  = $this->query($sql);
            if ($rs->hasRecords()) {
                if ($rs->foldername) {
                    return $rs->foldername;
                }
            }

		}
		return NULL;                
    }   
    
    public function deleteWebformById($formid){
        $sql = "CALL delete_webform_url($formid)";
        $rs  = $this->query($sql);
        if ($this->hasError()) { 
            return FALSE;
        }else{
            return TRUE;
        }
    }

        public function collectAttributesOnTheBasisOfDropdownAndCheckboxValues($webformUrlId){
        if ($webformUrlId) { 
            $sql = "CALL get_attributeids_based_on_dropdown_and_checkbox_for_webform($webformUrlId)";
            $rs  = $this->query($sql);
            if ($rs->hasRecords()) {
                    return $rs->fetchAll();
            }else
                return NULL;
		}
		return NULL;        
    }    
    
   /** temporary .......
   *   client gets notification of 
   *   a  new member opted in 
   *   by webform
   */  
 public function alertnewlead($email, $subject, $message){
            $mail = new Zend_Mail();
            $mail->addTo($email);
            $mail->setFrom('info@textmunication.com', 'Textmunication Admin');
            $mail->setSubject($subject);
            $mail->setBodyText($message);
            $mail->send();
        }
  /** temporary .......
   *   client gets notification of 
   *   a  new member opted in 
   *   by webform
   */  
        public function alertMessage($phone, $alert) {
        $apiUrl = 'https://text.mes.syniverse.com/SMSSend';
        $shortCode = '87365';
        $sendMessage = urlencode($alert);
        $username = '4400';
        $password = 'Fq0^Hc0^';

        $uri = $apiUrl;

        $ch = curl_init($uri);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "user=$username&pass=$password&smsfrom=$shortCode&smsto=$phone&smsmsg=$sendMessage");
        // Now set some params, start with username and password
//			curl_setopt($ch, CURLOPT_USERPWD, $username . ':' . $password);
        // Turn off header output in the response
        curl_setopt($ch, CURLOPT_HEADER, false);

        // Disable SSL peer verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        // Capture the output instead of echoing it
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Execute our request
        $rs = curl_exec($ch);

        // Close the cURL handle
        curl_close($ch);
    }
    /**
 * Posting new leads to the clubready
 * 
 */
 public  function postLeadsClubready($first,$last,$phone,$birthday,$email,$clubid,$sendmail) {
     $weblogFile = '/tmp/clubready.log';
     if (!file_exists($weblogFile)) {
            touch($weblogFile);
    }
     $info = array('ApiKey'=>'bf14aefb-1eb8-454b-a27c-e0f117d40352',
         'StoreID'=>$clubid,
         'FirstName'=>$first,
         'LastName'=>$last,
         'CellPhone'=>"$phone",
         'DateOfBirth'=>$birthday,
         'Email'=>$email,
         'SendEmail'=>$sendmail);
     $items = json_encode($info);
    $apiUrl = "http://www.clubready.com/api/users/prospect?";
//create cURL connection
    $curl_connection = curl_init($apiUrl);
    curl_setopt($curl_connection, CURLOPT_CONNECTTIMEOUT, 30);
    curl_setopt($curl_connection, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl_connection, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($items)));
    curl_setopt($curl_connection, CURLOPT_POST, true);
    curl_setopt($curl_connection, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl_connection, CURLOPT_FOLLOWLOCATION, 1);
//set data to be posted
    curl_setopt($curl_connection, CURLOPT_POSTFIELDS, $items);
//perform our request
   $result = curl_exec($curl_connection);
//    echo curl_errno($curl_connection) . '-' . curl_error($curl_connection);
//close the connection
    curl_close($curl_connection);
    $date = date('Y-m-d H:i:s');
    $fh = fopen($weblogFile, 'a');
    fwrite($fh, "\n $date \n $result\n");
    fclose($fh);
    if($result){
     return $result;
    }else{return 0;}
}
/**
 *  Status of the subscribers inthe account
 *  this is developed just to solve problem with
 *  clubread. 
 *  Web form shouldn't send disclosure msg if a subscribers already optin by keyword 
 *  to the same account
 */

public function getStatusOfSubscriberFromUserAccount($phonenumber, $userid){
    $sql = "Call getstatus_ofsubscriber_fromuser_account($phonenumber, $userid)";
    $rs = $this->query($sql);
    if($rs->hasRecords()){
        return 1;
    }else{return 0;}
}// 
}
