<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class ReportsController extends AuthorizedController {
      public function messageAction() {
       $reportObj =  new Application_Model_Report();
       $datareport =  new Application_Model_DataReporting();
       
       $this->view->id = $this->user->getID();
        $rmonth = "";
             $ryear = "";
             $seereportby = $this->request->getParam('reportby');
             if($this->request->getParam('searchvaluehide')!='')
             $searchvalue = $this->request->getParam('searchvaluehide'); 
             else
             $searchvalue = $this->request->getParam('searchvalue'); 
             if($seereportby=="month")
             {
                 $rmonth = $this->request->getParam('monthMessageReport');
                 $ryear = $this->request->getParam('yearMessageReport');
                 if($rmonth==0 OR $ryear==0)
                  {
                    $monthstartdate = 0;
                    $monthenddate = 0;
                  }else{ 
			 $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $rmonth, $ryear);
                    // echo 'rmstart-'.$monthstartdate = $ryear."-".$rmonth."-1 00:00:00"; echo '<br>';
                      //echo 'rmend-'.$monthenddate = $ryear."-".$rmonth."-30 00:00:00";  
                         $monthstartdate = $ryear."-".$rmonth."-1 00:00:00"; 
                      $monthenddate = $ryear."-".$rmonth."-".$daysInMonth." 00:00:00";    
                 }                      
             }elseif($seereportby=="date")
             {
                $monthstartdate = date("Y-m-d",strtotime($this->request->getParam('starttime')));
                $monthenddate = date("Y-m-d",strtotime($this->request->getParam('endtime')));         
             }elseif($seereportby=="")
             { 
		 $daysInMonth = cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y'));
                 $monthstartdate = date('Y')."-".date('m')."-1 00:00:00";
                 $monthenddate = date('Y')."-".date('m')."-".$daysInMonth." 00:00:00";                  
             }
             
         
        if($reportObj->checkAdminUser($this->view->id))  
        { 
//            $this->view->useraccounts = $reportObj->accountids_fromadminusers($this->view->id);
            $this->view->userTypeshowing = 'users'; 
            $this->view->accountnames = $reportObj->findChildEntityList($this->view->id,5,$searchvalue); 
        } else{
              $this->view->accountnames = $reportObj->findChildEntityList($this->view->id,4,$searchvalue);
              $this->view->userTypeshowing = 'locations';
            }   
       
       
       $this->view->starttime = $this->request->getParam('starttime');
       $this->view->endtime = $this->request->getParam('endtime');
       
       if($this->request->getParam('shownumlist')!='')
         {
            $this->view->showNumList = $this->request->getParam('shownumlist');
         }else{
           $this->view->showNumList = 10;
         } 
        
         if($rmonth=='' OR $ryear=='')
         {
             $this->view->monthSelected = date('m');
             $this->view->yearSelected = date('Y');
         }else{
             $this->view->monthSelected = $rmonth;
             $this->view->yearSelected = $ryear;
         } 
         $this->view->reportObject = $datareport;
         
   } 
      public function usermonthlyAction() {
       $reportObj =  new Application_Model_Report();
//       $datareport =  new Application_Model_DataReporting();
       
       $this->view->id = $this->user->getID();
       
       $ids = $reportObj->corparateIdList($this->view->id,5);
       
       if(count($ids) !=0 ){
        $obj =  $reportObj->userdailyStatusList($ids);
       }  
       else 
           {
           $obj =  $reportObj->userdailyStatusList($ids,$this->view->id);
       }
//       $sm = 0;
//       for($n=0;$n<count($obj);$n++){
//           $ob = $obj[$n];
//           $sm += $ob->totalsms.'<br>';
//       }
//       echo $this->view->id."<br>";
//       echo '<pre>'; print_r($ids);
//       echo '<br>';
//       echo $sm;
//       echo '<br>';
//       echo '<pre>'; print_r($obj);
//       exit;
       
       $this->view->obj = $obj;
   } 
 ########################   
    public function message_oldAction() {
        /*$userid = $this->user->getId();
        $messageObj = new Application_Model_Report();
        $numbermessage_currmonth = $messageObj->getMessageCountList($userid);
        $this->view->numbermessage_currmonth  = $numbermessage_currmonth;*/

         
         /*if ($this->request->isPost())
           {*/
             $rmonth = "";
             $ryear = "";
           $seereportby = $this->request->getParam('reportby');
             if($this->request->getParam('searchvaluehide')!='')
             $searchvalue = $this->request->getParam('searchvaluehide'); 
             else
             $searchvalue = $this->request->getParam('searchvalue'); 
             if($seereportby=="month")
             {
                 $rmonth = $this->request->getParam('monthMessageReport');
                 $ryear = $this->request->getParam('yearMessageReport');
                 if($rmonth==0 OR $ryear==0)
                  {
                    $monthstartdate = 0;
                   $monthenddate = 0;
                  }else{ 
			 $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $rmonth, $ryear);
                    // echo 'rmstart-'.$monthstartdate = $ryear."-".$rmonth."-1 00:00:00"; echo '<br>';
                      //echo 'rmend-'.$monthenddate = $ryear."-".$rmonth."-30 00:00:00";  
                         $monthstartdate = $ryear."-".$rmonth."-1 00:00:00"; 
                      $monthenddate = $ryear."-".$rmonth."-".$daysInMonth." 00:00:00";    
                 }                      
             }elseif($seereportby=="date")
             {
                $monthstartdate = date("Y-m-d",strtotime($this->request->getParam('starttime')));
                $monthenddate = date("Y-m-d",strtotime($this->request->getParam('endtime')));         
             }elseif($seereportby=="")
             { 
		 $daysInMonth = cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y'));
                 $monthstartdate = date('Y')."-".date('m')."-1 00:00:00";
                 $monthenddate = date('Y')."-".date('m')."-".$daysInMonth." 00:00:00";                  
             }
           /*}else{
                $searchvalue = $this->request->getParam('searchvalue');
                if($rmonth=='' OR $ryear==''){
                         $monthstartdate = date('Y')."-".date('m')."-1 00:00:00";
                         $monthenddate = date('Y')."-".date('m')."-30 00:00:00"; 
                }               
           }*/
        
		$this->view->monthstartdate1 = $monthstartdate;
		$this->view->monthenddate1 = $monthenddate;
		
        $reportObj =  new Application_Model_Report();
        
        $pagenum = $this->request->getParam('page');
        if($pagenum=='' OR $pagenum==0)
          $pagenum = 1;
               
        $arraystart = ($pagenum*5) - 5 ;
        $arrayend = ($pagenum*5);        
        
        if($this->request->getParam('reportuserid')!='')
            $userid = $this->request->getParam('reportuserid');
           else     
            $userid = $this->user->getId();         
        
        if($reportObj->checkAdminUser($userid))  
        { 
            $this->view->userTypeshowing = 'users'; 
            $subUsersArray = $reportObj->findChildEntityList($userid,5,$searchvalue);  //echo "<pre>"; print_r($subUsersArray); exit;
            $totalSubUsers = count($subUsersArray);
            
            if($totalSubUsers >0)
	    {
	      foreach ($subUsersArray as $key => $row) {
		  $username[$key]  = $row['username'];
	      } 
	    }
	    else
	    {
	      $subUsersArray ='';
	    }
            $orderby = $this->request->getParam('orderby');
            if($orderby == 'ASC')
                array_multisort($username, SORT_ASC, $subUsersArray);
            elseif($orderby == 'DESC')
                array_multisort($username, SORT_DESC, $subUsersArray);
            //echo "<pre>"; print_r($subUsersArray); exit;    
            $this->view->sortOrderBy = $orderby;
            

            //$this->view->userlist = $subUsersArray;
            $allDetailsArray = array();

	  if(!empty($subUsersArray))
	  {
	  
	    for($i = $arraystart; $i< $arrayend; $i++)
	    { 
	      if(!empty($subUsersArray[$i]))
	      {
		// echo "I am here1";
		$allDetailsArray[$subUsersArray[$i]['childuserid']]['username'] = $subUsersArray[$i]['username'];
		
		$allDetailsArray[$subUsersArray[$i]['childuserid']]['totalKeywords'] = $reportObj->reportCountTotalKeywordByUserId($subUsersArray[$i]['childuserid'],$monthstartdate,$monthenddate);
		$allDetailsArray[$subUsersArray[$i]['childuserid']]['totalSubscribers'] = $reportObj->reportCountTotalSubscribersByUserId($subUsersArray[$i]['childuserid'],$monthstartdate,$monthenddate);
		$allDetailsArray[$subUsersArray[$i]['childuserid']]['totalOptins'] = $reportObj->reportCountTotalOptinsByUserId($subUsersArray[$i]['childuserid'],$monthstartdate,$monthenddate);
		$allDetailsArray[$subUsersArray[$i]['childuserid']]['totalOptouts'] = $reportObj->reportCountTotalOptoutsByUserId($subUsersArray[$i]['childuserid'],$monthstartdate,$monthenddate); 
		$subUsersArray[$i]['childuserid'];
		$allDetailsArray[$subUsersArray[$i]['childuserid']]['totalCampaigns'] = $reportObj->reportCountTotalCampaignsByUserId($subUsersArray[$i]['childuserid'],$monthstartdate,$monthenddate);
		$allDetailsArray[$subUsersArray[$i]['childuserid']]['totalCampaignMessages'] = $reportObj->reportCountTotalCampaignMessagesByUserId($subUsersArray[$i]['childuserid'],$monthstartdate,$monthenddate);
		$allDetailsArray[$subUsersArray[$i]['childuserid']]['totaloutboundmessage'] = $reportObj->reportCountTotalMessagesByUserId($subUsersArray[$i]['childuserid'],$monthstartdate,$monthenddate);      
	      }
	    }
	  }
	  else
	  { 
			
                $this->view->userTypeshowing = 'locations';
                $subFoldersArray = $reportObj->findChildEntityList($userid,4); //echo "<pre>"; print_r($subFoldersArray); echo exit;
              
                $totalSubUsers = count($subFoldersArray);

                for($i = $arraystart; $i< $arrayend; $i++)
                { 
                    if(!empty($subFoldersArray[$i]))
                    {          
                        $allDetailsArray[$subFoldersArray[$i]['folderid']]['username'] = $subFoldersArray[$i]['foldername'];
                        $allDetailsArray[$subFoldersArray[$i]['folderid']]['totalKeywords'] = $subFoldersArray[$i]['totalkeywords'];
                        $allDetailsArray[$subFoldersArray[$i]['folderid']]['totalSubscribers'] = $reportObj->totalSubscribersByFolder($subFoldersArray[$i]['folderid'],$monthstartdate,$monthenddate);
                        $allDetailsArray[$subFoldersArray[$i]['folderid']]['totalOptins'] = $reportObj->totalOptinsByFolder($subFoldersArray[$i]['folderid'],$monthstartdate,$monthenddate);
                        $allDetailsArray[$subFoldersArray[$i]['folderid']]['totalOptouts'] = $reportObj->totalOptoutsByFolder($subFoldersArray[$i]['folderid'],$monthstartdate,$monthenddate); 
                        $allDetailsArray[$subFoldersArray[$i]['folderid']]['totalCampaigns'] = $reportObj->totalCampaignByFolder($subFoldersArray[$i]['folderid'],$monthstartdate,$monthenddate);
						$subFoldersArray[$i]['folderid']." ".$monthstartdate." ".$monthenddate;
                       $allDetailsArray[$subFoldersArray[$i]['folderid']]['totalCampaignMessages'] = $reportObj->getTotalCampaignMessagesByFolder($subFoldersArray[$i]['folderid'],$monthstartdate,$monthenddate);
                        $allDetailsArray[$subFoldersArray[$i]['folderid']]['totaloutboundmessage'] = $reportObj->getTotalOutboundMessages($subFoldersArray[$i]['folderid'],$monthstartdate,$monthenddate);                        
                    }
                }
	  }           
        }
	else{  
				//echo "I am here3";
                $this->view->userTypeshowing = 'locations';
                $subFoldersArray = $reportObj->findChildEntityList($userid,4); //echo "<pre>"; print_r($subFoldersArray); echo exit;
              
                $totalSubUsers = count($subFoldersArray);

                for($i = $arraystart; $i< $arrayend; $i++)
                { 
                    if(!empty($subFoldersArray[$i]))
                    {          
                        $allDetailsArray[$subFoldersArray[$i]['folderid']]['username'] = $subFoldersArray[$i]['foldername'];
                        $allDetailsArray[$subFoldersArray[$i]['folderid']]['totalKeywords'] = $subFoldersArray[$i]['totalkeywords'];
                        $allDetailsArray[$subFoldersArray[$i]['folderid']]['totalSubscribers'] = $reportObj->totalSubscribersByFolder($subFoldersArray[$i]['folderid'],$monthstartdate,$monthenddate);
                        $allDetailsArray[$subFoldersArray[$i]['folderid']]['totalOptins'] = $reportObj->totalOptinsByFolder($subFoldersArray[$i]['folderid'],$monthstartdate,$monthenddate);
                        $allDetailsArray[$subFoldersArray[$i]['folderid']]['totalOptouts'] = $reportObj->totalOptoutsByFolder($subFoldersArray[$i]['folderid'],$monthstartdate,$monthenddate); 
                        $allDetailsArray[$subFoldersArray[$i]['folderid']]['totalCampaigns'] = $reportObj->totalCampaignByFolder($subFoldersArray[$i]['folderid'],$monthstartdate,$monthenddate);
						$subFoldersArray[$i]['folderid']." ".$monthstartdate." ".$monthenddate;
                       $allDetailsArray[$subFoldersArray[$i]['folderid']]['totalCampaignMessages'] = $reportObj->getTotalCampaignMessagesByFolder($subFoldersArray[$i]['folderid'],$monthstartdate,$monthenddate);
                        $allDetailsArray[$subFoldersArray[$i]['folderid']]['totaloutboundmessage'] = $reportObj->getTotalOutboundMessages($subFoldersArray[$i]['folderid'],$monthstartdate,$monthenddate);                        
                    }
                }
        }

    if(!empty($allDetailsArray))
    {
	//echo "userid".$userid;
	//echo "I am here4";
        $allDetailsArray['total']['username'] = '<b>Total</b>';
        $allDetailsArray['total']['totalKeywords'] = '<b>'.$reportObj->reportCountTotalKeywordByUserId($userid,$monthstartdate,$monthenddate).'</b>';  
        $allDetailsArray['total']['totalSubscribers'] = $reportObj->reportCountTotalSubscribersByUserId($userid,$monthstartdate,$monthenddate);
        $allDetailsArray['total']['totalOptins'] = $reportObj->reportCountTotalOptinsByUserId($userid,$monthstartdate,$monthenddate);
       $allDetailsArray['total']['totalOptouts'] = '<b>'.$reportObj->reportCountTotalOptoutsByUserId($userid,$monthstartdate,$monthenddate).'</b>';
	 
	   $allDetailsArray['total']['totalOptouts'] = $allDetailsArray['total']['totalOptins'] - $allDetailsArray['total']['totalSubscribers'];
		$allDetailsArray['total']['totalCampaigns'] = '<b>'.$reportObj->reportCountTotalCampaignsByUserId($userid,$monthstartdate,$monthenddate);
        $allDetailsArray['total']['totalCampaignMessages'] = '<b>'.$reportObj->reportCountTotalCampaignMessagesByUserId($userid,$monthstartdate,$monthenddate).'</b>';
        $allDetailsArray['total']['totaloutboundmessage'] = '<b>'.$reportObj->reportCountTotalMessagesByUserId($userid,$monthstartdate,$monthenddate).'</b>';   
    }
        //echo "<pre>"; print_r($allDetailsArray); exit;
         
        
        $this->view->seeReportBy = $seereportby;
        $this->view->totalSubUsers = $totalSubUsers;
        $this->view->allDetailsArray = $allDetailsArray;
        $this->view->searchvalue = $searchvalue; 
        $this->view->reportuserid = $userid;
        $this->view->starttime = $this->request->getParam('starttime');
        $this->view->endtime = $this->request->getParam('endtime');
        
         if($this->request->getParam('shownumlist')!='')
         {
            $this->view->showNumList = $this->request->getParam('shownumlist');
         }else{
           $this->view->showNumList = 10;
         } 
        
         if($rmonth=='' OR $ryear=='')
         {
             $this->view->monthSelected = date('m');
             $this->view->yearSelected = date('Y');
         }else{
             $this->view->monthSelected = $rmonth;
             $this->view->yearSelected = $ryear;
         }          

    }  

 ########################   

    public function apireportAction() {
             $rmonth = "";
             $ryear = "";
           $seereportby = $this->request->getParam('reportby');
             if($this->request->getParam('searchvaluehide')!='')
             $searchvalue = $this->request->getParam('searchvaluehide'); 
             else
             $searchvalue = $this->request->getParam('searchvalue'); 
             if($seereportby=="month")
             {
                 $rmonth = $this->request->getParam('monthMessageReport');
                 $ryear = $this->request->getParam('yearMessageReport');
                 if($rmonth==0 OR $ryear==0)
                  {
                    $monthstartdate = 0;
                   $monthenddate = 0;
                  }else{ 
			 $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $rmonth, $ryear);
                         $monthstartdate = $ryear."-".$rmonth."-1 00:00:00"; 
                      $monthenddate = $ryear."-".$rmonth."-".$daysInMonth." 00:00:00";    
                 }                      
             }elseif($seereportby=="date")
             {
                $monthstartdate = date("Y-m-d",strtotime($this->request->getParam('starttime')));
                $monthenddate = date("Y-m-d",strtotime($this->request->getParam('endtime')));         
             }elseif($seereportby=="")
             { 
		 $daysInMonth = cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y'));
                 $monthstartdate = date('Y')."-".date('m')."-1 00:00:00";
                 $monthenddate = date('Y')."-".date('m')."-".$daysInMonth." 00:00:00";                  
             }   
		$this->view->monthstartdate1 = $monthstartdate;
		$this->view->monthenddate1 = $monthenddate;

        $reportObj =  new Application_Model_Report();
        
        $pagenum = $this->request->getParam('page');
        if($pagenum=='' OR $pagenum==0)
          $pagenum = 1;
               
        $arraystart = ($pagenum*5) - 5 ;
        $arrayend = ($pagenum*5);        
        
        if($this->request->getParam('reportuserid')!='')
            $userid = $this->request->getParam('reportuserid');
           else     
            $userid = $this->user->getId();   
    
            $apiKeyArray = $reportObj->find_entity_apikey($userid,$searchvalue);  //echo "<pre>"; print_r($subUsersArray); exit;
	    if(empty($apiKeyArray)) { $visible = "notvisible"; } else { $visible = "visible" ; }
	    $apikey = $apiKeyArray[0]['apikey'];  // should be dynamic (pending)
