<?php  

class MediaBean {

  const MEDIAKIND_QUALIFIER_FOTOS = "FOTOS";
  const MEDIAKIND_QUALIFIER_VIDEOS = "VIDEOS";
  const MEDIAKIND_QUALIFIER_AUDIO = "AUDIO";

  public static function getEventsByMediaKindQualifier($mediaKindQualifier) {
    $sql = " select mediaevent.*                                                                                               \n" .
            " from mediaevent                                                                                                  \n" .
            " join mediacathegory on (mediaevent.mediacathegoryId = mediacathegory.mediacathegoryId)                           \n" .
            " join mediakind on (mediakind.mediakindId = mediaevent.mediakindId and mediakind.qualifier = :mediaKindQualifier) \n" .
            " order by mediacathegory.orderval, year desc, mediaevent.mediaeventId desc                                        \n";

    try {
        $db = getDBPDO();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("mediaKindQualifier", $mediaKindQualifier, PDO::PARAM_STR);
        $stmt->execute();
        $mediaevents = array();
        while ($myrow = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $mediaevents[] = MediaBean::fillMediaEventObject($myrow);
        }
        $db = null;
        return $mediaevents;
    } catch (PDOException $e) {
        LoggingBean::dologging(LoggingBean::ERROR, "mediaevent", $e->getMessage(), $e);
    }
  }

  public static function getMediacategoryById($mediacategoryId) {
    $sql = "select * from mediacathegory where mediacathegoryId = :mediacathegoryId";
    try {
        $db = getDBPDO();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("mediacathegoryId", $mediacategoryId, PDO::PARAM_INT);
        $stmt->execute();
        $ret = null;
        $myrow = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($myrow) {
            $ret = MediaBean::fillMediacategoryObject($myrow);
        }
        $db = null;
        return $ret;
    } catch (PDOException $e) {
        LoggingBean::dologging(LoggingBean::ERROR, "mediacategory", $e->getMessage(), $e);
    }
  }

  public static function getMediaeventById($mediaeventid) {
    $sql = "select * from mediaevent where mediaeventid = :mediaeventid";
    try {
        $db = getDBPDO();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("mediaeventid", $mediaeventid, PDO::PARAM_INT);
        $stmt->execute();
        $ret = null;
        $myrow = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($myrow) {
            $ret = MediaBean::fillMediaEventObject($myrow);
        }
        $db = null;
        return $ret;
    } catch (PDOException $e) {
        LoggingBean::dologging(LoggingBean::ERROR, "mediaevent", $e->getMessage(), $e);
    }
  }

  public static function getMediaKindById($mediaKindId) {
    $sql = "select *                          \n" .
            "from mediakind                   \n" .
            "where mediaKindid = :mediaKindid \n";
    try {
        $db = getDBPDO();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("mediaKindid", $mediaKindId, PDO::PARAM_INT);
        $stmt->execute();
        $ret = null;
        $myrow = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($myrow) {
            $ret = MediaBean::fillMediaKindObject($myrow);
        }
        $db = null;
        return $ret;
    } catch (PDOException $e) {
        LoggingBean::dologging(LoggingBean::ERROR, "mediakind", $e->getMessage(), $e);
    }
  }

  public static function findAllMediaItemsByMediaEventId($mediaeventId) {
    $sql = "SELECT *                            \n" .
            "from mediaitem                     \n" .
            "where mediaeventId = :mediaeventId \n" .
            "order by filename                  \n";

    try {
        $db = getDBPDO();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("mediaeventId", $mediaeventId, PDO::PARAM_INT);
        $stmt->execute();
        $mediaitems = array();
        while ($myrow = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $mediaitems[] = MediaBean::fillMediaItemObject($myrow);
        }
        $db = null;
        return $mediaitems;
    } catch (PDOException $e) {
        print_r($e);
    }
  }

  static function fillMediaEventObject($myrow) {
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
    $mediaEvent->setMediacategoryId($myrow['mediacathegoryId']);
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

  static function fillMediaKindObject($myrow) {
    $mediaKind = new MediaKind();
    $mediaKind->setMediakindId($myrow['mediakindId']);
    $mediaKind->setDirectory($myrow['directory']);
    $mediaKind->setQualifier($myrow['qualifier']);
    $mediaKind->setDescription($myrow['description']);
    return $mediaKind;       
  }

  static function fillMediacategoryObject($myrow) {
    $mediacategory = new Mediacategory();
    $mediacategory->setMediacategoryId($myrow['mediacathegoryId']);
    $mediacategory->setDirectory($myrow['directory']);
    $mediacategory->setQualifier($myrow['Qualifier']);
    $mediacategory->setDescription($myrow['description']);
    $mediacategory->setOrderval($myrow['orderval']);
    return $mediacategory;       
  }
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



class Mediacategory {
  var $mediacathegoryId;
  var $directory;
  var $qualifier;
  var $description;
  var $orderval;
  
  function Mediacategory() {
  }
  
  function getMediacategoryId(){
      return $this->mediacategoryId;
    }
    
  function setMediacategoryId($mediacategoryId){
    $this->mediacategoryId = $mediacategoryId;
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
  var $mediacategoryId;
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
  
  function getMediacategoryId(){
      return $this->mediacategoryId;
    }
    
  function setMediacategoryId($mediacategoryId){
    $this->mediacategoryId = $mediacategoryId;
  }
  
  function getMediakindId(){
      return $this->mediakindId;
    }
    
  function setMediakindId($mediakindId){
    $this->mediakindId = $mediakindId;
  }
  
  function getMediaItems(){
      if ($this->mediaItems == null) {
        $this->mediaItems = MediaBean::findAllMediaItemsByMediaEventId($this->mediaeventId);
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
