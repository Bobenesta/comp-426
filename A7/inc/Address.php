<?php
require_once("mysql_settings.php");
require_once("class.googleHelper.php");

class Address {
	private $id;
	private $isUNC;
	private $addressLine;
	private $city;
	private $state;

	private function __construct($id, $isUNC, $addressLine, $city, $state) {
		$this->id = $id;
		$this->isUNC = $isUNC;
		$this->addressLine = $addressLine;
		$this->city = $city;
		$this->state = $state;
	}

	public static function getById($id) {
		$mysqli = getDBConnection();
		$result = $mysqli->query("SELECT * FROM addresses WHERE id = '" . $id . "'");
		if ($result) {
			if ($result->num_rows == 0)
				return null;

			$row = $result->fetch_assoc();

			return new Address($id, $row['isUNC'], $row['addressLine'],
					$row['city'], $row['state']);
		}
		return null;
	}

	public static function getOrCreate($isUNC, $addressLine, $city, $state) {
		$mysqli = getDBConnection();

		if ($isUNC == "false" && ($addressLine == "" || $city == "" || $state == ""))
			return null;

		if ($isUNC == "true") {
			$result = $mysqli->query("SELECT id FROM addresses WHERE isUNC = '1'");
			if ($result) {
				$id = 0;
				if ($result->num_rows == 0) {
					$result = $mysqli->query("INSERT INTO addresses (isUNC, addressLine, " .
								"city, state, longitude, latitude) VALUES ('1', '', '', '', '-79.0503810', '35.9052082')");

					if (!$result)
						return null;

					$id = $mysqli->insert_id;
				} else {
					$row = $result->fetch_assoc();
					$id = $row['id'];
				}
				return new Address($id, true, "", "", "");
			}
		} else if ($isUNC == "false") {
			$result = $mysqli->query("SELECT id FROM addresses WHERE isUNC = '0' AND addressLine = '" . 
						$mysqli->real_escape_string($addressLine) . "' AND city = '" .
						$mysqli->real_escape_string($city) . "' AND state = '" .
						$mysqli->real_escape_string($state) . "'");
			if ($result) {
				$id = 0;
				if ($result->num_rows == 0) {
					$location = googleHelper.getCoordinates("USA, " . $state . ", " . $city . ", " . $addressLine);
					if ($location == null)
						return null;

					$result = $mysqli->query("INSERT INTO addresses (isUNC, addressLine, " .
								"city, state, latitude, longitude) VALUES ('0', '" .
								$mysqli->real_escape_string($addressLine) . "', '" .
								$mysqli->real_escape_string($city) . "', '" .
								$mysqli->real_escape_string($state) . "', '" .
								$mysqli->real_escape_string($location['lat']) . "', '" .
								$mysqli->real_escape_string($location['long']) . "')");

					if (!$result)
						return null;

					$id = $mysqli->insert_id;
				} else {
					$row = $result->fetch_assoc();
					$id = $row['id'];
				}
				return new Address($id, false, $addressLine, $city, $state);
			}
		}
		return null;
	}

	public function getJSON() {
		$representation = array();
		$representation['id'] = $this->id;
		$representation['isUNC'] = $this->isUNC;
		$representation['addressLine'] = $this->addressLine;
		$representation['city'] = $this->city;
		$representation['state'] = $this->state;
		return $representation;
	}

	public function getId() {
		return $this->id;
	}
}

?>
