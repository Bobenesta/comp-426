<?php
require_once("Address.php");
require_once("mysql_settings.php");
require_once("date.php");

class Ride {
	private $id;
	private $addressFrom;
	private $addressTo;
	private $userId;
	private $date;
	private $isMorning;
	private $carDesc;
	private $carCapacity;

	private function __construct($id, $addressFrom, $addressTo, $userId, $date, $isMorning, $carDesc, $carCapacity) {
		$this->id = $id;
		$this->addressFrom = $addressFrom;
		$this->addressTo = $addressTo;
		$this->userId = $userId;
		$this->date = $date;
		$this->isMorning = $isMorning;
		$this->carDesc = $carDesc;
		$this->carCapacity = $carCapacity;
	}

	public static function getById($id) {
		$mysqli = getDBConnection();
		$result = $mysqli->query("SELECT * FROM rides WHERE id = '" . $id . "'");
		if ($result) {
			if ($result->num_rows == 0)
				return null;

			$row = $result->fetch_assoc();

			$addressTo = Address::getById($row['addressTo']);
			if (is_null($addressTo))
				return null;

			$addressFrom = Address::getById($row['addressFrom']);
			if (is_null($addressFrom))
				return null;

			return new Ride($id, $addressFrom, $addressTo,
					intval($row['userId']), $row['date'],
					$row['isMorning'] == 1, $row['carDesc']
					, $row['carCapacity']);
		}
		return null;
	}

	// Assumes $userId is a valid User ID and $addressFrom/$addressTo are valid Address objects
	public static function create($addressFrom, $addressTo, $userId, $date, $isMorning, $carDesc, $carCapacity) {
		$mysqli = getDBConnection();

		$mysqlDate = validateConvertDateFromWireToMySQL($date);
		if (is_null($mysqlDate))
			return null;

		if (is_null($isMorning) || ($isMorning != "false" && $isMorning != "true"))
			return null;
		$mysqlIsMorning = $isMorning == "true" ? 1 : 0;

		$result = $mysqli->query("INSERT INTO rides (addressFrom, addressTo, userId, ".
					"date, isMorning, carDesc, carCapacity) VALUES ('" .
					$addressFrom->getId() . "', '" . $addressTo->getId() . "', '" .
					$userId . "', '" . $mysqlDate . "', '". $mysqlIsMorning . "', '" .
					$mysqli->real_escape_string($carDesc) . "', '" .
					$mysqli->real_escape_string($carCapacity) . "')");

		if ($result) {
			$id = $mysqli->insert_id;
			return new Ride($id, $addressFrom, $addressTo,
					$userId, $date, $isMorning == "true",
					$carDesc, $carCapacity);
		}
		return null;
	}

	public static function getEncodedRidesBySearch($addressFrom, $addressTo, $date, $isMorning) {
		$mysqli = getDBConnection();

		$mysqlDate = null;
		if (!is_null($date)) {
			$mysqlDate = validateConvertDateFromWireToMySQL($date);
			if (is_null($mysqlDate))
				return null;
		}

		$mysqlIsMorning = null;
		if (!is_null($isMorning)) {
			if ($isMorning != "false" && $isMorning != "true")
				return null;
			$mysqlIsMorning = $isMorning == "true" ? 1 : 0;
		}

		$isFirst = true;
		$query = "SELECT * FROM rides WHERE ";
		if (!is_null($addressFrom)) {
			if ($isFirst)
				$isFirst = false;
			$query = $query . "addressFrom = '" . $addressFrom->getId() . "' ";
		}
		if (!is_null($addressTo)) {
			if ($isFirst)
				$isFirst = false;
			else
				$query = $query . "AND ";
			$query = $query . "addressTo = '" . $addressTo->getId() . "' ";
		}
		if (!is_null($mysqlDate)) {
			if ($isFirst)
				$isFirst = false;
			else
				$query = $query . "AND ";
			$query = $query . "date = '" . $mysqlDate . "' ";
		}
		if (!is_null($mysqlIsMorning)) {
			if ($isFirst)
				$isFirst = false;
			else
				$query = $query . "AND ";
			$query = $query . "isMorning = '" . $mysqlIsMorning . "' ";
		}
		$query = $query . "LIMIT 25";

		if ($isFirst)
			return null;

		$result = $mysqli->query($query);
		$resultsRepresentation = array();
		if ($result) {
			if ($result->num_rows == 0)
				return $resultsRepresentation;

			while ($row = $result->fetch_assoc()) {
				// This db request should be cached in a lot of cases
				$addressTo = Address::getById($row['addressTo']);
				if (is_null($addressTo))
					continue;

				// This db request should be cached in a lot of cases
				$addressFrom = Address::getById($row['addressFrom']);
				if (is_null($addressFrom))
					continue;

				// This can be cached when $date was originally not null
				$date = validateConvertDateFromMySQLToWire($row['date']);
				if (is_null($date))
					continue;

				$ride = new Ride($id, $addressFrom, $addressTo,
							intval($row['userId']), $date,
							$row['isMorning'] == 1, $row['carDesc'],
							$row['carCapacity']);
				$resultsRepresentation[] = $ride->getJSON();
			}
		}
		return $resultsRepresentation;
	}

	public function getJSON() {
		$representation = array();
		$representation['id'] = $this->id;
		$representation['addressFrom'] = $this->addressFrom->getJSON();
		$representation['addressTo'] = $this->addressTo->getJSON();
		$representation['userId'] = $this->userId;
		$representation['date'] = $this->date;
		$representation['isMorning'] = $this->isMorning;
		$representation['carDesc'] = $this->carDesc;
		$representation['carCapacity'] = $this->carCapacity;
		return $representation;
	}

	public function getUserId() {
		return $this->userId;
	}

	public function delete(){
		$mysqli = getDBConnection();
		$result = $mysqli->query("DELETE FROM rides WHERE id = '" . $this->id . "'");
	}

	// Assumes $addressFrom/$addressTo are valid Address objects
	public function update($addressFrom, $addressTo, $date, $isMorning, $carDesc, $carCapacity) {
		$mysqli = getDBConnection();

		if (is_null($carDesc) || is_null($carCapacity))
			return false;

		$mysqlDate = validateConvertDateFromWireToMySQL($date);
		if (is_null($mysqlDate))
			return false;

		if (is_null($isMorning) || ($isMorning != "false" && $isMorning != "true"))
			return false;
		$mysqlIsMorning = $isMorning == "true" ? 1 : 0;

		$result = $mysqli->query("UPDATE rides SET addressFrom = '" . $addressFrom->getId() .
					"', addressTo = '" . $addressTo->getId() .
					"', date = '" . $mysqlDate . "', isMorning = '" . $mysqlIsMorning .
					"', carDesc = '" . $mysqli->real_escape_string($carDesc) .
					"', carDescription = '" . $mysqli->real_escape_string($carDescription) .
					"' WHERE id = '" . $this->id . "'");
		return $mysqli->affected_rows > 0;
	}
}

?>