<?php

    function getTerminById($terminid) {
          $sql = "select * from termin where terminid = :terminid";

          try {
              $db = getDBPDO();
              $stmt = $db->prepare($sql);
              $stmt->bindParam("terminid", $terminid, PDO::PARAM_STR);
              $stmt->execute();
              if ($myrow = $stmt->fetch(PDO::FETCH_ASSOC)) {
                  $termin = fillTerminPDO($myrow);
                  $db = null;
                  return $termin;
              }
              $db = null;
              return null;
          } catch (PDOException $e) {
              print_r($e);
              return array();
          }
      }

  function getTermine($new, $count, $terminq, $asc = "ASC") {
        $termine = array();
        if ($count > 0) {
            if ($new) {
                $sql = "select * from termin where bisdateprimary >= CURDATE() and terminq = :qualifier order by vondateprimary " . $asc . ", vontimeprimary " . $asc . " limit 0, :limit";
            } else {
                $sql = "select * from termie where qualifier = :qualifier order by vondateprimary " . $asc . ", vontimeprimary " . $asc . " limit 0, :limit";
            }
        } else {
            if ($new) {
                $sql = "select * from termin where bisdateprimary >= CURDATE() and terminq = :terminq order by vondateprimary " . $asc . ", vontimeprimary " . $asc;
            } else {
                $sql = "select * from termin where qualifier = :qualifier order by vondateprimary " . $asc . ", vontimeprimary " . $asc;
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
            while ($myrow = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $termine[] = fillTerminPDO($myrow);
            }
            $db = null;
            return $termine;
        } catch (PDOException $e) {
            return array();
        }
    }

    function fillTerminPDO($terminPDO, Termin $termin = null) {
        if (!isset($termin)) {
            $termin = new Termin();
        }
        $termin->setTerminid($terminPDO['terminid']);
        $termin->setVondateprimary($terminPDO['vondateprimary']);
        $termin->setBisdateprimary($terminPDO['bisdateprimary']);
        $termin->setVontimeprimary($terminPDO['vontimeprimary']);
        $termin->setBistimeprimary($terminPDO['bistimeprimary']);
        $termin->setWhat($terminPDO['what']);
        $termin->setInfos($terminPDO['infos']);
        $termin->setWhere($terminPDO['where']);
        $termin->setTerminq($terminPDO['terminq']);
        return $termin;
    }

    function parseTerminFromRequest(Termin $termin = null) {
        if (!isset($termin)) {
            $termin = new Termin();
        }

        if (isset($_POST["terminid"])) {
            $termin->setTerminid($_POST["terminid"]);
        }

        if (isset($_POST["DatumVonPrimary"])) {
            $termin->setVondateprimary($_POST["DatumVonPrimary"]);
        }

        if (isset($_POST["DatumBisPrimary"])) {
            $termin->setBisdateprimary($_POST["DatumBisPrimary"]);
        }

        if (isset($_POST["ZeitVonPrimary"])) {
            $termin->setVontimeprimary($_POST["ZeitVonPrimary"]);
        }

        if (isset($_POST["ZeitBisPrimary"])) {
            $termin->setBistimeprimary($_POST["ZeitBisPrimary"]);
        }

        if (isset($_POST["Was"])) {
            $termin->setWhat($_POST["Was"]);
        }

        if (isset($_POST["Wo"])) {
            $termin->setWhere($_POST["Wo"]);
        }

        if (isset($_POST["Infos"])) {
            $termin->setInfos($_POST["Infos"]);
        }

        if (isset($_POST["terminq"])) {
            $termin->setTerminq($_POST["terminq"]);
        }

        return $termin;
    }

   class Termin {
    var $terminid;
    var $terminq;
    var $vondateprimary;
    var $bisdateprimary;
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
}
?>
