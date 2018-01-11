<?php

    function getTerminById($terminid) {
      $db = getDB();
      $result = mysql_query("select * from termin where terminid = " . mysql_real_escape_string($terminid), $db);
      if ($myrow = mysql_fetch_assoc($result)){
		    return new Termin($myrow['terminid'], $myrow['terminq'], $myrow['vondateprimary'], $myrow['bisdateprimary'], $myrow['vontimeprimary'], $myrow['bistimeprimary'], $myrow['what'],$myrow['where'],$myrow['infos']);
		  }
    }
	
    function getTermine($new, $count, $terminq) {
      $db = getDB();
			$termine = array();
      if ($new) {
        $sql = "select * from termin where vondateprimary >= date(now()) and terminq = '" . mysql_real_escape_string($terminq) . "' order by vondateprimary";
      } else {
        $sql = "select * from termin where terminid = '" . mysql_real_escape_string($terminq) . "' order by datumvonprimary";
      }
      $result = mysql_query($sql,$db);
      $countlocal = 0;
      //echo($sql);
      while ($myrow = mysql_fetch_assoc($result)){
        $termin = new Termin($myrow['terminid'], $myrow['terminq'], $myrow['vondateprimary'], $myrow['bisdateprimary'], $myrow['vontimeprimary'], $myrow['bistimeprimary'], $myrow['what'],$myrow['where'],$myrow['infos']);
        array_push($termine, $termin);
        $countlocal++;
        if ($count > 0 && $countlocal >= $count) {
          break;
        }
      }
      mysql_free_result($result);
      return $termine;
     }

   class Termin {
    var $terminid;
    var $terminq;
    var $vondateprimary;
    var $datumbisprimary;
    var $vontimeprimary;
    var $bistimeprimary;
    var $what;
    var $where;
    var $infos;

    function Termin($terminid, $terminq, $vondateprimary, $bisdateprimary, $vontimeprimary, $bistimeprimary, $what, $where, $infos) {
      $this->terminid = $terminid;
      $this->terminq = $terminq;
      $this->vondateprimary = $vondateprimary;
      $this->bisdateprimary = $bisdateprimary;
      $this->vontimeprimary = $vontimeprimary;
      $this->bistimeprimary = $bistimeprimary;
      $this->what = $what;
      $this->where = $where;
      $this->infos = $infos;
    }

    function getTerminid() {
      return $this->terminid;
    }

    function setTerminid($terminid){
      $this->terminid = $terminid;
    }

    function getTerminq() {
      return $this->terminq;
    }

    function setTerminq($terminq){
      $this->terminq = $terminq;
    }

    function getVondateprimary() {
      return $this->vondateprimary;
    }

    function setVondateprimary($vondateprimary){
      $this->vondateprimary = $vondateprimary;
    }

    function getBisdateprimary() {
      return $this->bisdateprimary;
    }

    function setBisdateprimary($bisdateprimary){
      $this->bisdateprimary = $bisdateprimary;
    }

    function getVontimeprimary() {
      return $this->vontimeprimary;
    }

    function setVontimeprimary($vontimeprimary){
      $this->vontimeprimary = $vontimeprimary;
    }

    function getBistimeprimary() {
      return $this->bistimeprimary;
    }

    function setBistimeprimary($bistimeprimary){
      $this->bistimeprimary = $bistimeprimary;
    }

    function getWhat() {
      return $this->what;
    }

    function setWhat($what){
      $this->what = $what;
    }

    function getWhere() {
      return $this->where;
    }

    function setWhere($where){
      $this->where = $where;
    }

    function getInfos() {
      return $this->infos;
    }

    function setInfos($infos){
      $this->infos = $infos;
    }
    
    function getFormatedVonDatePrimary() {
      return substr($this->vondateprimary,8,2).".".substr($this->vondateprimary,5,2).".".substr($this->vondateprimary,0,4);
    }
}
?>
