* {
	margin: 0;
	padding:0;
}

body {
  padding: 1em;
  font-family: Verdana, Arial, Helvetica, sans-serif; 
  background-color: #333333;
  <?php
    $fontsize="0.8";   
    if (isset($_SESSION['sizeem'])) {
      $fontsize = $_SESSION['sizeem'];
    }
  ?>
  //font-size: 0.8em;
  font-size:<?php echo($fontsize); ?>em;
  color: #000000;
  text-align: center;
  //battle for wesnoff
}

h1 {
  font-size: 1.7em;  
  color: #000000; 
  text-decoration: none; 
  font-weight: bold; 
  font-style: normal; 
  font-variant: normal; 
  text-align: center;
  margin-bottom:2em;
}

h2 {
  font-size: 1.5em; 
  color: #000000; 
  text-decoration: none; 
  font-weight: bold; 
  font-style: normal; 
  font-variant: normal; 
  text-align: center
}

a {
  color:#000000
}

img {
  border:0em;
}

#navbar {
  width: 9em;
  float: left;
  height: 35em;
  padding-top:1em;
  //border-top: 0.2em solid #ff0000;
  //background: #ff0000;
}

.nav {
  list-style: none;
  margin-left: 1em;
}

#header {
  border-bottom: 0.2em solid #aaa;
  height:5em;
}
#content {
  width: 50em;
  float: right;
  padding-top:1em;
  //border-top: 0.2em solid #00ff00;
  //background: #0000ff;
}

#footer {
  border-top: 0.2em solid #aaa;	
  height:5em;
  clear: both;
}

#wrap{
  width: 59em;
  background: #ffffff;
  border: 0.2em solid #aaa;
  margin: 1em auto;
  text-align: left;
}

.navlink {
  font-family: Verdana, Arial, Helvetica, sans-serif; 
  color: #000000; 
  //padding-left:1em;
  text-decoration: none; 
  font-weight: bold; 
  font-style: normal; 
  font-variant: normal;
}

.navliul {
  margin-top:0.4em;
  padding-left:0.2em;
}

.navbargrayover {
  background-color: #dddddd;
}


#headerlogo {
  position: relative;
  margin: 0.1em;
  text-align: right;
  float: left;
}

#headerheadline {
  //border: 0.2em solid #ff0000;
  position: relative;
  width: 23em;
  margin-top: 1em;
  margin-left: 3em;
  text-align: center;
  color:#000000;
  font-weight: bold;
  font-size:1.6em;
  float: left;
}

#sizeswitchheader {
  //border: 0.2em solid #ffaaaa;
  float: right;
  margin-top: 2.7em;
}

#footercontent {
 position: relative;
 top: 2em;
 color: #666666; 
 font-size:0.9em;
 text-align: left;
 margin-left:1em;
 //border: 0.2em solid #ff0000;
}

.bld {
  font-weight: bold;
}

.tblhead {
  background-color:#D2232A;
  padding-left:0.4em;
  padding-top:0.1em;
  padding-bottom:0.2em;
  font-weight: bold;
}

.contenttable {
  width: 45em;
  margin: 0.1em auto;
}

.personPicThumbnail { margin-top: 0.3em; width:7.77em; hight:9.324em }

#wrap, .tblhead, .navbargrayover {-moz-border-radius:0.7em; -khtml-border-radius:0.7em; border-radius:0.7em;}

.error
{font-size: 10pt; color: #FF0000; text-decoration: none; font-weight: bold; font-style: normal; font-variant: normal}

.successful
{font-size: 10pt; color: #00FF00; text-decoration: none; font-weight: bold; font-style: normal; font-variant: normal}

.buttondelete
{background-color:#FF0000; color:#FFFF00; height:20px; width:60px; font-size:7pt; font-weight:bold;}

.buttonchange
{background-color:#EEEEEE; color:#000000; height:20px; width:60px; font-size:7pt;}

