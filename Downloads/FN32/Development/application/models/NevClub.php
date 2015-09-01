<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of NevClub
 *
 * @author farad
 */
class Application_Model_NevClub extends Application_Model_Abstract {

    private $clubid;
    private $nevid;
    private $messages;
    private $phones;
    private $timezone;
    
    private $bodsend;
    private $bodmsg;
    private $bodconf;
    
    private $blngfirstsend;
    private $blngfirstmsg;
    private $blngfirstconf;

    private $blngsecondsend;
    private $blngsecondmsg;
    private $blngsecondconf;
    
    private $blngthirdsend;
    private $blngthirdmsg;
    private $blngthirdconf;
    
    private $blngfourthsend;
    private $blngfourthmsg;
    private $blngfourthconf;
    
    private $blngfifthsend;
    private $blngfifthmsg;
    private $blngfifthconf;
    
    private $blngsixthsend;
    private $blngsixthmsg;
    private $blngsixthconf;
            
    private $campaignsend;
    private $campaignmsg;
    private $campaignconf;

    private $continent;
    public function __construct() {
        $this->phones = array();
        $this->messages = array();
    }
    public function getTimezone(){
        return $this->timezone;
    }

    public function setTimezone($timezone){
       $this->timezone = $timezone;
   }
    public function getBodsend(){
        return $this->bodsend;
    }

    public function setBodsend($bodsend){
       $this->bodsend = $bodsend;
   }
    public function getBodmsg(){
        return $this->bodmsg;
    }

    public function setBodmsg($bodmsg){
       $this->bodmsg = $bodmsg;
   }
    public function getBodconf(){
        return $this->bodconf;
    }

    public function setBodconf($bodconf){
       $this->bodconf = $bodconf;
   }
    public function getBlngfirstsend(){
        return $this->blngfirstsend;
    }

    public function setBlngfirstsend($blngfirstsend){
       $this->blngfirstsend = $blngfirstsend;
   }
    public function getBlngfirstmsg(){
        return $this->blngfirstmsg;
    }

    public function setBlngfirstmsg($blngfirstmsg){
       $this->blngfirstmsg = $blngfirstmsg;
   }
    public function getBlngfirstconf(){
        return $this->blngfirstconf;
    }

    public function setBlngfirstconf($blngfirstconf){
       $this->blngfirstconf = $blngfirstconf;
   }
    public function getBlngsecondsend(){
        return $this->blngsecondsend;
    }

    public function setBlngsecondsend($blngsecondsend){
       $this->blngsecondsend = $blngsecondsend;
   }
    public function getBlngsecondmsg(){
        return $this->blngsecondmsg;
    }

    public function setBlngsecondmsg($blngsecondmsg){
       $this->blngsecondmsg = $blngsecondmsg;
   }
    public function getBlngsecondconf(){
        return $this->blngsecondconf;
    }

    public function setBlngsecondconf($blngsecondconf){
       $this->blngsecondconf = $blngsecondconf;
   }
    public function getBlngthirdsend(){
        return $this->blngthirdsend;
    }

    public function setBlngthirdsend($blngthirdsend){
       $this->blngthirdsend = $blngthirdsend;
   }
    public function getBlngthirdmsg(){
        return $this->blngthirdmsg;
    }

    public function setBlngthirdmsg($blngthirdmsg){
       $this->blngthirdmsg = $blngthirdmsg;
   }
    public function getBlngthirdconf(){
        return $this->blngthirdconf;
    }

    public function setBlngthirdconf($blngthirdconf){
       $this->blngthirdconf = $blngthirdconf;
   }
    public function getBlngfourthsend(){
        return $this->blngfourthsend;
    }

    public function setBlngfourthsend($blngfourthsend){
       $this->blngfourthsend = $blngfourthsend;
   }
    public function getBlngfourthmsg(){
        return $this->blngfourthmsg;
    }

    public function setBlngfourthmsg($blngfourthmsg){
       $this->blngfourthmsg = $blngfourthmsg;
   }
    public function getBlngfourthconf(){
        return $this->blngfourthconf;
    }

    public function getBlngfifthsend() {
        return $this->blngfifthsend;
    }

    public function setBlngfifthsend($blngfifthsend) {
        $this->blngfifthsend = $blngfifthsend;
    }

    public function getBlngfifthmsg() {
        return $this->blngfifthmsg;
    }

    public function setBlngfifthmsg($blngfifthmsg) {
        $this->blngfifthmsg = $blngfifthmsg;
    }

    public function getBlngfifthconf() {
        return $this->blngfifthconf;
    }

    public function setBlngfifthconf($blngfifthconf) {
        $this->blngfifthconf = $blngfifthconf;
    }

    public function getBlngsixthsend() {
        return $this->blngsixthsend;
    }

    public function setBlngsixthsend($blngsixthsend) {
        $this->blngsixthsend = $blngsixthsend;
    }

    public function getBlngsixthmsg() {
        return $this->blngsixthmsg;
    }

    public function setBlngsixthmsg($blngsixthmsg) {
        $this->blngsixthmsg = $blngsixthmsg;
    }

    public function getBlngsixthconf() {
        return $this->blngsixthconf;
    }

    public function setBlngsixthconf($blngsixthconf) {
        $this->blngsixthconf = $blngsixthconf;
    }

    public function setBlngfourthconf($blngfourthconf){
       $this->blngfourthconf = $blngfourthconf;
   }

   public function getCampaignsend(){
       return $this->campaignsend;
   }
    public function setCampaignsend($campaignsend){
       $this->campaignsend = $campaignsend;
   }
   public function getCampaignmsg(){
       return $this->campaignmsg;
   }

   public function setCampaignmsg($campaignmsg){
       $this->campaignmsg = $campaignmsg;
   }

   public function getCampaignconf() {
        return $this->campaignconf;
    }

    public function setCampaignconf($campaignconf) {
        $this->campaignconf = $campaignconf;
    }
    public function getNevid() {
        return $this->nevid;
    }

    public function setNevid($nevid) {
        $this->nevid = $nevid;
    }
    public function getClubid() {
        return $this->clubid;
    }

    public function setClubid($clubid) {
        $this->clubid = $clubid;
    }

    public function getMessages() {
        return $this->messages;
    }
    
    public function setMessages($messages) {
        $this->messages = $messages;
    }
    public function addMessages($message) {
        $this->messages[] = $message;
    }
     public function getPhones() {
        return $this->phones;
    }

    public function setPhones($phones) {
        $this->phones = $phones;
    }

    public function addPhones($phone) {
        $this->phones[] = $phone;
    }
   
    public function getContinent() {
        return $this->continent;
    }

    public function setContinent($continent) {
        $this->continent = $continent;
    }

         
        public function __destruct() {
        
    }

//    public function __call($method, $args) {
//        if (method_exists($this, $method)) {
//// use reflectio to figure visibility
//            $reflector = new \ReflectionClass(get_class($this));
//            $visibility = $reflector->getMethod($method)->isPrivate() ? 'Private' : 'Protected';
//            throw new \Exception($visibility . "method \"{$method}\" is called");
//        }
//        throw new \Exception("Unexisting method \"{$method}\" is called");
//    }

}

?>
