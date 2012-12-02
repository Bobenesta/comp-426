<?php
require_once("authentication.php");

if ($userIdLoggedIn != 0) {
	header("HTTP/1.1 307 Temporary Redirect");
	header("Location: landing.php");
	print("You must be unauthenticated for this page, redirecting to landing");
	exit();
}

?>
