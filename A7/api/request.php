<?php
require_once("../inc/Request.php");
require_once("../inc/require_authentication.php");

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
	if (is_null($_SERVER['PATH_INFO'])) {
		$hasAddressFrom = false;
		if (!is_null($_GET['addressFrom-isUNC']) || !is_null($_GET['addressFrom-addressLine']) ||
		    !is_null($_GET['addressFrom-city']) || !is_null($_GET['addressFrom-state']) ||
		    !is_null($_GET['addressFrom-radius']))
			$hasAddressFrom = true;

		$hasAddressTo = false;
		if (!is_null($_GET['addressTo-isUNC']) || !is_null($_GET['addressTo-addressLine']) ||
		    !is_null($_GET['addressTo-city']) || !is_null($_GET['addressTo-state']) ||
		    !is_null($_GET['addressTo-radius']))
			$hasAddressTo = true;

		if (($hasAddressFrom && (is_null($_GET['addressFrom-isUNC']) || is_null($_GET['addressFrom-addressLine']) ||
		    is_null($_GET['addressFrom-city']) || is_null($_GET['addressFrom-state']) ||
		    is_null($_GET['addressFrom-radius']))) ||
		    ($hasAddressTo && (is_null($_GET['addressTo-isUNC']) || is_null($_GET['addressTo-addressLine']) ||
		    is_null($_GET['addressTo-city']) || is_null($_GET['addressTo-state']) ||
		    is_null($_GET['addressTo-radius'])))) {
			header("HTTP/1.1 400 Bad Request");
			print("Request parameter was missing.");
			exit();
		}

		if (($hasAddressFrom && !is_numeric($_GET['addressFrom-radius'])) ||
			($hasAddressTo && !is_numeric($_GET['addressTo-radius']))) {
			header("HTTP/1.1 400 Bad Request");
			print("Request parameter was invalid.");
			exit();
		}

		$addressFrom = null;
		if ($hasAddressFrom) {
			$addressFrom = Address::getOrCreate($_GET['addressFrom-isUNC'], $_GET['addressFrom-addressLine'],
							$_GET['addressFrom-city'], $_GET['addressFrom-state']);
			if (is_null($addressFrom)) {
				header("HTTP/1.1 400 Bad Request");
				print("Request parameter was invalid.");
				exit();
			}
		}

		$addressTo = null;
		if ($hasAddressTo) {
			$addressTo = Address::getOrCreate($_GET['addressTo-isUNC'], $_GET['addressTo-addressLine'],
							$_GET['addressTo-city'], $_GET['addressTo-state']);
			if (is_null($addressTo)) {
				header("HTTP/1.1 400 Bad Request");
				print("Request parameter was invalid.");
				exit();
			}
		}

		header("Content-type: application/json");
		$result = Request::getEncodedRequestsBySearch($addressFrom, $hasAddressFrom ? intval($_GET['addressFrom-radius']) : null,
							$addressTo, $hasAddressTo ? intval($_GET['addressTo-radius']) : null,
							$_GET['date'], $_GET['isMorning']);

		if (is_null($result))
			print(json_encode(false));
		else
			print(json_encode($result));

		exit();
	} else {
		if (!is_numeric(substr($_SERVER['PATH_INFO'], 1))) {
			header("HTTP/1.1 400 Bad Request");
			print("Invalid ID");
			exit();
		}

		$request = Request::getById(intval(substr($_SERVER['PATH_INFO'], 1)));
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
} 
 else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$request = null;
	if (!is_null($_SERVER['PATH_INFO'])) {
		if (!is_numeric(substr($_SERVER['PATH_INFO'], 1))) {
			header("HTTP/1.1 400 Bad Request");
			print("Invalid ID");
			exit();
		}

		$request = Request::getById(intval(substr($_SERVER['PATH_INFO'], 1)));
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
	    is_null($_POST['addressTo-isUNC']) || is_null($_POST['addressTo-addressLine']) ||
	    is_null($_POST['addressTo-city']) || is_null($_POST['addressTo-state']) ||
	    is_null($_POST['date']) || is_null($_POST['isMorning'])) {
		header("HTTP/1.1 400 Bad Request");
		print("Request parameter was missing.");
		exit();
	}

	$addressFrom = Address::getOrCreate($_POST['addressFrom-isUNC'], $_POST['addressFrom-addressLine'],
					$_POST['addressFrom-city'], $_POST['addressFrom-state']);
	if (is_null($addressFrom)) {
		header("HTTP/1.1 400 Bad Request");
		print("Request parameter was invalid.");
		exit();
	}

	$addressTo = Address::getOrCreate($_POST['addressTo-isUNC'], $_POST['addressTo-addressLine'],
					$_POST['addressTo-city'], $_POST['addressTo-state']);

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
		header("Content-type: application/json");
		if ($request->update($addressFrom, $addressTo, $_POST['date'], $_POST['isMorning']))
			print(json_encode(true));
		else
			print(json_encode(false));
		exit();
	}
}

header("HTTP/1.1 400 Bad Request");
print("URL did not match any known method.");

?>