// 	    for($i=0; $i<count($apiKeyArray); $i++){
// 	      $apikey = $apiKeyArray[$i]['apikey'];
// 	      $allMessageArray[] = $reportObj->reportTotalMessagesByApiKey($apikey,$monthstartdate,$monthenddate);
// 	    }
	    $allMessageArray = $reportObj->reportTotalMessagesByApiKey($apikey,$monthstartdate,$monthenddate);  
	    //echo "<pre>"; print_r($subUsersArray); exit;
            $totalMessage = count($allMessageArray);            
            if($totalMessage >0)
	    {
	      foreach ($allMessageArray as $key => $row) {
		  $phonenumber[$key]  = $row['phonenumber'];
	      } 
	    }
	    else
	    {
	      $allMessageArray ='';
	    }
            $orderby = $this->request->getParam('orderby');
             if($orderby == 'ASC')
                 array_multisort($usernamchilduseride, SORT_ASC, $allMessageArray);
             elseif($orderby == 'DESC')
                 array_multisort($phonenumber, SORT_DESC, $allMessageArray);
  
            $this->view->sortOrderBy = $orderby;
            $allDetailsArray = array();

	  if(!empty($allMessageArray))
	  {	  
	    for($i = $arraystart; $i< $arrayend; $i++)
	    { 
	      if(!empty($allMessageArray[$i]))
	      {
		$allDetailsArray[$allMessageArray[$i]['messageid']]['phonenumber'] = $allMessageArray[$i]['phonenumber'];
		$allDetailsArray[$allMessageArray[$i]['messageid']]['senttime'] = $allMessageArray[$i]['senttime'];
		$allDetailsArray[$allMessageArray[$i]['messageid']]['messagebody'] = $allMessageArray[$i]['messagebody'];
		$allDetailsArray[$allMessageArray[$i]['messageid']]['apikey'] = $allMessageArray[$i]['apikey'];
	      }
	    }
	  } else { "No any record present." ;	  }           
	 $this->view->visible = $visible;
        $this->view->seeReportBy = $seereportby;
        $this->view->totalSubUsers = $totalMessage;
        $this->view->allDetailsArray = $allDetailsArray;
        $this->view->searchvalue = $searchvalue; 
        $this->view->reportuserid = $userid;
        $this->view->starttime = $this->request->getParam('starttime');
        $this->view->endtime = $this->request->getParam('endtime');
        
         if($this->request->getParam('shownumlist')!='')
         {
            $this->view->showNumList = $this->request->getParam('shownumlist');
         }else{
           $this->view->showNumList = 10;
         } 
        
         if($rmonth=='' OR $ryear=='')
         {
             $this->view->monthSelected = date('m');
             $this->view->yearSelected = date('Y');
         }else{
             $this->view->monthSelected = $rmonth;
             $this->view->yearSelected = $ryear;
         }          
    }


 ########################   
    public function apimessageAction() {
             $rmonth = "";
             $ryear = "";
           $seereportby = $this->request->getParam('reportby');
             if($this->request->getParam('searchvaluehide')!='')
             $searchvalue = $this->request->getParam('searchvaluehide'); 
             else
             $searchvalue = $this->request->getParam('searchvalue'); 
             if($seereportby=="month")
             {
                 $rmonth = $this->request->getParam('monthMessageReport');
                 $ryear = $this->request->getParam('yearMessageReport');
                 if($rmonth==0 OR $ryear==0)
                  {
                    $monthstartdate = 0;
                   $monthenddate = 0;
                  }else{ 
			 $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $rmonth, $ryear);
                    // echo 'rmstart-'.$monthstartdate = $ryear."-".$rmonth."-1 00:00:00"; echo '<br>';
                      //echo 'rmend-'.$monthenddate = $ryear."-".$rmonth."-30 00:00:00";  
                         $monthstartdate = $ryear."-".$rmonth."-1 00:00:00"; 
                      $monthenddate = $ryear."-".$rmonth."-".$daysInMonth." 00:00:00";    
                 }                      
             }elseif($seereportby=="date")
             {
                $monthstartdate = date("Y-m-d",strtotime($this->request->getParam('starttime')));
                $monthenddate = date("Y-m-d",strtotime($this->request->getParam('endtime')));         
             }elseif($seereportby=="")
             { 
		 $daysInMonth = cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y'));
                 $monthstartdate = date('Y')."-".date('m')."-1 00:00:00";
                 $monthenddate = date('Y')."-".date('m')."-".$daysInMonth." 00:00:00";                  
             }   
		$this->view->monthstartdate1 = $monthstartdate;
		$this->view->monthenddate1 = $monthenddate;

        $reportObj =  new Application_Model_Report();
        
        $pagenum = $this->request->getParam('page');
        if($pagenum=='' OR $pagenum==0)
          $pagenum = 1;
               
        $arraystart = ($pagenum*5) - 5 ;
        $arrayend = ($pagenum*5);        
        
        if($this->request->getParam('reportuserid')!='')
            $userid = $this->request->getParam('reportuserid');
           else     
            $userid = $this->user->getId();         
        
        if($reportObj->checkAdminUser($userid))  
        { 
            $subUsersArray = $reportObj->findChildEntityListApiReport($userid,$searchvalue);  //echo "<pre>"; print_r($subUsersArray); exit;
            $totalSubUsers = count($subUsersArray);            
            if($totalSubUsers >0)
	    {
	      foreach ($subUsersArray as $key => $row) {
		  $username[$key]  = $row['username'];
	      } 
	    }
	    else
	    {
	      $subUsersArray ='';
	    }
            $orderby = $this->request->getParam('orderby');
            if($orderby == 'ASC')
                array_multisort($usernamchilduseride, SORT_ASC, $subUsersArray);
            elseif($orderby == 'DESC')
                array_multisort($username, SORT_DESC, $subUsersArray);
  
            $this->view->sortOrderBy = $orderby;
            $allDetailsArray = array();

	  if(!empty($subUsersArray))
	  {	  
	    for($i = $arraystart; $i< $arrayend; $i++)
	    { 
	      if(!empty($subUsersArray[$i]))
	      {
		$allDetailsArray[$subUsersArray[$i]['childuserid']]['username'] = $subUsersArray[$i]['username'];
		$allDetailsArray[$subUsersArray[$i]['childuserid']]['childname'] = $reportObj->getChildName($subUsersArray[$i]['childuserid']);      
		
		$allDetailsArray[$subUsersArray[$i]['childuserid']]['totaloutboundmessage'] = $reportObj->reportCountTotalMessagesByApiKey($userid,$subUsersArray[$i]['username'],$monthstartdate,$monthenddate);      
	      }
	    }
	  } else { "No any subusers present." ;	  }           
        } else { "This report for the Admin User only."; }
        $this->view->seeReportBy = $seereportby;
        $this->view->totalSubUsers = $totalSubUsers;
        $this->view->allDetailsArray = $allDetailsArray;
        $this->view->searchvalue = $searchvalue; 
        $this->view->reportuserid = $userid;
        $this->view->starttime = $this->request->getParam('starttime');
        $this->view->endtime = $this->request->getParam('endtime');
        
         if($this->request->getParam('shownumlist')!='')
         {
            $this->view->showNumList = $this->request->getParam('shownumlist');
         }else{
           $this->view->showNumList = 10;
         } 
        
         if($rmonth=='' OR $ryear=='')
         {
             $this->view->monthSelected = date('m');
             $this->view->yearSelected = date('Y');
         }else{
             $this->view->monthSelected = $rmonth;
             $this->view->yearSelected = $ryear;
         }          
    }

 
