<!DOCTYPE HTML>
<html lang=en>
<head>
	<meta charset=utf-8>
	<title>Find A Ride</title>

	<link rel="stylesheet" href="style.css" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
	<script type="text/javascript" src="userinfo.js"></script>
	<script type="text/javascript" src="searchhelper.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			var emptySearchQuery = new SearchQuery(new Address(), new Address(), "07/07/2012", false);
			emptySearchQuery.placeResultSetInTable($('#findaride-table'));

			SearchQuery.fillSelectorWithMyRequests($('#myRequests'));
		});
	</script>
</head>

<body>
	<?php require("header.php"); ?>

	<div id="content">
	<div class="content-box">
		<div id="findaride-search">
			<form name="findaride" method="POST" action="#">
			<div id="findaride-search-standard">
				<!-- Steal styles from login to avoid useless duplication-->
				<div id="findaride-search-standard-start">
					Start at:&nbsp;&nbsp;<select id="start-type"><option value="unc">UNC</option><option value="other">Manual Entry</option></select>

					<div class="form-text-entry"><div class="form-text-box-label">Address:</div>
					<input name="address" id="start-address-textbox" class="form-text-box" maxlength="50"></div>

					<div class="form-text-entry"><div class="form-text-box-label">City, State:</div>
					<input name="citystate" id="start-citystate-textbox" class="form-text-box" maxlength="50"></div>

					Start within: <select id="start-within"><option value="10">10 Miles</option><option value="20">20 Miles</option><option value="50">50 Miles</option></select>
				</div>
				<div id="findaride-search-standard-dest">
					End at:&nbsp;&nbsp;&nbsp;<select id="dest-type"><option value="unc">UNC</option><option value="other">Manual Entry</option></select>

					<div class="form-text-entry"><div class="form-text-box-label">Address:</div>
					<input name="address" id="dest-address-textbox" class="form-text-box" maxlength="50"></div>

					<div class="form-text-entry"><div class="form-text-box-label">City, State:</div>
					<input name="citystate" id="dest-citystate-textbox" class="form-text-box" maxlength="50"></div>

					End within: <select id="dest-within"><option value="10">10 Miles</option><option value="20">20 Miles</option><option value="50">50 Miles</option></select>
				</div>
				<div id="findaride-search-standard-datetime">
					<div class="form-text-entry"><div class="form-text-box-label">Date:</div>
					<input type="date" name="date" id="datebox" class="form-text-box"></div>

					Time:&nbsp;&nbsp;&nbsp;&nbsp;<select id="time-selector"><option value="morning">Morning</option><option value="afternoon">Afternoon</option></select>
				</div>
			</div>
			<br>
			<div id="findaride-search-bymyrequests">
				Or...search by my request:
				<select id="myRequests" onchange="SearchQuery.updateForms($('#myRequests'), $('#findaride-search'))"></select>
			</div>
			<br>
			<div id="findaride-search-submit" class="center">
				<button type="submit">Update Results</button>
			</div>
			</form>
		</div>
		<table id="findaride-table">
			<tr>
				<th>From</th>
				<th>To</th>
				<th>When</th>
				<th>User</th>
			</tr>
		</table>		
	</div>
	</div>

	<?php require("footer.php"); ?>
</body>
</html>
