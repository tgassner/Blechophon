<?php

  function getDBPDO() {
      $dbhost = "localhost";
      $dbuser = "blechophon";
      $dbpass = getPassword();
      $dbname = "blechophon";

      $dbh = new PDO(
          "mysql:host=$dbhost;dbname=$dbname",
          $dbuser,
          $dbpass,
          array(PDO::ATTR_PERSISTENT => true, // Verbindung bleibt bis scriptende gecached !
              PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
              PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

      return $dbh;
  }

include('include/termine.inc.php');
include('include/user.inc.php');
include('../include/password.inc.php');

  
  function isLoggedIn(){
    if (isset($_SESSION['login']) && ($_SESSION['login'] == "true")){
      return true;
    }else{
      return false;
    }
  }
  
  function checkLogin(){
    if (isset($_POST['loginusername']) && (!(isset($_SESSION['login'])) || $_SESSION['login'] == "false")) {
      echo("XXXXXXXXXXXXXXXXXXXXXXX");
      $username = $_POST['loginusername'];
      $passwort = sha1(trim($_POST['loginpasswort']));
      
      $dbPdo = getDBPDO();

      $sql = "select userid from user where (username = :userName  or  email = :email )and password = :password and canlogin = 1";
      $stmt = $dbPdo->prepare($sql);
      $stmt->bindParam("userName", $username, PDO::PARAM_STR);
      $stmt->bindParam("email", $username, PDO::PARAM_STR);
      $stmt->bindParam("password", $passwort, PDO::PARAM_STR);
      $stmt->execute();
      $result = $stmt->fetch(PDO::FETCH_ASSOC);

      if (!($result)){
        echo("<div style=\"error\">login fehlgeschlagen</div>");
      } else {
        $userid = $result["userid"];
        
        $user = getFullUserByID($userid);
        
        $_SESSION['login'] = "true";
        $_SESSION['username'] = $user->getUsername();
        $_SESSION['userid'] = $userid;

        $sektionenlist = array();
        $sektionenlist[] = "admin";
        $sektionenlist[] = "termine";
        $_SESSION['sektionen'] = $sektionenlist;
      }
    } else if (isset($_POST['logout']) && (isset($_SESSION['login'])))  { // end if request method post  
        $_SESSION['login'] = "false";
        $_SESSION['username'] = "";
        $_SESSION['userid'] = -1;
    }
  }
  
  function formatTime($time){
    return substr($time,0,5);
  }

?>
