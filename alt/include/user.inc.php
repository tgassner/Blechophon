<?php

define('SELECT_ALL_FROM_USER_U', 'select distinct u.*, ROUND(DATEDIFF(CURDATE(), u.birthdate) / 365.25,3) as age  ');

function hasCurrentUserRight($recht) {
    if (!isLoggedIn()){
      return false;
    }else{
    
        if (isset($_SESSION['sektionen']) && (in_array($recht, $_SESSION['sektionen']) || in_array('admin', $_SESSION['sektionen']))){
            return true;
        }else{
          return false;
        }
    }
}

function getCurrentUserId() {
    return $_SESSION['userid'];
}

    function getFullUserByID($userid) {
        $sql = SELECT_ALL_FROM_USER_U . " FROM user u where userid = :userid";
        try {
            $db = getDBPDO();
            $stmt = $db->prepare($sql);
            $stmt->bindParam("userid", $userid, PDO::PARAM_INT);
            $stmt->execute();
            $myrow = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($myrow) {
                $user = fillUserObject($myrow);
            } else {
                $user = null;
            }
            $db = null;
            return $user;
        } catch (PDOException $e) {
        }
    }

  function fillUserObject($myrow) {
    return new User($myrow['userid'],$myrow['username'],$myrow['password'],
              $myrow['aktiv'],$myrow['canlogin'],$myrow['order'],
              $myrow['firstname'],$myrow['surname'],$myrow['nickname'],$myrow['email'],
              $myrow['phone'],$myrow['foto'],$myrow['description'],$myrow['instrument1'],
              $myrow['instrument2'], $myrow['instrument3'], $myrow['icq'], 
              $myrow['skype'], $myrow['street'], $myrow['zip'], 
              $myrow['city'], $myrow['birthdate']
           );
  }
  
   class User {
    var $userid;
    var $username;
    var $password;
    var $aktiv;
    var $canlogin;
    var $order;
    var $firstname;
    var $surname;
    var $nickname;
    var $email;
    var $phone;
    var $foto;
    var $description;
    var $instrument1;
    var $instrument2;
    var $instrument3;
    var $icq;
    var $skype;
    var $street;
    var $zip;
    var $city;
    var $birthdate;

    function User($userid, $username, $password, $aktiv, $canlogin, $order, $firstname, $surname, $nickname, 
                      $email, $phone, $foto, $description, $instrument1, $instrument2, $instrument3, $icq, $skype,
                      $street, $zip, $city, $birthdate) 
      {
          $this->userid = $userid;
          $this->username = $username;
          $this->password = $password;
          $this->aktiv = $aktiv;
          $this->canlogin = $canlogin;
          $this->order = $order;
          $this->firstname = $firstname;
          $this->surname = $surname;
          $this->nickname = $nickname;
          $this->email = $email;
          $this->phone = $phone;
          $this->foto = $foto;
          $this->description = $description;
          $this->instrument1 = $instrument1;
          $this->instrument2 = $instrument2;
          $this->instrument3 = $instrument3;
          $this->icq = $icq;
          $this->skype = $skype;
          $this->street = $street;
          $this->zip = $zip;
          $this->city = $city;
          $this->birthdate = $birthdate;
      }   
    
    function getUserid() {
      return $this->userid;
    }

    function setUserid($userid){
      $this->userid = $userid;
    }

    function getUsername() {
      return $this->username;
    }

    function setUsername($username){
      $this->username = $username;
    }

    function getPassword() {
      return $this->password;
    }

    function setPassword($password){
      $this->password = $password;
    }

    function getAktiv() {
      return $this->aktiv;
    }

    function setAktiv($aktiv){
      $this->aktiv = $aktiv;
    }

    function getCanlogin() {
      return $this->canlogin;
    }

    function setCanlogin($canlogin){
      $this->canlogin = $canlogin;
    }

    function getOrder() {
      return $this->order;
    }

    function setOrder($order){
      $this->order = $order;
    }

    function getFirstname() {
      return $this->firstname;
    }

    function setFirstname($firstname){
      $this->firstname = $firstname;
    }

    function getSurname() {
      return $this->surname;
    }

    function setSurname($surname){
      $this->surname = $surname;
    }

    function getNickname() {
      return $this->nickname;
    }

    function setNickname($nickname){
      $this->nickname = $nickname;
    }
    
    function getEmail() {
      return $this->email;
    }

    function setEmail($email){
      $this->email = $email;
    }

    function getPhone() {
      return $this->phone;
    }

    function setPhone($phone){
      $this->phone = $phone;
    }

    function getFoto() {
      return $this->foto;
    }

    function setFoto($foto){
      $this->foto = $foto;
    }

    function getDescription() {
      return $this->description;
    }

    function setDescription($description){
      $this->description = $description;
    }

    function getInstrument1() {
      return $this->instrument1;
    }

    function setInstrument1($instrument1){
      $this->instrument1 = $instrument1;
    }

    function getInstrument2() {
      return $this->instrument2;
    }

    function setInstrument2($instrument2){
      $this->instrument2 = $instrument2;
    }

    function getInstrument3() {
      return $this->instrument3;
    }

    function setInstrument3($instrument3){
      $this->instrument3 = $instrument3;
    }

    function getIcq() {
      return $this->icq;
    }

    function setIcq($icq){
      $this->icq = $icq;
    }

    function getSkype() {
      return $this->skype;
    }

    function setSkype($skype){
      $this->skype = $skype;
    }

    function getStreet() {
      return $this->street;
    }

    function setStreet($street){
      $this->street = $street;
    }

    function getZip() {
      return $this->zip;
    }

    function setZip($zip){
      $this->zip = $zip;
    }

    function getCity() {
      return $this->city;
    }

    function setCity($city){
      $this->city = $city;
    }
    
    function getBirthdate() {
      return $this->birthdate;
    }

    function setBirthdate($birthdate){
      $this->birthdate = $birthdate;
    }
}


?>
