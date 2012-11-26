<?php
require_once("mysql_settings.php");

// session is valid on *.cs.unc.edu/Courses/comp426-f12/corallo when using https for 1 hour
session_set_cookie_params(3600, "/Courses/comp426-f12/corallo", ".cs.unc.edu", TRUE);
session_start();

$userIdLoggedIn = 0;
if (!is_null($_SESSION['userIdAuthenticated']))
	$userIdLoggedIn = $_SESSION['userIdAuthenticated'];

function authenticate($userName, $passwordHash) {
	$mysqli = getDBConnection();
	$result = $mysqli->query("SELECT id, passwordHash FROM users WHERE userName = '" .
					$mysqli->real_escape_string($userName) . "'");
	if ($result) {
		if ($result->num_rows == 0)
			return 0;

		$row = $result->fetch_assoc();

		// Though not explicity stated, $passwordHash should be
		// hex(sha256(password + "gEp3XuY9r7ajWxSIG7mW04PHlL9JxqXhhVs"))
		// eg password1 becomes b9e108874aa0b938cb42d07fca687cbad465175713d62447e1371f2854edf7f2

		if (hash("sha256", $passwordHash . "35dlLfZQpUyLJf9KBARov85GvtlHkhwTin8", true) == $row['passwordHash']) {
			$userIdLoggedIn = intval($row['id']);
			$_SESSION['userIdAuthenticated'] = $userIdLoggedIn;
			return $userIdLoggedIn;
		} else {
			return -1;
		}
	}
	return 0;
}

?>
