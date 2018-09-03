<?php

    class VideoBean {
        function getVideoByID($videoid) {
            $sql = "select * from video where videoid = :videoid";
            try {
                $db = getDBPDO();
                $stmt = $db->prepare($sql);
                $stmt->bindParam("videoid", $videoid, PDO::PARAM_INT);
                $stmt->execute();
                $ret = null;
                $myrow = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($myrow) {
                    $ret = VideoBean::fillVideoObject($myrow);
                }
                $db = null;
                return $ret;
            } catch (PDOException $e) {
                print_r($e);
            }
        }

        public static function getAllVideosSorted() {
            $sql = "select * from video order by year desc, datum";
            try {
                $db = getDBPDO();
                $stmt = $db->prepare($sql);
                $stmt->execute();
                $videos = array();
                while ($myrow = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $videos[] = VideoBean::fillVideoObject($myrow);
                }
                $db = null;
                return $videos;
            } catch (PDOException $e) {
                print_r($e);
            }
        }

        public static function fillVideoObject($myrow) {
            $VideoData = new Video($myrow['videoID'], $myrow['url'], $myrow['embeded'], $myrow['headline'], $myrow['year'], $myrow['datum'], $myrow['userID']);
            return $VideoData;
        }
    }

class Video {

    var $videoid;
    var $url;
    var $embeded;
    var $headline;
    var $year;
    var $datum;
    var $userid;

    function Video($videoid, $url, $embeded, $headline, $year, $datum, $userid) {
        $this->videoid = $videoid;
        $this->url = $url;
        $this->embeded = $embeded;
        $this->headline = $headline;
        $this->year = $year;
        $this->datum = $datum;
        $this->userid = $userid;
    }

    function getvideoid() {
        return $this->videoid;
    }

    function setvideoid($videoid) {
        $this->videoid = $videoid;
    }

    function geturl() {
        return $this->url;
    }

    function seturl($url) {
        $this->url = $url;
    }

    function getembeded() {
        return $this->embeded;
    }

    function setembeded($embeded) {
        $this->embeded = $embeded;
    }

    function getheadline() {
        return $this->headline;
    }

    function setheadline($headline) {
        $this->headline = $headline;
    }

    function getyear() {
        return $this->year;
    }

    function setyear($year) {
        $this->year = $year;
    }

    function getdatum() {
        return $this->datum;
    }

    function setdatum($datum) {
        $this->datum = $datum;
    }

    function getuserid() {
        return $this->userid;
    }

    function setuserid($userid) {
        $this->userid = $userid;
    }

}

?>