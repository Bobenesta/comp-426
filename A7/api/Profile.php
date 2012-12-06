<?php
require_once("../inc/require_authentication.php");
require_once("../inc/Profile.php");

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
	$profileId= $_GET['profileId'];
	if(is_null($profileId)){
				header("HTTP/1.1 400 Bad Request");
				print("Request parameter was invalid.");
				exit();
				
	}
	else{
		header("Content-type: application/json");
		$profile= Profile::create($profileId);
		print(json_encode($profile->getJSON()));
		exit();
	}
}

header("HTTP/1.1 400 Bad Request");
print("URL did not match any known method.");

?>