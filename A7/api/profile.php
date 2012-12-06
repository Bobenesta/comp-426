<?php
require_once("../inc/require_authentication.php");
require_once("../inc/Profile.php");

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
	$profileId= $_GET['profileId'];
	if(is_null($profileId) || !is_numeric($profileId)){
		header("HTTP/1.1 400 Bad Request");
		print("Request parameter was invalid.");
		exit();
	}
	else{
		$profile= Profile::getById($profileId);
		if ($profile == null) {
			header("HTTP/1.1 404 Not Found");
			print("The ride was not found");
			exit();
		}
		header("Content-type: application/json");
		print(json_encode($profile->getJSON()));
		exit();
	}
}

header("HTTP/1.1 400 Bad Request");
print("URL did not match any known method.");

?>
