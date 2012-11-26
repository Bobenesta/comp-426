<?php

function getDBConnection() {
	$mysqli = new mysqli("classroom.cs.unc.edu", "corallo", "z3tnx5dJd8pJ9KfH", //TODO DBNAME);
	if ($mysqli)
		return $mysqli;

	header("HTTP/1.1 500 Internal Server Error");
	print("Could not connect to database");
	exit();
}

?>
