<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Abcclub
 *
 * @author farad
 */
class Application_Model_Abcclub extends Application_Model_Abstract{
    private $memberid;
    private $abcclubid;
    private $firstname;
    private $lastname;
    private $phonenumber;
    private $email;
    private $gender;
    private $birthday;
    private $dues;
    private $duepaymentdate;
    private $alertdate;
    
    public function __construct() {
        ;
    }
    
    public function getMemberid() {
        return $this->memberid;
    }

    public function setMemberid($memberid) {
        $this->memberid = $memberid;
    }

    public function getAbcclubid() {
        return $this->abcclubid;
    }

    public function setAbcclubid($abcclubid) {
        $this->abcclubid = $abcclubid;
    }

    public function getFirstname() {
        return $this->firstname;
    }

    public function setFirstname($firstname) {
        $this->firstname = $firstname;
    }

    public function getLastname() {
        return $this->lastname;
    }

    public function setLastname($lastname) {
        $this->lastname = $lastname;
    }

    public function getPhonenumber() {
        return $this->phonenumber;
    }

    public function setPhonenumber($phonenumber) {
        $this->phonenumber = $phonenumber;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getGender() {
        return $this->gender;
    }

    public function setGender($gender) {
        $this->gender = $gender;
    }

    public function getBirthday() {
        return $this->birthday;
    }

    public function setBirthday($birthday) {
        $this->birthday = $birthday;
    }

    public function getDuepaymentdate() {
        return $this->duepaymentdate;
    }

    public function getDues() {
        return $this->dues;
    }

    public function setDues($dues) {
        $this->dues = $dues;
    }

    public function setDuepaymentdate($duepaymentdate) {
        $this->duepaymentdate = $duepaymentdate;
    }

    public function getAlertdate() {
        return $this->alertdate;
    }

    public function setAlertdate($alertdate) {
        $this->alertdate = $alertdate;
    }

    public function __destruct() {
        unset($this->abcclubid);
        unset($this->firstname);
        unset($this->lastname);
        unset($this->phonenumber);
        unset($this->birthday);
        unset($this->gender);
        unset($this->email);
        unset($this->alertdate);
        unset($this->duepaymentdate);
    }
}

?>
