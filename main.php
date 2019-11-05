<!-- Header -->
<header>
        <div class="header-content">
            <div class="header-content-inner">
                <h1 style="margin-top: 5.5em;text-shadow: 3px 3px 3px #D2232A, 4px 4px 3px #FFFFFF, -1px -1px 3px #FFFFFF, 1px -1px 3px #FFFFFF, -1px 1px 3px #FFFFFF;">Blechophon</h1>
            </div>
        </div>
    </header>

	<!-- Intro Section -->
    <section class="intro" id="ueberUns" name="ueberUns">
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    <img class="img-responsive img-circle center-block" src="images/ueberUns.jpg" alt="">
                </div>
                <div class="col-sm-6">
                    <h2 class="section-heading">Über uns</h2>
                    <p class="text-light justify">Die Wurzeln von BLECHOPHON liegen in einem Blechbläserensemble welches im Jahr 2006 für einen Auftritt bei einer Bergmesse gegründet wurde und aus Mitgliedern bestand welche sich bereits aus Ihrer gemeinsamen Zeit beim Jugendorchester in Engerwitzdorf kannten.</p>
                    <p class="text-light justify">In den Jahren danach wurde die Besetzung dieses Ensembles immer wieder verändert und neue Mitglieder kamen hinzu bis schließlich im Jahr 2008 BLECHOPHON als eigenständiges Ensemble gegründet wurde.</p>
                    <p class="text-light justify">Seit dieser Zeit spielt BLECHOPHON bei Früh-bzw. Dämmerschoppen, Polterabenden sowie Weihnachts – und Firmenfeiern frei nach unserem Motto „Musik mit Begeisterung und Leidenschaft auf hohem Niveau“.</p>
                    <p class="text-light justify">Doch nicht nur Unterhaltungsmusik zählt zum Repertoire von BLECHOPHON sondern auch konzertante Blechbläsermusik wie mehrmalige Erfolge beim Wettbewerb Musik in kleinen Gruppen, ein Gemeinschaftskonzert mit dem Wartberger Chor „Pro Musica“ und eigene Konzerte mit dem Titel „Blechophon in Concert“ im Jahr 2014, „Blechophon XL in Concert“ im Jahr 2016 und „abraBRASSdabra“ im Jahr 2018 zeigen.</p>
                    <p class="text-light justify">BLECHOPHON immer die richtige Wahl für gute Unterhaltung und Stimmung bei Ihrer Veranstaltung.</p>
                </div>
            </div>
        </div>
    </section>

	<!-- Termine -->
    <section class="content" id="termine" name="termine">
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                	<h2 class="section-header">Termine</h2>
                    <table>
                        <tr>
                            <th style="vertical-align: top; padding: 4px">Datum</th>
                            <th style="vertical-align: top; padding: 4px">Zeit</th>
                            <th style="vertical-align: top; padding: 4px">Was</th>
                            <th style="vertical-align: top; padding: 4px">Infos</th>
                        </tr>
                	    <?php $termine = TerminBean::getTermine(true, 0, "TERMIN"); ?>
                        <?php foreach($termine as $termin) {  ?>
                            <tr>
                                <td style="vertical-align: top; padding: 4px"><?php echo($termin->getFormatedVonDatePrimary()); ?></td>
                                <td style="vertical-align: top; padding: 4px"><?php echo($termin->getFormatedTimeVon()); ?></td>
                                <td style="vertical-align: top; padding: 4px">
                                    <?php
                                        echo(htmlentities(utf8_encode($termin->getWhat())));
                                        if ($termin->getWhere() != "") {
                                            echo("<br><small>[" . $termin->getWhere() . "]</small>");
                                        }
                                    ?>
                                </td>
                                <td style="vertical-align: top; padding: 4px"><smaller><?php echo(utf8_encode($termin->getInfos())); ?></smaller></td>
                            </tr>
                        <?php }  ?>
                        <?php $proben = TerminBean::getTermine(true, 0, "PROBE"); ?>
                        <?php if (!empty($proben)) { ?>
                            <tr>
                                <td colspan="4" style="vertical-align: top; padding: 4px; color: #999999">Proben</td>
                            </tr>
                            <?php foreach($proben as $probe) {  ?>
                                <tr>
                                    <td style="vertical-align: top; padding: 4px; color: #999999"><?php echo($probe->getFormatedVonDatePrimary()); ?></td>
                                    <td style="vertical-align: top; padding: 4px; color: #999999"><?php echo($probe->getFormatedTimeVon()); ?></td>
                                    <td style="vertical-align: top; padding: 4px; color: #999999"><?php echo($probe->getWhere()); ?></td>
                                    <td style="vertical-align: top; padding: 4px; color: #999999"><smaller><?php echo(utf8_encode($probe->getInfos())); ?></smaller></td>
                                </tr>
                            <?php }  ?>
                        <?php }  ?>
                    </table>
                </div>                
                <div class="col-sm-6">
                    <img class="img-responsive img-circle center-block" src="images/logo_rechteckig.png" alt="">
                </div>                
            </div>
        </div>
    </section>

    <!-- Fotos / Videos -->
	<div class="container-fluid">
        <div class="row promo">
        	<a href="?s=fotos">
				<div class="col-md-6 promo-item item-1">
					<h3 style="padding-left:150px">Fotos</h3>
				</div>
            </a>
            <a href="#">
				<div class="col-md-6 promo-item item-2">
					<h3 style="padding-right:150px">Videos</h3>
				</div>
            </a>
        </div>
    </div><!-- /.container-fluid -->

    <!-- Links -->
    <section class="content content-2" id="links" name="links">
        <div class="container">
            <h2 class="section-header">Links</h2>
            <div class="container">
                <div class="col-sm-6">
                    <p class="text-light">Musikverein Engerwitzdorf</p>
                </div>
                <div class="col-sm-6">
                    <p class="text-lighter"><a class="text-lighter" href="http://musik-engerwitzdorf.at">http://musik-engerwitzdorf.at</a>	 </p>
                </div>
            </div>
            <div class="container">
                <div class="col-sm-6">
                    <p class="text-light">Musikverein Grünbach</p>
                </div>
                <div class="col-sm-6">
                    <p class="text-lighter"><a class="text-lighter" href="http://musikgr.bplaced.net/">http://musikgr.bplaced.net/</a></p>
                </div>
            </div>
            <div class="container">
                <div class="col-sm-6">
                    <p class="text-light">Musikverein Katsdorf</p>
                </div>
                <div class="col-sm-6">
                    <p class="text-lighter"><a class="text-lighter" href="http://www.musikverein-katsdorf.at/">http://www.musikverein-katsdorf.at/</a>	     </p>
                </div>
            </div>
            <div class="container">
                <div class="col-sm-6">
                    <p class="text-light">Musikverein Bad Leonfelden</p>
                </div>
                <div class="col-sm-6">
                    <p class="text-lighter"><a class="text-lighter" href="http://www.mv-badleonfelden.at">http://www.mv-badleonfelden.at</a>	 </p>
                </div>
            </div>
            <div class="container">
                <div class="col-sm-6">
                    <p class="text-light">Mehl Brass</p>
                </div>
                <div class="col-sm-6">
                    <p class="text-lighter"><a class="text-lighter" href="http://mehlbrass.mmsoft.at/">http://mehlbrass.mmsoft.at/</a>	 </p>
                </div>
            </div>
            <div class="container">
                <div class="col-sm-6">
                    <p class="text-light">Theatergruppe Engerwitzdorf</p>
                </div>
                <div class="col-sm-6">
                    <p class="text-lighter"><a class="text-lighter" href="https://www.theatergruppe-engerwitzdorf.at/">https://www.theatergruppe-engerwitzdorf.at/</a> </p>
                </div>
            </div>
        </div>
    </section>


    <!-- Referenzen -->
    <section class="content" id="references" name="references">
        <div class="container">
            <h2 class="section-header">Referenzen</h2>
            <p>Über die Jahre konnten wir neben gemütlichen, kleineren Auftritten auch zahlreiche größere Veranstaltungen musikalisch umrahmen. Hier ein kleiner Auszug daraus.</p>

            <ul>
                <li>MidEurope (Schladming)</li>
                <li>Bezirksmusikfest Urfahr-Umgebung (Bad Leonfelden)</li>
                <li>Bezirksmusikfest Katsdorf (Bezirk Perg)</li>
                <li>Mühlviertler Wies’n (Messehalle Freistadt)</li>
                <li>Nacht der Blasmusik (Lacken)</li>
                <li>ORF OÖ Wandertag (Gallneukirchen)</li>
                <li>Oktoberfest Hirschbach</li>
                <li>OÖ Landesgartenschau (Kremsmünster)</li>
                <li>Marktfest Schenkenfelden</li>
                <li>Musiroas Herzogsdorf</li>
                <li>Konzerte bei „Blasmusik in Linz“ (Musikpavillon/Hauptplatz)</li>
            </ul>
        </div>
    </section>


