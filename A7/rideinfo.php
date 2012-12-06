<?php require_once("inc/redirect_unauthenticated.php"); ?>

<!DOCTYPE HTML>
<html lang=en>
<head>
	<meta charset=utf-8>
	<title>My request</title>

	<link rel="stylesheet" href="style.css" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
	<script type="text/javascript" src="searchhelper.js"></script>
	<script type="text/javascript" src="userinfo.js"></script>
	<script type="text/javascript" src="request.js"></script>
	<script type="text/javascript">
	$(document).ready(function(){
		Request.display(<?php if (!is_null($_GET['id']))
						print($_GET['id']);
					else
						exit(0);
				?>);});
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
						(rated <span id="rideinfo-rating"></span>/5)
					</div>
					Car capacity: <span id="rideinfo-car-capacity"></span>
					<br>
					Other information: <span id="rideinfo-car-desc"></span>
				</div>
			</div>
			<div id="map"></div>
		</div>
	</div>

	<?php require("footer.php"); ?>
</body>
</html>
