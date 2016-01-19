<h1>Termine</h1><?php initTermine(); ?><?php termineList(); ?><br><script type='text/JavaScript' src='js/scw.js'></script><center> <?php  //include("calendarEnsemletermine.php"); ?></center><?phpfunction printAddFormular() {  if (hasCurrentUserRight("termine") && isset($_POST["aendern"])) {    $termin = getTerminById($_POST["terminid"], 1);    $aendern = true;  } else {    $aendern = false;  }?>    <form name="termineadd" method="post" action="<?php echo $_SERVER["PHP_SELF"] . "?" . $_SERVER['QUERY_STRING']; ?>" class="toggle" <?php if (!$aendern) { echo("style=\"display: none\""); } ?> >    <input type="hidden" name="terminid" value="<?php if ($aendern) { echo(htmlentities($termin->getTerminid())); } ?>">    <table align=center>      <tr>        <td> Datum von <small>(YYYY-MM-TT)</small>: </td>        <td> <input onclick='scwShow(this,event);' type="Text" name="DatumVonPrimary" value="<?php if ($aendern) { echo(htmlentities($termin->getVondateprimary())); } else { echo(""); } ?>" SIZE="40" MAXLENGTH="10"> </td>      </tr><tr>        <td> Datum bis <small>(YYYY-MM-TT)</small>: </td>        <td> <input onclick='scwShow(this,event);' type="Text" name="DatumBisPrimary" value="<?php if ($aendern) { echo(htmlentities($termin->getBisdateprimary())); } else { echo(""); } ?>" SIZE="40" MAXLENGTH="10"> </td>      </tr><tr>        <td> </td>        <td> <small>Bei eint&auml;tigen Events das selbe Datum wie <br>bei "Datum von" eingeben </small></td>      </tr><tr>        <td> Zeit von<small>(hh:mm)</small>: </td>        <td> <input type="Text" name="ZeitVonPrimary" value="<?php if ($aendern) { echo(formatTime(htmlentities($termin->getVontimeprimary()))); } else { echo("00:00"); } ?>" SIZE="40" MAXLENGTH="5"> </td>      </tr><tr>        <td> Zeit bis<small>(hh:mm)</small>: </td>        <td> <input type="Text" name="ZeitBisPrimary" value="<?php if ($aendern) { echo(formatTime(htmlentities($termin->getBistimeprimary()))); } else { echo("00:00"); } ?>" SIZE="40" MAXLENGTH="5"> </td>      </tr><tr>        <td> Was: </td>        <td> <input type="Text" name="Was" value="<?php if ($aendern) { echo(htmlentities(utf8_encode($termin->getWhat()))); } else { echo(""); } ?>" SIZE="40" MAXLENGTH="150"> </td>      </tr><tr>        <td> Wo: </td>        <td> <input type="Text" name="Wo" value="<?php if ($aendern) { echo(htmlentities(utf8_encode($termin->getWhere()))); } else { echo(""); } ?>" SIZE="40" MAXLENGTH="150"> </td>      </tr><tr>        <td> Infos: </td>        <td> <input type="Text" name="Infos" value="<?php if ($aendern) { echo(htmlentities(utf8_encode($termin->getInfos()))); } else { echo(""); } ?>" SIZE="40" MAXLENGTH="150"> </td>      </tr><tr>        <td> Termin Art: </td>        <td>           <select name="terminq" SIZE="2" style="width:25em" MAXLENGTH="150">              <option value="TERMIN" <?php if (!$aendern || $termin->getTerminq() == "TERMIN") echo("selected='selected'"); ?> >Termin</option>             <option value="PROBE" <?php if ($aendern && $termin->getTerminq() == "PROBE") echo("selected='selected'"); ?>>Probe</option>          <select>        </td>      </tr><tr>        <td></td>        <td>             <input type=submit name="<?php if ($aendern) { echo("update"); } else { echo("speichern"); } ?>"  value="<?php if ($aendern) { echo("  &Auml;nderung Speichern "); } else { echo("   Neu Speichern     "); } ?>" >             <input type=reset                   value="        Reset        ">        </td>      </tr>    </table>    <br>  </form><?php}function deleteTermin() {  if (hasCurrentUserRight("termine") && isset($_POST["loeschen"])) {    $db = getDB();    $sql = "delete from termin where terminid = " . mysql_real_escape_string($_POST["terminid"]);    //echo($sql);    $result = mysql_query($sql);    $errorno = mysql_errno();    if (mysql_affected_rows() != 1 || $errorno != 0){      echo "<center class=\"error\">L&ouml;schen fehlgeschlagen</center>";    }else{      echo "<center class=\"successful\">L&ouml;schen erfolgreich</center>";    }  }}function storeTermin() {  if (hasCurrentUserRight("termine") && isset($_POST["speichern"])) {    $db = getDB();    $sql = "INSERT INTO termin (terminq, vondateprimary, bisdateprimary, vontimeprimary, bistimeprimary, what, `where`, infos, createuserid, createdate) " .           "VALUES  ('TERMIN', '" .            mysql_real_escape_string(trim($_POST["DatumVonPrimary"])) . "','" .           mysql_real_escape_string(trim($_POST["DatumBisPrimary"])) . "','" .             mysql_real_escape_string(trim($_POST["ZeitVonPrimary"])) . "','" .            mysql_real_escape_string(trim($_POST["ZeitBisPrimary"])) . "','" .            mysql_real_escape_string(trim($_POST["Was"])) . "','" .            mysql_real_escape_string(trim($_POST["Wo"])) . "','" .            mysql_real_escape_string(trim($_POST["Infos"])) . "'," .            $_SESSION['userid'] . ", now() )";    //echo($sql);    $result = mysql_query($sql);    $errorno = mysql_errno();    if (mysql_affected_rows() != 1 || $errorno != 0){      echo "<center class=\"error\">Eintrag fehlgeschlagen</center>";    }else{      echo "<center class=\"successful\">Eintrag erfolgreich</center>";    }  }}function updateTermin() {  if (hasCurrentUserRight("termine") && isset($_POST["update"])) {    $db = getDB();    $sql = "UPDATE termin set " .           "vondateprimary = '" . mysql_real_escape_string(trim($_POST["DatumVonPrimary"])) . "', " .            "bisdateprimary = '" . mysql_real_escape_string(trim($_POST["DatumBisPrimary"])) . "', " .            "vontimeprimary = '" . mysql_real_escape_string(trim($_POST["ZeitVonPrimary"])) . "', " .           "bistimeprimary = '" . mysql_real_escape_string(trim($_POST["ZeitBisPrimary"])) . "', " .           "what = '" . mysql_real_escape_string(trim($_POST["Was"])) . "', " .           "`where` = '" . mysql_real_escape_string(trim($_POST["Wo"])) . "', " .           "infos = '" . mysql_real_escape_string(trim($_POST["Infos"])) . "', " .            "changeuserid=" . $_SESSION['userid'] . ", " .           "changedate = now() " .            "WHERE terminid = " . mysql_real_escape_string(trim($_POST["terminid"]));    //echo($sql);     $result = mysql_query($sql);    $errorno = mysql_errno();    if (mysql_affected_rows() != 1 || $errorno != 0){      echo "<center class=\"error\">&Auml;nderung fehlgeschlagen</center>";    }else{      echo "<center class=\"successful\">&Auml;nderung erfolgreich</center>";    }  }}function initTermine() {  if (hasCurrentUserRight("termine")) { ?>     <script language="JavaScript" type="text/javascript">      function askDelete() {        if (confirm("Wirklich löschen")){          return true;        } else {          return false;        }       }    </script>    <script  type="text/javascript" >      jQuery(document).ready(function(){            jQuery("button").click(function () {          jQuery("form.toggle").slideToggle("slow");          jQuery("td.toggle").slideToggle("slow");          jQuery("th.toggle").slideToggle("slow");          jQuery("input.toggle").slideToggle("slow");        });      });    </script>    <center><button>Show/Hide Change Termin</button></center>    <br>    <?php storeTermin(); updateTermin(); deleteTermin(); printAddFormular();    }}function termineList() { ?>  <table cellpadding="0" cellspacing="0" border="0" align="center" style="width: 45em; margin: 0.1em auto;">    <tr class="tblhead">      <?php        if (hasCurrentUserRight("termine")) { ?>           <th style="border-top-left-radius:0.7em;border-bottom-left-radius:0.7em;" class="thclass" class="toggle"> </th>         <?php } ?>           <th style="border-top-left-radius:0.7em;border-bottom-left-radius:0.7em;" width="145">&nbsp;Datum</th>        <th>&nbsp;&nbsp;</th>        <th>Zeit</th>        <th>&nbsp;&nbsp;</th>        <th>Was</th>        <th>&nbsp;&nbsp;</th>        <th style="border-top-right-radius:0.7em;border-bottom-right-radius:0.7em;">Infos</th>      </tr>      <tr> <td colspan="*" height="3"> </td> </tr>       <?php       $termine = array();       $termine = array_merge($termine, getTermine(true, 0, "TERMIN"));       $termine = array_merge($termine, getTermine(true, 0, "PROBE"));       foreach($termine as $ensembletermin) { ?>	      <tr class="trclass">          <?php if (hasCurrentUserRight("termine")) {  ?>              <td class="tdclass" class="toggle">                   <form name="terminchange" method="post" action="<?php echo $_SERVER["PHP_SELF"] . "?" . $_SERVER['QUERY_STRING'];  ?>" >                     <input type=submit name="aendern" value="aendern" class="toggle buttonchange" style="display: none">                     <input type=submit name="loeschen" value="loeschen" class="toggle buttondelete" onClick="return askDelete()" style="display: none">                      <input type="hidden" name="terminid" value="<?php echo(htmlentities($ensembletermin->getTerminid()))?>">                  </form>              </td>           <?php } ?>  			            <td valign="top"> &nbsp;<?php echo($ensembletermin->getFormatedVonDatePrimary()); ?> </td>            <td> </td>            <td valign="top"> <?php echo($ensembletermin->getVontimeprimary()); ?> </td>            <td> </td>            <td valign="top">                <?php                     echo(htmlentities(utf8_encode($ensembletermin->getWhat())));                     if ($ensembletermin->getWhere() != "") {                        echo("<br><small>[" . $ensembletermin->getWhere() . "]</small>");                    }                ?>            </td>            <td> </td>            <td valign="top"><?php echo(htmlentities(utf8_encode($ensembletermin->getInfos()))); ?></td>	      </tr>        <tr> <td colspan="*" height="3"> </td> </tr>     <?php } ?>            </table> <?php}?>