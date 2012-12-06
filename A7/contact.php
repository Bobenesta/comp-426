<?php require_once("inc/redirect_authenticated.php"); ?>

<!DOCTYPE html>
<html>
<head>
	<meta charset=utf-8>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
	<script src="contactmap.js"></script>
	<link rel="stylesheet" href="style.css"/>
	<link rel="stylesheet" href="contactstyle.css"/>
	<style>
		.hide{
			display:none;
		}
	</style>
</head>
<body>

<?php require("header.php"); ?>

<div id="content">
	<address>
		Written by <a href="mailto:matt.corallo@uncarpooling.com">Matt Corallo</a>
		, <a href="mailto:jack.wanguncarpooling.com">Jack Wang</a>
		and <a href="mailto:benoit.barge@uncarpooling.com">Benoit Barge</a>.<br/>
		
		<br/>		
		Visit us at:<br/>
		<a href="#">uncarpooling.com</a><br/>
		
		<br/>
		Address:<br/>
		CB# 3175, Brooks Computer Science Building <br/>
		201 S Columbia St. <br/>
		UNC-CH <br/>
		Chapel Hill, NC 27599-3175 <br/>
		USA<br/>
		<button id="displayMapButton">Display on map</button>
		<br/>
		<div id="map" class="hide">
		<iframe width="425" height="350" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?f=q&amp;source=s_q&amp;hl=en&amp;geocode=&amp;q=sitterson+hall+unc&amp;aq=&amp;sll=35.930848,-79.031288&amp;sspn=0.596021,1.571045&amp;t=h&amp;ie=UTF8&amp;hq=&amp;hnear=Sitterson+Hall,+Chapel+Hill,+North+Carolina+27514&amp;z=14&amp;iwloc=A&amp;output=embed"></iframe><br /><small><a href="https://maps.google.com/maps?f=q&amp;source=embed&amp;hl=en&amp;geocode=&amp;q=sitterson+hall+unc&amp;aq=&amp;sll=35.930848,-79.031288&amp;sspn=0.596021,1.571045&amp;t=h&amp;ie=UTF8&amp;hq=&amp;hnear=Sitterson+Hall,+Chapel+Hill,+North+Carolina+27514&amp;z=14&amp;iwloc=A" style="color:#0000FF;text-align:left">View Larger Map</a></small>
		</div>
	</address>
</div>

<?php require("footer.php"); ?>

</body>
</html>