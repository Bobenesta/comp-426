<?php
require_once("../inc/require_authentication.php");
require_once("../inc/Request.php");
require_once("../inc/Ride.php");

class Profile{
	private $name;
	private $rating;
	
	private function __construct($name, $rating) {
		$this->name = $name;
		$this->rating = $rating;
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

public function getRating($id){
	$name= "";
		$mysqli = getDBConnection();
		$result = $mysqli->query("SELECT * FROM rating WHERE userId = '" . $id . "'");
		if ($result) {
			if ($result->num_rows == 0)
				return null;

			$row = $result->fetch_assoc();

			$name= $row['rating'];
			return $name;
		}
		return null;
}


public static function create($id){
	$name= getNameById($id);
	if(is_null($name)){
		return null;
	}
	$rating= getRatingByUserId($id);
	if(is_null($rating)){
		return null;
	}
	return new Profile($name, $rating);
}

public static function getJSON(){
	$ret= array();
		$ret['name'] = $this->name;
		$ret['rating'] = $this->rating;
		return $ret;
}
}

?>