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
	
	private static function getNameById($id){
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

	private static function getRatingById($id){
		$rating= "";
		$mysqli = getDBConnection();
		$result = $mysqli->query("SELECT AVG(rating) FROM ratings WHERE userId = '" . $id . "'");
		if ($result) {
			if ($result->num_rows == 0)
				return null;

			$row = $result->fetch_assoc();
//TODO
print_r($row);
			$rating= $row['rating'];
			return $rating;
		}
		return null;
	}


	public static function getById($id){
		$name= Profile::getNameById($id);
		if(is_null($name)){
			return null;
		}
		$rating= Profile::getRatingById($id);
		if(is_null($rating))
			return new Profile($name, 0);
		else
			return new Profile($name, $rating);
	}

	public function getJSON(){
		$ret= array();
		$ret['name'] = $this->name;
		$ret['rating'] = $this->rating;
		return $ret;
	}
}

?>