###################################
    public function init()
    {
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext->addActionContext('list', 'html')
                    ->addActionContext('modify', 'html')
                    ->initContext();
    }      
    public function messagemonthyearoldAction() { 

        $this->_helper->layout->disableLayout();
 
		$this->view->msg_limit   = $this->user->messagelimit;
	
		$this->view->key_limit   = $this->user->keywordlimit;
		
		$key_used    = $this->user->getKeywordsCount();
		$key_deleted  = $this->user->getDeletedKeywordsCount();
		$this->view->key_used = $key_used - $key_deleted;
		$this->view->key_percent = sprintf("%d", ($this->view->key_used / $this->view->key_limit) * 100);
		
		// Large graph
		$dayrange = 30;
		$daystep  =  2;
		$this->view->graphrange   = $dayrange;
		$this->view->graphstep    = $daystep;
		$optins  = $this->user->getTotalSubscriberCountByDay($dayrange, 'asc');
		$optouts = $this->user->getDailyOptoutsByDay($dayrange, 'asc');
		$this->view->graphoptins  = $optins;
		$this->view->graphoptouts = $optouts;        
        
        $rmonth = $this->request->getParam('rmonth');
        $ryear = $this->request->getParam('ryear');
        if($rmonth=='0' OR $rmonth=='0')
        {
            $rmonth = date('n');
            $ryear = date('Y');
        } 
        $userid = $this->user->getId();
        $messageObj = new Application_Model_Report(); 
        
        //*********** Calculate startdate and end date................
		$rmonth = date('m');
		$ryear = date('Y');
		$daysInMonth = cal_days_in_month(CAL_GREGORIAN, $rmonth, $ryear);
        // echo 'rmstart-'.$monthstartdate = $ryear."-".$rmonth."-1 00:00:00"; echo '<br>';
        //echo 'rmend-'.$monthenddate = $ryear."-".$rmonth."-30 00:00:00";  
      $monthstartdate = $ryear."-".$rmonth."-1 00:00:00"; 
      $monthenddate = $ryear."-".$rmonth."-".$daysInMonth." 00:00:00";    
		
        // End of Script
		//$this->view->msg_used    = $messageObj->reportCountTotalMessagesByUserId($userid);
		$this->view->msg_used    = $messageObj->reportCountTotalMessagesByUserId($userid,$monthstartdate,$monthenddate);
		//reportCountTotalMessagesByUserId($userid,$monthstartdate,$monthenddate)
		$msgpercent = ($this->view->msg_used / $this->view->msg_limit) * 100;
		if($msgpercent >= 100 ) $msgpercent = 100;
		$this->view->msg_percent = sprintf("%d", $msgpercent);
		//$totalOptIns = $messageObj->getTotalOptIns($userid);
		$totalOptIns = $messageObj->reportCountTotalOptinsByUserId($userid);
        //$totalOptOuts = $messageObj->getTotalOptOuts($userid);
		$totalOptOuts = $messageObj->reportCountTotalOptoutsByUserId($userid);
		
        //$totalSubscribers = $messageObj->getTotalSubscribers($userid);
		$totalSubscribers = $messageObj->reportCountTotalSubscribersByUserId($userid,$monthstartdate,$monthenddate);
        
        $this->view->totalOptIns  = $totalOptIns;
        $this->view->totalOptOuts  = $totalOptOuts;  
        $this->view->totalSubscribers  = $totalSubscribers;   
        
        //$this->view->totalCampaignMessages = $messageObj->getTotalCampaignMessagesCountByuser($userid);
		$this->view->totalCampaignMessages = $messageObj->reportCountTotalCampaignMessagesByUserId($userid,$monthstartdate,$monthenddate);
        
       // $this->view->totalCampaigns = $messageObj->getTotalCampaignCountByuser($userid);
	   $this->view->totalCampaigns = $messageObj->reportCountTotalCampaignsByUserId($userid,$monthstartdate,$monthenddate);
             
        $monthstartdate = strtotime($ryear."-".$rmonth."-01");
        $todaydate = strtotime(date("Y-m-d"));
        $currmonthperiod = round(($todaydate - $monthstartdate)/86400); 
        
        $topThreeFolderArray = $messageObj->listTopthreeFolders($userid);
		if(!empty($topThreeFolderArray)){
        foreach($topThreeFolderArray as $key => $topThreeFolder)
        { 
            //$topThreeFolderArray[$key]['totalsubscriber'] = $messageObj->getTotalSubscriberByFolder($topThreeFolder['folderid']);
			$topThreeFolderArray[$key]['totalsubscriber'] = $messageObj->totalSubscribersByFolder($topThreeFolder['folderid']);
			
            //$topThreeFolderArray[$key]['totaloptins'] = $topThreeFolder['total']; //$messageObj->totalOptinsByFolder($topThreeFolder['folderid']);
			$topThreeFolderArray[$key]['totaloptins'] =  $messageObj->totalOptinsByFolder($topThreeFolder['folderid']);
			
            $topThreeFolderArray[$key]['totaloptouts'] = $messageObj->totalOptoutsByFolder($topThreeFolder['folderid']);
			//$topThreeFolderArray[$key]['totaloptouts'] = $messageObj->totalOptoutsByFolder($topThreeFolder['folderid']);
            $topThreeFolderArray[$key]['totalcampaign'] = $messageObj->totalCampaignByFolder($topThreeFolder['folderid']);
        }//echo "<pre>"; print_r($topThreeFolderArray); exit;
		}
        
       // $topThreeKeywordArray = $messageObj->listTopthreeKeywords($userid);
	$topThreeKeywordArray = $messageObj->listTopthreeKeywordsFilterDeleted($userid); // new to restrict deleted keyword 
		if(!empty($topThreeKeywordArray)){
        foreach($topThreeKeywordArray as $key => $topThreeKeyword)
        { 
            		
	    $topThreeKeywordArray[$key]['totalsubscriber'] = $messageObj->totalSubscribersByFolder($topThreeKeyword['folderid']);
            $topThreeKeywordArray[$key]['totaloptins'] = $messageObj->totalOptinsByFolder($topThreeKeyword['folderid']); //$messageObj->totalOptinsByKeyword($topThreeKeyword['keywordid']);
            $topThreeKeywordArray[$key]['totaloptouts'] = $messageObj->totalOptoutsByFolder($topThreeKeyword['folderid']);
            $topThreeKeywordArray[$key]['totalcampaign'] = $messageObj->totalCampaignByFolder($topThreeKeyword['folderid']);
        }       
		}

        $this->view->topThreeFolderDetails = $topThreeFolderArray;
        $this->view->topThreeKeywordArray = $topThreeKeywordArray; 
		############ Calculating Start Date and End Date for Report ##################
		$todate = date("Y-m-d h:i:s");
		
		 
		
		
		####Last Seven Days
		$fromdate7 = strtotime ( '-6 day' , strtotime ( $todate ) ) ;
		$fromdate7 = date ( 'Y-m-d' , $fromdate7 );
		
		####Last Fourteen Days
		$fromdate14 = strtotime ( '-13 day' , strtotime ( $todate ) ) ;
		$fromdate14 = date ( 'Y-m-d' , $fromdate14 );
		
		###Last Thirty Days
		$fromdate30 = strtotime ( '-29 day' , strtotime ( $todate ) ) ;
		$fromdate30 = date ( 'Y-m-d' , $fromdate30 );
		
		###Last Ninty Days
		$fromdate90 = strtotime ( '-89 day' , strtotime ( $todate ) ) ;
		$fromdate90 = date ( 'Y-m-d' , $fromdate90 );
		
		###Last 1 Year
		$fromdate365 = strtotime ( '-364 day' , strtotime ( $todate ) ) ;
		$fromdate365 = date ( 'Y-m-d' , $fromdate365 );
		
		##################### End of Calculation ####################################
        
       // $this->view->totalOptIns_seven  = $messageObj->getTotalOptInsPeriod($userid,6);
	    $this->view->totalOptIns_seven  = $messageObj->reportCountTotalOptinsByUserId($userid,$fromdate7,$todate);
		
       // $this->view->totalOptIns_forteen  = $messageObj->getTotalOptInsPeriod($userid,13);
	   $this->view->totalOptIns_forteen  = $messageObj->reportCountTotalOptinsByUserId($userid,$fromdate14,$todate);
        //$this->view->totalOptIns_thirty  = $messageObj->getTotalOptInsPeriod($userid,29);
		$this->view->totalOptIns_thirty  = $messageObj->reportCountTotalOptinsByUserId($userid,$fromdate30,$todate);
       // $this->view->totalOptIns_ninty  = $messageObj->getTotalOptInsPeriod($userid,89);
	   $this->view->totalOptIns_ninty  = $messageObj->reportCountTotalOptinsByUserId($userid,$fromdate90,$todate);
        //$this->view->totalOptIns_lastyear  = $messageObj->getTotalOptInsPeriod($userid,364);        
		//$this->view->totalOptIns_lastyear  = $messageObj->reportCountTotalOptinsByUserId($userid,$fromdate365,$todate);
		$this->view->totalOptIns_lastyear  = $messageObj->reportCountTotalOptinsByUserId($userid);
		

        //$this->view->totalOptOuts_seven  = $messageObj->getTotalOptOutsPeriod($userid,6);
		$this->view->totalOptOuts_seven  = $messageObj->reportCountTotalOptoutsByUserId($userid,$fromdate7,$todate);
        //$this->view->totalOptOuts_forteen  = $messageObj->getTotalOptOutsPeriod($userid,13);
		$this->view->totalOptOuts_forteen  = $messageObj->reportCountTotalOptoutsByUserId($userid,$fromdate14,$todate);
       // $this->view->totalOptOuts_thirty  = $messageObj->getTotalOptOutsPeriod($userid,29);
	   $this->view->totalOptOuts_thirty  = $messageObj->reportCountTotalOptoutsByUserId($userid,$fromdate30,$todate);
        //$this->view->totalOptOuts_ninty  = $messageObj->getTotalOptOutsPeriod($userid,89);
		$this->view->totalOptOuts_ninty  = $messageObj->reportCountTotalOptoutsByUserId($userid,$fromdate90,$todate);
        //$this->view->totalOptOuts_lastyear  = $messageObj->getTotalOptOutsPeriod($userid,364);    
		//$this->view->totalOptOuts_lastyear  = $messageObj->reportCountTotalOptoutsByUserId($userid,$fromdate365,$todate);
		$this->view->totalOptOuts_lastyear  = $messageObj->reportCountTotalOptoutsByUserId($userid);
        
        //$this->view->totalCampaign_currmonth  = $totalCampaign_currmonth;
       // $this->view->totalCampaign_seven  = $messageObj->totalCampaignByUser($userid,7);
		$this->view->totalCampaign_seven  = $messageObj->reportCountTotalCampaignsByUserId($userid,$fromdate7,$todate);
       // $this->view->totalCampaign_forteen  = $messageObj->totalCampaignByUser($userid,14);
	   $this->view->totalCampaign_forteen  = $messageObj->reportCountTotalCampaignsByUserId($userid,$fromdate14,$todate);
        //$this->view->totalCampaign_thirty  = $messageObj->totalCampaignByUser($userid,30);
		$this->view->totalCampaign_thirty  = $messageObj->reportCountTotalCampaignsByUserId($userid,$fromdate30,$todate);
        //$this->view->totalCampaign_ninty  = $messageObj->totalCampaignByUser($userid,90);
		$this->view->totalCampaign_ninty  = $messageObj->reportCountTotalCampaignsByUserId($userid,$fromdate90,$todate);
        //$this->view->totalCampaign_lastyear  = $messageObj->totalCampaignByUser($userid,365); 
		//$this->view->totalCampaign_lastyear  = $messageObj->reportCountTotalCampaignsByUserId($userid,$fromdate365,$todate);
		$this->view->totalCampaign_lastyear  = $messageObj->reportCountTotalCampaignsByUserId($userid);
              
    }   
    
    public function campaignhistoryAction() {
        $userid = $this->user->getId();
        $historyObj = new Application_Model_Report();        
        $campaignhistoryArray = $historyObj->reportCampaignHistory($userid);

        if(!empty($campaignhistoryArray))
        {
            foreach($campaignhistoryArray as $campaignhistorydetail)
            { 
               //$campaignMessageStatus = $historyObj->reportCampaignMessageStatus($campaignhistorydetail['messageid']); 
               if(($campaignhistorydetail['senttime'] == NULL) OR ($campaignhistorydetail['senttime'] == '0000-00-00 00:00:00') OR ($campaignhistorydetail['senttime'] == ''))
                  $campaignhistorydetail['status']= 'Scheduled'; 
               else 
                  $campaignhistorydetail['status']= 'Sent';  
            }            
        }
        
        $this->view->campaignhistory = $campaignhistoryArray;
    }  
    
    public function campaignAction() {
        
        $historyObj = new Application_Model_Report(); 

        $userid = $this->user->getId();
        $subUsersArray = $historyObj->findChildEntityList($userid,5,"");  //echo "<pre>"; print_r($subUsersArray); exit;        
        if($this->request->getParam('locationName')!='' AND $this->request->getParam('locationName')!=0)
        {
            if($this->request->getParam('seehistoryby')=='month')
            {
                $startdate = $this->request->getParam('yearMessageReport').'-'.$this->request->getParam('monthMessageReport').'-1 00:00:00';
                $enddate = $this->request->getParam('yearMessageReport').'-'.$this->request->getParam('monthMessageReport').'-30 23:59:59';
                $campaignhistoryArray = $historyObj->reportCampaignHistoryByPeriod($this->request->getParam('locationName'),$startdate,$enddate);                
            }else
                $campaignhistoryArray = $historyObj->reportCampaignHistory($this->request->getParam('locationName'));
                
        }else{
                $campaignhistoryArray = $historyObj->reportCampaignHistory($userid);
        }

         //echo $this->request->getParam('locationName'); echo "<pre>"; print_r($campaignhistoryArray); exit;


        $this->view->campaignhistory = $campaignhistoryArray;
        $this->view->subusers = $subUsersArray;
        $this->view->monthList = $this->monthList(); 
        $this->view->yearList = $this->yearList();
        
        $this->view->monthSelected = $this->request->getParam('monthMessageReport');
        $this->view->yearSelected = $this->request->getParam('yearMessageReport');
        $this->view->locationSelected = $this->request->getParam('locationName');
        $this->view->seebySelected = $this->request->getParam('seehistoryby');
        $this->view->userid = $userid;
    }      
    
   public function monthList(){
       //$this->_helper->layout->disableLayout();
       $monthListArray = array(
                                '0' => '-Month-',
                                '1' => 'Jan',
                                '2' => 'Feb',
                                '3' => 'Mar',
                                '4' => 'Apr',
                                '5' => 'May',
                                '6' => 'Jun',
                                '7' => 'Jul',
                                '8' => 'Aug',
                                '9' => 'Sep',
                                '10' => 'Oct',
                                '11' => 'Nov',
                                '12' => 'Dec'
       );
       return $monthListArray;
   } 
   
   public function yearList(){
       //$this->_helper->layout->disableLayout();
       $yearListArray = array('0' => '-Year-');
       for($i=2004; $i<=date('Y');$i++)
       {
           $yearListArray[$i] = $i;
       }
       return $yearListArray;
   }    
   
   public function makeExcelReport($clientid){
       
    function cleanData(&$str) { 
        $str = preg_replace("/\t/", "\\t", $str); 
        $str = preg_replace("/\r?\n/", "\\n", $str); 
        if(strstr($str, '"')) 
                $str = '"' . str_replace('"', '""', $str) . '"'; 
    } 
// file name for download 
       $filepath = '/home/textm/textmunication.com/htdocs/public/reportdocs/';
       $filename = "weeklyreport_clientid" .$clientid.'_'. date('Ymd') . ".xls"; 
       echo $excelfile = $filepath.$filename;
       //$ourFileHandle = fopen($excelfile, 'w') or die("can't open file");
        if (!file_exists($excelfile)) {
            if(!touch($excelfile))
                echo "File can't be touched";
        } 
         
       //header("Content-Disposition: attachment; filename=\"$filename\""); 
       //header("Content-Type: application/vnd.ms-excel"); 
        header("Content-Type: text/plain");
       $reportObj = New Application_Model_Report();
       $flag = false; 
       $result = $reportObj->getWeeklyReport(); //print_r($result); exit;
       foreach($result as $row) { 
           if(!$flag) { 
// display field/column names as first row 
               //echo implode("\t", array_keys($row)) . "\r\n"; 
               $row1 = implode("\t", array_keys($row)) . "\r\n"; 
               logWrite("$row1");
               $flag = true;               
           } 
               array_walk($row, 'cleanData'); 
               //echo implode("\t", array_values($row)) . "\r\n"; 
               $row2 = implode("\t", array_values($row)) . "\r\n"; 
               logWrite("$row2");
             
       } 
  }
  
  public function sendweeklyAction(){
  
  }
  
  public function sendweeklyAction1(){
        $weeklyreport = new Application_Model_Report();
        $sendReportsTo = $weeklyreport->sendRportTo();
        $queueCount = count($sendReportsTo);
        $sendCount = 0;      
        if (is_array($sendReportsTo)) {
            if (!empty($sendReportsTo)) {
                foreach ($sendReportsTo as $user) { 
                    
                    $mail = new Zend_Mail();
                    $mail->setBodyText('Please see the weekly report:');
                    $mail->setFrom('reports@textmunication.com', 'Textmunication.com');
                    $mail->addTo($user['email'], 'Joseph Saunders');
                    $mail->addCc('wais@textmunication.com', 'Wais Asefi');
                    //$mail->addCc('robert.anthony.gonzalez@gmail.com', 'Robert Gonzalez');
                    $mail->setSubject('Weekly Reports');          
                            // Get the Excel model
                            $excel = new Application_Model_Excel();                    
                    if($weeklyreport->checkAdminUser($user['id']) AND $user['id']!=187)  
                    {
                        if(isset($user['edituser'])){
                            $excelDataArray = $weeklyreport->getWeeklyReportByEditUser($user['edituser']); //echo "single"; print_r($excelDataArray);   exit;
                            $date = date('Ymd');
                            $excelFileName = "weeklyreport_pollo" .$user['edituser'].'_'. $date;
                            logWrite("Creating the Excel spreadsheets");
                            $excel = new Application_Model_Excel();
                            $excelFile = $excel->create($excelDataArray, $excelFileName);
                            logWrite("Attaching the spreadsheets");
                            $at = $mail->createAttachment(file_get_contents($excelFile['path']));
                            $at->filename = $excelFile['name']; 
                        }else{
                        $excelDataArray = $weeklyreport->getWeeklyReport($user['id']);
                        $date = date('Ymd');
                        $excelFileName = "weeklyreport_clientid" .$user['id'].'_'. $date;
                        $excel = new Application_Model_Excel();
                        $excelFile = $excel->create($excelDataArray, $excelFileName);
                        $at = $mail->createAttachment(file_get_contents($excelFile['path']));
                        $at->filename = $excelFile['name'];                         
                        }}else{
                            // Get the subscriber datasets
                            $excelDataArray = $weeklyreport->getWeeklyReport(); //echo "<pre>"; print_r($excelDataArray);   exit;

                            // Get a date stamp for the file
                            $date = date('Ymd');

                            // Create our file names
                            $excelFileName = "weeklyreport_clientid" .$user['id'].'_'. $date;
                            

                            // Log the steps
                            //logWrite("Creating the Excel spreadsheets");

                            // Make the Excel files for each day
                            $excelFile = $excel->create($excelDataArray, $excelFileName); 
                            $at = $mail->createAttachment(file_get_contents($excelFile['path']));
                            $at->filename = $excelFile['name'];                            
                    }                                        

                    // Log the steps
                    //logWrite("Preparing to send...");
                    // Send it off
                    if (!$mail->send()) {
                        echo "MESSAGE NOT SENT";
                    } else {
                        echo "Message sent";
                    } 				
                }
            } else {
                $this->error = "No reports to send";
            }
        } else {
            $this->error = "Send to report was not properly fetched";
        }      
  }
  
    public function subscribersAction() {

             $rmonth = "";
             $ryear = "";
             $seereportby = $this->request->getParam('reportby');
             if($this->request->getParam('searchvaluehide')!='')
                    $searchvalue = $this->request->getParam('searchvaluehide');
             else
                    $searchvalue = $this->request->getParam('searchvalue');
             if($seereportby=="month")
             {
                 $rmonth = $this->request->getParam('monthMessageReport');
                 $ryear = $this->request->getParam('yearMessageReport');
                 if($rmonth==0 OR $ryear==0)
                  {
                     $monthstartdate = 0;
                     $monthenddate = 0;
                  }else{
                         $monthstartdate = $ryear."-".$rmonth."-1 00:00:00"; 
                         $monthenddate = $ryear."-".$rmonth."-30 00:00:00";                 
                 }                      
             }elseif($seereportby=="date")
             {
                $monthstartdate = date("Y-m-d",strtotime($this->request->getParam('starttime')));
                $monthenddate = date("Y-m-d",strtotime($this->request->getParam('endtime')));         
             }elseif($seereportby=="")
             {
                 $monthstartdate = date('Y')."-".date('m')."-1 00:00:00";
                 $monthenddate = date('Y')."-".date('m')."-30 00:00:00";                  
             }
        
        $reportObj =  new Application_Model_Report();
        
        $pagenum = $this->request->getParam('page');
        if($pagenum=='' OR $pagenum==0)
          $pagenum = 1;
                

        $arraystart = ($pagenum*5) - 5 ;
        $arrayend = ($pagenum*5);        
        
        if($this->request->getParam('reportuserid')!='')
              $userid = $this->request->getParam('reportuserid');
           else     
              $userid = $this->user->getId();          
        
        if($reportObj->checkAdminUser($userid))  
        {
            $this->view->userTypeshowing = 'users'; 
            $subUsersArray = $reportObj->findChildEntityList($userid,5,$searchvalue);  //echo "<pre>"; print_r($subUsersArray); exit;
            $totalSubUsers = count($subUsersArray);
            
            
            foreach ($subUsersArray as $key => $row) {
                $username[$key]  = $row['username'];
            } 
            $orderby = $this->request->getParam('orderby');
            if($orderby == 'ASC')
                array_multisort($username, SORT_ASC, $subUsersArray);
            elseif($orderby == 'DESC')
                array_multisort($username, SORT_DESC, $subUsersArray);
            //echo "<pre>"; print_r($subUsersArray); exit;    
            $this->view->sortOrderBy = $orderby;
            

            //$this->view->userlist = $subUsersArray;
            $allDetailsArray = array();

             foreach($subUsersArray as $subUser)
            {
                $allDetailsArray[$subUser['childuserid']]['username'] = $subUser['username'];
                $allDetailsArray[$subUser['childuserid']]['totalKeywords'] = $reportObj->reportCountTotalKeywordByUserId($subUser['childuserid'],$monthstartdate,$monthenddate);
                 $allDetailsArray[$subUser['childuserid']]['totalSubscribers'] = $reportObj->reportCountTotalSubscribersByUserId($subUser['childuserid']['childuserid'],$monthstartdate,$monthenddate); 
                $allDetailsArray[$subUser['childuserid']]['totalOptins'] = $reportObj->reportCountTotalOptinsByUserId($subUser['childuserid'],$monthstartdate,$monthenddate); 
                $allDetailsArray[$subUser['childuserid']]['totalOptouts'] = $reportObj->reportCountTotalOptoutsByUserId($subUser['childuserid'],$monthstartdate,$monthenddate); 
                $allDetailsArray[$subUser['childuserid']]['totalCampaigns'] = $reportObj->reportCountTotalCampaignsByUserId($subUser['childuserid'],$monthstartdate,$monthenddate); 
                $allDetailsArray[$subUser['childuserid']]['totalCampaignMessages'] = $reportObj->reportCountTotalCampaignMessagesByUserId($subUser['childuserid'],$monthstartdate,$monthenddate);
                $allDetailsArray[$subUser['childuserid']]['totaloutboundmessage'] = $reportObj->reportCountTotalMessagesByUserId($subUser['childuserid'],$monthstartdate,$monthenddate);
            }
//echo "<pre>"; print_r($allDetailsArray); exit;
            
        }else{
                $this->view->userTypeshowing = 'locations';
                $subFoldersArray = $reportObj->findChildEntityList($userid,4); //echo "<pre>"; print_r($subFoldersArray); echo exit;
              
                $totalSubUsers = count($subFoldersArray);

                for($i = $arraystart; $i< $arrayend; $i++)
                { 
                    if(!empty($subFoldersArray[$i]))
                    {              
                        $allDetailsArray[$subFoldersArray[$i]['folderid']]['username'] = $subFoldersArray[$i]['foldername'];
                        $allDetailsArray[$subFoldersArray[$i]['folderid']]['totalKeywords'] = $subFoldersArray[$i]['totalkeywords'];
                        $allDetailsArray[$subFoldersArray[$i]['folderid']]['totalSubscribers'] = $reportObj->totalSubscribersByFolder($subFoldersArray[$i]['folderid'],$monthstartdate,$monthenddate);
                        $allDetailsArray[$subFoldersArray[$i]['folderid']]['totalOptins'] = $reportObj->totalOptinsByFolder($subFoldersArray[$i]['folderid'],$monthstartdate,$monthenddate);
                        $allDetailsArray[$subFoldersArray[$i]['folderid']]['totalOptouts'] = $reportObj->totalOptoutsByFolder($subFoldersArray[$i]['folderid'],$monthstartdate,$monthenddate); 
                        $allDetailsArray[$subFoldersArray[$i]['folderid']]['totalCampaigns'] = $reportObj->totalCampaignByFolder($subFoldersArray[$i]['folderid'],$monthstartdate,$monthenddate);
                        $allDetailsArray[$subFoldersArray[$i]['folderid']]['totalCampaignMessages'] = $reportObj->getTotalCampaignMessagesByFolder($subFoldersArray[$i]['folderid'],$monthstartdate,$monthenddate);
                        $allDetailsArray[$subFoldersArray[$i]['folderid']]['totaloutboundmessage'] = $reportObj->getTotalOutboundMessages($subFoldersArray[$i]['folderid'],$monthstartdate,$monthenddate);                        
                    }
                }
        }
    if(!empty($allDetailsArray))
    {
        $allDetailsArray['total']['username'] = '<b>Total</b>';
        $allDetailsArray['total']['totalKeywords'] = '<b>'.$reportObj->reportCountTotalKeywordByUserId($userid,$monthstartdate,$monthenddate).'</b>';  
        $allDetailsArray['total']['totalSubscribers'] = '<b>'.$reportObj->reportCountTotalSubscribersByUserId($userid,$monthstartdate,$monthenddate).'</b>';
        $allDetailsArray['total']['totalOptins'] = '<b>'.$reportObj->reportCountTotalOptinsByUserId($userid,$monthstartdate,$monthenddate).'</b>';
        $allDetailsArray['total']['totalOptouts'] = '<b>'.$reportObj->reportCountTotalOptoutsByUserId($userid,$monthstartdate,$monthenddate).'</b>';
        $allDetailsArray['total']['totalCampaignMessages'] = '<b>'.$reportObj->reportCountTotalCampaignMessagesByUserId($userid,$monthstartdate,$monthenddate).'</b>';
        $allDetailsArray['total']['totaloutboundmessage'] = '<b>'.$reportObj->reportCountTotalMessagesByUserId($userid,$monthstartdate,$monthenddate).'</b>';   
    }
        //echo "<pre>"; print_r($allDetailsArray); exit;
         
        
        $this->view->seeReportBy = $seereportby;
        $this->view->totalSubUsers = $totalSubUsers;
        $this->view->allDetailsArray = $allDetailsArray;
        $this->view->searchvalue = $searchvalue; 
        $this->view->reportuserid = $userid;
        $this->view->starttime = $this->request->getParam('starttime');
        $this->view->endtime = $this->request->getParam('endtime');
        
         if($this->request->getParam('shownumlist')!='')
         {
            $this->view->showNumList = $this->request->getParam('shownumlist');
         }else{
           $this->view->showNumList = 10;
         } 
        
         if($rmonth=='' OR $ryear=='')
         {
             $this->view->monthSelected = date('m');
             $this->view->yearSelected = date('Y');
         }else{
             $this->view->monthSelected = $rmonth;
             $this->view->yearSelected = $ryear;
         }          

    }

