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


 echo("<tr><td>From</td><td>To</td><td>date</td></tr>");
if ($result = $mysqli->query("SELECT * FROM requests WHERE uesrId = '" . $id . "'")) {
echo $result->num_rows;
    /* fetch object array */
    while ($row = $result->fetch_assoc()) {
    	$from= $mysqli->query("SELECT addressLine FROM addresses WHERE id = '" . $row['addressFrom'] . "'");
		$to= $mysqli->query("SELECT addressLine FROM addresses WHERE id = '" . $row['addressTo'] . "'");
		$date= $row['date'];
        echo("<tr><td>".$from."</td><td>".$to."</td><td>".$date."</td></tr>");
    }

    /* free result set */
    $result->close();
}
?>