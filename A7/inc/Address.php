<?php
require_once("mysql_settings.php");

class Address {
	private $id;
	private $isUNC;
	private $addressLine;
	private $city;
	private $state;
	private $radius;

	private function __construct($id, $isUNC, $addressLine, $city, $state, $radius) {
		$this->id = $id;
		$this->isUNC = $isUNC;
		$this->addressLine = $addressLine;
		$this->city = $city;
		$this->state = $state;
		$this->radius = $radius;
	}

	public static function getById($id) {
		$mysqli = getDBConnection();
		$result = $mysqli->query("SELECT * FROM addresses WHERE id = '" . $id . "'");
		if ($result) {
			if ($result->num_rows == 0)
				return null;

			$row = $result->fetch_assoc();

			return new Address($id, $row['isUNC'], $row['addressLine'],
					$row['city'], $row['state'], intval($row['radius']));
		}
		return null;
	}

	public static function getOrCreate($isUNC, $addressLine, $city, $state, $radius) {
		$mysqli = getDBConnection();

		if (!is_numeric($radius) || ($isUNC == "false" && ($addressLine == "" || $city == "" || $state == "")))
			return null;

		if ($isUNC == "true") {
			$result = $mysqli->query("SELECT id FROM addresses WHERE isUNC = '1' AND " .
						"radius = '" . $radius . "'");
			if ($result) {
				$id = 0;
				if ($result->num_rows == 0) {
					$result = $mysqli->query("INSERT INTO addresses (isUNC, addressLine, " .
								"city, state, radius) VALUES ('1', '', '', '', '" .
								$radius . "')");

					if (!$result)
						return null;

					$id = $mysqli->insert_id;
				} else {
					$row = $result->fetch_assoc();
					$id = $row['id'];
				}
				return new Address($id, true, "", "", "", $radius);
			}
		} else if ($isUNC == "false") {
			$result = $mysqli->query("SELECT id FROM addresses WHERE isUNC = '0' AND " .
						"radius = '" . $radius. "' AND addressLine = '" . 
						$mysqli->real_escape_string($addressLine) . "' AND city = '" .
						$mysqli->real_escape_string($city) . "' AND state = '" .
						$mysqli->real_escape_string($state) . "'");
			if ($result) {
				$id = 0;
				if ($result->num_rows == 0) {
					$result = $mysqli->query("INSERT INTO addresses (isUNC, addressLine, " .
								"city, state, radius) VALUES ('0', '" .
								$mysqli->real_escape_string($addressLine) . "', '" .
								$mysqli->real_escape_string($city) . "', '" .
								$mysqli->real_escape_string($state) . "', '" .
								$radius . "')");

					if (!$result)
						return null;

					$id = $mysqli->insert_id;
				} else {
					$row = $result->fetch_assoc();
					$id = $row['id'];
				}
				return new Address($id, false, $addressLine, $city, $state, $radius);
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
		$representation['radius'] = $this->radius;
		return $representation;
	}

	public function getId() {
		return $this->id;
	}
}

?>
