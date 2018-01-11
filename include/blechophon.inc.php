<?php

include('include/termine.inc.php');
include('include/media.inc.php');
include('include/user.inc.php');
include('include/video.inc.php');
include('include/password.inc.php');

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

//function getDB() {
//    $db = mysql_connect("localhost","blechophon", getPassword());
//    mysql_select_db("blechophon",$db);
//   return $db;
//}

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
      
      $db = getDB();

      $result = mysql_query("select userid from user where (username = '" . mysql_real_escape_string($username) . "'  or  email = '" . mysql_real_escape_string($username) . "' )and password = '" . $passwort . "' and canlogin = 1",$db);
      if (!$result) {
          echo 'Abfrage konnte nicht ausgeführt werden: ' . mysql_error();
      }
      $myrow = mysql_fetch_row($result);
      mysql_free_result($result);
      if (!($myrow)){
        $state = "loginerror";
        echo("<div style=\"error\">login fehlgeschlagen</div>");
      }else{	      	
        $state = "loginok";
        $userid = $myrow[0];
        
        $user = getFullUserByID($userid);
        
        $_SESSION['login'] = "true";
        $_SESSION['username'] = $user->getUsername();
        $_SESSION['userid'] = $userid;

        $result = mysql_query("select sektion from rechte r, rechteuser ru where r.rechteid = ru.rechteid and userid = '" . $myrow[0] . "'",$db);
        $sektionenlist = array();
        
        while ($myrow = mysql_fetch_row($result)){
          $sektionenlist[] = $myrow[0];
        } //while für sektionen
        mysql_free_result($result);
        $_SESSION['sektionen'] = $sektionenlist;
        
        $logsql = "insert into log (userid, ip, httplang, useragent, logdatetime) values(" . $_SESSION['userid'] . ", '" . $_SERVER['REMOTE_ADDR'] . "', '" . $_SERVER['HTTP_ACCEPT_LANGUAGE'] . "', '" . $_SERVER['HTTP_USER_AGENT'] . "', now() )";
        //echo($logsql);
        $result = mysql_query($logsql);
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
