<?php 

$videoid = $_GET["videoid"];

$video = VideoBean::getVideoByID($videoid);
if ($video == null) {
    echo("<h1>Video Not Found!</h1>");
    return;
}

?>

<section class="intro">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2">
                    <h2 class="section-heading"><?php echo($video->getheadline()); ?></h2>
                </div>
            </div>
        </div>
</section>


<section class="content content-fotomenu">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2">
                <?php 
                    echo($video->getembeded());
                ?>
            </div>
        </div>
    </div>
</section>