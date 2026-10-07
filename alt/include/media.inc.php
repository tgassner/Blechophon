<?php

class MediaBean {

    const MEDIAKIND_QUALIFIER_FOTOS = "FOTOS";
    const MEDIAKIND_QUALIFIER_VIDEOS = "VIDEOS";
    const MEDIAKIND_QUALIFIER_AUDIO = "AUDIO";


    public static function storeMediaEvent(MediaEvent $mediaEvent)
    {
        $sqlInsert = " INSERT INTO mediaevent (directoryEvent, directoryMain, directoryThumbs, eventName, year, mediacathegoryId, mediakindId) \n" .
                     " VALUES (:directoryEvent, :directoryMain, :directoryThumbs, :eventName, :year, :mediacathegoryId, :mediakindId) ";

        $sqlUpdate = "UPDATE mediaevent SET      \n" .
            "directoryEvent = :directoryEvent,     \n" .
            "directoryMain = :directoryMain,       \n" .
            "directoryThumbs = :directoryThumbs,   \n" .
            "eventName = :eventName,               \n" .
            "year = :year,                         \n" .
            "mediacathegoryId= :mediacathegoryId,  \n" .
            "mediakindId = :mediakindId            \n" .
            "WHERE mediaeventId = :mediaeventId    \n";

        //try {
            $db = getDBPDO();

            if ($mediaEvent->getMediaeventId() > 0) {
                $stmt = $db->prepare($sqlUpdate);
                $stmt->bindValue("mediaeventId", $mediaEvent->getMediaeventId(), PDO::PARAM_INT);
            } else {
                $stmt = $db->prepare($sqlInsert);
            }

            $stmt->bindValue("directoryEvent", $mediaEvent->getDirectoryEvent(), PDO::PARAM_STR);
            $stmt->bindValue("directoryMain", $mediaEvent->getDirectoryMain(), PDO::PARAM_STR);
            $stmt->bindValue("directoryThumbs", $mediaEvent->getDirectoryThumbs(), PDO::PARAM_STR);
            $stmt->bindValue("eventName", $mediaEvent->getEventName(), PDO::PARAM_STR);
            $stmt->bindValue("year", $mediaEvent->getYear(), PDO::PARAM_STR);
            $stmt->bindValue("mediacathegoryId", $mediaEvent->getMediacategoryId(), PDO::PARAM_INT);
            $stmt->bindValue("mediakindId", $mediaEvent->getMediakindId(), PDO::PARAM_INT);

            $stmt->execute();

            if ($mediaEvent->getMediaeventId() <= 0) {
                $mediaEvent->setMediaeventId($db->lastInsertId());
            }
            $db = null;

        //    return true;
        //} catch (PDOException $e) {
        //    //LoggingBean::dologging(LoggingBean::ERROR, "mediaevent", $e->getMessage(), $e);
        //    return false;
        //}
    }

    public static function storeMediaItem(MediaItem $mediaItem)
    {
        $sqlInsert = "INSERT INTO mediaitem (filename, comment, mainwidth, mainheight, thumbwidth, thumbheight, duration, bytes, mediaeventId) \n".
            " VALUES (:filename, :comment, :mainwidth, :mainheight, :thumbwidth, :thumbheight, :duration, :bytes, :mediaeventId) ";


        $sqlUpdate = "UPDATE mediaitem SET             \n" .
                     "    filename = :filename,        \n" .
                     "    comment = :comment,          \n" .
                     "    mainwidth = :mainwidth,      \n" .
                     "    mainheight = :mainheight,    \n" .
                     "    thumbwidth = :thumbwidth,    \n" .
                     "    thumbheight = :thumbheight,  \n" .
                     "    duration = :duration,        \n" .
                     "    bytes = :bytes,              \n" .
                     "    mediaeventId = :mediaeventId \n" .
                     "WHERE                            \n" .
                     "    mediaItemId = :mediaItemId   \n";

        //try {
        $db = getDBPDO();

        if ($mediaItem->getMediaItemId() > 0) {
            $stmt = $db->prepare($sqlUpdate);
            $stmt->bindValue("mediaItemId", $mediaItem->getMediaItemId(), PDO::PARAM_INT);
        } else {
            $stmt = $db->prepare($sqlInsert);
        }

        $stmt->bindValue("filename", $mediaItem->getFilename(), PDO::PARAM_STR);
        $stmt->bindValue("comment", $mediaItem->getComment(), PDO::PARAM_STR);
        $stmt->bindValue("mainwidth", $mediaItem->getMainwidth(), PDO::PARAM_INT);
        $stmt->bindValue("mainheight", $mediaItem->getmainheight(), PDO::PARAM_INT);
        $stmt->bindValue("thumbwidth", $mediaItem->getthumbwidth(), PDO::PARAM_INT);
        $stmt->bindValue("thumbheight", $mediaItem->getthumbheight(), PDO::PARAM_INT);
        $stmt->bindValue("duration", $mediaItem->getduration(), PDO::PARAM_INT);
        $stmt->bindValue("bytes", $mediaItem->getbytes(), PDO::PARAM_INT);
        $stmt->bindValue("mediaeventId", $mediaItem->getmediaeventId(), PDO::PARAM_INT);

        $stmt->execute();

        if ($mediaItem->getMediaItemId() <= 0) {
            $mediaItem->setMediaItemId($db->lastInsertId());
        }
        $db = null;

        //    return true;
        //} catch (PDOException $e) {
        //    //LoggingBean::dologging(LoggingBean::ERROR, "mediaevent", $e->getMessage(), $e);
        //    return false;
        //}
    }

