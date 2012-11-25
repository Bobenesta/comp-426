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
		$result = $mysqli->query("SELECT * FROM addresses WHERE id = " . $id);
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
		//TODO check that $radius is sane
		//TODO interpret $isUNC as bool
		if ($isUNC) {
			//TODO should be able to do this in one query (INSERT OR UPDATE?)
			$result = $mysqli->query("SELECT id FROM addresses WHERE isUNC = true AND " .
						"radius = " . $radius);
			$id = 0;
			if ($result) {
				if ($result->num_rows == 0) {
					$result = $mysqli->query("INSERT INTO addresses (isUNC, addressLine, " .
								"city, state, radius) VALUES (true, '', '', '', " .
								$radius . ")");

					if (!$result)
						return null;

					$id = $mysqli->insert_id;
				} else {
					$row = $result->fetch_assoc();//TODO if only selecing one column...?
					$id = $row['id'];
				}
				return new Address($id, true, "", "", "", $radius);
			}
		} else {
			//TODO
		}
		return null;
	}

	public function getJSON() {
		$representation = array();
		$representation['id'] = $id;
		$representation['isUNC'] = $isUNC;
		$representation['addressLine'] = $addressLine;
		$representation['city'] = $city;
		$representation['state'] = $state;
		$representation['radius'] = $radius;
		return $representation;
	}
}

?>
