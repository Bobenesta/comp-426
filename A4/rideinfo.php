<!DOCTYPE HTML>
<html lang=en>
<head>
	<meta charset=utf-8>
	<title>My request</title>

	<link rel="stylesheet" href="style.css" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
	<script type="text/javascript" src="userinfo.js"></script>
	<script type="text/javascript" src="request.js"></script>
	<script type="text/javascript">
	$(document).ready(function(){
    Request.display(1);});
	</script>
</head>
</head>

<body>
	<?php require("header.php"); ?>

	<div id="content">
		<div class="content-box">
			<div id="all-without-map">
				<div id="my-request">
					To: <span id="rideinfo-to"></span>
					<br>
					From: <span id="rideinfo-from"></span>
					<br>
					<div id="rideinfo-driver-info">
						Name of the driver: <span id="rideinfo-name"></span>
						<a href="#">See full profile</a>(rated ...)
					</div>
					Estimated travel time: <span id="rideinfo-time"></span>
					<br>
					Estimated travel cost: <span id="rideinfo-cost"></span>
					<br>
					Car capacity/description: <span id="rideinfo-car-type"></span>
					<br>
					<span id="rideinfo-space-left"></span>
				</div>
				<div id="validate-my-spot">
					<form name="my-information" method="POST" action="#">
						<div id="let-a-message">
							<textarea rows="4" cols="50">Enter your message here...</textarea> 
						</div>
						<div id="show-my-phone">
							<input type="checkbox" name="visible-phone" value="phone">Show my phone # to...
						</div>
						<div id="request-spot-submit" class="center">
							<button type="submit">Request a spot</button>
						</div>
					</form>
				</div>
			</div>
			<div id="map">
				<iframe width="425" height="350" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://maps.google.com/maps?f=q&amp;source=s_q&amp;hl=en&amp;geocode=&amp;q=unc+chapel+hill&amp;aq=&amp;sll=35.920959,-79.039291&amp;sspn=0.138738,0.338173&amp;vpsrc=6&amp;t=h&amp;ie=UTF8&amp;hq=unc+chapel+hill&amp;hnear=&amp;radius=15000&amp;ll=35.92103,-79.039421&amp;spn=0.071946,0.071946&amp;output=embed"></iframe>
				<br>
				<small><a href="http://maps.google.com/maps?f=q&amp;source=embed&amp;hl=en&amp;geocode=&amp;q=unc+chapel+hill&amp;aq=&amp;sll=35.920959,-79.039291&amp;sspn=0.138738,0.338173&amp;vpsrc=6&amp;t=h&amp;ie=UTF8&amp;hq=unc+chapel+hill&amp;hnear=&amp;radius=15000&amp;ll=35.92103,-79.039421&amp;spn=0.071946,0.071946" style="color:#0000FF;text-align:left">View Larger Map</a></small>
			</div>
		</div>
	</div>

	<?php require("footer.php"); ?>
</body>
</html>
