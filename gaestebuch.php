<script language="JavaScript" type="text/javascript">
  function checkform() {
    if ((document.gaestbook.email.value.indexOf('@') == -1) && (document.gaestbook.email.value != "")) {
      alert("Eine Emailadresse beinhaltet einen @");
      document.gaestbook.email.focus();
      return false;
    }       

    if ((document.gaestbook.homepage.value.indexOf('http://') == -1) && (document.gaestbook.homepage.value != "")) {
      alert("Bitte http:// bei der Homepage voranstellen");
      document.gaestbook.homepage.focus();
      return false;
    }  
  }
</script>
  <?php
	  if (isset($_GET["Seite"])){
			$Seite = $_GET["Seite"];
		}else{
		  $Seite = "";
		}
    $db = getDB();
    $result = mysql_query("select count(*) from gaestebuchtable",$db);
    
    $anzeintraege = mysql_fetch_row($result);
    
    $proseite = 12;
    if (isset($_SERVER['QUERY_STRING'])){
      $serverstringg = $_SERVER['QUERY_STRING'];
    }else{
      $serverstringg = "";
    }
    $QUERY_STRING = ereg_replace("&change=true", "", $serverstringg);
  ?>
  <h1>Gästebuch</h1>
  <div style="margin-left:0.8em; margin-right:0.8em; margin-bottom:1em">
  <?php
    if (isset($_POST["kommentar"])) {  //wenn &uuml;bertragen - jetzt wird die Seite zum 2. mal aufgerufen
      if ($_POST["jscheck"] == sha1(date("Y-m-d", time()))){
        if (1 == 2 && checkLockesIPs()){
          echo("<font color=\"ff0000\" size=\"5\">Die IP-Adresse: ".getenv("REMOTE_ADDR")." ist gesperrt!!!<br></font>");
        }else{
	        $dat = date("Y-m-d");
	        $zei = date("H:i:s");
	        if (isLoggedIn()) {
	        	$currentuserid = $_SESSION['userid'];
	        } else {
	        	$currentuserid = "null";
	        }
	        $result = mysql_query("INSERT INTO gaestebuchtable (name,email,homepage,kommentar,datum,zeit,ip, createuserid, createdate) VALUES ('" . mysql_real_escape_string($_POST["name"]) . "','" . mysql_real_escape_string($_POST["email"]) . "','" . mysql_real_escape_string($_POST["homepage"]) . "','" . mysql_real_escape_string($_POST["kommentar"]) . "','" . $dat . "','" . $zei . "','" . getenv("REMOTE_ADDR") . "'," . $currentuserid . ", now() )");
          if (mysql_affected_rows() == 0 || $errorno != 0){
            echo "<center class='error'>Eintrag fehlgeschlagen</center>";
          }else{
            echo "<center class='successful'>Eintrag Erfolgreich</center><br>";
          }
        }
      }else{
	      echo "<center class='error'>Spamschutz nicht korrekt.</center>";
      }
    }else{  // wenn die Seite zum 1. Mal aufgerufen wird: ?>
      <h2>Hier kannst du dich in unser Gästebuch eintragen:</h2>
      <div style="font-size:0.7em">
        Die Blechophon-Foren sind allgemein zug&auml;ngliche, offene und demokratische Diskursplatten.
        Bitte bleiben Sie sachlich und bem&uuml;hen Sie sich um eine faire und freundliche Diskussionsatmosph&auml;re.
        Blechophon &uuml;bernimmt keinerlei Verantwortung f&uuml;r den Inhalt der Beitr&auml;ge, beh&auml;lt sich aber das Recht vor,
        krass unsachliche, rechtswidrige oder moralisch bedenkliche Beitr&auml;ge sowie Beitr&auml;ge, die dem Ansehen des Mediums schaden,
        zu l&ouml;schen.
        Sie als Verfasser haften f&uuml;r s&auml;mtliche von Ihnen ver&ouml;ffentlichte Beitr&auml;ge selbst und k&ouml;nnen daf&uuml;r auch gerichtlich
        zur Verantwortung gezogen werden. Beachten Sie daher bitte, dass auch die freie Meinungs&auml;u&szlig;erung im Internet den Schranken
        des geltenden Rechts, insbesondere des Strafgesetzbuches (&Uuml;ble Nachrede, Ehrenbeleidigung etc.) und des Verbotsgesetzes,
        unterliegt. Die Redaktion beh&auml;lt sich vor, strafrechtlich relevante Tatbest&auml;nde gegebenenfalls den zust&auml;ndigen Beh&ouml;rden
        zur Kenntnis zu bringen.
        Die Chatiquette und Netiquette sind zu akzeptieren und einzuhalten! 
        <br><br>
      </div>  

        <?php 
          $name = "";
          $email = "";
          if (1 == 2 && yisLoggedIn()) {
            $user = getFullUserByID(getCurrentUserId());
            $name = $user->getSpitznameOrVorname();
            $email = $user->getemail();
          }
        ?>
      
    <form name="gaestbook" method="post" action="<?php echo $_SERVER["PHP_SELF"] . "?" . $QUERY_STRING; ?>" onSubmit="return checkform()">
    <table align=center cellpadding="0" cellspacing="0" border="0">
      <tr>
        <td class="links"> Name: </td>
        <td> <input type="Text" name="name" value="<?php echo($name); ?>" SIZE="40" MAXLENGTH="60" style="font-size:8pt"> </td>
      </tr><tr>
        <td class="links"> E-Mail: </td>
        <td> <input type="Text" name="email" value="<?php echo($email); ?>" SIZE="40" MAXLENGTH="60" style="font-size:8pt"> </td>
      </tr><tr>
        <td class="links"> Homepage: </td>
        <td> <input type="Text" name="homepage" value="" SIZE="40" MAXLENGTH="80" style="font-size:8pt"> </td>
      </tr><tr>
        <td class="links"> Kommentar: </td>
        <td> <textarea name="kommentar" rows="6" cols="40"></textarea> </td>
      </tr> <!--<tr>
        <td class="links"><?php echo(GAESTEBUCH_SPAMSCHUTZ); ?>:</td>
        <td> <?php //echo(recaptcha_get_html("6LdsV7wSAAAAANRLN_j6m6apAzB6NebdqFCl1bwx")); ?> </td>
      </tr>--><tr>
        <td></td>
        <td>
             <input type="hidden" id="jscheck" name="jscheck" value="">
             <input type=submit name="submit" value="      Absenden      " style="font-size:8pt">
             <input type=reset                value="      Löschen       " style="font-size:8pt">
        </td>
      </tr>
    </table>
  </form>
  
  <script language="JavaScript" type="text/javascript">
    jQuery('#jscheck').val("<?php echo(sha1(date("Y-m-d", time())));?>");
  </script>
  
  <?php } ?>
  <?php  if ($Seite=="") { $Seite = 1; } ?>
    <center> 
      <font class="links">Es sind derzeit <?php echo $anzeintraege[0];  ?> Einträge seit 05.06.2011 vorhanden</font>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <font class="links"> Seite <?php echo $Seite ?> </font>
    </center>
    <center>
  <?php if (($Seite > 1) && ($Seite!="")) { ?>
    <A HREF="<?php echo $_SERVER["PHP_SELF"] . "?" . $QUERY_STRING . "&amp;" . "Seite=" . ($Seite - 1); ?>"  class="links">Vorige</A>
   <?php }else{ ?> 
    <font class="links">Vorige</font>
  <?php } ?>
  | 
  <?php if (($anzeintraege[0] > ($Seite*$proseite)))
        { ?>
           <A HREF="<?php echo $_SERVER["PHP_SELF"] . "?" . $QUERY_STRING . "&amp;" . "Seite=" . ($Seite + 1); ?>"  class="links">Nächste</A>
	   <?php }else{ ?> <font class="links">Nächste</font><?php
        } ?>
  <font class="links">Seite</font> </center>
  <?php
    $result = mysql_query("SELECT * FROM gaestebuchtable order by datum desc,zeit desc",$db);
    $count = 0;
    while (($myrow = mysql_fetch_assoc($result)) && ($count < ((($Seite-1)*$proseite)-1))) {
      $count++;
    }

    if($Seite==1) {$result = mysql_query("SELECT * FROM gaestebuchtable order by datum desc,zeit desc",$db); }  //noch mal anfangen, weil 1. Eintrag scho weg w&auml;re
    $count = 0;

    while (($myrow = mysql_fetch_assoc($result)) && ($count<$proseite)){  
      $count++; ?>
      <br>
      <table>
      	  <colgroup>
            <col width=80>
          </colgroup>
          <tr> 
             <td> </td>
	     <td class="links"> <?php if (htmlentities($myrow['email']) != '') { echo(getEncryptetEmailLinkWithSeperatLinkText($myrow['email'],$myrow['name'])); ?>  
                      <?php }else{ ?> <font class="links"> <?php echo htmlentities($myrow['name']); ?> </font> <?php } ?>
                  <?php if (htmlentities($myrow['homepage']) != '') { ?>| <A HREF="<?php echo htmlentities($myrow['homepage']); ?>" target="_blank" class="links"><?php echo("Homepage"); ?></A> <?php } ?>
                  schrieb am <?php echo htmlentities($myrow['datum']); ?> <?php if (htmlentities($myrow['zeit']) != '') ?> um <?php echo htmlentities($myrow['zeit']); ?>&nbsp;<?php echo($myrow['gaestebuchid']); ?>
             </td>
          <tr> 
             <td> </td>
             <td class="links"> <?php echo htmlentities($myrow['kommentar']); ?> </td>
          </tr>
	</table>
   <?php }
    mysql_free_result($result);
   ?>
  </div>