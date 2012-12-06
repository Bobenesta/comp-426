<?php
require_once("../inc/require_authentication.php");
require_once("../inc/Request.php");
require_once("../inc/Ride.php");

class Profile{
public function getNameById($id){
        $name= "";
		$mysqli = getDBConnection();
		$result = $mysqli->query("SELECT * FROM users WHERE id = '" . $id . "'");
		if ($result) {
			if ($result->num_rows == 0)
				return null;

			$row = $result->fetch_assoc();

			$name= $row['userName'];
			return $name;
		}
		return null;
}

public function getRequestById($id){
return Request::getById($id);
}

public function getOfferById($id){
return Ride::getById($id);
}

}

// 
 // echo("<br><br><tr><td> From </td><td> Rating </td><td> Message </td></tr>");
// if ($result = $mysqli->query("SELECT * FROM ratings WHERE userTo = '" . $id . "'")) {
// echo $result->num_rows;
    // /* fetch object array */
    // while ($row = $result->fetch_assoc()) {
    	// $from= $mysqli->query("SELECT userName FROM users WHERE id = '" . $row['userFrom'] . "'");
		// $rating= $row['rating'];
		// $message= $row['message'];
        // echo("<tr><td>".$from."</td><td>".$rating."</td><td>".$message."</td></tr><br>");
    // }
// 
    // /* free result set */
    // $result->close();
// }

?>