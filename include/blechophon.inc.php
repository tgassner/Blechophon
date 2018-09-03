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


?>
