<?php
require_once("inc/authentication.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST' &&
    !is_null($_POST['userName']) && !is_null($_POST['passwordHash'])) {
	$newUserId = authenticate($_POST['userName'], $_POST['passwordHash']);

	if ($newUserId == 0) {
		header("HTTP/1.1 404 Not Found");
		print("The given user name was not found (or there was a DB error).");
		exit();
	} else if ($newUserId == -1) {
		header("HTTP/1.1 403 Forbidden");
		print("The given password has was incorrect.");
		exit();
	}

	print("Successfully logged in as user " . $_POST['userName'] . " with ID " . $newUserId);
	exit();
} else {
	header("Content-type: application/json");
	print(json_encode(generateSalt()));
}

?>
