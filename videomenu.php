<section class="intro">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2">
                    <h2 class="section-heading" style="font-size: 3em;">Videos</h2>
                </div>
            </div>
        </div>
</section>

<section class="content content-fotomenu">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2">
                <?php 
                    $videos = VideoBean::getAllVideosSorted();
                    $lastyear = "";
                    foreach ($videos as $video) {
                        if ($lastyear != $video->getyear()) {
                            if ($lastyear != "") {
                                echo("</div>");
                            }
                            echo("<h2 style='font-weight:bold'>" . $video->getyear() . "</h2>");
                            echo("<div class='foto-menu-group'>");
                        }

                        echo("<div class='foto-link'><a href='?s=videoviewer&videoid=" . $video->getvideoid() . "' class='fotomenue'>" . $video->getheadline() . "</a></div>");
                        $lastyear = $video->getyear();
                    }
                    echo("</div>");
                ?>
            </div>
        </div>
    </div>
</section>