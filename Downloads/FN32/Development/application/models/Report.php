<?php

/**
 * Application_Model_User class.
 * 
 * @extends Application_Model_Entityabstract
 */
class Application_Model_Report extends Application_Model_Abstract {

    /**
     * Can this user create contests?
     * 
     * @access public
     * @return void
     */
    public function getMessageCountList($userid) {
        if ($userid) {
            $sql = "CALL user_message_sent_count($userid)";
            $rs = $this->query($sql);
            if ($rs->hasRecords()) {
                if ($rs->total) {
                    return $rs->total;
                }
            }
        }
        return 0;
    }

    public function getMessageCountListMonthYear($userid, $rmonth, $ryear) {
        if ($userid) {
            $monthStartDate = date("Y-m-d H:i:s", strtotime($ryear . '-' . $rmonth . '-01' . ' 00:00:00'));
            $monthEndDate = date("Y-m-d H:i:s", strtotime('-1 second', strtotime('+1 month', strtotime($monthStartDate))));

            $sql = "CALL user_message_sent_count_my($userid,'$monthStartDate','$monthEndDate')";
            $rs = $this->query($sql);
            if ($rs->hasRecords()) {
                if ($rs->total) {
                    return $rs->total;
                }
            }
        }
        return 0;
    }

    public function getMessageCountListLastPeriod($userid, $period) {
        if ($userid) {
            $monthEndDate = date("Y-m-d H:i:s");
            $monthStartDate = date('Y-m-d H:i:s', strtotime('-' . $period . ' days'));

            $sql = "CALL user_message_sent_count_my($userid,'$monthStartDate','$monthEndDate')";
            $rs = $this->query($sql);
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
            $rs = $this->query($sql);
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
            $rs = $this->query($sql);
            if ($rs->hasRecords()) {
                if ($rs->total) {
                    return $rs->total;
                }
            }
        }
        return 0;
    }

    public function getTotalSubscribers($userid) {
        if ($userid) {
            $sql = "CALL total_subscribers($userid)";
            $rs = $this->query($sql);
            if ($rs->hasRecords()) {
                if ($rs->total) {
                    return $rs->total;
                }
            }
        }
        return 0;
    }

    public function getUsername($userid) {
        $sql = "CALL get_username_by_userid($userid)";
        $rs = $this->query($sql);

        if ($rs->hasRecords()) {
            // Not sure if there is an easier way to get this, but this works...
            $user = $rs->getResults(0);
            //$userid = $user[0]['id'];
            //echo '<pre>'; print_r($user); exit;
            return $user;
        }

        $this->error = 'Record not found.';
        return false;
    }

    public function getTotalOutBoundByPeriod($userid, $period) {
        if ($userid) {
            $monthEndDate = date("Y-m-d H:i:s");
            $monthStartDate = date('Y-m-d H:i:s', strtotime('-' . $period . ' days'));
            //echo  $monthEndDate;
            //echo $monthStartDate;
            //exit;

            $sql = "CALL total_out_bound_period($userid,'$monthStartDate','$monthEndDate')";
            $rs = $this->query($sql);
            if ($rs->hasRecords()) {
                if ($rs->total) {
                    return $rs->total;
                }
            }
        }
        return 0;
    }

    public function getTotalCampaignMessagesCountByuser($userid) {
        if ($userid) {
            $sql = "CALL count_campaign_history($userid)";
            $rs = $this->query($sql);
            if ($rs->hasRecords()) {
                if ($rs->total) {
                    return $rs->total;
                }
            }
        }
        return 0;
    }

    public function getTotalCampaignCountByuser($userid) {
        if ($userid) {
            $sql = "CALL report_count_campaign_byid_2($userid)";
            $rs = $this->query($sql);
            if ($rs->hasRecords()) {
                if ($rs->total) {
                    return $rs->total;
                }
            }
        }
        return 0;
    }

    public function getTotalOptInsPeriod($userid, $period) {
        if ($userid) {
            $monthEndDate = date("Y-m-d H:i:s");
            $monthStartDate = date('Y-m-d H:i:s', strtotime('-' . $period . ' days'));

            $sql = "CALL total_opt_ins_period($userid,'$monthStartDate','$monthEndDate')";
            $rs = $this->query($sql);
            if ($rs->hasRecords()) {
                if ($rs->total) {
                    return $rs->total;
                }
            }
        }
        return 0;
    }

    public function getTotalOptOutsPeriod($userid, $period) {
        if ($userid) {
            $monthEndDate = date("Y-m-d H:i:s");
            $monthStartDate = date('Y-m-d H:i:s', strtotime('-' . $period . ' days'));

            $sql = "CALL total_opt_outs_period($userid,'$monthStartDate','$monthEndDate')";
            $rs = $this->query($sql);
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
            $rs = $this->query($sql);
            if ($rs->hasRecords()) {
                return $rs->fetchAll();
            }
        }
        return NULL;
    }

    public function reportCampaignHistoryByPeriod($userid, $startdate, $enddate) {
        if ($userid) {
            $sql = "CALL campaign_history_report_byperiod($userid,'$startdate','$enddate')";
            $rs = $this->query($sql);
            if ($rs->hasRecords()) {
                return $rs->fetchAll();
            }
        }
        return NULL;
    }

    public function reportCampaignMessageStatus($messageid) {
        if ($messageid) {
            $sql = "CALL campaign_message_status($messageid)";
            $rs = $this->query($sql);
            if ($rs->hasRecords()) {
                return $rs->fetchAll();
            }
        }
        return NULL;
    }

    public function listTopthreeFolders($userid) {
        if ($userid) {
            $sql = "CALL top_three_folder_optins($userid)";
            $rs = $this->query($sql);
            if ($rs->hasRecords()) {
                return $rs->fetchAll();
            }
        }
        return NULL;
    }

    public function listTopthreeKeywords($userid) {
        if ($userid) {
            //$sql = "CALL top_three_keyword_optins($userid)";
            //now trying to get all keywords
            $sql = "CALL keyword_folder_report($userid)";

            $rs = $this->query($sql);
            if ($rs->hasRecords()) {
                return $rs->fetchAll();
            }
        }
        return NULL;
    }

    public function listTopthreeKeywordsFilterDeleted($userid) {
        if ($userid) {
            //$sql = "CALL top_three_keyword_optins($userid)";
            //now trying to get all keywords
            $sql = "CALL keyword_folder_report_topthree($userid)";

            $rs = $this->query($sql);
            if ($rs->hasRecords()) {
                return $rs->fetchAll();
            }
        }
        return NULL;
    }

    public function totalSubscribersByFolder($folderid, $startdate = 0, $enddate = 0) {
        if ($folderid) {
            if ($startdate == 0 OR $enddate == 0) {
                $sql = "CALL count_total_subscribers_byfolder($folderid)";
            } else {
                $sql = "CALL count_total_subscribers_byfolder_byperiod($folderid,'$startdate','$enddate')";
            }
            $rs = $this->query($sql);
            if ($rs->hasRecords()) {
                if ($rs->total) {
                    return $rs->total;
                }
            }
        }
        return NULL;
    }

    public function totalOptinsByFolder($folderid, $startdate = 0, $enddate = 0) {
        if ($folderid) {
            if ($startdate == 0 OR $enddate == 0) {
                $sql = "CALL count_total_optins_byfolder($folderid)";
            } else {
                $sql = "CALL count_total_optins_byfolder_byperiod($folderid,'$startdate','$enddate')";
            }
            $rs = $this->query($sql);
            if ($rs->hasRecords()) {
                if ($rs->total) {
                    return $rs->total;
                }
            }
        }
        return NULL;
    }

    public function totalOptoutsByFolder($folderid, $startdate = 0, $enddate = 0) {
        if ($folderid) {
            if ($startdate == 0 OR $enddate == 0) {
                $sql = "CALL count_total_optouts_byfolder($folderid)";
            } else {
                $sql = "CALL count_total_optouts_byfolder_byperiod($folderid,'$startdate','$enddate')";
            }

            $rs = $this->query($sql);
            if ($rs->hasRecords()) {
                if ($rs->total) {
                    return $rs->total;
                }
            }
        }
        return NULL;
    }

    public function totalCampaignByFolder($folderid, $startdate = 0, $enddate = 0) {
        if ($folderid) {
            if ($startdate == 0 OR $enddate == 0) {
                $sql = "CALL count_total_campaign_byfolder($folderid)";
            } else {
                $sql = "CALL count_total_campaign_byfolder_byperiod($folderid,'$startdate','$enddate')";
            }
            $rs = $this->query($sql);
            if ($rs->hasRecords()) {
                if ($rs->total) {
                    return $rs->total;
                }
            }
        }
        return NULL;
    }

    public function getTotalOutboundMessages($folderid, $startdate = 0, $enddate = 0) {
        if ($folderid) {
            if ($startdate == 0 OR $enddate == 0) {
                $sql = "CALL count_totaloutbound_byfolder($folderid)";
            } else {
                $sql = "CALL count_totaloutbound_byfolder_byperiod($folderid,'$startdate','$enddate')";
            }
            $rs = $this->query($sql);
            if ($rs->hasRecords()) {
                if ($rs->total) {
                    return $rs->total;
                }
            }
        }
        return 0;
    }

    public function getTotalCampaignMessagesByFolder($folderid, $startdate = 0, $enddate = 0) {
        if ($folderid) {
            if ($startdate == 0 OR $enddate == 0) {
                $sql = "CALL count_totalcampaign_byfolder($folderid)";
            } else {
                $sql = "CALL count_totalcampaign_byfolder_byperiod($folderid,'$startdate','$enddate')";
            }
            $rs = $this->query($sql);
            if ($rs->hasRecords()) {
                if ($rs->total) {
                    return $rs->total;
                }
            }
        }
        return 0;
    }

    public function totalOptinsByKeyword($keywordid) {
        if ($keywordid) {
            $sql = "CALL count_total_optins_bykeyword($keywordid)";
            $rs = $this->query($sql);
            if ($rs->hasRecords()) {
                if ($rs->total) {
                    return $rs->total;
                }
            }
        }
        return NULL;
    }

    public function totalOptoutsByKeyword($keywordid) {
        if ($keywordid) {
            $sql = "CALL count_total_optouts_bykeyword($keywordid)";
            $rs = $this->query($sql);
            if ($rs->hasRecords()) {
                if ($rs->total) {
                    return $rs->total;
                }
            }
        }
        return NULL;
    }

    public function totalCampaignByKeyword($keywordid) {
        if ($keywordid) {
            $sql = "CALL count_total_campaign_bykeyword($keywordid)";
            $rs = $this->query($sql);
            if ($rs->hasRecords()) {
                if ($rs->total) {
                    return $rs->total;
                }
            }
        }
        return NULL;
    }

    public function totalCampaignByUser($userid, $period) {
        if ($userid) {
            $sql = "CALL count_total_campaign_byuser($userid,$period)";
            $rs = $this->query($sql);
            if ($rs->hasRecords()) {
                if ($rs->total) {
                    return $rs->total;
                }
            }
        }
        return NULL;
    }

    public function findChildUser($userid) {
        if ($userid) {
            $sql = "CALL find_child_user($userid)";
            $rs = $this->query($sql);
            if ($rs->hasRecords()) {
                return $rs->fetchAll();
            }
        }
        return NULL;
    }

