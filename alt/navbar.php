<script  language="JavaScript" type="text/javascript">
  jQuery(document).ready(function() {
    jQuery(" li.navbargrayjquery").mouseover(function () {
      jQuery(this).addClass("navbargrayover");
    });

    jQuery("li.navbargrayjquery").mouseout(function () {
      jQuery(this).removeClass("navbargrayover");
    });
  });
</script>


<ul class="nav">
  <li class="navliul navbargrayjquery"><a class="navlink" href="index.php?site=termine.php"><div>Termine</div></a></li>
</ul>