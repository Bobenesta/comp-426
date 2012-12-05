<?php
require_once("../inc/require_authentication.php");

$id= $_GET['profileId'];
$name= "";
		$mysqli = getDBConnection();
		$result = $mysqli->query("SELECT * FROM users WHERE id = '" . $id . "'");
		if ($result) {
			if ($result->num_rows == 0)
				return null;

			$row = $result->fetch_assoc();

			$name= $row['userName'];
		}
echo($name);
?>