#########################################################################

public function customreportAction(){
        $weeklyreport = new Application_Model_Report();
        
                            $excel = new Application_Model_Excel();                    
                    $userid = 787;
                        $excelDataArray = $weeklyreport->getWeeklyReport($userid);
                        $date = date('Ymd');
                        $excelFileName = "report_clientid" .$user['id'].'_'. $date;
                        $excel = new Application_Model_Excel();
                        $excelFile = $excel->create($excelDataArray, $excelFileName);    
  }

public function reportAction() {
 $monthstartdate =$this->request->getParam('startdate');
 $monthenddate =$this->request->getParam('enddate');;
 $searchvalue = "";
 $yearmonth =$this->request->getParam('yearmonth');
// echo $monthstartdate."<>".$monthenddate; 
 
// exit;

          $filename = 'myreport';
		  $file_path = "/home/textm/textmunication.com/htdocs/public/reportdocs/$filename";
header("Content-type: application/vnd.ms-excel");
header("Content-disposition: csv" . date("Y-m-d") . ".csv");
header( "Content-disposition: filename=".$filename.".csv");
 echo "Accounts/Location/Folder,Total Keyword,Total Subscribers,Total Optsin,Total Optsout,Total Campaign,Total Campaign Message,Retention\r\n";
			
        $reportObj =  new Application_Model_Report();
        $datareport = new Application_Model_DataReporting();
        $pagenum = $this->request->getParam('page');
        if($pagenum=='' OR $pagenum==0)
          $pagenum = 1;
               
        $arraystart = ($pagenum*5) - 5 ;
        $arrayend = ($pagenum*5);        
        
        if($this->request->getParam('reportuserid')!='')
            $userid = $this->request->getParam('reportuserid');
           else     
            $userid = $this->user->getId();         
        
        if($reportObj->checkAdminUser($userid))  
        { 
            $this->view->userTypeshowing = 'users'; 
//            $subUsersArray = $reportObj->findChildEntityList($userid,5,$searchvalue);  //echo "<pre>"; print_r($subUsersArray); exit;
            $subUsersArray = $datareport->corporate_message_reporting($userid, $yearmonth,$monthstartdate,$monthenddate);
            $totalSubUsers = count($subUsersArray);
            
            if($totalSubUsers >0)
	    {
	      foreach ($subUsersArray as $key => $row) {
		  $username[$key]  = $row['username'];
	      } 
	    }
	    else
	    {
	      $subUsersArray ='';
	    }
            $orderby = $this->request->getParam('orderby');
            if($orderby == 'ASC')
                array_multisort($username, SORT_ASC, $subUsersArray);
            elseif($orderby == 'DESC')
                array_multisort($username, SORT_DESC, $subUsersArray);
            //echo "<pre>"; print_r($subUsersArray); exit;    
            $this->view->sortOrderBy = $orderby;
            

            //$this->view->userlist = $subUsersArray;
                           $allDetailsArray = array();

		$arraystart= 0;
		$arrayend = $totalSubUsers;
		

	  if(!empty($subUsersArray))
	  {
	  
	    for($i = $arraystart; $i< $arrayend; $i++)
	    { 
	      if(!empty($subUsersArray[$i]))
	      {
		echo $allDetailsArray[$i] = $subUsersArray[$i]['firstname']." ".$subUsersArray[$i]['lastname'];
		echo ",".$allDetailsArray[$i] = $subUsersArray[$i]['keywords'];
		echo ",".$allDetailsArray[$i] = $subUsersArray[$i]['optin'];
		echo ",".$allDetailsArray[$i] = $subUsersArray[$i]['subscribers'];
		echo ",".$allDetailsArray[$i] = $subUsersArray[$i]['optout'];
		echo ",".$allDetailsArray[$i] = $subUsersArray[$i]['campaign'];
		echo ",".$allDetailsArray[$i] = $subUsersArray[$i]['sms'];
		
		if($subUsersArray[$i]['optin']!=0){
		echo ",".($subUsersArray[$i]['subscribers']/$subUsersArray[$i]['optin'])*100;
		}else{
		echo ","."0";
		}
		echo "%";
		echo "\r\n";
	      }
	    }
	  }
	  else
	  { 
				$this->view->userTypeshowing = 'locations';
                $subFoldersArray = $reportObj->findChildEntityList($userid,4); //echo "<pre>"; print_r($subFoldersArray); echo exit;
              
                $totalSubUsers = count($subFoldersArray);

	  }           
        }
	else{  
                $this->view->userTypeshowing = 'locations';
//                $subFoldersArray = $reportObj->findChildEntityList($userid,4); //echo "<pre>"; print_r($subFoldersArray); echo exit;
                $subFoldersArray = $datareport->account_message_reporting($userid, $yearmonth,$monthstartdate,$monthenddate);
                $totalSubUsers = count($subFoldersArray);

                for($i = 0; $i< $totalSubUsers; $i++)
                { 
                    if(!empty($subFoldersArray[$i]))
                    {          
                       echo $allDetailsArray[$i] = $subFoldersArray[$i]['value'];
                       echo ",".$allDetailsArray[$i] = $subFoldersArray[$i]['keywords'];
                       echo ",".$allDetailsArray[$i] = $subFoldersArray[$i]['optin'];
                       echo ",".$allDetailsArray[$i] = $subFoldersArray[$i]['subscribers'];
                       echo ",".$allDetailsArray[$i] = $subFoldersArray[$i]['optout'];
                       echo ",".$allDetailsArray[$i] = $subFoldersArray[$i]['campaign'];
                       echo ",". $allDetailsArray[$i] = $subFoldersArray[$i]['total_messages'];
                       
                            if($subFoldersArray[$i]['optin']!=0){
                            echo ",".($subFoldersArray[$i]['subscribers'])/($subFoldersArray[$i]['optin'])*100;
                            }else{
                            echo ","."0";
                            }
                            echo "%";
                            //echo "i am here";
                            echo "\r\n";
                    }
                }
        }
         
        exit;
    }  
	
	public function testAction(){
	echo "this is my test page";
	}

