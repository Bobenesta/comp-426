<?php
require_once("../inc/require_authentication.php");

$id= $_GET['profileId'];
echo(getById($id)+"'s profile");

public static function getById($id) {
		$mysqli = getDBConnection();
		$result = $mysqli->query("SELECT * FROM users WHERE id = '" . $id . "'");
		if ($result) {
			if ($result->num_rows == 0)
				return null;

			$row = $result->fetch_assoc();

			return $row['userName']);
		}
		return null;
	}


?>