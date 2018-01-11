<section class="intro">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2">
                    <h2 class="section-heading" style="font-size: 3em;">Fotos</h2>
                </div>
            </div>
        </div>
</section>

<section class="content content-fotomenu">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2">
                <?php 
                    $events = MediaBean::getEventsByMediaKindQualifier(MediaBean::MEDIAKIND_QUALIFIER_FOTOS);
                    $lastCath = "";
                    $lastyear = "";

                    foreach ($events as $event) {
                        if ($lastyear != $event->getYear()) {
                            if ($lastyear != "") {
                                echo("</div>");
                            }
                            echo("<h2 style='font-weight:bold'>" . $event->getYear() . "</h2>");
                            echo("<div class='foto-menu-group'>");
                        }

                        echo("<div class='foto-link'><a href='?s=fotoviewer&mediaeventid=" . $event->getMediaeventId() . "' class='fotomenue'>" . $event->getEventName() . "</a></div>");
                        $lastCath = $event->getMediacategoryId();
                        $lastyear = $event->getYear();
                    }
                    echo("</div>");
                ?>
            </div>
        </div>
    </div>
</section>