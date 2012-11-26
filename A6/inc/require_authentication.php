<?php
require_once("authentication.php");

if ($userIdLoggedIn == 0) {
	header("HTTP/1.1 401 Authentication Required");
	print("You must be authenticated to make this request");
	exit();
}

?>
