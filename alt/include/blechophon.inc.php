<?php

  function getDB() {
    $db = mysql_connect("localhost","blechophon","Gleis15");
    mysql_select_db("blechophon",$db);
    return $db;
  }

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

function calcImgSize($original) {
  $ret = $original;
  if (isset($_SESSION['sizeno'])) {
    if ($_SESSION['sizeno'] == 1) {
      $ret = $original * 0.5;
    } else if ($_SESSION['sizeno'] == 2) {
      $ret = $original * 0.75;
    } else if ($_SESSION['sizeno'] == 3) {
      $ret = $original * 1;
    } else if ($_SESSION['sizeno'] == 4) {
      $ret = $original * 1.25;
    } else if ($_SESSION['sizeno'] == 5) {
      $ret = $original * 1.5;
    } else if ($_SESSION['sizeno'] == 6) {
      $ret = $original * 1.75;
    }
  }

  $ret = round($ret);

  //echo("Debug:" . $original . " " . $ret . " " . $_SESSION['sizeno'] . " ");

  return $ret;
}
  
  function isLoggedIn(){
    if (isset($_SESSION['login']) && ($_SESSION['login'] == "true")){
      return true;
    }else{
      return false;
    }
  }
  
  function checkLogin(){
    if (isset($_POST['loginusername']) && !(isset($_SESSION['login']))) {
      $username = $_POST['loginusername'];
      $passwort = sha1(trim($_POST['loginpasswort']));
      
      //$db = getDB();
      $dbPdo = getDBPDO();

      $sql = "select userid from user where (username = :userName  or  email = :email )and password = :password and canlogin = 1";
      $stmt = $dbPdo->prepare($sql);
      $stmt->bindParam("userName", $username, PDO::PARAM_STR);
      $stmt->bindParam("email", $username, PDO::PARAM_STR);
      $stmt->bindParam("password", $passwort, PDO::PARAM_STR);
      $stmt->execute();
      $result = $stmt->fetch(PDO::FETCH_ASSOC);

      if (!($result)){
        $state = "loginerror";
        echo("<div style=\"error\">login fehlgeschlagen</div>");
      }else{	      	
        $state = "loginok";
        $userid = $result["userid"];
        
        $user = getFullUserByID($userid);
        
        $_SESSION['login'] = "true";
        $_SESSION['username'] = $user->getUsername();
        $_SESSION['userid'] = $userid;

        $sql2 = "select sektion from rechte r, rechteuser ru where r.rechteid = ru.rechteid and userid = :userId";
        $stmt2 = $dbPdo->prepare($sql2);
        $stmt2->bindParam("userId", $userid, PDO::PARAM_INT);
        $stmt2->execute();

        while ($result2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {
          $sektionenlist[] = $result2["sektion"];
        } //while für sektionen
        $_SESSION['sektionen'] = $sektionenlist;
        
      } //else
    } else if (isset($_POST['logout']) && (isset($_SESSION['login'])))  { // end if request method post  
        $_SESSION['login'] = "false";
        $_SESSION['username'] = "";
        $_SESSION['userid'] = -1;
    }
  } //checkLogin
  
  function formatTime($time){
    return substr($time,0,5);
  }

  function getEncryptEmail($plainText){
      $encryptet = "";
      $strlength = strlen($plainText);
      for ($i = 0; $i < $strlength; $i++){
        $element = substr($plainText,$i,1);
        if ((ord($element) >= ord('a')) && (ord($element) < ord('z'))) {
          $element = chr(ord($element) + 1);
        } else if (ord($element) == ord('z')) {
          $element = "a";
        } else if ((ord($element) >= ord('A')) && (ord($element) < ord('Z'))){
          $element = chr(ord($element) + 1);
        } else if (ord($element) == ord('Z')){
          $element = "A";
        } else if ((ord($element) >= ord('0')) && (ord($element) < ord('9'))){
          $element = chr(ord($element) + 1);
        } else if (ord($element) == ord('9')){
          $element = "0";
        }

        $encryptet = $encryptet . $element;
      }
      return $encryptet;
    }
    
    function getMaskedEMailLinkText($plainText){
      $encryptet = "";
      $arr = str_split($plainText);
      
      foreach($arr as $value){   
        if(($value != "@") && ($value != ".")){
          $encryptet = $encryptet . $value;
        }else if ($value == "@"){
          $encryptet = $encryptet . "<img src=\"" . $GLOBALS["ATlink"] ."\" border=\"0\" alt=\"\">";
        }else if ($value == "."){
          $encryptet = $encryptet . "<img src=\"" . $GLOBALS["dotLink"] ."\" border=\"0\" alt=\"\">";
        }
      }
      return $encryptet;
    }
    
    function getEncryptetEmailLink($plainText){
      return "<a href=\"javascript:linkTo_UnCryptMailto('" . getEncryptEmail("mailto:" . htmlentities($plainText)) . "');\">" . getMaskedEMailLinkText(htmlentities($plainText)) . "</a>";
    }
    
    function getEncryptetEmailLinkWithSeperatLinkText($plainText,$linkText){
      return "<a href=\"javascript:linkTo_UnCryptMailto('" . getEncryptEmail("mailto:" . htmlentities($plainText)) . "');\">" . htmlentities($linkText) . "</a>";
    }

?>
