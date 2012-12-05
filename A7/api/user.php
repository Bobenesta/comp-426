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



  echo $mysqli->query("SELECT addressLine FROM addresses WHERE id = '" . $id . "'")." ".$row['date'];

  
  
  
  $query = "SELECT * FROM requests WHERE uesrId = '" . $id . "'";

if ($result = $mysqli->query($query)) {

    /* fetch object array */
    while ($row = $result->fetch_assoc()) {
        echo $row['date'];
    }

    /* free result set */
    $result->close();
}
?>