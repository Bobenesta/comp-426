<?php require_once("inc/redirect_unauthenticated.php"); ?>

<!DOCTYPE HTML>
<html lang=en>
<head>
	<meta charset=utf-8>
	<title>Offer Your Car</title>

	<link rel="stylesheet" href="style.css" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
	<!-- TODO Need jquery and others? -->
</head>

<body>
	<?php require("header.php"); ?>

	<div id="content">
	<div class="content-box">
		<div id="findaride-search">
			<form name="findaride" id="findaride-form" method="POST" action="#">
			<div id="findaride-search-standard">
				<!-- Steal styles from login and findaride to avoid useless duplication-->
				<div id="findaride-search-standard-start">
					Start at:&nbsp;&nbsp;<select id="start-type"><option value="unc">UNC</option><option value="other">Manual Entry</option></select>

					<div class="form-text-entry"><div class="form-text-box-label">Address:</div>
					<input name="address" id="start-address-textbox" class="form-text-box" maxlength="50"></div>

					<div class="form-text-entry"><div class="form-text-box-label">City, State:</div>
					<input name="citystate" id="start-city-textbox" class="form-text-box" maxlength="40">
					<input name="citystate" id="start-state-textbox" class="form-text-box" maxlength="10"></div>

					Start within: <select id="start-within"><option value="10">10 Miles</option><option value="20">20 Miles</option><option value="50">50 Miles</option></select>
				</div>
				<div id="findaride-search-standard-dest">
					End at:&nbsp;&nbsp;&nbsp;<select id="dest-type"><option value="unc">UNC</option><option value="other">Manual Entry</option></select>

					<div class="form-text-entry"><div class="form-text-box-label">Address:</div>
					<input name="address" id="dest-address-textbox" class="form-text-box" maxlength="50"></div>

					<div class="form-text-entry"><div class="form-text-box-label">City, State:</div>
					<input name="citystate" id="dest-city-textbox" class="form-text-box" maxlength="40">
					<input name="citystate" id="dest-state-textbox" class="form-text-box" maxlength="10"></div>

					End within: <select id="dest-within"><option value="10">10 Miles</option><option value="20">20 Miles</option><option value="50">50 Miles</option></select>
				</div>
				<div id="findaride-search-standard-datetime">
					<div class="form-text-entry"><div class="form-text-box-label">Date:</div>
					<input type="date" name="date" id="datebox" class="form-text-box"></div>

					Time:&nbsp;&nbsp;&nbsp;&nbsp;<select id="time-selector"><option value="morning">Morning</option><option value="afternoon">Afternoon</option></select>
				</div>
			</div>
			<br>
			<div id="findaride-search-submit" class="center">
				<button type="submit">Offer your car!</button>
			</div>
			</form>
		</div>		
	</div>
	</div>

	<?php require("footer.php"); ?>
</body>
</html>
