<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LoyaltyController
 *
 * @author farad
 */
class LoyaltyController extends AuthorizedController {
    
    public function indexAction(){}
    /**
     * 
     * 
     */
    public function addAction(){
//       $rpt = new  Application_Model_Report();
//       echo '<pre>'; print_r($rpt->getWeeklyReportApiuser(1121));
//       exit;
         $userid = $this->user->getId();
          $rwd = new Application_Model_Rewards();
         $all = $rwd->getAllRewards($userid);
          $isrange = $rwd->getVisitorsrange($userid);
         $cnt = count($all);
         $this->view->userid = $userid;
         $this->view->rwobj = $rwd;
         $this->view->allrewards = $all;
         $this->view->range = $isrange[1];
    }
    /**
     * 
     * 
     */
       public function newviewAction() {
        $userid = $this->user->getId();
        $rwd = new Application_Model_Rewards();
        $all = $rwd->getAllRewards($userid);
        $range = $rwd->getVisitorsrange($userid);
        $this->view->userid = $userid;
        $this->view->rwobj = $rwd;
        $this->view->allrewards = $all;
        $ids = $rwd->getRows($userid);
        $this->view->rows =$ids;
        $this->view->range = $range[1];
//        echo $rwd->rewordCycle($userid);
    }
    /**
     * 
     * 
     */
    public function rewardmsgAction(){
          $rwd = new Application_Model_Rewards();
          if ($this->getRequest()->isPost()) {
             
            $userid     = trim($this->request->getParam('userid'));
            $phone     = trim($this->request->getParam('phone'));
            
            $msgs = $rwd->getRewardMsg($userid, $phone);
           
            $rst = json_encode($msgs);
            
            echo $rst;
            exit;
         }
    }
    /**
     * 
     * 
     */
    public function addonAction(){
          $rwd = new Application_Model_Rewards();
         if ($this->getRequest()->isPost()) {
             
            $row     = trim($this->request->getParam('row'));
            $msg     = utf8_decode(trim($this->request->getParam('msg')));
            $rable     = trim($this->request->getParam('rable'));
            $visit     = trim($this->request->getParam('visit'));
            $userid = $this->user->getId();
            
            $reward = $rwd->verifyRewardmsg($userid, $row);
            if($reward == 0)
                {
                $rwd->insertRewardmsg($userid, $msg, $visit, $rable, $row);
                echo 'Data has been saved';
               }
            else{
                $rwd->updateReward($userid, $msg, $visit, $rable, $row);
                echo 'Changes has been saved';
            }
//            echo $row."\n".$msg."\n".$rable."\n".$visit."\n".$userid."\nRew: ".$reward;
            exit;
         }
    }
    /**
     * 
     * 
     */
     public function visitorrangeAction() {
        if ($this->getRequest()->isPost()) {
            $userid = $this->user->getId();
            $rwd = new Application_Model_Rewards();
             $range = trim($this->request->getParam('range'));
             
             $isrange = $rwd->getVisitorsrange($userid);
             
            if(count($isrange) ==0){
                $rwd->insertVisitorsrange($userid, $range);
                echo "Data has been saved";
            }else{
                $rwd->updateVisitorsrange($userid, $range);
                echo "Changes has been saved";
            }
           
        }
        exit;
    }
    /**
     * 
     * 
     */
     public function rewardmsgbodyAction(){
       if ($this->getRequest()->isPost()) {
           $rwd = new Application_Model_Rewards();
            $userid = $this->user->getId();
           $rownum = trim($this->request->getParam('row'));
           $msg = $rwd->getrewardMsgbody($userid, $rownum);
           echo $msg;
       }
       exit;
   }
}


?>
