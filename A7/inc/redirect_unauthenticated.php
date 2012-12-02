<?php
require_once("authentication.php");

if ($userIdLoggedIn == 0) {
	header("HTTP/1.1 307 Temporary Redirect");
	header("Location: homepage.php");
	print("You must be authenticated for this page, redirecting to homepage.php");
	exit();
}

?>
