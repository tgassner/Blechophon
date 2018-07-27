<?php
header("Content-Type: text/html;charset=UTF-8");
session_start();
include('include/blechophon.inc.php');

$title = "Blechophon";

$siteQualifier = "";
if (isset($_GET['s'])) {
    $siteQualifier = htmlentities($_GET['s']);
}
 
switch ($siteQualifier) {
    case "legal" : 
        $site = "impressum.php";
        $title = "Blechophon - Impressum";
        break;
    case "fotos" : 
        $site = "fotomenu.php";
        $title = "Blechophon - Fotos";
        break;
    case "fotoviewer" : 
        $site = "fotoviewer.php";
        $title = "Blechophon - Fotos";
        break;
    case "videos" : 
        $site = "videomenu.php";
        $title = "Blechophon - Videos";
        break;
    case "videoviewer" : 
        $site = "videoviewer.php";
        $title = "Blechophon - Videos";
        break;
    case "musiker" : 
        $site = "musiker.php";
        $title = "Blechophon - Musiker";
        break;
    case "aktuelles" : 
        $site = "aktuelles.php";
        $title = "Blechophon - Aktuelles - Facebook";
        break;
    default :
        $site = "main.php";
        $siteQualifier = "main";
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Ing. Thomas Gassner, M.Sc.">
    <meta name="keywords" content="Blechophon, Blasmusik, Trompete, Tenorhorn, Tuba, Schlagzeug, Ensemble">
    <meta NAME="robots" CONTENT="index,follow">
    <meta NAME="language" CONTENT="de">
    <meta name="description" content="Blechophon - Blechbläser Ensemble" >
    <link rel="icon" href="images/favicon.png" type="image/png">
    <title><?php echo($title); ?></title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/custom.css?rid=<?php echo(time()); ?>" rel="stylesheet">
    <script src="js/jquery-3.2.1.min.js"></script>

    <!-- Custom Fonts from Google -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>
    
</head>

<body>

<!--
<div id='BruckMuehle' style="position: fixed; height:160px; z-index:999999; width: 100%; top: 52px; text-align: center;">
    <a href="http://www.bruckmuehle.at/?id=82&event=3637" target="_blank"><img src="images/Bruckmuehle_Werbebild02.jpg" alt="" style="height: 120px"></a>
</div>
-->

    <!-- Navigation -->
    <nav id="siteNav" class="navbar navbar-default navbar-fixed-top affix" role="navigation">
        <div class="container">
            <!-- Logo and responsive toggle -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="?s=home">
                	<span class="glyphicon glyphicon-music"></span> 
                	Blechophon
                </a>
            </div>
            <!-- Navbar links -->
            <div class="collapse navbar-collapse" id="navbar">
                <ul class="nav navbar-nav navbar-right">
                    <li class="active">
                        <a href="?s=home">Home</a>
                    </li>
                    <li>
                        <a href="?s=aktuelles">Aktuelles</a>
                    </li>
                    <li>
                        <a href="?s=home#ueberUns">Über uns</a>
                    </li>
                    <li>
                        <a href="?s=home#termine">Termine</a>
                    </li>
                    <li>
                        <a href="?s=musiker">Musiker</a>
                    </li>
                    <li>
                        <a href="?s=fotos">Fotos</a>
                    </li>
                    <li>
                        <a href="?s=videos">Videos</a>
                    </li>
                    <li>
                        <a href="?s=home#links">Links</a>
                    </li>
                    <li>
                        <a href="?s=home#references">Referenzen</a>
                    </li>
                    <li>
                        <a href="?s=home#kontakt">Kontakt</a>
                    </li>
                </ul>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container -->
    </nav>

<?php include($site); ?>

	
    
	<!-- Footer -->
    <footer class="page-footer" id="kontakt" name="kontakt">
    
    	<!-- Contact Us -->
        <div class="contact">
        	<div class="container">
                <div class="row">
                    <div class="col-sm-3" style="margin-top:50px">
                        <h2 class="section-heading">Contact Us</h2>
                    </div>    
                    <div class="col-sm-3" style="margin-top:40px">
                        <p><span class="glyphicon glyphicon-earphone"></span><br> +43 664 5974144</p>
                    </div>  
                    <div class="col-sm-3" style="margin-top: 40px">
                        <p><span class="glyphicon glyphicon-envelope"></span><br> max.eckert@blechophon.at</p>
                    </div>  
                    <div class="col-sm-3" style="">
                        <p><img class="img-responsive img-circle center-block" src="images/personen/thumbnails/eckertmarkus.jpg" alt="Markus Eckert"></p>
                        <p><span>Markus Eckert</p>
                    </div>            
                </div>
            </div>
        </div>
        	
        <!-- Copyright etc -->
        <div class="small-print">
        	<div class="container">
                <p><a href="?s=legal">imressum</a></p>
        	</div>
        </div>
        
    </footer>

    <!-- jQuery -->
    <script src="js/jquery-1.11.3.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    <!-- Plugin JavaScript -->
    <script src="js/jquery.easing.min.js"></script>
    
    <!-- Custom Javascript -->
    <script type="text/javascript">
        <?php include("js/custom.js"); ?>

        // Offset for Site Navigation
        <?php if ($siteQualifier == "main") { ?>
        $('#siteNav').affix({
            offset: {
                top: 100
            }
        })
        <?php } ?>


    </script>

</body>

</html>
