<?php
require_once("authentication.php");

if ($userIdLoggedIn == 0) {
	header("HTTP/1.1 403 Forbidden"); // Should be 401, but that is only for WWW-Authenticate
	print("You must be authenticated to make this request");
	exit();
}

?>
