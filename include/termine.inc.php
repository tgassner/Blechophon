<?php

    class TerminBean {

      public static function getTerminById($terminid) {
          $sql = "select * from termin where terminid = :terminid";

          try {
              $db = getDBPDO();
              $stmt = $db->prepare($sql);
              $stmt->bindParam("terminid", $terminid, PDO::PARAM_STR);
              $stmt->execute();
              $termine = array();
              if ($myrow = $stmt->fetch(PDO::FETCH_ASSOC)) {
                  $termine[] = TerminBean::fillTerminPDO($myrow);
                  $db = null;
                  return $termine;
              }
              $db = null;
              return null;
          } catch (PDOException $e) {
              print_r($e);
              return array();
          }
      }
    
      public static function getTermine($new, $count, $terminq, $asc = "ASC") {
        if ($count > 0) {
          if ($new) {
              $sql = "select * from termin where bisdateprimary >= CURDATE() and terminq = :terminq order by vondateprimary " . $asc . ", vontimeprimary " . $asc . " limit 0, :limit";
          } else {
              $sql = "select * from termin where terminq = :terminq order by vondateprimary " . $asc . ", vontimeprimary " . $asc . " limit 0, :limit";
          }
        } else {
          if ($new) {
              $sql = "select * from termin where bisdateprimary >= CURDATE() and terminq = :terminq order by vondateprimary " . $asc . ", vontimeprimary " . $asc;
          } else {
              $sql = "select * from termin where terminq = :terminq order by vondateprimary " . $asc . ", vontimeprimary " . $asc;
          }
        }

        try {
          $db = getDBPDO();
          $stmt = $db->prepare($sql);
          if ($count > 0) {
              $stmt->bindParam("limit", $count, PDO::PARAM_INT);
          }
          $stmt->bindParam("terminq", $terminq, PDO::PARAM_STR);
          $stmt->execute();
          $termine = array();
          while ($myrow = $stmt->fetch(PDO::FETCH_ASSOC)) {
              $termine[] = TerminBean::fillTerminPDO($myrow);
          }
          $db = null;
          return $termine;
        } catch (PDOException $e) {
          print_r($e);
          return array();
        }

      }

      private static function fillTerminPDO($terminPDO, Termin $termin = null) {
        if (!isset($termin)) {
          $termin = new Termin();
        }
        $termin->setTerminid($terminPDO['terminid']);
        $termin->setVondateprimary($terminPDO['vondateprimary']);
        $termin->setBisdateprimary($terminPDO['bisdateprimary']);
        $termin->setVontimeprimary($terminPDO['vontimeprimary']);
        $termin->setBisdateprimary($terminPDO['bistimeprimary']);
        $termin->setWhat($terminPDO['what']);
        $termin->setWhere($terminPDO['where']);
        $termin->setInfos($terminPDO['infos']);
        $termin->setTerminq($terminPDO['terminq']);
        return $termin;
      }
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

    public function getFormatedTimeVon() {
      if ($this->vontimeprimary == "00:00:00") {
        return;
      }

      return substr($this->vontimeprimary, 0, 5);
   }
}
?>
