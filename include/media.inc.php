<?php  

function getNumberOfMediaItemByKindQualifier($qualifier) {
  $db = getDB();
  $sql = " select count(*) as numberof                                                                          \n" .
         " from mediaitem                                                                                       \n" .
         " join mediaevent on (mediaevent.mediaeventId = mediaitem.mediaeventId)                                \n" .
         " join mediakind on (mediakind.mediakindId = mediaevent.mediakindid                                    \n" .
         "                    and mediakind.qualifier = '" . trim(mysql_real_escape_string($qualifier)) . "')   \n";
  $result = mysql_query($sql, $db);
  if ($myrow = mysql_fetch_assoc($result)){  
      return $myrow['numberof'];
  }
  return "";
}

function getMediaeventById($mediaeventid) {
  $db = getDB();
  $sql = "select * from mediaevent where mediaeventid = " . trim(mysql_real_escape_string($mediaeventid));
  $result = mysql_query($sql, $db);
  if ($myrow = mysql_fetch_assoc($result)){  
      return fillMediaEventObject($myrow);
  }
  return null;
}

function getMediaKindById($mediaKindId) {
  $db = getDB();
  $sql = "select * from mediakind where mediaKindid = " . trim(mysql_real_escape_string($mediaKindId));
  $result = mysql_query($sql, $db);
  if ($myrow = mysql_fetch_assoc($result)){  
      return fillMediaKindObject($myrow);
  }
  return null;
}

function getMediacathegoryById($mediacathegoryId) {
  $db = getDB();
  $sql = "select * from mediacathegory where mediacathegoryId = " . trim(mysql_real_escape_string($mediacathegoryId));
  $result = mysql_query($sql, $db);
  if ($myrow = mysql_fetch_assoc($result)){  
      return fillMediacathegoryObject($myrow);
  }
  return null;
}

function findAllMediaItemsByMediaEventId($mediaeventId) {
    $db = getDB();
    $sql = "SELECT * from mediaitem where mediaeventId = ". trim(mysql_real_escape_string($mediaeventId));
    $result = mysql_query($sql, $db);
    $ret = array();
    while ($myrow = mysql_fetch_assoc($result)){  
      $mediaItem = fillMediaItemObject($myrow);
      array_push($ret,$mediaItem);
    }
    return $ret;
}

function fillMediaEventObject($myrow) {
  $mediaEvent = new MediaEvent();
  $mediaEvent->setMediaeventId($myrow['mediaeventId']);
  $mediaEvent->setDirectoryEvent($myrow['directoryEvent']);
  $mediaEvent->setDirectoryMain($myrow['directoryMain']);
  $mediaEvent->setDirectoryThumbs($myrow['directoryThumbs']);
  $mediaEvent->setEventName($myrow['eventName']);
  $mediaEvent->setZipfile($myrow['zipfile']);
  $mediaEvent->setZipsize($myrow['zipsize']);
  $mediaEvent->setPrivate($myrow['private']);
  $mediaEvent->setYear($myrow['year']);
  $mediaEvent->setMediacathegoryId($myrow['mediacathegoryId']);
  $mediaEvent->setMediakindId($myrow['mediakindId']);
  $mediaEvent->setMediaItems(null);
  return $mediaEvent;       
}

function fillMediaItemObject($myrow) {
  $mediaItem = new MediaItem();
  $mediaItem->setMediaItemId($myrow['mediaItemId']);
  $mediaItem->setFilename($myrow['filename']);
  $mediaItem->setComment($myrow['comment']);
  $mediaItem->setMainwidth($myrow['mainwidth']);
  $mediaItem->setMainheight($myrow['mainheight']);
  $mediaItem->setThumbwidth($myrow['thumbwidth']);
  $mediaItem->setThumbheight($myrow['thumbheight']);
  $mediaItem->setDuration($myrow['duration']);
  $mediaItem->setBytes($myrow['bytes']);
  $mediaItem->setMediaeventId($myrow['mediaeventId']);
  return $mediaItem;       
}

function fillMediaKindObject($myrow) {
  $mediaKind = new MediaKind();
  $mediaKind->setMediakindId($myrow['mediakindId']);
  $mediaKind->setDirectory($myrow['directory']);
  $mediaKind->setQualifier($myrow['qualifier']);
  $mediaKind->setDescription($myrow['description']);
  return $mediaKind;       
}

function fillMediacathegoryObject($myrow) {
  $mediacathegory = new Mediacathegory();
  $mediacathegory->setMediacathegoryId($myrow['mediacathegoryId']);
  $mediacathegory->setDirectory($myrow['directory']);
  $mediacathegory->setQualifier($myrow['Qualifier']);
  $mediacathegory->setDescription($myrow['description']);
  $mediacathegory->setOrderval($myrow['orderval']);
  return $mediacathegory;       
}

class Mediakind {
	var $mediakindId;
	var $directory;
  var $qualifier;
	var $description;
  
  function Mediakind() {
  }
  
  function getMediakindId(){
      return $this->mediakindId;
    }
    
  function setMediakindId($mediakindId){
    $this->mediakindId = $mediakindId;
  }
  
  function getDirectory(){
      return $this->directory;
    }
    
  function setDirectory($directory){
    $this->directory = $directory;
  }
  
  function getQualifier(){
      return $this->qualifier;
    }
    
  function setQualifier($qualifier){
    $this->qualifier = $qualifier;
  }
  
