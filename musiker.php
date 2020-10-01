<!--h2 class="section-header"><span class="glyphicon glyphicon-pushpin text-primary"></span><br> Sanity Check</h2>-->

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

<section class="intro">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2">
                    <h2 class="section-heading">Musiker</h2>
                </div>
            </div>
        </div>
</section>

<link rel="stylesheet" href="css/photoswipe.css"> 
<link rel="stylesheet" href="css/photoswipe-skin.css"> 
<script src="js/photoswipe.min.js"></script> 
<script src="js/photoswipe-ui-default.min.js"></script> 

<section class="content content-3">
        <div class="container">
            <div class="row">
                <?php person(1, "puererfellnermichael.jpg", "Michael Pürerfellner", "Trompete, Flügelhorn", false); ?>

                <?php person(2, "mayrhoferstefan.jpg", "Stefan Mayrhofer", "Trompete, Flügelhorn", false); ?>

                <?php person(3, "hofbauerfranz.jpg", "Franz Hofbauer", "Trompete, Flügelhorn", false); ?>

                <?php person(11, "anonymous.jpg", "Julian Mörzinger", "Trompete, Flügelhorn", false); ?>

                <?php person(4, "dumphartmartin.jpg", "Martin Dumphart", "Tenorhorn, Posaune", false); ?>

                <?php person(5, "hoffelnerjosefjun.jpg", "Pepi Hoffelner", "Bariton, Posaune", false); ?>

                <?php person(6, "eckertmarkus.jpg", "Markus Eckert", "Tuba", false); ?>

                <?php person(12, "AntonWall.jpg", "Anton Wall", "Schlagzeug", false); ?>

                <h2>Ehemalige Musiker</h2>
                <br>

                <?php person(7, "haidingerlukas.jpg", "Lukas Haidinger", "Schlagzeug", true); ?>

                <?php person(8, "bockmarkus.jpg", "Markus Bock", "Trompete, Flügelhorn", true); ?>

                <?php person(9, "bockjohannes.jpg", "Johannes Bock", "Bariton", true); ?>

                <?php person(10, "schinnerlpenknerchristian.jpg", "Christian Schinnerl-Penkner", "Trompete, Flügelhorn", true); ?>

            </div>
        </div>
    </section>

<?php
 function person($key, $filename, $name, $instrument, $grayscale) {
    $arr = getimagesize("images/personen/fotos/" . $filename);
    ?>
        <div class="row">
            <div class="col-sm-3"></div>
            <div class="col-sm-2">
                <p><?php echo($name); ?></p>
            </div>
            <div class="col-sm-2">
                <p><?php echo($instrument); ?></p>
            </div>
            <div class="col-sm-2">
                <div id='my-gallery_<?php echo($key); ?>'>
                    <a class='image-link center-block' href='images/personen/fotos/<?php echo($filename); ?>' data-size='<?php echo($arr[0]); ?>x<?php echo($arr[1]); ?>'>
                        <img src='images/personen/thumbnails/<?php echo($filename); ?>' style='filter: drop-shadow(2px 4px 6px black) <?php if ($grayscale) { echo(" grayscale(100%) "); } ?>' width='100px' height='100px' alt='<?php echo($name); ?>' title='<?php echo($name); ?>' /> 
                    </a>
                </div>
            </div>
            <div class="col-sm-3"></div>
        </div>
        <br>


<script language="javascript">

jQuery('#my-gallery_<?php echo($key); ?>').each(function () {
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
<?php
}
?>