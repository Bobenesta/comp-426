<?php
require_once("inc/Request.php");

//TODO: If no user is logged in, fail here
//TODO this whole block can probably move to a inc/require_auth.php page
if (false) {
	header("HTTP/1.1 401 Authentication Required");//TODO there is a better method for http code (lots of these...)
	print("You must log in to use make requests");
	exit();
}
//TODO: do auth and set this in inc/...php
$userIdLoggedIn = 0;

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
	if (is_null($_SERVER['PATH_INFO']) {
		//TODO: Search
	} else {
		//TODO sanity check $id
		$id = intval(substr($_SERVER['PATH_INFO']));//TODO substr?
		$request = Request::getById($id);
		if (is_null($request)) {
			header("HTTP/1.1 404 Not Found");
			print("The request was not found");
			exit();
		} else {
			header("Content-type: application/json");
			print(json_encode($request->getJSON()));
			exit();
		}
	}
} else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	if (is_null($_SERVER['PATH_INFO'])) {
		if (is_null($_POST['addressFrom']) || is_null($_POST['addressTo']) ||
		    is_null($_POST['date']) || is_null($_POST['isMorning'])) {
			header("HTTP/1.1 403 Forbidden");
			print("This request does not belong to you!");
			exit();
		}
		$request = Request::create($_POST['addressFrom'], $_POST['addressTo'],
					$userIdLoggedIn, $_POST['date'], $_POST['isMorning']);
		if (is_null($request)) {
			header("HTTP/1.1 400 Bad Request");
			print("Request parameter was invalid.");
			exit();
		}
		header("Content-type: application/json");
		print(json_encode($request->getJSON()));
		exit();
	} else {
		//TODO sanity check $id
		$id = intval(substr($_SERVER['PATH_INFO']));//TODO substr?
		$request = Request::getById($id);
		if (is_null($request)) {
			header("HTTP/1.1 404 Not Found");
			print("The request was not found");
			exit();
		}
		if (is_null($_POST['delete'])) {
			if (is_null($_POST['addressFrom']) || is_null($_POST['addressTo']) ||
			    is_null($_POST['date']) || is_null($_POST['isMorning'])) {
			$request = Request::create($_POST['addressFrom'], $_POST['addressTo'],
						$userIdLoggedIn, $_POST['date'], $_POST['isMorning']);
			if (is_null($request)) {
				header("HTTP/1.1 400 Bad Request");
				print("Request parameter was invalid.");
				exit();
			}
			header("Content-type: application/json");
			print(json_encode($request->getJSON()));
			exit();
		}
		} else {
			if ($userIdLoggedIn != $request->getUserId()) {
				header("HTTP/1.1 403 Forbidden");
				print("This request does not belong to you!");
				exit();	
			}
			$request->delete();
			header("Content-type: application/json");
			print(json_encode(true));
			exit();
		}
	}
}

header("HTTP/1.1 400 Bad Request");
print("URL did not match any known method.");

?>
