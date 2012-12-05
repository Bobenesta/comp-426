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
echo($name."'s profile");


echo($name+"'s request");
$result = $mysqli->query("SELECT * FROM requests WHERE id = '" . $id . "'");
while($row = $result->fetch_array())
  {
  echo $row['date'];
  echo "<br />";
  }
?>