public function messagemonthyearAction($showLayout = false) {
    	if(!$showLayout)
    		$this->_helper->layout->disableLayout(); 
    	list($key_used, $key_deleted) = $this->user->getKeywordsCountNew();
    	$this->view->key_used = $key_used - $key_deleted;
		$this->view->key_limit   = $this->user->keywordlimit;    	    	
		$this->view->key_percent = sprintf("%d", ($this->view->key_used / $this->view->key_limit) * 100);
		
		
		$this->view->msg_limit   = $this->user->messagelimit;
                $messageObj = new Application_Model_Report(); 
		$userid = $this->user->getId();
		        //*********** Calculate startdate and end date................
		$rmonth = date('m');
		$ryear = date('Y');
                $yearmonth = date('Y-m');
		$daysInMonth = cal_days_in_month(CAL_GREGORIAN, $rmonth, $ryear);
        // echo 'rmstart-'.$monthstartdate = $ryear."-".$rmonth."-1 00:00:00"; echo '<br>';
        //echo 'rmend-'.$monthenddate = $ryear."-".$rmonth."-30 00:00:00";  
		$monthstartdate = $ryear."-".$rmonth."-1 00:00:00"; 
		$monthenddate = $ryear."-".$rmonth."-".$daysInMonth." 00:00:00";    
	
		$this->view->msg_used    = $messageObj->reportCountTotalMessagesByUserIdNew($userid,$monthstartdate,$monthenddate);

		$this->view->totalNewSubscribers = $messageObj->reportCountTotalSubscribersByUserIdNew( $userid, $monthstartdate, $monthenddate, true);
                             $this->view->totalKeywordSubscribers = $messageObj->reportCountRegGrpBySubscribersByUserIdNewKeys($userid,life);                       
                             $this->view->totalImportedSubscribers = $this->user->totalupload;
                             $myla = $messageObj->getMylaFoldersId();
                             $this->view->totalMyla = $messageObj->getMyla($userid,$myla[$userid],'life');

                     $this->view->topThreeKeywordArray = $topThreeKeywordArray; 
        /*
          /*one more mess wont change face of this horror*/  
                $this->view->dashboardKeywordsAct = $messageObj->dashboardKeyword_activity($userid);
//                $this->view->dashboardTotalOptins = $messageObj->dashboard_totalOptedIn($userid);
                $this->view->dashboardTotalOptins = $this->user->totalsubscribers;
//                $this->view->dashboardTotalOptinsByWebform = $messageObj->dashboard_totalOptedInsByWebform($userid);
                $this->view->dashboardTotalOptinsByWebform = $this->user->weboptin;
                $this->view->dashboardTotalMessagesSend = $messageObj->dashboard_totalMessagesSentOut($userid,$yearmonth);
//                $this->view->dashboardTotalCampaignSend = $messageObj->dashboard_totalCampaignSentOut($userid,$yearmonth);
                $msgpercent = ($this->view->dashboardTotalMessagesSend / $this->view->msg_limit) * 100;
		if($msgpercent >= 100 ) $msgpercent = 100;
		$this->view->msg_percent = sprintf("%d", $msgpercent);
                $this->view->userid = $userid;
                $this->view->mylamt = $this->user->mylamt;
                $this->view->kwdmt = $this->user->keywordmt;
                $this->view->webmt = $this->user->webmt;
                $this->view->dobmt = $this->user->dobmt;
//                $this->view->totalcampsent = $this->user->totalcampsent;
                $this->view->totalcampsent = $messageObj->totalCampaignFromUserAccount($userid,$yearmonth);
//                echo $userid."  :  ".$yearmonth."  : ".$this->view->totalcampsent; exit;
                $this->view->totalmt = $this->user->totalmt;
                $this->view->totalmrkmt = $this->user->totalmrkmt;
                
    }	
}
?>
