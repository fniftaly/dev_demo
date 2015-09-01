<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of NevController
 *
 * @author farad
 */
class NevController  extends AuthorizedController{
    
   public function setnevAction(){
     $clubid = $this->user->getId();
     $nvd = new Application_Model_Nevdata();
     $nev = $nvd->nevsetSelect($clubid);
     $nevlocations = $nvd->getNevlocations($clubid);
     if($this->user->nev){
       $continent = $nvd->getContinent($clubid);
      if($continent == 1){$nevtype = "Ufc";}
         elseif($continent == 2){$nevtype = "Snf";}
         elseif($continent == 3){$nevtype = "Jv";}
         elseif($continent == 0){$nevtype = "show";}
      }
      if($this->user->nev){$autotype = "Nev";}
     if($this->user->abc){$autotype = "Abc";$nevtype="hide";}
     if(count($nevlocations) != 0){
         $bloc = TRUE;
     }
     if($bloc){
         reset($nevlocations);
         $key = key($nevlocations);
         $nevsetup =$nvd->nevsetSelect($key);
     }else{
         $nevsetup =$nev;
     }
//     echo $autotype;
     $this->view->nevsetup = $nevsetup;
     $this->view->locations = $nevlocations;
     $this->view->bloc = $bloc;
     $this->view->autotype = $autotype;
     $this->view->nevtype = $nevtype;
     $this->view->continent = $continent;
    }
   public function getdataAction(){
 if ($this->getRequest()->isPost()) {
     $clbn = $this->user->getId();
     $nvc = new Application_Model_NevClub();
     $nvd = new Application_Model_Nevdata();
    
     $id = $this->request->getParam('id');
      $clubid = ($id)?$id:$clbn;
     
     $nvc->setClubid($clubid);
     $bodsend = $this->request->getParam('bodsend').':00';
     $bodmsg = $this->request->getParam('bodmsg');
     $bodconf = $this->request->getParam('bodconf');
     
     $nvc->setBodsend($bodsend);
     $nvc->setBodmsg($bodmsg);
     $nvc->setBodconf($bodconf);
     
     $blngfirstsend = $this->request->getParam('blngfirstsend').':00';
     $blngfirstmsg = $this->request->getParam('blngfirstmsg');
     $blngfirstconf = $this->request->getParam('blngfirstconf');
     
     $nvc->setBlngfirstsend($blngfirstsend);
     $nvc->setBlngfirstmsg($blngfirstmsg);
     $nvc->setBlngfirstconf($blngfirstconf);
     
     $blngsecondsend = $this->request->getParam('blngsecondsend').':00';
     $blngsecondmsg = $this->request->getParam('blngsecondmsg');
     $blngsecondconf = $this->request->getParam('blngsecondconf');
     
     $nvc->setBlngsecondsend($blngsecondsend);
     $nvc->setBlngsecondmsg($blngsecondmsg);
     $nvc->setBlngsecondconf($blngsecondconf);
     
     $blngthirdsend = $this->request->getParam('blngthirdsend').':00';
     $blngthirdmsg = $this->request->getParam('blngthirdmsg');
     $blngthirdconf = $this->request->getParam('blngthirdconf');
     
     $nvc->setBlngthirdsend($blngthirdsend);
     $nvc->setBlngthirdmsg($blngthirdmsg);
     $nvc->setBlngthirdconf($blngthirdconf);
     
     $blngfourthsend = $this->request->getParam('blngfourthsend').':00';
     $blngfourthmsg = $this->request->getParam('blngfourthmsg');
     $blngfourthconf = $this->request->getParam('blngfourthconf');
     
     $nvc->setBlngfourthsend($blngfourthsend);
     $nvc->setBlngfourthmsg($blngfourthmsg);
     $nvc->setBlngfourthconf($blngfourthconf);
     
     $blngfifthsend = $this->request->getParam('blngfifthsend').':00';
     $blngfifthmsg = $this->request->getParam('blngfifthmsg');
     $blngfifthconf = $this->request->getParam('blngfifthconf');
     
     $nvc->setBlngfifthsend($blngfifthsend);
     $nvc->setBlngfifthmsg($blngfifthmsg);
     $nvc->setBlngfifthconf($blngfifthconf);
     
     $blngsixthsend = $this->request->getParam('blngsixthsend').':00';
     $blngsixthmsg = $this->request->getParam('blngsixthmsg');
     $blngsixthconf = $this->request->getParam('blngsixthconf');
     
     $nvc->setBlngsixthsend($blngsixthsend);
     $nvc->setBlngsixthmsg($blngsixthmsg);
     $nvc->setBlngsixthconf($blngsixthconf);
     
     $campaignsend = $this->request->getParam('campaignsend').':00';
     $campaignmsg = $this->request->getParam('campaignmsg');
     $campaignconf = $this->request->getParam('campaignconf');
     
     $nvc->setCampaignsend($campaignsend).':00';
     $nvc->setCampaignmsg($campaignmsg);
     $nvc->setCampaignconf($campaignconf);
     
     $continent = $this->request->getParam('continent');
     $nvc->setContinent($continent);
     
     
    $club = $nvd->verifyclubstatus($clubid);
        if(!$club)
            {
            if($nvd->setnevstatus($nvc))
             $rtn = "true";   
        } else{
            if($nvd->updateMonitoring($nvc))
                 $rtn = "true";
        } 
        echo $rtn;
  }
//        echo "bod: ".$bodsend."\nbodmsg: ".$bodmsg."\nbodconf: ".$bodconf."\nblng1send: ".$blngfirstsend."\nblng1msg: ".$blngfirstmsg."\nblng1con: ".$blngfirstconf.
//                "\nblng2send: ".$blngsecondsend."\nblng2msg: ".$blngsecondmsg."\nblng2conf: ".$blngsecondconf."\nblng3send: ".$blngthirdsend.
//                "\nblng3msg: ".$blngthirdmsg."\nblng3conf: ".$blngthirdconf."\nblng4send: ".$blngfourthsend."\nblng4msg: ".$blngfourthmsg.
//                "\nblng4conf: ".$blngfourthconf."\ncamsend: ".$campaignsend."\ncammsg: ".$campaignmsg."\ncampconf: ".$campaignconf;
        exit;
    }
    
     public function nevlocdataAction(){
        if ($this->getRequest()->isPost()) {
            $clubid = $this->request->getParam('id');
            $nvd = new Application_Model_Nevdata();
            $nevset = $nvd->nevsetSelect($clubid);
            echo json_encode($nevset);
        }
        exit;
    }
}

?>
