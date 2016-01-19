<?php

function getVideoByID($videoid) {
    $db = getDB();
    $result = mysql_query("select * from video where videoid = " . mysql_real_escape_string($videoid), $db);
    if ($myrow = mysql_fetch_assoc($result)) {
        return fillVideoObject($myrow);
    }
}

function getAllVideosSorted() {
    $db = getDB();
    $result = mysql_query("select * from video order by year desc, datum", $db);
    $ret = array();
    while ($myrow = mysql_fetch_assoc($result)) {
        array_push($ret, fillVideoObject($myrow));
    }
    return $ret;
}

function storeVideo($url, $embeded, $headline, $year) {
    $db = getDB();
    $sql = "INSERT INTO video (url,embeded,headline,year,datum,userid) VALUES ('" . mysql_real_escape_string($url) . "','" . $embeded . "','" . mysql_real_escape_string($headline) . "'," . $year . ",now()," . getCurrentUserId() . ")";
    $result = mysql_query($sql);
    if (mysql_affected_rows() != 1 || $errorno != 0) {
        echo "<center class=\"error\">Eintrag fehlgeschlagen</center>";
    } else {
        echo "<center class=\"successful\">Eintrag erfolgreich</center>";
    }
}

function fillVideoObject($myrow) {
    $VideoData = new Video($myrow['videoID'], $myrow['url'], $myrow['embeded'], $myrow['headline'], $myrow['year'], $myrow['datum'], $myrow['userID']);
    return $VideoData;
}

function deleteVideo($videoid) {
    $result = mysql_query("DELETE FROM video where videoid = " . $videoid);
    if (mysql_affected_rows() != 1 || $errorno != 0) {
        echo "<center class=\"error\">Video l&öuml;schen fehlgeschlagen</center>";
    } else {
        echo "<center class=\"successful\">Video l&öuml;schen erfolgreich</center>";
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