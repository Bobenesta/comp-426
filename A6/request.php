<?php
require_once("inc/Request.php");

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
	$request = null;
	if (!is_null($_SERVER['PATH_INFO'])) {
		//TODO sanity check $id
		$id = intval(substr($_SERVER['PATH_INFO']));//TODO substr?
		$request = Request::getById($id);
		if (is_null($request)) {
			header("HTTP/1.1 404 Not Found");
			print("The request was not found");
			exit();
		}

		if ($userIdLoggedIn != $request->getUserId()) {
			header("HTTP/1.1 403 Forbidden");
			print("This request does not belong to you!");
			exit();	
		}
	}

	if (!is_null($_POST['delete'])) {
		if (is_null($request)) {
			header("HTTP/1.1 400 Bad Request");
			print("Request parameter was missing.");
			exit();
		}

		$request->delete();

		header("Content-type: application/json");
		print(json_encode(true));
		exit();
	}

	if (is_null($_POST['addressFrom-isUNC']) || is_null($_POST['addressFrom-addressLine']) ||
	    is_null($_POST['addressFrom-city']) || is_null($_POST['addressFrom-state']) ||
	    is_null($_POST['addressFrom-radius']) ||
	    is_null($_POST['addressTo-isUNC']) || is_null($_POST['addressTo-addressLine']) ||
	    is_null($_POST['addressTo-city']) || is_null($_POST['addressTo-state']) ||
	    is_null($_POST['addressTo-radius']) ||
	    is_null($_POST['date']) || is_null($_POST['isMorning'])) {
		header("HTTP/1.1 400 Bad Request");
		print("Request parameter was missing.");
		exit();
	}

	$addressFrom = Address::getOrCreate($_POST['addressFrom-isUNC'],
					$_POST['addressFrom-addressLine'], $_POST['addressFrom-city'],
					$_POST['addressFrom-state'], $_POST['addressFrom-radius']);
	if (is_null($addressFrom)) {
		header("HTTP/1.1 400 Bad Request");
		print("Request parameter was invalid.");
		exit();
	}

	$addressTo = Address::getOrCreate($_POST['addressTo-isUNC'],
					$_POST['addressTo-addressLine'], $_POST['addressTo-city'],
					$_POST['addressTo-state'], $_POST['addressTo-radius']);

	if (is_null($addressTo)) {
		header("HTTP/1.1 400 Bad Request");
		print("Request parameter was invalid.");
		exit();
	}

	if (is_null($_SERVER['PATH_INFO'])) {
		$request = Request::create($addressFrom, $addressTo, $userIdLoggedIn,
						$_POST['date'], $_POST['isMorning']);
		if (is_null($request)) {
			header("HTTP/1.1 400 Bad Request");
			print("Request parameter was invalid.");
			exit();
		}

		header("Content-type: application/json");
		print(json_encode($request->getJSON()));
		exit();
	} else {
		$request->update($addressFrom, $addressTo, $_POST['date'], $_POST['isMorning']);

		header("Content-type: application/json");
		print(json_encode(true));
		exit();
	}
}

header("HTTP/1.1 400 Bad Request");
print("URL did not match any known method.");

?>