    public function findChildEntityList($userid, $type, $searchvalue = "") {
        if ($userid) {
            $sql = "CALL find_childentity_list($userid,$type,'$searchvalue')";
            $rs = $this->query($sql);
            if ($rs->hasRecords()) {
                return $rs->fetchAll();
            }
        }
        return NULL;
    }
    /**
     *  Selecting daily status of the User.
     * 
     */
    public function corparateIdList($userid, $type) {
        $ids = array();
        if ($userid) {
            $sql = "CALL daily_userstatus_list($userid,$type)";
            $rs = $this->query($sql);
            if ($rs->hasRecords()) {
               foreach($rs->fetchAll() as $id=>$v){
                   $ids[] = $v['id'];
               }
               return $ids;
            }
        }
        return NULL;
    }
    public function userdailyStatusList($ids, $user=0, $date='') {
//        echo '<pre>'; print_r($ids);
//       exit;
        $date = date('Y-m-d');
        $ostatus = array();
        if (count($ids) !=0 ) {
            foreach($ids as $idc=>$id){
              $sql = "Select* from dashboardinfo where createuser=$id and createtime like'%$date%'";
              $rs = $this->query($sql);
              if($rs->hasRecords()){
                  foreach($rs->fetchAll() as $ass=>$uval){
                      $uobj  = new stdClass();
                      $uobj->name = $uval['firstname']." ".$uval['lastname'];
                      $uobj->business = $uval['business'];
                      $uobj->campaigns = $uval['campaigns'];
                      $uobj->keywordoptin = $uval['keywordoptin'];
                      $uobj->weboptin = $uval['weboptin'];
                      $uobj->keywordmt = $uval['keywordmt'];
                      $uobj->webformmt = $uval['webformmt'];
                      $uobj->birthday = $uval['birthday'];
                      $uobj->marketingmt = $uval['marketingmt'];
                      $uobj->myla = $uval['myla'];
                      $uobj->mylamt = $uval['mylamt'];
                      $uobj->totaloptin = $uval['totaloptin'];
                      $uobj->totaloptout = $uval['totaloptout'];
                      $uobj->totalsub = $uval['totalsub'];
                      $uobj->totalsms = $uval['totalsms'];
                      $uobj->createuser = $uval['createuser'];
                      $ostatus[] = $uobj;
                  }
//                  echo '<pre>'; print_r($uobj);
//                  exit;
              }
            }
            return $ostatus;
         }
         else{
              $sql = "Select* from dashboardinfo where createuser=$user and createtime like'%$date%'";
              $rs = $this->query($sql);
              if($rs->hasRecords()){
                  foreach($rs->fetchAll() as $ass=>$uval){
                      $uobj  = new stdClass();
                      $uobj->name = $uval['firstname']." ".$uval['lastname'];
                      $uobj->business = $uval['business'];
                      $uobj->campaigns = $uval['campaigns'];
                      $uobj->keywordoptin = $uval['keywordoptin'];
                      $uobj->weboptin = $uval['weboptin'];
                      $uobj->keywordmt = $uval['keywordmt'];
                      $uobj->webformmt = $uval['webformmt'];
                      $uobj->birthday = $uval['birthday'];
                      $uobj->marketingmt = $uval['marketingmt'];
                      $uobj->myla = $uval['myla'];
                      $uobj->mylamt = $uval['mylamt'];
                      $uobj->totaloptin = $uval['totaloptin'];
                      $uobj->totaloptout = $uval['totaloptout'];
                      $uobj->totalsub = $uval['totalsub'];
                      $uobj->totalsms = $uval['totalsms'];
                      $ostatus[] = $uobj;
                  }
              }
              return $ostatus;
         }
        return NULL;
    }

    public function getChildName($entityid) {
        if ($entityid) {
            $sql = "CALL getChildName($entityid)";
            $rs = $this->query($sql);
            if ($rs->hasRecords()) {
                return $rs->fetchAll();
            }
        }
        return NULL;
    }

    public function findChildEntityListApiReport($userid, $searchvalue = "") {
        if ($userid) {
            $sql = "CALL find_childentity_list_api_report($userid,'$searchvalue')";
            $rs = $this->query($sql);
            if ($rs->hasRecords()) {
                return $rs->fetchAll();
            }
        }
        return NULL;
    }

    public function find_entity_apikey($userid, $searchvalue = "") {
        if ($userid) {
            $sql = "CALL find_entity_apikey($userid,'$searchvalue')";
            $rs = $this->query($sql);
            if ($rs->hasRecords()) {
                return $rs->fetchAll();
            }
        }
        return NULL;
    }

    public function getTotalSubscriberByPeriod($userid, $period) {
        if ($userid) {
            $monthEndDate = date("Y-m-d H:i:s");
            $monthStartDate = date('Y-m-d H:i:s', strtotime('-' . $period . ' days'));

            $sql = "CALL folder_get_subscribers_period($userid,'$monthStartDate','$monthEndDate')";
            $rs = $this->query($sql);
            if ($rs->hasRecords()) {
                if ($rs->total) {
                    return $rs->total;
                }
            }
        }
        return NULL;
    }

    public function getTotalSubscriberByFolder($folderid) {
        if ($folderid) {
            $sql = "CALL folder_get_subscribers_inout($folderid)";
            $rs = $this->query($sql);
            if ($rs->hasRecords()) {
                if ($rs->num_rows) {
                    return $rs->num_rows;
                }
            }
        }
        return 0;
    }

    public function getTotalSubscriberByKeyword($keywordid) {
        if ($keywordid) {
            $sql = "CALL folder_get_subscribers_inout_bykeyword($keywordid)";
            $rs = $this->query($sql);
            if ($rs->hasRecords()) {
                if ($rs->num_rows) {
                    return $rs->num_rows;
                }
            }
        }
        return NULL;
    }

