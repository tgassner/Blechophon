<?php 

$mediaeventid = $_GET["mediaeventid"];

$mediaevent = MediaBean::getMediaeventById($mediaeventid);
if ($mediaevent == null) {
    echo("<h1>MediaEvent Not Found!</h1>");
    return;
}

?>

<section class="intro">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2">
                    <h2 class="section-heading"><?php echo($mediaevent->getEventName()); ?></h2>
                </div>
            </div>
        </div>
</section>

<link rel="stylesheet" href="css/photoswipe.css"> 
<link rel="stylesheet" href="css/photoswipe-skin.css"> 

<!-- Core JS file -->
<script src="js/photoswipe.min.js"></script> 

<!-- UI JS file -->
<script src="js/photoswipe-ui-default.min.js"></script> 

<section class="content content-fotomenu">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2">
                <?php 

                    $mediaKind = MediaBean::getMediaKindById($mediaevent->getMediakindId());
                    $mediacategory = MediaBean::getMediacategoryById($mediaevent->getMediacategoryId());

                    echo("<div id='my-gallery'>\n");
                    foreach ($mediaevent->getMediaItems() as $mediaItem) {
                        $fotopath = $mediaKind->getDirectory() . "/" . $mediacategory->getDirectory() . "/" . $mediaevent->getYear() . "/" . $mediaevent->getDirectoryEvent() . "/" . $mediaevent->getDirectorymain() . "/" . $mediaItem->getFilename();
                        $thumnailpath = $mediaKind->getDirectory() . "/" . $mediacategory->getDirectory() . "/" . $mediaevent->getYear() . "/" . $mediaevent->getDirectoryEvent() . "/" . $mediaevent->getDirectoryThumbs() . "/" . $mediaItem->getFilename();
                        
                        echo("<a class='image-link' href='" . htmlentities($fotopath) . "' data-size='" . $mediaItem->getMainwidth() . "x" . $mediaItem->getMainheight() . "'");
                        
                        echo(">");
                
                        echo("<img style='margin:2px' src='" . htmlentities($thumnailpath) . "' ");
                        echo("width='" . $mediaItem->getThumbwidth() . "' height='" . $mediaItem->getThumbheight() . "' ");
                        echo("alt='" . htmlentities($mediaItem->getFilename()) . " (" . $mediaItem->getMainwidth() . "x" . $mediaItem->getMainheight() . "px) - " . $mediaItem->getBytes() . " Bytes' ");
                        echo("title='" . $mediaevent->getEventName() . "\nAuflösung=" . $mediaItem->getMainwidth() . "x" . $mediaItem->getMainheight() . "'");
                        echo("/> </a>\n");
                    }
                    echo("</div>");
                ?>


<!-- Root element of PhotoSwipe. Must have class pswp. -->
<div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">

    <!-- Background of PhotoSwipe. 
         It's a separate element as animating opacity is faster than rgba(). -->
    <div class="pswp__bg"></div>

    <!-- Slides wrapper with overflow:hidden. -->
    <div class="pswp__scroll-wrap">

        <!-- Container that holds slides. 
            PhotoSwipe keeps only 3 of them in the DOM to save memory.
            Don't modify these 3 pswp__item elements, data is added later on. -->
        <div class="pswp__container">
            <div class="pswp__item"></div>
            <div class="pswp__item"></div>
            <div class="pswp__item"></div>
        </div>

        <!-- Default (PhotoSwipeUI_Default) interface on top of sliding area. Can be changed. -->
        <div class="pswp__ui pswp__ui--hidden">

            <div class="pswp__top-bar">

                <!--  Controls are self-explanatory. Order can be changed. -->

                <div class="pswp__counter"></div>

                <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>

                <button class="pswp__button pswp__button--share" title="Share"></button>

                <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>

                <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>

                <!-- Preloader demo http://codepen.io/dimsemenov/pen/yyBWoR -->
                <!-- element will get class pswp__preloader--active when preloader is running -->
                <div class="pswp__preloader">
                    <div class="pswp__preloader__icn">
                      <div class="pswp__preloader__cut">
                        <div class="pswp__preloader__donut"></div>
                      </div>
                    </div>
                </div>
            </div>

            <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                <div class="pswp__share-tooltip"></div> 
            </div>

            <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)">
            </button>

            <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)">
            </button>

            <div class="pswp__caption">
                <div class="pswp__caption__center"></div>
            </div>

        </div>

    </div>

</div>

<script language="javascript">

jQuery('#my-gallery').each(function () {
    var $pic = $(this),
        getItems = function () {
            var items = [];
            $pic.find('a').each(function () {
                var $href = $(this).attr('href'),
                    $size = $(this).data('size').split('x'),
                    $title = ($(this).data('fotodescription') ? $(this).data('fotodescription') : '') 
                            + ($(this).data('fullres') && $(this).data('fotodescription') ? "<br>" : '') 
                            + ($(this).data('fullres') ? 'Originalauflösung: ' + $(this).data('fullresinfo') + ' <a target="blank" href="' + $(this).data('fullres') + '">[DOWNLOAD]</a> ' : ''),
                    $width = $size[0],
                    $height = $size[1];

                var item = {
                    src: $href,
                    w: $width,
                    h: $height,
                    title: $title
                }

                items.push(item);
            });
            return items;
        }

    var items = getItems();

    console.log(items);


    var $pswp = $('.pswp')[0];

    $pic.on('click', 'a', function (event) {
        event.preventDefault();

        var $index = $(this).index();
        var options = {
            index: $index,
            bgOpacity: 0.9,
            showHideOpacity: true
        }

        // Initialize PhotoSwipe
        var lightBox = new PhotoSwipe($pswp, PhotoSwipeUI_Default, items, options);
        lightBox.init();
    });

});
</script>

            </div>
        </div>
    </div>
</section>