  function getDescription(){
      return $this->description;
    }
    
  function setDescription($description){
    $this->description = $description;
  }
}



class Mediacathegory {
  var $mediacathegoryId;
  var $directory;
  var $qualifier;
  var $description;
  var $orderval;
  
  function Mediacathegory() {
  }
  
  function getMediacathegoryId(){
      return $this->mediacathegoryId;
    }
    
  function setMediacathegoryId($mediacathegoryId){
    $this->mediacathegoryId = $mediacathegoryId;
  }
  
  function getDirectory(){
      return $this->directory;
    }
    
  function setDirectory($directory){
    $this->directory = $directory;
  }
  
  function getQualifier(){
      return $this->qualifier;
    }
    
  function setQualifier($qualifier){
    $this->qualifier = $qualifier;
  }
  
  function getDescription(){
      return $this->description;
    }
    
  function setDescription($description){
    $this->description = $description;
  }
  
  function getOrderval(){
      return $this->orderval;
    }
    
  function setOrderval($orderval){
    $this->orderval = $orderval;
  }
}

 class MediaEvent {
  var $mediaeventId;
  var $directoryEvent;
  var $directoryMain;
  var $directoryThumbs;
  var $eventName;
  var $zipfile;
  var $zipsize;
  var $private;
  var $year;
  var $mediacathegoryId;
  var $mediakindId;
  var $mediaItems;
 
  function MediaEvent() {
  
  }
  
  function getMediaeventId(){
      return $this->mediaeventId;
    }
    
  function setMediaeventId($mediaeventId){
    $this->mediaeventId = $mediaeventId;
  }
  
  function getDirectoryEvent(){
      return $this->directoryEvent;
    }
    
  function setDirectoryEvent($directoryEvent){
    $this->directoryEvent = $directoryEvent;
  }
  
  function getDirectoryMain(){
      return $this->directoryMain;
    }
    
  function setDirectoryMain($directoryMain){
    $this->directoryMain = $directoryMain;
  }
  
  function getDirectoryThumbs(){
      return $this->directoryThumbs;
    }
    
  function setDirectoryThumbs($directoryThumbs){
    $this->directoryThumbs = $directoryThumbs;
  }
  
  function getEventName(){
      return $this->eventName;
    }
    
  function setEventName($eventName){
    $this->eventName = $eventName;
  }
  
  function getZipfile(){
      return $this->zipfile;
    }
    
  function setZipfile($zipfile){
    $this->zipfile = $zipfile;
  }
  
  function getZipsize(){
      return $this->zipsize;
    }
    
  function setZipsize($zipsize){
    $this->zipsize = $zipsize;
  }
  
  function isPrivate(){
      return $this->private;
    }
    
  function setPrivate($private){
    $this->private = $private;
  }
  
  function getYear(){
      return $this->year;
    }
    
  function setYear($year){
    $this->year = $year;
  }
  
  function getMediacathegoryId(){
      return $this->mediacathegoryId;
    }
    
  function setMediacathegoryId($mediacathegoryId){
    $this->mediacathegoryId = $mediacathegoryId;
  }
  
  function getMediakindId(){
      return $this->mediakindId;
    }
    
  function setMediakindId($mediakindId){
    $this->mediakindId = $mediakindId;
  }
  
  function getMediaItems(){
      if ($this->mediaItems == null) {
        $this->mediaItems = findAllMediaItemsByMediaEventId($this->mediaeventId);
      }
      return $this->mediaItems;
    }
    
  function setMediaItems($mediaItems){
    $this->mediaItems = $mediaItems;
  }
 }

 class MediaItem {
    var $mediaItemId;
    var $filename;
    var $comment;
    var $mainwidth;
    var $mainheight;
    var $thumbwidth;
    var $thumbheight;
    var $duration;
    var $bytes;
    var $mediaeventId;
    
    function MediaItem() {
    
    }
    
    function getMediaItemId(){
      return $this->mediaItemId;
    }
    
    function setMediaItemId($mediaItemId){
      $this->mediaItemId = $mediaItemId;
    }
    
    function getFilename(){
      return $this->filename;
    }
    
    function setFilename($filename){
      $this->filename = $filename;
    }
    
    function getComment(){
      return $this->comment;
    }
    
    function setComment($comment){
      $this->comment = $comment;
    }
    
    function getMainwidth(){
      return $this->mainwidth;
    }
    
    function setMainwidth($mainwidth){
      $this->mainwidth = $mainwidth;
    }
    
    function getMainheight(){
      return $this->mainheight;
    }
    
    function setMainheight($mainheight){
      $this->mainheight = $mainheight;
    }
    
    function getThumbwidth(){
      return $this->thumbwidth;
    }
    
    function setThumbwidth($thumbwidth){
      $this->thumbwidth = $thumbwidth;
    }
    
    function getThumbheight(){
      return $this->thumbheight;
    }
    
    function setThumbheight($thumbheight){
      $this->thumbheight = $thumbheight;
    }
    
    function getDuration(){
      return $this->duration;
    }
    
    function setDuration($duration){
      $this->duration = $duration;
    }
    
    function getBytes(){
      return $this->bytes;
    }
    
    function setBytes($bytes){
      $this->bytes = $bytes;
    }
    
    function getMediaeventId(){
      return $this->mediaeventId;
    }
    
    function setMediaeventId($mediaeventId){
      $this->mediaeventId = $mediaeventId;
    }
 }
?>
