<?php
require_once("../inc/require_authentication.php");
require_once("../inc/Request.php");
require_once("../inc/Ride.php");

class Profile{
	private $name;
	private $requests;
	private $rides;
	
	private function __construct($name, $requests, $rides) {
		$this->name = $name;
		$this->requests = $requests;
		$this->rides = $rides;
	}
	
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

public function getRequestByUserId($userid){
	$mysqli = getDBConnection();
	$result = $mysqli->query("SELECT * FROM requests WHERE userId = '" . $id . "'");
	
	if ($row = $result->fetch_assoc()) {
		return Request::getById($row['userid']);
    }

}

public function getOfferByUserId($userid){
	$mysqli = getDBConnection();
	$result = $mysqli->query("SELECT * FROM rides WHERE userId = '" . $id . "'");
	
	if ($row = $result->fetch_assoc()) {
		return Ride::getById($row['userId']);
    }
	
}

public static function create($id){
	$name= getNameById($id);
	if(is_null($name)){
		return null;
	}
	$request= getRequestByUserId($id);
	if(is_null($request)){
		return null;
	}
	$ride= getOfferByUserId($id);
	if(is_null($ride)){
		return null;
	}
	return new Profile($name, $request, $ride);
}

public static function getJSON(){
	$ret= array();
		$ret['name'] = $this->name;
		$ret['requests'] = $this->requests->getJSON();
		$ret['rides'] = $this->rides->getJSON();
		return $ret;
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