    public static function getMediaCategoriesByMediaKindQualifier($qualifier) {
        $sql = " select distinct mc.*                                                                        \n" .
            " from mediacategory mc                                                                       \n" .
            " join mediaevent me on me.mediacategoryId = mc.mediacategoryId                               \n" .
            " join mediakind mk on mk.mediakindId = me.mediakindId and mk.qualifier = :qualifier          \n" .
            " order by orderval                                                                           \n";
            " order by orderval                                                                           \n";
        try {
            $db = getDBPDO();
            $stmt = $db->prepare($sql);
            $stmt->bindParam("qualifier", $qualifier, PDO::PARAM_STR);
            $stmt->execute();
            $mediacategories = array();
            while ($myrow = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $mediacategories[] = MediaBean::fillMediacategoryObject($myrow);
            }
            $db = null;
            return $mediacategories;
        } catch (PDOException $e) {
            LoggingBean::dologging(LoggingBean::ERROR, "media", $e->getMessage(), $e);
        }
    }


    public static function getEventsByMediaKindQualifier($mediaKindQualifier) {
        $sql = " select mediaevent.*                                                                                               \n" .
            " from mediaevent                                                                                                  \n" .
            " join mediacategory on (mediaevent.mediacategoryId = mediacategory.mediacategoryId)                               \n" .
            " join mediakind on (mediakind.mediakindId = mediaevent.mediakindId and mediakind.qualifier = :mediaKindQualifier) \n" .
            " order by mediacategory.orderval, year desc                                                                       \n";

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

    public static function getAllMediaEvents() {
        $sql = " select mediaevent.*                   \n" .
            " from mediaevent                       \n" .
            " order by year desc, mediaeventId desc \n";

        try {
            $db = getDBPDO();
            $stmt = $db->prepare($sql);
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

    public static function getRandomPhotoPath($categQualiyArr, $minYear) {
        $sql = " select mk.directory as mkdir, mc.directory as mcdir, me.year, me.directoryevent, directorymain, filename, me.eventname    \n" .
            " from mediaitem mi                                                                                                        \n" .
            " join mediaevent me on me.mediaeventId = mi.mediaeventId                                                                  \n" .
            " join mediacategory mc on mc.mediacategoryId = me.mediacategoryId                                                         \n" .
            " join mediakind mk on mk.mediakindId = me.mediakindId and mk.Qualifier = 'FOTOS'                                          \n" .
            " where mc.Qualifier in " . makeInclauseString($categQualiyArr) . "                                                        \n" .
            " AND me.year >= :minYear                                                                                                  \n" .
            " order by rand() limit 1                                                                                                  \n";
        try {
            $db = getDBPDO();
            $stmt = $db->prepare($sql);
            $stmt->bindParam("minYear", $minYear, PDO::PARAM_INT);
            $stmt->execute();
            $foto = array();
            if ($myrow = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $path = $myrow['mkdir'] . "/" . $myrow['mcdir'] . "/" . $myrow['year'] . "/" . $myrow['directoryevent'] . "/" . $myrow['directorymain'] . "/" . $myrow['filename'];
                $name = $myrow['eventname'];
                $foto['path'] = $path;
                $foto['name'] = $name;
            }
            $db = null;
            return $foto;
        } catch (PDOException $e) {
            LoggingBean::dologging(LoggingBean::ERROR, "mediaevent", $e->getMessage(), $e);
        }
    }

    public static function getNumberOfMediaItemByKindQualifier($qualifier) {

        $sql = " select count(*) as numberof                                            \n" .
            " from mediaitem                                                        \n" .
            " join mediaevent on (mediaevent.mediaeventId = mediaitem.mediaeventId) \n" .
            " join mediakind on (mediakind.mediakindId = mediaevent.mediakindid     \n" .
            "                    and mediakind.qualifier = :qualifier)              \n";
        try {
            $db = getDBPDO();
            $stmt = $db->prepare($sql);
            $stmt->bindParam("qualifier", $qualifier, PDO::PARAM_STR);
            $stmt->execute();
            $ret = "";
            $myrow = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($myrow) {
                $ret = $myrow['numberof'];
            }
            $db = null;
            return $ret;
        } catch (PDOException $e) {
            LoggingBean::dologging(LoggingBean::ERROR, "mediaitem*", $e->getMessage(), $e);
        }
    }

    public static function getNumberOfMediaItemsByMediaEventId($mediaEventId) {

        $sql = " SELECT COUNT(*) AS anz FROM mediaitem WHERE mediaeventId = :mediaEventId;";
        try {
            $db = getDBPDO();
            $stmt = $db->prepare($sql);
            $stmt->bindParam("mediaEventId", $mediaEventId, PDO::PARAM_INT);
            $stmt->execute();
            $ret = 0;
            $myrow = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($myrow) {
                $ret = $myrow['anz'];
            }
            $db = null;
            return $ret;
        } catch (PDOException $e) {
            LoggingBean::dologging(LoggingBean::ERROR, "mediaitem", $e->getMessage(), $e);
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

    public static function getAllMediaKinds() {
        $sql = " select * from mediakind order by mediakindId ";
        try {
            $db = getDBPDO();
            $stmt = $db->prepare($sql);
            $stmt->execute();
            $ret = array();
            while ($myrow = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $ret[] = MediaBean::fillMediaKindObject($myrow);
            }
            $db = null;
            return $ret;
        } catch (PDOException $e) {
            LoggingBean::dologging(LoggingBean::ERROR, "mediakind", $e->getMessage(), $e);
        }
    }

    public static function getMediacategoryById($mediacategoryId) {
        $sql = "select * from mediacategory where mediacategoryId = :mediacategoryId";
        try {
            $db = getDBPDO();
            $stmt = $db->prepare($sql);
            $stmt->bindParam("mediacategoryId", $mediacategoryId, PDO::PARAM_INT);
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

    public static function findAllMediaItemsByMediaEventId($mediaeventId, $photographer = null) {
        $sql = "SELECT *                                \n" .
            "from mediaitem                         \n" .
            "where mediaeventId = :mediaeventId     \n" .
            ($photographer == null ? "" : "and photographer = :photographer \n") .
            "order by filename \n";

        try {
            $db = getDBPDO();
            $stmt = $db->prepare($sql);
            $stmt->bindParam("mediaeventId", $mediaeventId, PDO::PARAM_INT);
            if ($photographer != null) {
                $stmt->bindParam("photographer", $photographer, PDO::PARAM_STR);
            }
            $stmt->execute();
            $mediaitems = array();
            while ($myrow = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $mediaitems[] = MediaBean::fillMediaItemObject($myrow);
            }
            $db = null;
            return $mediaitems;
        } catch (PDOException $e) {
            print_r($e);
            //LoggingBean::dologging(LoggingBean::ERROR, "mediaitem", $e->getMessage(), $e);
        }
    }

    public static function findAllPhotographersByMediaEventId($mediaeventId) {
        $sql = "SELECT photographer, COUNT(*) AS anzahl \n" .
            "FROM mediaitem                          \n" .
            "WHERE mediaeventid = :mediaeventId      \n" .
            "GROUP BY photographer                   \n" .
            "ORDER BY photographer                   \n";
        try {
            $db = getDBPDO();
            $stmt = $db->prepare($sql);
            $stmt->bindParam("mediaeventId", $mediaeventId, PDO::PARAM_INT);
            $stmt->execute();
            $photographers = array();
            while ($myrow = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $photographers[$myrow['photographer']] = $myrow['anzahl'];
            }
            $db = null;
            return $photographers;
        } catch (PDOException $e) {
            LoggingBean::dologging(LoggingBean::ERROR, "mediaitem", $e->getMessage(), $e);
        }
    }

    private static function fillMediaEventObject($myrow) {
        $mediaEvent = new MediaEvent();
        $mediaEvent->setMediaeventId($myrow['mediaeventId']);
        $mediaEvent->setDirectoryEvent($myrow['directoryEvent']);
        $mediaEvent->setDirectoryMain($myrow['directoryMain']);
        $mediaEvent->setDirectoryThumbs($myrow['directoryThumbs']);
        $mediaEvent->setEventName($myrow['eventName']);
        $mediaEvent->setYear($myrow['year']);
        $mediaEvent->setMediacategoryId($myrow['mediacathegoryId']);
        $mediaEvent->setMediakindId($myrow['mediakindId']);
        $mediaEvent->setMediaItems(null);
        return $mediaEvent;
    }

    private static function fillMediaItemObject($myrow) {
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
        //$mediaItem->setMediatype($myrow['mediatype']);
        //$mediaItem->setOriginalwidth($myrow['originalwidth']);
        //$mediaItem->setOriginalheight($myrow['originalheight']);
        //$mediaItem->setOriginalBytes($myrow['originalBytes']);
        return $mediaItem;
    }

    private static function fillMediaKindObject($myrow) {
        $mediaKind = new MediaKind();
        $mediaKind->setMediakindId($myrow['mediakindId']);
        $mediaKind->setDirectory($myrow['directory']);
        $mediaKind->setQualifier($myrow['qualifier']);
        $mediaKind->setDescription($myrow['description']);
        return $mediaKind;
    }

    private static function fillMediacategoryObject($myrow) {
        $mediacategory = new Mediacategory();
        $mediacategory->setMediacategoryId($myrow['mediacategoryId']);
        $mediacategory->setDirectory($myrow['directory']);
        $mediacategory->setQualifier($myrow['Qualifier']);
        $mediacategory->setDescription($myrow['description']);
        $mediacategory->setDescriptionLong($myrow['descriptionLong']);
        $mediacategory->setOrderval($myrow['orderval']);
        return $mediacategory;
    }

}

class Mediakind {

    var $mediakindId;
    var $directory;
    var $qualifier;
    var $description;

    function getMediakindId() {
        return $this->mediakindId;
    }

    function setMediakindId($mediakindId) {
        $this->mediakindId = $mediakindId;
    }

    function getDirectory() {
        return $this->directory;
    }

    function setDirectory($directory) {
        $this->directory = $directory;
    }

    function getQualifier() {
        return $this->qualifier;
    }

    function setQualifier($qualifier) {
        $this->qualifier = $qualifier;
    }

    function getDescription() {
        return $this->description;
    }

    function setDescription($description) {
        $this->description = $description;
    }

}

class Mediacategory {

    var $mediacategoryId;
    var $directory;
    var $qualifier;
    var $description;
    var $descriptionLong;
    var $orderval;

    function getMediacategoryId() {
        return $this->mediacategoryId;
    }

    function setMediacategoryId($mediacategoryId) {
        $this->mediacategoryId = $mediacategoryId;
    }

    function getDirectory() {
        return $this->directory;
    }

    function setDirectory($directory) {
        $this->directory = $directory;
    }

    function getQualifier() {
        return $this->qualifier;
    }

    function setQualifier($qualifier) {
        $this->qualifier = $qualifier;
    }

    function getDescription() {
        return $this->description;
    }

    function setDescription($description) {
        $this->description = $description;
    }

    public function getDescriptionLong() {
        return $this->descriptionLong;
    }

    public function setDescriptionLong($descriptionLong) {
        $this->descriptionLong = $descriptionLong;
    }

    function getOrderval() {
        return $this->orderval;
    }

    function setOrderval($orderval) {
        $this->orderval = $orderval;
    }

}

class MediaEvent {

    var $mediaeventId;
    var $directoryEvent;
    var $directoryMain;
    var $directoryThumbs;
    var $eventName;
    var $year;
    var $mediacategoryId;
    var $mediakindId;
    var $mediaItems;

    function getMediaeventId() {
        return $this->mediaeventId;
    }

    function setMediaeventId($mediaeventId) {
        $this->mediaeventId = $mediaeventId;
    }

    function getDirectoryEvent() {
        return $this->directoryEvent;
    }

    function setDirectoryEvent($directoryEvent) {
        $this->directoryEvent = $directoryEvent;
    }

    function getDirectoryMain() {
        return $this->directoryMain;
    }

    function setDirectoryMain($directoryMain) {
        $this->directoryMain = $directoryMain;
    }

    function getDirectoryThumbs() {
        return $this->directoryThumbs;
    }

    function setDirectoryThumbs($directoryThumbs) {
        $this->directoryThumbs = $directoryThumbs;
    }

    function getEventName() {
        return $this->eventName;
    }

    function setEventName($eventName) {
        $this->eventName = $eventName;
    }

    function getYear() {
        return $this->year;
    }

    function setYear($year) {
        $this->year = $year;
    }

    function getMediacategoryId() {
        return $this->mediacategoryId;
    }

    function setMediacategoryId($mediacategoryId) {
        $this->mediacategoryId = $mediacategoryId;
    }

    function getMediakindId() {
        return $this->mediakindId;
    }

    function setMediakindId($mediakindId) {
        $this->mediakindId = $mediakindId;
    }

    function getMediaItems() {
        if ($this->mediaItems == null) {
            $this->mediaItems = MediaBean::findAllMediaItemsByMediaEventId($this->mediaeventId);
        }
        return $this->mediaItems;
    }

    function setMediaItems($mediaItems) {
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
    var $mediatype;
    var $originalwidth;
    var $originalheight;
    var $originalBytes;

    function getMediaItemId() {
        return $this->mediaItemId;
    }

    function setMediaItemId($mediaItemId) {
        $this->mediaItemId = $mediaItemId;
    }

    function getFilename() {
        return $this->filename;
    }

    function setFilename($filename) {
        $this->filename = $filename;
    }

    function getComment() {
        return $this->comment;
    }

    function setComment($comment) {
        $this->comment = $comment;
    }

    function getMainwidth() {
        return $this->mainwidth;
    }

    function setMainwidth($mainwidth) {
        $this->mainwidth = $mainwidth;
    }

    function getMainheight() {
        return $this->mainheight;
    }

    function setMainheight($mainheight) {
        $this->mainheight = $mainheight;
    }

    function getThumbwidth() {
        return $this->thumbwidth;
    }

    function setThumbwidth($thumbwidth) {
        $this->thumbwidth = $thumbwidth;
    }

    function getThumbheight() {
        return $this->thumbheight;
    }

    function setThumbheight($thumbheight) {
        $this->thumbheight = $thumbheight;
    }

    function getDuration() {
        return $this->duration;
    }

    function setDuration($duration) {
        $this->duration = $duration;
    }

    function getBytes() {
        return $this->bytes;
    }

    function setBytes($bytes) {
        $this->bytes = $bytes;
    }

    function getMediaeventId() {
        return $this->mediaeventId;
    }

    function setMediaeventId($mediaeventId) {
        $this->mediaeventId = $mediaeventId;
    }

    function getMediatype() {
        return $this->mediatype;
    }

    function setMediatype($mediatype) {
        $this->mediatype = $mediatype;
    }

    function getOriginalwidth() {
        return $this->originalwidth;
    }

    function getOriginalheight() {
        return $this->originalheight;
    }

    function getOriginalBytes() {
        return $this->originalBytes;
    }

    function setOriginalwidth($originalwidth) {
        $this->originalwidth = $originalwidth;
    }

    function setOriginalheight($originalheight) {
        $this->originalheight = $originalheight;
    }

    function setOriginalBytes($originalBytes) {
        $this->originalBytes = $originalBytes;
    }

}
