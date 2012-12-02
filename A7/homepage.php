<!DOCTYPE HTML>
<html lang=en>
<head>
	<meta charset=utf-8>
	<title>First assignment comp 426 - carpooling web application</title>

	<link rel="stylesheet" href="style.css"/>
</head>

<body>
	<?php require("header.php"); ?>

	<div id="push-footer-down">

	<!-- Override the background/border as it is replaced with two boxes instead -->
	<div id="content" style="border: 0px; background-color: white;">
		<div id="main-left" class="content-box">
			<h1>Welcome!<br>!<br>!<br>!<br>!<br>!<br>!<br></h1>
		</div>
		<div id="main-signup" class="content-box">
			<form id="signup-form" name="signup" method="POST" action="?">
				<H2>Sign Up</H2>
				<!-- Steal styles from login to avoid useless duplication-->
				<div class="form-text-entry"><div class="form-text-box-label">Username:</div>
				<input name="username" id="signup-username" class="form-text-box" maxlength="50" size=45></div>
				<div class="form-text-entry"><div class="form-text-box-label">Password:</div>
				<input name="password" id="signup-password" class="form-text-box" type="password" maxlength="512" size=45></div>
				<div class="form-text-entry"><div class="form-text-box-label">Email:</div>
				<input name="email" id="signup-email" class="form-text-box" maxlength="512" size=50></div>
				<div class="center"><button type="submit" id="signup-submit">Sign Up</button></div>
			</form>
		</div>
	</div>
	</div>

	<footer class="center">
		<a href="?">Contact Us</a>&nbsp;&nbsp;&nbsp;
		<a href="?">TOS</a>
	</footer>
</body>
</html>