    public function checkAdminUser($userid) {
        if ($userid) {
            $sql = "CALL report_check_adminuser($userid)";
            $rs = $this->query($sql);
            if ($rs->hasRecords()) {
                if ($rs->value == 'on') {
                    return TRUE;
                }
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

    public function reportCountTotalKeywordByUserId($userid, $startdate = 0, $enddate = 0) {
        if ($userid) {
            if ($startdate == 0 OR $enddate == 0) {
                //$sql1 = "CALL report_count_keywords_byid_1($userid)";
                if ($this->checkAdminUser($userid))
                    $sql1 = "CALL report_count_keywords_byid_whitelabel($userid)";

                if ($this->checkAdminUser($userid))
                    $sql3 = "CALL report_count_keywords_byid_3($userid)";
                else
                    $sql2 = "CALL report_count_keywords_byid_2($userid)";

                if (!$this->hassubuser($userid)) {
                    $sql4 = "CALL report_count_keywords_byid_2($userid)";
                }
            } else {
                if ($this->checkAdminUser($userid))
                    $sql1 = "CALL report_count_keywords_byid_whitelabel($userid)";

                if ($this->checkAdminUser($userid))
                    $sql3 = "CALL report_count_keywords_byid_3($userid)";
                else
                    $sql2 = "CALL report_count_keywords_byid_2($userid)";


                if (!$this->hassubuser($userid)) {
                    $sql4 = "CALL report_count_keywords_byid_2($userid)";
                }
                /* $sql1 = "CALL report_count_keywords_byid_byperiod_1($userid,'$startdate','$enddate')";
                  $sql2 = "CALL report_count_keywords_byid_byperiod_2($userid,'$startdate','$enddate')";
                  $sql3 = "CALL report_count_keywords_byid_byperiod_3($userid,'$startdate','$enddate')"; */
            }
            $total = 0;
            //echo "here again:".$sql1;
            if (!empty($sql1)) {
                $rs1 = $this->query($sql1); // echo "<pre>"; print_r($rs1); 
                if ($rs1->total)
                    $total += $rs1->total;
            }
            if (!empty($sql2)) {
                $rs2 = $this->query($sql2);
                if ($rs2->total)
                    $total += $rs2->total;
            }

            if (!empty($sql3)) {
                $rs3 = $this->query($sql3);
                if ($rs3->total)
                    $total += $rs3->total;


                /// adding if admin user just have folder but no sub users
                if (!$this->hassubuser($userid)) {
                    $rs2 = $this->query($sql4);
                    if ($rs2->total)
                        $total += $rs2->total;
                }
            }

            return $total;
        }
        return 0;
    }

    public function reportCountTotalKeywordByUserIdAdminOnly($userid, $startdate = 0, $enddate = 0) {
        if ($userid) {
            if ($startdate == 0 OR $enddate == 0) {
                //$sql1 = "CALL report_count_keywords_byid_1($userid)";
                $sql2 = "CALL report_count_keywords_byid_2($userid)";
            } else {

                $sql2 = "CALL report_count_keywords_byid_2($userid)";
                /* $sql1 = "CALL report_count_keywords_byid_byperiod_1($userid,'$startdate','$enddate')";
                  $sql2 = "CALL report_count_keywords_byid_byperiod_2($userid,'$startdate','$enddate')";
                  $sql3 = "CALL report_count_keywords_byid_byperiod_3($userid,'$startdate','$enddate')"; */
            }
            $total = 0;
            /* $rs1  = $this->query($sql1); //echo "<pre>"; print_r($rs1); exit;
              if($rs1->total)
              $total += $rs1->total; */
            if (!empty($sql2)) {
                $rs2 = $this->query($sql2);
                if ($rs2->total)
                    $total += $rs2->total;
            }

            return $total;
        }
        return 0;
    }

    public function reportCountTotalSubscribersByUserId($userid, $startdate = 0, $enddate = 0) {
        if ($userid) {
            if ($startdate == 0 OR $enddate == 0) {
                if ($this->checkAdminUser($userid))
                    $sql1 = "CALL report_count_subscribers_byid_1($userid)";
                else
                    $sql2 = "CALL report_count_subscribers_byid_2($userid)";

                if (!$this->hassubuser($userid)) {
                    $sql4 = "CALL report_count_subscribers_byid_2($userid)";
                }
            } else {
                if ($this->checkAdminUser($userid))
                    $sql1 = "CALL report_count_subscribers_byid_byperiod_1($userid,'$startdate','$enddate')";
                else
                    $sql2 = "CALL report_count_subscribers_byid_byperiod_2($userid,'$startdate','$enddate')";

                if (!$this->hassubuser($userid)) {
                    $sql4 = "CALL report_count_subscribers_byid_byperiod_2($userid,'$startdate','$enddate')";
                }
            }
            $total = 0;

            if (!empty($sql1)) {
                $rs1 = $this->query($sql1); //echo "<pre>"; print_r($rs1); exit;
                if ($rs1->total)
                    $total += $rs1->total;

                if (!$this->hassubuser($userid)) {
                    $rs2 = $this->query($sql4);
                    if ($rs2->total)
                        $total += $rs2->total;
                }
            }

            if (!empty($sql2)) {
                $rs2 = $this->query($sql2);
                if ($rs2->total)
                    $total += $rs2->total;
            }

            return $total;
        }
        return 0;
    }

    public function reportCountTotalSubscribersByUserIdAdminOnly($userid, $startdate = 0, $enddate = 0) {
        if ($userid) {
            if ($startdate == 0 OR $enddate == 0) {
                $sql2 = "CALL report_count_subscribers_byid_2($userid)";
            } else {

                $sql2 = "CALL report_count_subscribers_byid_byperiod_2($userid,'$startdate','$enddate')";
            }
            $total = 0;


            if (!empty($sql2)) {
                $rs2 = $this->query($sql2);
                if ($rs2->total)
                    $total += $rs2->total;
            }

            return $total;
        }
        return 0;
    }

    public function reportCountTotalOptinsByUserId($userid, $startdate = 0, $enddate = 0) {
        if ($userid) {
            if ($startdate == 0 OR $enddate == 0) {
                if ($this->checkAdminUser($userid))
                //$sql1 = "CALL report_count_optins_byid_1($userid)";
                    $sql1 = "
                   	SELECT count(DISTINCT(s.`phonenumber`)) AS total 
						FROM `entity` as e1,
						    `entity` as e2,
						    `entity` as e3,
						    `subscribers` as s
						WHERE e1.`id` = e2.`parententity`
						AND   e2.`id` = e3.`parententity`
						AND   e3.`id` = s.`folderid`
						AND   e1.`id` = iUSERID 
						AND   e3.`typeid` = 4;
						# AND   s.`optouttime` = '0000-00-00 00:00:00'
                   ";

                else
                //$sql2 = "CALL report_count_optins_byid_2($userid)";
                    $sql2 = "
                   	SELECT count(DISTINCT(s.`phonenumber`)) AS total  
						FROM `entity` as e1,
						   `entity` as e2,
						   `subscribers` as s
						WHERE e1.`id` = e2.`parententity`
						AND   e2.`id` = s.`folderid`
						AND   e1.`id` = iUSERID 
						AND   e2.`typeid` = 4;
						#AND   s.`optouttime` = '0000-00-00 00:00:00'
                   ";
                if (!$this->hassubuser($userid)) {
                    $sql4 = "CALL report_count_optins_byid_2($userid)";
                }
            } else {
                if ($this->checkAdminUser($userid))
                    $sql1 = "CALL report_count_optins_byid_byperiod_1($userid,'$startdate','$enddate')";
                else
                    $sql2 = "CALL report_count_optins_byid_byperiod_2($userid,'$startdate','$enddate')";

                if (!$this->hassubuser($userid)) {
                    $sql4 = "CALL report_count_optins_byid_byperiod_2($userid,'$startdate','$enddate')";
                }
            }
            $total = 0;

            if (!empty($sql1)) {
                $rs1 = $this->query($sql1); //echo "<pre>"; print_r($rs1); exit;
                if ($rs1->total)
                    $total += $rs1->total;

                if (!$this->hassubuser($userid)) {
                    $rs2 = $this->query($sql4);
                    if ($rs2->total)
                        $total += $rs2->total;
                }
            }

            if (!empty($sql2)) {
                $rs2 = $this->query($sql2);
                if ($rs2->total)
                    $total += $rs2->total;
            }

            return $total;
        }
        return 0;
    }

    public function reportCountTotalOptinsByUserIdAdminOnly($userid, $startdate = 0, $enddate = 0) {
        if ($userid) {
            if ($startdate == 0 OR $enddate == 0) {

                $sql2 = "CALL report_count_optins_byid_2($userid)";
            } else {

                $sql2 = "CALL report_count_optins_byid_byperiod_2($userid,'$startdate','$enddate')";
            }
            $total = 0;

            if (!empty($sql2)) {
                $rs2 = $this->query($sql2);
                if ($rs2->total)
                    $total += $rs2->total;
            }

            return $total;
        }
        return 0;
    }

    public function reportCountTotalOptoutsByUserId($userid, $startdate = 0, $enddate = 0) {
        if ($userid) {
            if ($startdate == 0 OR $enddate == 0) {
                if ($this->checkAdminUser($userid))
                    $sql1 = "CALL report_count_optouts_byid_1($userid)";
                else
                    $sql2 = "CALL report_count_optouts_byid_2($userid)";

                if (!$this->hassubuser($userid)) {
                    $sql4 = "CALL report_count_optouts_byid_2($userid)";
                }
            } else {
                if ($this->checkAdminUser($userid))
                    $sql1 = "CALL report_count_optouts_byid_byperiod_1($userid,'$startdate','$enddate')";
                else
                    $sql2 = "CALL report_count_optouts_byid_byperiod_2($userid,'$startdate','$enddate')";
                //$sql2 = "CALL report_count_optouts_byid_byperiod_2_test($userid,'$startdate','$enddate')";

                if (!$this->hassubuser($userid)) {
                    $sql4 = "CALL report_count_optouts_byid_byperiod_2($userid,'$startdate','$enddate')";
                }
            }
            $total = 0;

            if (!empty($sql1)) {
                $rs1 = $this->query($sql1); //echo "<pre>"; print_r($rs1); exit;
                if ($rs1->total)
                    $total += $rs1->total;

                if (!$this->hassubuser($userid)) {
                    $rs2 = $this->query($sql4);
                    if ($rs2->total)
                        $total += $rs2->total;
                }
            }

            if (!empty($sql2)) {
                $rs2 = $this->query($sql2);
                if ($rs2->total)
                    $total += $rs2->total;
            }

            return $total;
        }
        return 0;
    }

    public function reportCountTotalOptoutsByUserIdAdminOnly($userid, $startdate = 0, $enddate = 0) {
        if ($userid) {
            if ($startdate == 0 OR $enddate == 0) {

                $sql2 = "CALL report_count_optouts_byid_2($userid)";
            } else {

                $sql2 = "CALL report_count_optouts_byid_byperiod_2($userid,'$startdate','$enddate')";
            }
            $total = 0;


            if (!empty($sql2)) {
                $rs2 = $this->query($sql2);
                if ($rs2->total)
                    $total += $rs2->total;
            }

            return $total;
        }
        return 0;
    }

    public function reportCountTotalCampaignsByUserId($userid, $startdate = 0, $enddate = 0) {
        if ($userid) {
            if ($startdate == 0 OR $enddate == 0) {
                if ($this->checkAdminUser($userid))
                    $sql1 = "CALL report_count_campaign_byid_1($userid)";
                else
                    $sql2 = "CALL report_count_campaign_byid_2($userid)";

                if (!$this->hassubuser($userid)) {
                    $sql4 = "CALL report_count_campaign_byid_2($userid)";
                }
            } else {
                if ($this->checkAdminUser($userid))
                    $sql1 = "CALL report_count_campaign_byid_byperiod_1($userid,'$startdate','$enddate')";
                else
                    $sql2 = "CALL report_count_campaign_byid_byperiod_2($userid,'$startdate','$enddate')";

                if (!$this->hassubuser($userid)) {
                    $sql4 = "CALL report_count_campaign_byid_byperiod_2($userid,'$startdate','$enddate')";
                }
            }
            $total = 0;

            if (!empty($sql1)) {
                $rs1 = $this->query($sql1); //echo "<pre>"; print_r($rs1); exit;
                if ($rs1->total)
                    $total += $rs1->total;

                if (!$this->hassubuser($userid)) {
                    $rs2 = $this->query($sql4);
                    if ($rs2->total)
                        $total += $rs2->total;
                }
            }

            if (!empty($sql2)) {
                $rs2 = $this->query($sql2);
                if ($rs2->total)
                    $total += $rs2->total;
            }

            return $total;
        }
        return 0;
    }

    public function reportCountTotalCampaignsByUserIdAdminOnly($userid, $startdate = 0, $enddate = 0) {
        if ($userid) {
            if ($startdate == 0 OR $enddate == 0) {
                $sql2 = "CALL report_count_campaign_byid_2($userid)";
            } else {

                $sql2 = "CALL report_count_campaign_byid_byperiod_2($userid,'$startdate','$enddate')";
            }
            $total = 0;

            if (!empty($sql2)) {
                $rs2 = $this->query($sql2);
                if ($rs2->total)
                    $total += $rs2->total;
            }

            return $total;
        }
        return 0;
    }

    public function reportCountTotalCampaignMessagesByUserId($userid, $startdate = 0, $enddate = 0) {
        if ($userid) {
            if ($startdate == 0 OR $enddate == 0) {
                if ($this->checkAdminUser($userid))
                    $sql1 = "CALL report_count_campaignmessage_byid_1($userid)";
                else
                    $sql2 = "CALL report_count_campaignmessage_byid_2($userid)";

                if (!$this->hassubuser($userid)) {
                    $sql4 = "CALL report_count_campaignmessage_byid_2($userid)";
                }
            } else {
                if ($this->checkAdminUser($userid))
                    $sql1 = "CALL report_count_campaignmessage_byid_byperiod_1($userid,'$startdate','$enddate')";
                else
                    $sql2 = "CALL report_count_campaignmessage_byid_byperiod_2($userid,'$startdate','$enddate')";

                if (!$this->hassubuser($userid)) {
                    $sql4 = "CALL report_count_campaignmessage_byid_byperiod_2($userid,'$startdate','$enddate')";
                }
            }
            $total = 0;

            if (!empty($sql1)) {
                $rs1 = $this->query($sql1); //echo "<pre>"; print_r($rs1); exit;
                if ($rs1->total)
                    $total += $rs1->total;

                if (!$this->hassubuser($userid)) {
                    $rs2 = $this->query($sql4);
                    if ($rs2->total)
                        $total += $rs2->total;
                }
            }

            if (!empty($sql2)) {
                $rs2 = $this->query($sql2);
                if ($rs2->total)
                    $total += $rs2->total;
            }

            return $total;
        }
        return 0;
    }

    public function reportCountTotalCampaignMessagesByUserIdAdminOnly($userid, $startdate = 0, $enddate = 0) {
        if ($userid) {
            if ($startdate == 0 OR $enddate == 0) {
                $sql2 = "CALL report_count_campaignmessage_byid_2($userid)";
            } else {
                $sql2 = "CALL report_count_campaignmessage_byid_byperiod_2($userid,'$startdate','$enddate')";
            }
            $total = 0;

            if (!empty($sql2)) {
                $rs2 = $this->query($sql2);
                if ($rs2->total)
                    $total += $rs2->total;
            }

            return $total;
        }
        return 0;
    }

    public function reportCountTotalMessagesByUserId($userid, $startdate = 0, $enddate = 0) {
        if ($userid) {
            if ($startdate == 0 OR $enddate == 0) {
                if ($this->checkAdminUser($userid))
                    $sql1 = "CALL report_count_totalmessage_byid_1($userid)";
                else
                    $sql2 = "CALL report_count_totalmessage_byid_2($userid)";

                if (!$this->hassubuser($userid)) {
                    $sql4 = "CALL report_count_totalmessage_byid_2($userid)";
                }
            } else {
                if ($this->checkAdminUser($userid))
                    $sql1 = "CALL report_count_totalmessage_byid_byperiod_1($userid,'$startdate','$enddate')";
                else
                    $sql2 = "CALL report_count_totalmessage_byid_byperiod_2($userid,'$startdate','$enddate')";

                if (!$this->hassubuser($userid)) {
                    $sql4 = "CALL report_count_totalmessage_byid_byperiod_2($userid,'$startdate','$enddate')";
                }
            }
            $total = 0;

            if (!empty($sql1)) {
                $rs1 = $this->query($sql1); //echo "<pre>"; print_r($rs1); exit;
                if ($rs1->total)
                    $total += $rs1->total;

                if (!$this->hassubuser($userid)) {
                    $rs2 = $this->query($sql4);
                    if ($rs2->total)
                        $total += $rs2->total;
                }
            }

            if (!empty($sql2)) {
                $rs2 = $this->query($sql2);
                if ($rs2->total)
                    $total += $rs2->total;
            }

            return $total;
        }
        return 0;
    }

    public function reportCountTotalMessagesByApiKey($userid, $apikey, $startdate = 0, $enddate = 0) {
        if ($apikey) {
            if ($startdate == 0 OR $enddate == 0) {
                if ($this->checkAdminUser($userid))
                    $sql1 = "CALL report_count_totalmessage_byapikey('$apikey')";
                else
                    return 0;
            }else {
                if ($this->checkAdminUser($userid))
                    $sql1 = "CALL report_count_totalmessage_byapikey_byperiod('$apikey','$startdate','$enddate')";
                else
                    return 0;
            }
            $total = 0;
            if (!empty($sql1)) {
                $rs1 = $this->query($sql1); //echo "<pre>"; print_r($rs1); exit;
                if ($rs1->total)
                    $total += $rs1->total;
            }
            return $total;
        }
        return 0;
    }

    public function reportTotalMessagesByApiKey($apikey, $startdate = 0, $enddate = 0) {
        if ($apikey) {
            if ($startdate == 0 OR $enddate == 0) {
                $sql = "CALL report_all_messages_byapikey('$apikey')";
            } else {
                $sql = "CALL report_all_messages_byapikey_byperiod('$apikey','$startdate','$enddate')";
            }
            $rs = $this->query($sql);
            if ($rs->hasRecords()) {
                return $rs->fetchAll();
            }
        }
        return NULL;
    }

    public function reportCountTotalMessagesByUserIdAdminOnly($userid, $startdate = 0, $enddate = 0) {
        if ($userid) {
            if ($startdate == 0 OR $enddate == 0) {
                $sql2 = "CALL report_count_totalmessage_byid_2($userid)";
            } else {
                $sql2 = "CALL report_count_totalmessage_byid_byperiod_2($userid,'$startdate','$enddate')";
            }
            $total = 0;


            if (!empty($sql2)) {
                $rs2 = $this->query($sql2);
                if ($rs2->total)
                    $total += $rs2->total;
            }

            return $total;
        }
        return 0;
    }

    public function sendRportTo() {
        $sendReportArray = Array(
            '0' => Array(
                'id' => '1121',
                'edituser' => '162',
                'typeidextra' => '2',
                'user' => 'pros',
                'email' => 'StephenW@ProsolutionsSoftware.com brentw@prosolutionssoftware.com farad@textmunication.com'
            )
        );
        return $sendReportArray;
    }

    public function sendMonthlyRportTo() {
        $sendReportArray = Array(
            '0' => Array(
                'id' => '1121',
                'edituser' => '162',
                'typeidextra' => '2',
                'user' => 'pros',
                'topic' => "Proslon",
                'func' => 'getWeeklyReportApiuser',
                'email' => 'StephenW@ProsolutionsSoftware.com'
            ),
            '1' => Array(
                'id' => '689',
                'edituser' => '162',
                'typeidextra' => '2',
                'user' => 'farad',
                'topic' => "Ufc-Crunch",
                'func' => 'getMonthlyReportUfc_Crunch()',
                'email' => 'al@nev.com wais@textmunication.com'
            )
        );
        return $sendReportArray;
    }

    public function sendRportToService() {
        $sendReportArray = Array(
            '0' => Array(
                'id' => '4087',
                'edituser' => '162',
                'typeidextra' => '2',
                'user' => 'pros',
                'email' => 'amalia@textmunication.com nick@textmunication.com'
            )
        );
        return $sendReportArray;
    }

    public function getWeeklyReportByEditUser($edituserid = null) {
        $enddate = date("Y-m-d H:i:s");
        $startdate = date('Y-m-d H:i:s', strtotime('-7 days')); //echo $edituserid.'##'.$usertypeid.'##'.$enddate.'##'.$startdate;

        if ($edituserid != null) {
            $sql = "CALL get_weekly_report_byedituser($edituserid,'$startdate','$enddate')";
//          echo '<br>InsideEditUSER: '.$edituserid.'##'.$usertypeid.'##'.$enddate.'##'.$startdate;
        }

        $rs = $this->query($sql);
        if ($rs->hasRecords()) {
            return $rs->fetchAll();
        }
    }

    public function getWeeklyReport($userid = null, $usertypeid = null) {
        $enddate = date("Y-m-d H:i:s");
        $startdate = date('Y-m-d H:i:s', strtotime('-7 days'));
        $sql = null;


        if ($userid != null AND $usertypeid != null) {
            if ($usertypeid == 1) {
                $sql = "CALL get_weekly_report_bywhitelabel($userid,'$startdate','$enddate')";
//                echo 'InsideOne: '.$userid.'##'.$usertypeid.'##'.$enddate.'##'.$startdate; exit;
            } elseif ($usertypeid == 2) {
                $sql = "CALL get_weekly_report_byagent($userid,'$startdate','$enddate')";
//                 echo 'InsideSec: '.$userid.'##'.$usertypeid.'##'.$enddate.'##'.$startdate; exit;
            }
        } else {
            $sql = "CALL get_weekly_report('$startdate','$enddate')";
//            echo 'InsideElse: '.$userid.'##'.$usertypeid.'##'.$enddate.'##'.$startdate; exit;
        }
        $rs = $this->query($sql);
        if ($rs->hasRecords()) {
            return $rs->fetchAll();
        }
    }

    /**
     *  daily report list for auto sms
     * 
     */
    public function sendDailyReportTo() {
        $sendReportArray = Array(
            '0' => Array(
                'id' => '2854',
                'edituser' => '689',
                'typeidextra' => '2',
                'user' => 'UFCNEV',
                'topic' => "Ufc",
                'continent' => 1,
                'email' => 'al@nev.com rick.germaine@ufcgym.com'
//                'email' => 'farad@textmunication.com andrew@textmunication.com wais@textmunication.com'
            ),
            '1' => Array(
                'id' => '2855',
                'edituser' => '689',
                'typeidextra' => '2',
                'user' => 'UFCNEV',
                'topic' => "Snfc",
                'continent' => 2,
                'email' => 'al@nev.com todd.ingledew@stevenashsportsclub.com'
//                'email' => 'farad@textmunication.com andrew@textmunication.com wais@textmunication.com'
            ),
//            '2' => Array(
//                'id' => '2856',
//                'edituser' => '689',
//                'typeidextra' => '2',
//                'user' => 'UFCNEV',
//                'topic'=>"Jv",
//                'continent'=>3,
//                'email' => 'sbcrunchnw@gmail.com'
////                'email' => 'farad@textmunication.com andrew@textmunication.com wais@textmunication.com'
//            ),
//            '3' => Array(
//                'id' => '2857',
//                'edituser' => '689',
//                'typeidextra' => '2',
//                'user' => 'UFCNEV',
//                'topic'=>"Jv",
//                'continent'=>3,
//                'email' => 'markpolli.crunch@gmail.com'
////                'email' => 'farad@textmunication.com'
//            ),
//            '4' => Array(
//                'id' => '2858',
//                'edituser' => '689',
//                'typeidextra' => '2',
//                'user' => 'UFCNEV',
//                'topic'=>"Jv",
//                'continent'=>3,
//                'email' => 'sclinefelter@roadrunner.com'
////                'email' => 'farad@textmunication.com'
//            ),
//            '5' => Array(
//                'id' => '2514',
//                'edituser' => '689',
//                'typeidextra' => '2',
//                'user' => 'UFCNEV',
//                'topic'=>"Jv",
//                'continent'=>3,
//                'email' => 'john.romeo@crunch.com'
////                'email' => 'farad@textmunication.com'
//            ),
//            '6' => Array(
//                'id' => '3118',
//                'edituser' => '689',
//                'typeidextra' => '2',
//                'user' => 'UFCNEV',
//                'topic'=>"Jv",
//                'continent'=>3,
//                'email' => 'manager.upland@crunch.com'
////                'email' => 'farad@textmunication.com'
//            ),
            '2' => Array(
                'id' => '689',
                'edituser' => '162',
                'typeidextra' => '2',
                'user' => 'UFCNEV',
                'topic' => "Jv",
                'continent' => 0,
                'email' => 'al@nev.com'
//                'email' => 'farad@textmunication.com'
            )
        );
        return $sendReportArray;
    }

    /**
     *   Daily report to ufc and snfc
     * 
     */
    public function sendingDailyReport_AutoSms($continent) {
//        $date = date('Y-m-d');
        $date = date('2013-11');
        $sql = "SELECT b.location, a.msgtype as billing, count(distinct a.phonenumber) as sms
        FROM nevusage a, nevclubs b where a.clubid = b.nevid and a.createtime like '%" . $date . "%' and b.continent=$continent and a.msgtype in('bng1','bng2','bng3','bng4','bng5','bng6')
        group by a.msgtype, b.textmid";

        $rs = $this->query($sql);
        if ($rs->hasRecords()) {
            return $rs->fetchAll();
        }
    }

    /**
     *   Daily report for JVs location
     * 
     */
    public function sendingDailyReportJVlocation($continent, $createuser) {
//        $date = date('Y-m-d');
        $date = date('2013-11');
        $sql = "SELECT b.location, a.msgtype as billing, count(distinct a.phonenumber) as sms
        FROM nevusage a, nevclubs b where a.clubid = b.nevid and a.createtime like '%" . $date . "%' and 
        b.continent=$continent and b.createuser=$createuser and a.msgtype  in('bng1','bng2','bng3','bng4','bng5','bng6')  group by a.msgtype, b.textmid";

        $rs = $this->query($sql);
        if ($rs->hasRecords()) {
            return $rs->fetchAll();
        }
    }

    /**
     *  sending weekly report for API user
     *  from corporate account
     * @name getWeeklyReportApiuser
     * @access public
     * @param int $userid corparete account id
     * @return array object 
     */
    public function getWeeklyReportApiuser($userid) {
        $enddate = date("Y-m-d");
        $startdate = date('Y-m-d', strtotime('-7 days'));
        $sql = "SELECT e.id textmid, m1.value prosalonid, m.value business_name,
          
            (select count(mr.mobilenumber) from messages_outbound_recipients mr, messages_outbound mo where mr.createuser=e.id

            and mo.id = mr.gatewaymessageid and DATE_FORMAT(mr.pickuptime, '%Y-%m-%d') between  '$startdate' and '$enddate'

           ) as sms  from entity e, entitymeta m,entitymeta m1

            where e.createuser=$userid and m.entityid=e.id and e.typeid=5 and m.profileid=38 and m1.entityid=e.id and m1.profileid=45";

        $rs = $this->query($sql);
        if ($rs->hasRecords()) {
            return $rs->fetchAll();
        }
    }

    public function getMonthlyReportApiuser($usrid) {
        $formonth = date('Y-m', strtotime("last month"));
        $sql = "";
        if ($usrid == 1121) {
            $sql = "SELECT e.id as id, m.value accountname, m1.value accountid,

        (select count(mr.mobilenumber) from messages_outbound_recipients mr, messages_outbound mo where 

        mo.createuser=e.id and mo.createuser=mr.createuser

        and mo.id = mr.gatewaymessageid and mr.pickuptime like '%" . $formonth . "%' ) as sms

        from entity e, entitymeta m,entitymeta m1

        where e.createuser=1121 and m.entityid=e.id and e.typeid=5 and m.profileid=38 and m1.entityid=e.id and m1.profileid=45";
        } elseif ($usrid == 689) {
            $sql = "SELECT e.id, m.value as businessname,m1.value as firstname, m2.value as lastname,

        (select count(mr.mobilenumber) from messages_outbound_recipients mr, messages_outbound mo where 

        mo.createuser=e.id and mo.createuser=mr.createuser

        and mo.id = mr.gatewaymessageid and mr.pickuptime like '%" . $formonth . "%' ) as sms

        from entity e, entitymeta m,entitymeta m1,entitymeta m2

        where e.createuser=689 and m.entityid=e.id and e.typeid=5 and m.profileid=38 and
            
        m1.entityid=e.id and m1.profileid=22 and m2.entityid=e.id and m2.profileid=23";
        }
        $rs = $this->query($sql);
        if ($rs->hasRecords()) {
            return $rs->fetchAll();
        }
    }

    public function getMonthlyReportUfc_Crunch() { // is not used yet
        $formonth = date('Y-m', strtotime("last month"));

        $sql = "SELECT e.id, m.value as businessname,m1.value as firstname, m2.value as lastname,

        (select count(mr.mobilenumber) from messages_outbound_recipients mr, messages_outbound mo where 

        mo.createuser=e.id and mo.createuser=mr.createuser

        and mo.id = mr.gatewaymessageid and mr.pickuptime like '%" . $formonth . "%' ) as sms

        from entity e, entitymeta m,entitymeta m1,entitymeta m2

        where e.createuser=689 and m.entityid=e.id and e.typeid=5 and m.profileid=38 and
            
        m1.entityid=e.id and m1.profileid=22 and m2.entityid=e.id and m2.profileid=23";

        $rs = $this->query($sql);
        if ($rs->hasRecords()) {
            return $rs->fetchAll();
        }
    }

    /**
     *  sends weekly report to service
     *  Keyword activites from textm user
     *  @return array object
     */
    public function getWeeklyReportService() {
        $sql = "SELECT e.id, m.value as businessname, m2.value as first, m3.value as last, k.keyword as keyword,(
                 select count(distinct phonenumber)from subscribers where keywordid = k.id and optouttime='0000-00-00 00:00:00' and 
            createtime  BETWEEN DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND createtime
             ) as optin_7,

            (
            select count(distinct phonenumber)from subscribers where keywordid = k.id and optouttime='0000-00-00 00:00:00' and 
            createtime  BETWEEN DATE_SUB(CURDATE(), INTERVAL 30 DAY) AND createtime
             ) as optin_30,
            (
            select count(distinct phonenumber)from subscribers where keywordid = k.id and optouttime='0000-00-00 00:00:00' and 
            createtime  BETWEEN DATE_SUB(CURDATE(), INTERVAL 60 DAY) AND createtime
             ) as optin_60,
            (select createtime from messages_outbound where  

            createtime BETWEEN DATE_SUB(CURDATE(), INTERVAL 60 DAY) and now() and createuser=e.id and campaignid!='(NULL)' order by createtime desc limit 1) as lstcamp60

             from entity e, entitymeta m, entitymeta m1,entitymeta m2,entitymeta m3, keywords k

             where e.id = m.entityid and m.profileid = 38 and e.typeid=5 and m1.entityid=m.entityid and m1.profileid=8 and m1.value=1 and m1.entityid!=162 and m1.entityid!=163

            and m2.entityid = m.entityid and m2.profileid=22 and m3.entityid = m.entityid and m3.profileid=23 and k.createuser = m.entityid
            ";

        $rs = $this->query($sql);
        if ($rs->hasRecords()) {
            return $rs->fetchAll();
        }
    }

// end of getWeeklyReportService

    public function customReport($userid = null, $usertypeid = null) {
        $enddate = date("Y-m-d H:i:s");
        $startdate = date('Y-m-d H:i:s', strtotime('-7 days')); //echo $userid.'##'.$usertypeid.'##'.$enddate.'##'.$startdate;

        if ($userid != null AND $usertypeid != null) {
            if ($usertypeid == 1)
                $sql = "CALL get_weekly_report_bywhitelabel($userid,'$startdate','$enddate')";
            elseif ($usertypeid == 2)
                $sql = "CALL get_weekly_report_byagent($userid,'$startdate','$enddate')";
        }else
            $sql = "CALL get_weekly_report('$startdate','$enddate')";

        $rs = $this->query($sql);
        if ($rs->hasRecords()) {
            return $rs->fetchAll();
        }
    }

    function hassubuser($userid) {
        $subUsersArray = $this->findChildEntityList($userid, 5);  //echo "<pre>"; print_r($subUsersArray); exit;
        $totalSubUsers = count($subUsersArray);
        //echo "userid:".$userid;
        //print_r($subUsersArray);
        if ($totalSubUsers > 0) {
            //echo "having user";
            return true;
        } else {
            //echo "not having user";
            return false;
        }
    }

    //Added by Jeevan Technologies for Dashboard
    public function reportCountTotalMessagesByUserIdNew($userid, $startdate = 0, $enddate = 0) {
        if ($userid) {
            if ($startdate == 0 OR $enddate == 0) {
                if ($this->checkAdminUser($userid)) {
                    //$sql1 = "CALL report_count_totalmessage_byid_1($userid)";
                    $sql1 = "SELECT COUNT(r.`id`) AS total FROM `entity` as e1, `entity` as e2,
                    	    `entity` as e3, `messages_outbound` as o, `messages_outbound_recipients` as r 
                    	    WHERE e1.`id` = e2.`parententity` AND   e2.`id` = e3.`parententity`
                    	    AND   e3.`id` = o.`folderid`  AND   o.`id` = r.`gatewaymessageid` 
                    	    AND   e1.`id` = '" . $userid . "'  AND   e3.`typeid` = 4";
                } else {
                    //$sql2 = "CALL report_count_totalmessage_byid_2($userid)";
                    $sql2 = "SELECT COUNT(r.`id`) AS total FROM `entity` as e1, `entity` as e2, 
                         `messages_outbound` as o,  `messages_outbound_recipients` as r 
                         WHERE e1.`id` = e2.`parententity`  AND   e2.`id` = o.`folderid` 
                         AND   o.`id` = r.`gatewaymessageid`  AND   e1.`id` = '" . $userid . "' 
                         AND   e2.`typeid` = 4";
                }

                if (!$this->hassubuser($userid)) {
                    //$sql4  = "CALL report_count_totalmessage_byid_2($userid)";
                    $sql4 = "SELECT COUNT(r.`id`) AS total FROM `entity` as e1, `entity` as e2, 
                         `messages_outbound` as o,  `messages_outbound_recipients` as r 
                         WHERE e1.`id` = e2.`parententity`  AND   e2.`id` = o.`folderid` 
                         AND   o.`id` = r.`gatewaymessageid`  AND   e1.`id` = '" . $userid . "' 
                         AND   e2.`typeid` = 4";
                }
            } else {
                if ($this->checkAdminUser($userid)) {
                    //$sql1 = "CALL report_count_totalmessage_byid_byperiod_1($userid,'$startdate','$enddate')";
                    $sql1 = "SELECT COUNT(r.`id`) AS total FROM `entity` as e1, `entity` as e2,
                         `entity` as e3, `messages_outbound` as o, `messages_outbound_recipients` as r 
                         WHERE e1.`id` = e2.`parententity` AND   e2.`id` = e3.`parententity`
                         AND   e3.`id` = o.`folderid`  AND   o.`id` = r.`gatewaymessageid` 
                         AND   e1.`id` = '" . $userid . "'   AND   e3.`typeid` = 4  
                         AND   r.`senttime` BETWEEN '" . $startdate . "'  AND '" . $enddate . "' ";
                } else {
                    //$sql2 = "CALL report_count_totalmessage_byid_byperiod_2($userid,'$startdate','$enddate')";
                    $sql2 = "SELECT COUNT(r.`id`) AS total FROM `entity` as e1, `entity` as e2, 
                         `messages_outbound` as o,  `messages_outbound_recipients` as r 
                         WHERE e1.`id` = e2.`parententity`  AND   e2.`id` = o.`folderid` 
                         AND   o.`id` = r.`gatewaymessageid`  AND   e1.`id` = '" . $userid . "' 
                         AND   e2.`typeid` = 4  AND   r.`senttime` BETWEEN '" . $startdate . "'  AND '" . $enddate . "' ";
                }

                if (!$this->hassubuser($userid)) {
                    //$sql4  = "CALL report_count_totalmessage_byid_byperiod_2($userid,'$startdate','$enddate')";
                    $sql4 = "SELECT COUNT(r.`id`) AS total FROM `entity` as e1, `entity` as e2, 
                         `messages_outbound` as o,  `messages_outbound_recipients` as r 
                         WHERE e1.`id` = e2.`parententity`  AND   e2.`id` = o.`folderid` 
                         AND   o.`id` = r.`gatewaymessageid`  AND   e1.`id` = '" . $userid . "' 
                         AND   e2.`typeid` = 4  AND   r.`senttime` BETWEEN '" . $startdate . "'  AND '" . $enddate . "' ";
                }
            }
            $total = 0;

            if (!empty($sql1)) {
                $rs1 = $this->query($sql1); //echo "<pre>"; print_r($rs1); exit;
                if ($rs1->total)
                    $total += $rs1->total;

                if (!$this->hassubuser($userid)) {
                    $rs2 = $this->query($sql4);
                    if ($rs2->total)
                        $total += $rs2->total;
                }
            }

            if (!empty($sql2)) {
                $rs2 = $this->query($sql2);
                if ($rs2->total)
                    $total += $rs2->total;
            }

            return $total;
        }
        return 0;
    }

    public function reportCountTotalSubscribersByUserIdNew($userid, $startdate = 0, $enddate = 0, $newSubscribers = false) {
        if ($userid) {
            if ($startdate == 0 OR $enddate == 0) {
                if ($this->checkAdminUser($userid)) {
                    //$sql1 = "CALL report_count_subscribers_byid_1($userid)";
                    $sql1 = "SELECT count(DISTINCT(s.`phonenumber`)) AS total FROM `entity` as e1, `entity` as e2,
                        `entity` as e3, `subscribers` as s 
                        WHERE e1.`id` = e2.`parententity` AND   e2.`id` = e3.`parententity`
                        AND   e3.`id` = s.`folderid` AND   e1.`id` = '" . $userid . "' 
                        AND   e3.`typeid` = 4  AND   s.`optouttime` = '0000-00-00 00:00:00'";
                } else {
                    //$sql2 = "CALL report_count_subscribers_byid_2($userid)";
                    $sql2 = "SELECT count(DISTINCT(s.`phonenumber`)) AS total FROM `entity` as e1,  `entity` as e2,
                       `subscribers` as s WHERE e1.`id` = e2.`parententity` AND   e2.`id` = s.`folderid`
                       AND   e1.`id` = '" . $userid . "'  AND   e2.`typeid` = 4 AND   s.`optouttime` = '0000-00-00 00:00:00'";
                }

                if (!$this->hassubuser($userid)) {
                    //$sql4  = "CALL report_count_subscribers_byid_2($userid)";
                    $sql4 = "SELECT count(DISTINCT(s.`phonenumber`)) AS total FROM `entity` as e1,  `entity` as e2,
                       `subscribers` as s WHERE e1.`id` = e2.`parententity` AND   e2.`id` = s.`folderid`
                       AND   e1.`id` = '" . $userid . "'  AND   e2.`typeid` = 4 AND   s.`optouttime` = '0000-00-00 00:00:00'";
                }
            } else {
                if ($this->checkAdminUser($userid)) {
                    //$sql1 = "CALL report_count_subscribers_byid_byperiod_1($userid,'$startdate','$enddate')";
                    $sql1 = "SELECT count(DISTINCT(s.`phonenumber`)) AS total FROM `entity` as e1, `entity` as e2,
                        `entity` as e3, `subscribers` as s WHERE e1.`id` = e2.`parententity`
                        AND   e2.`id` = e3.`parententity` AND   e3.`id` = s.`folderid` AND   e1.`id` = '" . $userid . "'
                        AND   e3.`typeid` = 4 AND   s.`optouttime` = '0000-00-00 00:00:00' 
                       ";
                    $sql1 = "SELECT count(DISTINCT(s.`phonenumber`)) AS total FROM `entity` as e1, `entity` as e2, 
                    	`subscribers` as s WHERE e1.`id` = s.`folderid` AND e1.parententity=e2.id 
                    	and e1.typeid=4 and e2.id='" . $userid . "' AND   s.`optouttime` = '0000-00-00 00:00:00' ";
                    if ($newSubscribers)
                        $sql1 .= " AND   s.`createtime` BETWEEN '" . $startdate . "' AND '" . $enddate . "'";
                }
                else {
                    //$sql2 = "CALL report_count_subscribers_byid_byperiod_2($userid,'$startdate','$enddate')";
                    $sql2 = "SELECT count(s.`id`) AS total FROM `entity` as e1, `entity` as e2,  `subscribers` as s
                    		WHERE e1.`id` = e2.`parententity`  AND   e2.`id` = s.`folderid` 
                    		AND   e1.`id` = '" . $userid . "'  AND   e2.`typeid` = 4 AND   s.`optouttime` = '0000-00-00 00:00:00' 
                    		";
                    if ($newSubscribers)
                        $sq2 .= " AND   s.`createtime` BETWEEN '" . $startdate . "' AND '" . $enddate . "'";
                    //AND   s.`createtime` BETWEEN '".$startdate."' AND '".$enddate."'
                }
                if (!$this->hassubuser($userid)) {
                    //$sql4  =  "CALL report_count_subscribers_byid_byperiod_2($userid,'$startdate','$enddate')";
                    $sql4 = "SELECT count(s.`id`) AS total FROM `entity` as e1, `entity` as e2,  `subscribers` as s
                    		WHERE e1.`id` = e2.`parententity`  AND   e2.`id` = s.`folderid` 
                    		AND   e1.`id` = '" . $userid . "'  AND   e2.`typeid` = 4 AND   s.`optouttime` = '0000-00-00 00:00:00' 
                    		";
                    if ($newSubscribers)
                        $sql4 .= " AND   s.`createtime` BETWEEN '" . $startdate . "' AND '" . $enddate . "'";
                    //AND   s.`createtime` BETWEEN '".$startdate."' AND '".$enddate."'
                }
            }
            $total = 0;
            $sql1 = "SELECT count(DISTINCT(s.`phonenumber`)) AS total FROM `entity` as e1, `entity` as e2, 
                    	`subscribers` as s WHERE e1.`id` = s.`folderid` AND e1.parententity=e2.id 
                    	and e1.typeid=4 and e2.id='" . $userid . "' AND   s.`optouttime` = '0000-00-00 00:00:00' 
                    	AND (s.through ='' or s.through='upload' or s.through='webform' ) ";
            if ($newSubscribers)
                $sql1 .= " AND   s.`createtime` BETWEEN '" . $startdate . "' AND '" . $enddate . "'";
            //echo $sql1;exit;
            if (!empty($sql1)) {
                $rs1 = $this->query($sql1); //echo "<pre>"; print_r($rs1); exit;
                if ($rs1->total)
                    $total += $rs1->total;

//				if(!$this->hassubuser($userid)){
//					$rs2  = $this->query($sql4); 
//					if($rs2->total)
//						 $total += $rs2->total;
//				}
            }

//            if(!empty($sql2)){
//                $rs2  = $this->query($sql2); 
//                if($rs2->total)
//                     $total += $rs2->total;                  
//            }         

            return $total;
        }
        return 0;
    }

    public function totalCampaignFromUserAccount($userid, $yearmonth) {
        $sql = "SELECT count(distinct `campaignid`) as sms  from messages_outbound
           where createuser = $userid and campaignid != '(NULL)' and createtime like  '%$yearmonth%'";
        $rs = $this->query($sql);
        if ($rs->sms) {
            return $rs->sms;
        } else {
            return 0;
        }
    }

    public function reportCountTotalCampaignsByUserIdNew($userid, $startdate = 0, $enddate = 0) {
        if ($userid) {
            if ($startdate == 0 OR $enddate == 0) {
                if ($this->checkAdminUser($userid)) {
                    //$sql1 = "CALL report_count_campaign_byid_1($userid)";
                    $sql1 = "SELECT COUNT(DISTINCT(o.`reportingkey1`)) AS total FROM `entity` as e1, `entity` as e2,
                    		 `entity` as e3, `messages_outbound` as o WHERE e1.`id` = e2.`parententity`
                    		 AND   e2.`id` = e3.`parententity` AND   e3.`id` = o.`folderid` AND   e1.`id` = '" . $userid . "' 
                    		 AND   e3.`typeid` = 4 AND   length(o.`campaignid`) >6";
                } else {
                    //$sql2 = "CALL report_count_campaign_byid_2($userid)";
                    $sql2 = "SELECT COUNT(DISTINCT(o.`reportingkey1`)) AS total  FROM `entity` as e1, `entity` as e2,
                         `messages_outbound` as o WHERE e1.`id` = e2.`parententity` AND   e2.`id` = o.`folderid`
                         AND   e1.`id` = '" . $userid . "'  AND   e2.`typeid` = 4 AND   length(o.`campaignid`) >6";
                }

                if (!$this->hassubuser($userid)) {
                    //$sql4  = "CALL report_count_campaign_byid_2($userid)";
                    $sql4 = "SELECT COUNT(DISTINCT(o.`reportingkey1`)) AS total  FROM `entity` as e1, `entity` as e2,
                         `messages_outbound` as o WHERE e1.`id` = e2.`parententity` AND   e2.`id` = o.`folderid`
                         AND   e1.`id` = '" . $userid . "'  AND   e2.`typeid` = 4 AND   length(o.`campaignid`) >6";
                }
            } else {
                if ($this->checkAdminUser($userid)) {
                    //$sql1 = "CALL report_count_campaign_byid_byperiod_1($userid,'$startdate','$enddate')";
                    $sql1 = "SELECT COUNT(DISTINCT(o.`reportingkey1`)) AS total  FROM `entity` as e1, `entity` as e2,
                        `entity` as e3, `messages_outbound` as o WHERE e1.`id` = e2.`parententity` 
                        AND   e2.`id` = e3.`parententity` AND   e3.`id` = o.`folderid` AND   e1.`id` = '" . $userid . "' 
                        AND   e3.`typeid` = 4 AND   length(o.`campaignid`) >6  
                        AND   o.`createtime` BETWEEN '" . $startdate . "' AND '" . $enddate . "'";
                } else {
                    //$sql2 = "CALL report_count_campaign_byid_byperiod_2($userid,'$startdate','$enddate')";
                    $sql2 = "SELECT COUNT(DISTINCT(o.`reportingkey1`)) AS total FROM `entity` as e1, `entity` as e2,
                        `messages_outbound` as o WHERE e1.`id` = e2.`parententity` AND   e2.`id` = o.`folderid` 
                        AND   e1.`id` = '" . $userid . "'  AND   e2.`typeid` = 4 AND   length(o.`campaignid`) >6 
                        AND   o.`createtime` BETWEEN '" . $startdate . "' AND '" . $enddate . "'";
                }

                if (!$this->hassubuser($userid)) {
                    //$sql4  = "CALL report_count_campaign_byid_byperiod_2($userid,'$startdate','$enddate')";
                    $sql4 = "SELECT COUNT(DISTINCT(o.`reportingkey1`)) AS total FROM `entity` as e1, `entity` as e2,
                        `messages_outbound` as o WHERE e1.`id` = e2.`parententity` AND   e2.`id` = o.`folderid` 
                        AND   e1.`id` = '" . $userid . "'  AND   e2.`typeid` = 4 AND   length(o.`campaignid`) >6 
                        AND   o.`createtime` BETWEEN '" . $startdate . "' AND '" . $enddate . "'";
                }
            }
            $total = 0;

            if (!empty($sql1)) {
                $rs1 = $this->query($sql1); //echo "<pre>"; print_r($rs1); exit;
                if ($rs1->total)
                    $total += $rs1->total;

                if (!$this->hassubuser($userid)) {
                    $rs2 = $this->query($sql4);
                    if ($rs2->total)
                        $total += $rs2->total;
                }
            }

            if (!empty($sql2)) {
                $rs2 = $this->query($sql2);
                if ($rs2->total)
                    $total += $rs2->total;
            }

            return $total;
        }
        return 0;
    }

    public function reportCountTotalCampaignMessagesByUserIdNew($userid, $startdate = 0, $enddate = 0) {
        if ($userid) {
            if ($startdate == 0 OR $enddate == 0) {
                if ($this->checkAdminUser($userid)) {
                    //$sql1 = "CALL report_count_campaignmessage_byid_1($userid)";
                    $sql1 = "SELECT COUNT(r.`id`) AS total FROM `entity` as e1, `entity` as e2, `entity` as e3, 
                         `messages_outbound` as o, `messages_outbound_recipients` as r 
                         WHERE e1.`id` = e2.`parententity` AND   e2.`id` = e3.`parententity`
                         AND   e3.`id` = o.`folderid`  AND   o.`id` = r.`gatewaymessageid` 
                         AND   e1.`id` = '" . $userid . "'  AND   e3.`typeid` = 4 AND   length(o.`campaignid`) >6";
                } else {
                    //$sql2 = "CALL report_count_campaignmessage_byid_2($userid)";
                    $sql2 = "SELECT COUNT(r.`id`) AS total  FROM `entity` as e1, `entity` as e2,  
                    		`messages_outbound` as o, `messages_outbound_recipients` as r 
                    		WHERE e1.`id` = e2.`parententity`  AND   e2.`id` = o.`folderid` 
                    		AND   o.`id` = r.`gatewaymessageid`  AND   e1.`id` = '" . $userid . "' 
                    		AND   e2.`typeid` = 4 AND   length(o.`campaignid`) >6";
                }

                if (!$this->hassubuser($userid)) {
                    //$sql4  = "CALL report_count_campaignmessage_byid_2($userid)";
                    $sql4 = "SELECT COUNT(r.`id`) AS total  FROM `entity` as e1, `entity` as e2,  
                    		`messages_outbound` as o, `messages_outbound_recipients` as r 
                    		WHERE e1.`id` = e2.`parententity`  AND   e2.`id` = o.`folderid` 
                    		AND   o.`id` = r.`gatewaymessageid`  AND   e1.`id` = '" . $userid . "' 
                    		AND   e2.`typeid` = 4 AND   length(o.`campaignid`) >6";
                }
            } else {
                if ($this->checkAdminUser($userid)) {
                    //$sql1 = "CALL report_count_campaignmessage_byid_byperiod_1($userid,'$startdate','$enddate')";
                    $sql1 = "SELECT COUNT(r.`id`) AS total  FROM `entity` as e1, `entity` as e2, `entity` as e3,
                         `messages_outbound` as o,  `messages_outbound_recipients` as r 
                         WHERE e1.`id` = e2.`parententity` AND   e2.`id` = e3.`parententity` 
                         AND   e3.`id` = o.`folderid`  AND   o.`id` = r.`gatewaymessageid`  
                         AND   e1.`id` = '" . $userid . "'  AND   e3.`typeid` = 4
                         AND   length(o.`campaignid`) >6  
                         AND   r.`senttime` BETWEEN '" . $startdate . "' AND '" . $enddate . "'";
                } else {
                    //$sql2 = "CALL report_count_campaignmessage_byid_byperiod_2($userid,'$startdate','$enddate')";
                    $sql2 = "SELECT COUNT(r.`id`) AS total  FROM `entity` as e1, `entity` as e2,  
                    	`messages_outbound` as o, `messages_outbound_recipients` as r 
                    	WHERE e1.`id` = e2.`parententity`  AND   e2.`id` = o.`folderid` 
                    	AND   o.`id` = r.`gatewaymessageid`  AND   e1.`id` = '" . $userid . "' 
                    	AND   e2.`typeid` = 4 AND   length(o.`campaignid`) >6 
                    	AND   r.`senttime` BETWEEN '" . $startdate . "' AND '" . $enddate . "'";
                }

                if (!$this->hassubuser($userid)) {
                    //$sql4  = "CALL report_count_campaignmessage_byid_byperiod_2($userid,'$startdate','$enddate')";
                    $sql4 = "SELECT COUNT(r.`id`) AS total  FROM `entity` as e1, `entity` as e2,  
                    	`messages_outbound` as o, `messages_outbound_recipients` as r 
                    	WHERE e1.`id` = e2.`parententity`  AND   e2.`id` = o.`folderid` 
                    	AND   o.`id` = r.`gatewaymessageid`  AND   e1.`id` = '" . $userid . "' 
                    	AND   e2.`typeid` = 4 AND   length(o.`campaignid`) >6 
                    	AND   r.`senttime` BETWEEN '" . $startdate . "' AND '" . $enddate . "'";
                }
            }
            $total = 0;

            if (!empty($sql1)) {
                $rs1 = $this->query($sql1); //echo "<pre>"; print_r($rs1); exit;
                if ($rs1->total)
                    $total += $rs1->total;

                if (!$this->hassubuser($userid)) {
                    $rs2 = $this->query($sql4);
                    if ($rs2->total)
                        $total += $rs2->total;
                }
            }

            if (!empty($sql2)) {
                $rs2 = $this->query($sql2);
                if ($rs2->total)
                    $total += $rs2->total;
            }

            return $total;
        }
        return 0;
    }

    public function listTopthreeKeywordsFilterDeletedNew($userid) {
        if ($userid) {
            //$sql = "CALL top_three_keyword_optins($userid)";
            //now trying to get all keywords
            //$sql = "CALL keyword_folder_report_topthree($userid)";
            $sql = "SELECT COUNT(`phonenumber`) as total,s.folderid, m.value as foldername, s.keywordid, 
				k.keyword as keywordname, k.`deactivatetime` as deactivatetime 
				FROM  `subscribers` as s 
				LEFT JOIN   `entitymeta` as m ON s.`folderid`=m.`entityid` 
				LEFT JOIN   `keywords` as k ON s.`keywordid`=k.`id` 
				WHERE m.edituser = '" . $userid . "' AND s.`keywordid`!=0 
				AND k.`deactivatetime` = '0000-00-00 00:00:00' 
				GROUP BY s.`keywordid` ORDER BY total DESC";

            $rs = $this->query($sql);
            if ($rs->hasRecords()) {
                return $rs->fetchAll();
            }
        }
        return NULL;
    }

    public function listTopthreeKeywordsOptinsNew($userid) {
        if ($userid) {
            //$sql = "CALL top_three_keyword_optins($userid)";
            //now trying to get all keywords
            //$sql = "CALL keyword_folder_report_topthree($userid)";
            $sql = "SELECT COUNT(`phonenumber`) as total,s.folderid 
				FROM  `subscribers` as s 
				LEFT JOIN   `entitymeta` as m ON s.`folderid`=m.`entityid` 
				LEFT JOIN   `keywords` as k ON s.`keywordid`=k.`id` 
				WHERE m.edituser = '" . $userid . "' AND s.`keywordid`!=0 
				AND k.`deactivatetime` = '0000-00-00 00:00:00' 
				AND s.optouttime = '0000-00-00 00:00:00' 
				GROUP BY s.`keywordid` ORDER BY total DESC";

            $rs = $this->query($sql);
            if ($rs->hasRecords()) {
                return $rs->fetchAll();
            }
        }
        return NULL;
    }

    public function totalCampaignByFolderNew($folderid = '', $startdate = 0, $enddate = 0) {
        if ($folderid)
            $addlWhere = " AND o.folderid=$folderid";
        else
            $addlWhere = "";
        if ($startdate == 0 OR $enddate == 0) {
            //$sql = "CALL count_total_campaign_byfolder($folderid)";
            $sql = "SELECT COUNT(DISTINCT(o.`reportingkey1`)) as total,o.folderid 
                		FROM `messages_outbound` as o WHERE length(o.`campaignid`) > 6 $addlWhere 
                		GROUP BY o.folderid ";
        } else {
            //$sql = "CALL count_total_campaign_byfolder_byperiod($folderid,'$startdate','$enddate')";
            $sql = "SELECT COUNT(DISTINCT(o.`reportingkey1`)) as total,o.folderid 
                		FROM `messages_outbound` as o WHERE length(o.`campaignid`) > 6 $addlWhere 
                		AND o.`createtime` BETWEEN '" . $startdate . "' AND '" . $enddate . "'  
                		GROUP BY o.folderid ";
        }
        $rs = $this->query($sql);
        if ($rs->hasRecords()) {
            if ($rs->total) {
                return $rs->total;
            }
        }
        return NULL;
    }

    public function reportCountCampOptInOut($userid, $startdate = 0, $enddate = 0) {
        $addlWhere2 = $addlWhere = "";
        if ($startdate == 0 OR $enddate == 0) {
            
        } else {
            $addlWhere = " AND o.`createtime` BETWEEN '" . $startdate . "' AND '" . $enddate . "' ";
            $addlWhere2 = " AND s.`createtime` BETWEEN '" . $startdate . "' AND '" . $enddate . "' ";
        }

        if ($this->checkAdminUser($userid)) {
            $sql = "SELECT 
	(SELECT COUNT(DISTINCT(o.`reportingkey1`)) AS total FROM `entity` as e1, `entity` as e2, `entity` as e3, `messages_outbound` as o WHERE e1.`id` = e2.`parententity` AND e2.`id` = e3.`parententity` AND e3.`id` = o.`folderid` AND e1.`id` = '" . $userid . "' AND e3.`typeid` = 4 AND length(o.`campaignid`) >6 $addlWhere 
	) as campcount, 
	(SELECT count(DISTINCT(s.`phonenumber`)) AS total FROM `entity` as e1, `entity` as e2, `entity` as e3, `subscribers` as s LEFT JOIN `keywords` as k ON s.`keywordid`=k.`id`  
	WHERE e1.`id` = e2.`parententity` AND e2.`id` = e3.`parententity` AND e3.`id` = s.`folderid` AND e1.`id` = " . $userid . " AND e3.`typeid` = 4 AND s.`optouttime` = '0000-00-00 00:00:00' $addlWhere2 AND s.keywordid !=0
	) as optincount, 
	(SELECT count(DISTINCT(s.`phonenumber`)) AS total FROM `entity` as e1, `entity` as e2, `entity` as e3, `subscribers` as s LEFT JOIN `keywords` as k ON s.`keywordid`=k.`id`  
	WHERE e1.`id` = e2.`parententity` AND e2.`id` = e3.`parententity` AND e3.`id` = s.`folderid` AND e1.`id` = " . $userid . " AND e3.`typeid` = 4 AND s.`optouttime` != '0000-00-00 00:00:00' $addlWhere2 AND s.keywordid !=0
	) as optoutcount";
        } else {
            $sql = " SELECT 
    		(SELECT COUNT(DISTINCT(o.`reportingkey1`)) AS total FROM `entity` as e1, `entity` as e2,
                        `messages_outbound` as o WHERE e1.`id` = e2.`parententity` AND   e2.`id` = o.`folderid` 
                        AND   e1.`id` = '" . $userid . "'  AND   e2.`typeid` = 4 AND   length(o.`campaignid`) >6 
                        $addlWhere) as campcount, 
            (SELECT count(s.`id`) AS total FROM `entity` as e1, `entity` as e2, `subscribers` as s
            	WHERE e1.`id` = e2.`parententity`  AND   e2.`id` = s.`folderid` AND   e1.`id` = '" . $userid . "' 
            	AND   e2.`typeid` = 4 AND   s.`optouttime` = '0000-00-00 00:00:00' 
            	$addlWhere2 AND s.keywordid !=0 ) as optincount,
            (SELECT count(s.`id`) AS total FROM `entity` as e1, `entity` as e2, `subscribers` as s
            	WHERE e1.`id` = e2.`parententity`  AND   e2.`id` = s.`folderid` AND   e1.`id` = '" . $userid . "' 
            	AND   e2.`typeid` = 4 AND   s.`optouttime` != '0000-00-00 00:00:00' 
            	$addlWhere2 AND s.keywordid !=0 ) as optoutcount
			";
        }

        if (!empty($sql)) {
            $rs1 = $this->query($sql);
            // echo "<BR>$sql".mysql_error();
            return $rs1;
        }
        return 0;
    }

    public function reportCountRegGrpBySubscribersByUserIdNewKeys($userid,$type) {
        $date = date('Y-m');
        if($type =="life"){
           $sql = "SELECT count(distinct s.phonenumber) as subscriber FROM `subscribers_report` s 
            WHERE s.createuser = $userid and s.keywordid !=0 and s.optouttime ='0000-00-00 00:00:00'";  
        }else{
             $sql = "SELECT count(distinct s.phonenumber) as subscriber FROM `subscribers_report` s 
            WHERE s.createuser = $userid and s.keywordid !=0 and s.createtime like '%$date%' s.optouttime ='0000-00-00 00:00:00'";
        }
       

//        $sql = "SELECT count(distinct s.phonenumber) as subscriber FROM `subscribers_tmp` s 
//            WHERE s.keywordid in(select id from keywords where createuser=$userid) and
//            s.optouttime ='0000-00-00 00:00:00'";
        $rs = $this->query($sql);
        if ($rs->hasRecords()) {
            return $rs->subscriber;
        }
        return 0;
    }

    public function reportCountRegGrpBySubscribersByUserIdNew($userid, $startdate = 0, $enddate = 0, $regThrough = '') {

        if (empty($regThrough))
            $addlQry = " AND (s.through IS NULL OR s.through = '') AND s.keywordid !=0 ";
        else
            $addlQry = " AND s.through ='" . $regThrough . "'";

        if ($regThrough == "upload")
            $addlQry = " AND (s.through ='" . $regThrough . "' OR s.through='') AND s.keywordid=0 ";

        if ($userid) {
            if ($startdate == 0 OR $enddate == 0) {
                if ($this->checkAdminUser($userid)) {
                    $sql = "SELECT count(DISTINCT(s.`phonenumber`)) AS total FROM `entity` as e1, `entity` as e2,
                        `entity` as e3, `subscribers` as s 
                        WHERE e1.`id` = e2.`parententity` AND   e2.`id` = e3.`parententity`
                        AND   e3.`id` = s.`folderid` AND   e1.`id` = '" . $userid . "' 
                        AND   e3.`typeid` = 4  AND   s.`optouttime` = '0000-00-00 00:00:00'";
                } else {
                    $sql = "SELECT count(DISTINCT(s.`phonenumber`)) AS total FROM `entity` as e1,  `entity` as e2,
                       `subscribers` as s WHERE e1.`id` = e2.`parententity` AND   e2.`id` = s.`folderid`
                       AND   e1.`id` = '" . $userid . "'  AND   e2.`typeid` = 4 AND   s.`optouttime` = '0000-00-00 00:00:00'";
                }
            } else {
                if ($this->checkAdminUser($userid)) {
                    $sql = "SELECT count(DISTINCT(s.`phonenumber`)) AS total FROM `entity` as e1, `entity` as e2,
                        `entity` as e3, `subscribers` as s WHERE e1.`id` = e2.`parententity`
                        AND   e2.`id` = e3.`parententity` AND   e3.`id` = s.`folderid` AND   e1.`id` = '" . $userid . "'
                        AND   e3.`typeid` = 4 AND   s.`optouttime` = '0000-00-00 00:00:00' 
                        ";
                    $sql = "SELECT count(DISTINCT(s.`phonenumber`)) AS total FROM `entity` as e1, `entity` as e2, 
                    	`subscribers` as s WHERE e1.`id` = s.`folderid` AND e1.parententity=e2.id 
                    	and e1.typeid=4 and e2.id='" . $userid . "' AND   s.`optouttime` = '0000-00-00 00:00:00' ";
                    //AND   s.`createtime` BETWEEN '".$startdate."' AND '".$enddate."'
                } else {
                    $sql = "SELECT count(s.`id`) AS total FROM `entity` as e1, `entity` as e2,  `subscribers` as s
                    		WHERE e1.`id` = e2.`parententity`  AND   e2.`id` = s.`folderid` 
                    		AND   e1.`id` = '" . $userid . "'  AND   e2.`typeid` = 4 AND   s.`optouttime` = '0000-00-00 00:00:00' 
                    		";
                    //AND   s.`createtime` BETWEEN '".$startdate."' AND '".$enddate."'
                }
            }

            $sql = "SELECT count(DISTINCT(s.`phonenumber`)) AS total FROM `entity` as e1, `entity` as e2, 
                    	`subscribers` as s WHERE e1.`id` = s.`folderid` AND e1.parententity=e2.id 
                    	and e1.typeid=4 and e2.id='" . $userid . "' AND   s.`optouttime` = '0000-00-00 00:00:00' ";
            //echo $sql;exit;
            $total = 0;

            if (!empty($sql)) {
                $sql .= "  $addlQry ";
                $rs1 = $this->query($sql);
                if ($rs1->total)
                    $total += $rs1->total;
            }
            return $total;
        }
        return 0;
    }

    public function reportCountTotalOptInOut($userid, $startdate = 0, $enddate = 0) {
        $addlWhere2 = $addlWhere = "";
        if ($startdate == 0 OR $enddate == 0) {
            
        } else {
            $addlWhere = " AND o.`createtime` BETWEEN '" . $startdate . "' AND '" . $enddate . "' ";
            $addlWhere2 = " AND s.`createtime` BETWEEN '" . $startdate . "' AND '" . $enddate . "' ";
        }

        if ($this->checkAdminUser($userid)) {
            $sql = "SELECT 
	(SELECT count(DISTINCT(s.`phonenumber`)) AS total FROM `entity` as e1, `entity` as e2, `entity` as e3, `subscribers` as s LEFT JOIN `keywords` as k ON s.`keywordid`=k.`id`  
	WHERE e1.`id` = e2.`parententity` AND e2.`id` = e3.`parententity` AND e3.`id` = s.`folderid` AND e1.`id` = " . $userid . " AND e3.`typeid` = 4 AND s.`optouttime` = '0000-00-00 00:00:00' $addlWhere2 AND s.keywordid !=0
	) as optincount, 
	(SELECT count(DISTINCT(s.`phonenumber`)) AS total FROM `entity` as e1, `entity` as e2, `entity` as e3, `subscribers` as s LEFT JOIN `keywords` as k ON s.`keywordid`=k.`id`  
	WHERE e1.`id` = e2.`parententity` AND e2.`id` = e3.`parententity` AND e3.`id` = s.`folderid` AND e1.`id` = " . $userid . " AND e3.`typeid` = 4 AND s.`optouttime` != '0000-00-00 00:00:00' $addlWhere2 AND s.keywordid !=0
	) as optoutcount";
        } else {
            $sql = " SELECT 
            (SELECT count(s.`id`) AS total FROM `entity` as e1, `entity` as e2, `subscribers` as s
            	WHERE e1.`id` = e2.`parententity`  AND   e2.`id` = s.`folderid` AND   e1.`id` = '" . $userid . "' 
            	AND   e2.`typeid` = 4 AND   s.`optouttime` = '0000-00-00 00:00:00' 
            	$addlWhere2 AND s.keywordid !=0 ) as optincount,
            (SELECT count(s.`id`) AS total FROM `entity` as e1, `entity` as e2, `subscribers` as s
            	WHERE e1.`id` = e2.`parententity`  AND   e2.`id` = s.`folderid` AND   e1.`id` = '" . $userid . "' 
            	AND   e2.`typeid` = 4 AND   s.`optouttime` != '0000-00-00 00:00:00' 
            	$addlWhere2 AND s.keywordid !=0 ) as optoutcount
			";
        }

        if (!empty($sql)) {
            $rs1 = $this->query($sql);
            // echo "<BR>$sql".mysql_error();
            return $rs1;
        }
        return 0;
    }

    public function dashboardKeyword_activity($userid) {
        $sql = "CALL keyword_activity_dashboard($userid)";
        $rs = $this->query($sql);

        if ($rs->hasRecords()) {
            return $rs->fetchAll();
        }
    }
  
    public function dashboard_totalOptedIn($userid,$type) {
//        $sql = "CALL getTotal_optins_fromUser_account($userid)";
        $date = date('Y-m');
        if($type=="life"){
         $sql = "Select count(distinct phonenumber) as total from subscribers_report where createuser=$userid and optouttime ='0000-00-00 00:00:00'";
        }else{
            $sql = "Select count(distinct phonenumber) as total from subscribers_report where createuser=$userid and createtime like '%$date%' and optouttime ='0000-00-00 00:00:00'";
        }
        $rs = $this->query($sql);
        if ($rs->hasRecord()) {
            return $rs->total;
        } else {
            return false;
        }
    }
    public function totalOptedIn_optedout_fortheday($userid,$type) {
        $date = date('Y-m');
        if($type=="optin"){
         $sql = "Select count(distinct phonenumber) as total from subscribers_report where createuser=$userid and createtime like '%$date%'";
        }else{
            $sql = "Select count(distinct phonenumber) as total from subscribers_report where createuser=$userid  and optouttime like '%$date%'";
        }
        $rs = $this->query($sql);
        if ($rs->hasRecord()) {
            return $rs->total;
        } else {
            return false;
        }
    }

    public function dashboard_totalUpload($userid) {
        $ids = array();
        $sql = "SELECT count(distinct phonenumber) as ph_nmbrs FROM `subscribers_report` WHERE (`through`='' OR `through`='upload') and 
                        createuser=$userid and optouttime='0000-00-00 00:00:00'";
        $rs = $this->query($sql);

        if ($rs->hasRecord()) {
            return $rs->ph_nmbrs;
        } else {
            return false;
        }
    }

// end of  dashboard_totalOpedIn;

    public function dashboard_totalOptedInsByWebform($userid,$type) {
        $date=date('Y-m');
        if($type=='life'){
        $sql = "SELECT count(distinct phonenumber) as ph_nmbrs FROM `subscribers_report` WHERE `through`='webform' and `webform_url_id` !=0 and 
                        createuser=$userid and optouttime='0000-00-00 00:00:00'";
        }else{
        $sql = "SELECT count(distinct phonenumber) as ph_nmbrs FROM `subscribers_report` WHERE `through`='webform' and `webform_url_id` !=0 and 
                        createuser=$userid and createtime like '%$date%' and optouttime='0000-00-00 00:00:00'";
        }
        $rs = $this->query($sql);

        if ($rs->hasRecord()) {
            return $rs->ph_nmbrs;
        } else {
            return false;
        }
    }

// end of  dashboard_totalOpedIn;
    /**
     * this function returns total sms
     * send out by user at the current
     * month of the year. This is for 
     * dash board
     * @param $userid int $yearmonth
     * @name dashboard_totalMessagesSentOut;
     */
    public function dashboard_totalMessagesSentOut($userid, $yearmonth) {
        $sql = "CALL totalSms_from_user_account_tmp($userid,'$yearmonth')";
        $rs = $this->query($sql);
        if ($rs->hasRecord()) {
            return $rs->sms;
        } else {
            return false;
        }
    }

// end of  dashboard_totalMessagesSentOut;

    /**
     * this function returns total sms
     * send out by user at the current
     * month of the year. This is for 
     * dash board
     * @param $userid int $yearmonth
     * @name dashboard_totalMessagesSentOut;
     */
    public function dashboard_totalCampaignSentOut($userid, $yearmonth) {
        $$ids = array();
        $sql = "CALL totalCampaign_from_user_account($userid,'$yearmonth')";
        $rs = $this->query($sql);

        if ($rs->hasRecord()) {
            return $rs->campaign;
        } else {
            return false;
        }
    }

// end of  dashboard_totalCampaignSentOut;

    public function getMyla($userid, $fid,$type) {
        $date = date('Y-m');
        if($type=="life"){
             $sql = "SELECT count(distinct phonenumber) as num FROM `subscribers_report` WHERE folderid =$fid and createuser=$userid and optouttime ='0000-00-00 00:00:00'";
        }else{
             $sql = "SELECT count(distinct phonenumber) as num FROM `subscribers_report` WHERE folderid =$fid and createuser=$userid and createtime like'%$date%' and optouttime ='0000-00-00 00:00:00'";
        }
        $rs = $this->query($sql);
        if ($rs->hasRecord()) {
            return $rs->num;
        } else {
            return 0;
        }
    }

    public function getMylaFoldersId() {
        $myla = array();
        $sql = "select e2.id as id, e.id as fid from entity e,entity e2,entitymeta m
             where e.typeid=4 and m.entityid =e.id  and e2.typeid=5 and e.createuser=e2.id and m.value like CONCAT('%', e2.id, '')";
        $rs = $this->query($sql);
        if ($rs->hasRecords()) {
            foreach ($rs->fetchAll() as $ml => $mid) {
                $myla[$mid['id']] = $mid['fid'];
            }
            return $myla;
        } else {
            return 0;
        }
    }

    //DASHBORD INFO GET FROM USER ACCOUNT

    public function accountids() {
        $ids = array();
        $sql = "select e.id as id from entity e, entitymeta m where e.typeid=5 and m.entityid=e.id and m.profileid=8 and m.value=1";
        $rs = $this->query($sql);
        if ($rs->hasRecords()) {
            foreach ($rs->fetchAll() as $fid => $val) {
                $ids[] = $val['id'];
            }
            return $ids;
        }
    }

    public function activateUserproperty($userid, $profileid, $value) {
//          $value = $this->_dbh->real_escape_string($value);
        $sql = "CALL entity_meta_update($userid, $profileid, '$value', $userid)";
        $rs = $this->query($sql);
        return $rs;
    }

    /**
     *   Update subcribers_report table from subscribers
     * 
     */
    public function subscribers_reportUpdate() {
        $sql = "Call subcribers_reporting()";
        $rs = $this->query($sql);
        return $rs->rows;
    }

    /**
     *   Gets total campaigns at this moment of the month
     * 
     */
    public function getUserTotalCampaign($userid) {
        $date = date('Y-m');
        $sql = "SELECT sum(sended) as campaign FROM `wklymonthlyreoccurance` WHERE `createuser`=$userid and `createtime` like '%$date%'";
        $rs = $this->query($sql);
        return $rs->campaign;
    }

    /**
     *   Gets total user marketing info for each activity
     *  myla, bday, keywords, webform and marketing
     * 
     */
    public function getUserMarketingInfo($source, $userid) {
        $date = date('Y-m');
        $sql = "select count(`mobilenumber`) as total from messages_outbound_recipients where depth=$source and `createuser`=$userid and senttime like '%$date%'";
        $rs = $this->query($sql);
        return $rs->total;
    }
    public function getUserMarketingInfo_Mo($userid) {
        $date = date('Y-m');
        $sql = "select count(`phonenumber`) as total from mt_for_mo_request where `createuser`=$userid and createtime like '%$date%'";
        $rs = $this->query($sql);
        return $rs->total;
    }

    /**
     *    Total sms send out from client account
     *    selecting from messages_outbound_recipients by today from beg of current month
     *   @access public
     */
    public function getTotalMarketingMt($userid, $dformat) {
        $ym = date('Y-m');
        $ymd = date('Y-m-d');
        $sql = '';
        if ($dformat == 'ym') {
            $sql = "SELECT count(`mobilenumber`) as total FROM `messages_outbound_recipients` WHERE `createuser`=$userid and senttime like '%$ym%'";
        } else {
            $sql = "SELECT count(`mobilenumber`) as total FROM `messages_outbound_recipients` WHERE `createuser`=$userid and senttime like '%$ymd%'";
        }
        $rs = $this->query($sql);
        return $rs->total;
    }

    /**
     * 
     * 
     */
    public function saveUserDailyactivity() {
        $usrids = $this->accountids();
//        $usrids = $this->corparateIdList(185, 5);
        $totalusr = count($usrids);
        for ($id = 0; $id < $totalusr; $id++) 
        {
            $user = new Application_Model_User((int) $usrids[$id]);
            $first = addslashes($user->firsrtname = ($user->firstname) ? $user->firstname : '');
            $last = addslashes($user->lastname = ($user->lastname) ? $user->lastname : '');
            $business = addslashes($user->businessname = ($user->businessname) ? $user->businessname : '');
            $totalcampsent = $user->totalcampsent = ($user->totalcampsent) ? $user->totalcampsent : 0;
            $keywordoptin = $user->keywordoptin = ($user->keywordoptin) ? $user->keywordoptin : 0;
            $weboptin = $user->weboptin = ($user->weboptin) ? $user->weboptin : 0;
            $totalupload = $user->totalupload = ($user->totalupload) ? $user->totalupload : 0;
            $keywordmt = $user->keywordmt = ($user->keywordmt) ? $user->keywordmt : 0;
            $webmt = $user->webmt = ($user->webmt) ? $user->webmt : 0;
            $dobmt = $user->dobmt = ($user->dobmt) ? $user->dobmt : 0;
//            $dailymt = $user->dailymt = ($user->dailymt) ? $user->dailymt : 0;  // daily marketing mt
            $myla = $user->myla = ($user->myla) ? $user->myla : 0;
            $mylamt = $user->mylamt = ($user->mylamt) ? $user->mylamt : 0;
            $totaloptin = $user->totaloptin = ($user->totaloptin) ? $user->totaloptin : 0;
            $totaloptout = $user->totaloptout = ($user->totaloptout) ? $user->totaloptout : 0;
            $totalsubscribers = $user->totalsubscribers = ($user->totalsubscribers) ? $user->totalsubscribers : 0;
            $totalmt = $user->totalmt = ($user->totalmt) ? $user->totalmt : 0;
            $totalmrkmt = $user->totalmrkmt = ($user->totalmrkmt) ? $user->totalmrkmt : 0;
            $usrid = $usrids[$id];
//                       echo '<br>'.'First: '.$first.' last: '.$last.' business: '.$business.' totaCamsent: '.$totalcampsent.
//                               ' keyOptin: '.$keywordoptin.' weboptin: '.$weboptin.' totalUp: '.$totalupload.' kwdmt: '.$keywordmt.' webmt: '.$webmt.
//                               ' myla; '.$myla.' mylamt: '.$mylamt.' toptin: '.$totaloptin.' toptout: '.$totaloptout.' tsubscrs: '. $totalsubscribers.' totalmt: '.$totalmt.
//                               ' totalmkmt: '.$totalmrkmt;
             $this->query("insert into dashboardinfo (`createuser`, `firstname`, `lastname`, `business`,`campaigns`, 
                            `newusers`,`keywordoptin`,`weboptin`, `upload`,`keywordmt`,`webformmt`,`birthday`,
                            `marketingmt`, `myla`, `mylamt`, `totaloptin`, `totaloptout`,`totalsub`,`totalsms`) values($usrid, '$first',  '$last', 
                             '$business',  $totalcampsent, 0,$keywordoptin,$weboptin, $totalupload, $keywordmt,$webmt,$dobmt,$totalmt,$myla,$mylamt,
                             $totaloptin, $totaloptout, $totalsubscribers, $totalmrkmt)");
//           $rs = $this->query("insert into dashboardinfo (`createuser`, `firstname`, `lastname`, `business`,`campaigns`, 
//                            `newusers`,`keywordoptin`,`weboptin`, `upload`,`keywordmt`,`webformmt`,`birthday`,
//                            `marketingmt`, `myla`, `mylamt`, `totaloptin`, `totaloptout`,`totalsub`,`totalsms`) values($usrid, '$user->firstname',  '$user->lastname', 
//                             '$user->businessname',  $user->totalcampsent, 0,$user->keywordoptin,$user->weboptin,
//                             $user->totalupload, $user->keywordmt,$user->webmt,$user->dobmt,$user->totalmt,$user->myla,$user->mylamt,$user->totaloptin,
//                             $user->totaloptout, $user->totalsubscribers, $user->totalmrkmt)");
        }
    }

    /**
     * 
     * 
     */
    public function getBadNumbers($usrid, $num) {
        if (count($num) > 0) {
            $arstr = implode(',', $num);
            $sql = "select distinct `destination` from messages_drs_delivery where `destination` in($arstr) and statuscode in(90,91)";
            $rs = $this->query($sql);
            $arbad = array();
            if ($rs->hasRecords()) {
                foreach ($rs->fetchAll() as $ind => $pn) {
                    $phone = $pn['destination'];
                    $this->query("insert into badnumbers (`createuser`,`destination`) values($usrid,$phone)");
                    $arbad[] = $pn['destination'];
                    $this->query("delete from subscribers where phonenumber=$phone");
                }
                return json_encode($arbad);
            }
        }
        return 0;
    }

}
