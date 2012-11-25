<?php

function getDBConnection() {
	$mysqli = new mysqli("classroom.cs.unc.edu", "corallo", "z3tnx5dJd8pJ9KfH", //TODO DBNAME);
	return $mysqli;
}

?>
