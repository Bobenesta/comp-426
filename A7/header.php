	<script type="text/javascript" src="authentication.js"></script>
	<script type="text/javascript" src="sha256.js"></script>

	<header>
		<img id="logo" src="images/uncarpooling_logo.jpg" alt="uncarpooling_logo"/>
		<h1>UNCarpooling</h1>
		<img id="unc_logo" src="images/unc_logo.jpg" alt="unc_logo"/>
		<p id="header-subtitle">New cars, new friends, new concept...</p><br>
<?php
require_once("inc/authentication.php");

if ($userIdLoggedIn != 0) {
?>
		<div id="user-bar">
			Hello <?php echo(getUserNameLoggedIn()); ?>!&nbsp;&nbsp;&nbsp;
			<a href="">Preferences</a>&nbsp;&nbsp;&nbsp;
			<a href="#" onclick="logout();">Logout</a>
		</div>
	</header>

	<div id="push-footer-down">
	<nav>
		<div><a href="findaride.php">Find a Ride</a></div><br>
		<div><a href="offercar.php">Offer Your Car</a></div><br>
		<div><a href="#">See Requests</a></div><br>
		<div><a href="#">About Us</a></div>
	</nav>
<?php
} else {
?>
		<div id="user-bar">
			<a href="#" onclick="if (document.getElementById('login-box').style.visibility == 'hidden'){document.getElementById('login-box').style.visibility = 'visible';}else {document.getElementById('login-box').style.visibility = 'hidden';}"
				>Login</a>
		</div>
	</header>

	<div id="login-box" style="visibility: hidden;"> <!--Inline style required for JS to work the first time-->
		<form id="login-form">
			<div class="form-text-entry"><div class="form-text-box-label">Username:</div>
			<input name="username" id="login-username" class="form-text-box" maxlength="50"></div>
			<div class="form-text-entry"><div class="form-text-box-label">Password:</div>
			<input name="password" id="login-password" class="form-text-box" type="password" maxlength="512"></div>

			<!--<input type="checkbox" name="remember-pass">Remember Password
			<br>-->
			<div class="center"><button type="submit">Login</button></div>
		</form>
	</div>

	<script type="text/javascript">
		$("#login-form").submit(function(event) {
			event.preventDefault();
			event.stopPropagation();
			login($("#login-username").val(), $("#login-password").val());
		});
	</script>
<?php
}
?>
