<?php
require_once("Address.php");
require_once("mysql_settings.php");
require_once("date.php");

class Request {
	private $id;
	private $addressFrom;
	private $addressTo;
	private $userId;
	private $date;
	private $isMorning;

	private function __construct($id, $addressFrom, $addressTo, $userId, $date, $isMorning) {
		$this->id = $id;
		$this->addressFrom = $addressFrom;
		$this->addressTo = $addressTo;
		$this->userId = $userId;
		$this->date = $date;
		$this->isMorning = $isMorning;
	}

	public static function getById($id) {
		$mysqli = getDBConnection();
		$result = $mysqli->query("SELECT * FROM requests WHERE id = '" . $id . "'");
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

			return new Request($id, $addressFrom, $addressTo,
					intval($row['userId']), $row['date'],
					$row['isMorning'] == 1);
		}
		return null;
	}

	// Assumes $userId is a valid User ID and $addressFrom/$addressTo are valid Address objects
	public static function create($addressFrom, $addressTo, $userId, $date, $isMorning) {
		$mysqli = getDBConnection();

		$mysqlDate = validateConvertDateFromWireToMySQL($date);
		if (is_null($mysqlDate))
			return null;

		if (is_null($isMorning) || ($isMorning != "false" && $isMorning != "true"))
			return null;
		$mysqlIsMorning = $isMorning == "true" ? 1 : 0;

		$result = $mysqli->query("INSERT INTO requests (addressFrom, addressTo, userId, ".
					"date, isMorning) VALUES ('" . $addressFrom->getId() . "', '" .
					$addressTo->getId() . "', '" . $userId . "', '" . $mysqlDate . "', '".
					$mysqlIsMorning . "')");

		if ($result) {
			$id = $mysqli->insert_id;
			return new Request($id, $addressFrom, $addressTo,
					$userId, $date, $isMorning == "true");
		}
		return null;
	}

	public static function getEncodedRequestsBySearch($addressFrom, $addressTo, $date, $isMorning) {
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
		$query = "SELECT * FROM requests WHERE ";
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

				$request = new Request(intval($row['id']), $addressFrom, $addressTo,
							intval($row['userId']), $date,
							$row['isMorning'] == 1);
				$resultsRepresentation[] = $request->getJSON();
			}
		}
		return $resultsRepresentation;
	}

	public function getJSON() {
		$representation = array();
		$representation['id'] = $this->id;
		$representation['fromAddress'] = $this->addressFrom->getJSON();
		$representation['toAddress'] = $this->addressTo->getJSON();
		$representation['userId'] = $this->userId;
		$representation['date'] = $this->date;
		$representation['isMorning'] = $this->isMorning;
		return $representation;
	}

	public function getUserId() {
		return $this->userId;
	}

	public function delete(){
		$mysqli = getDBConnection();
		$result = $mysqli->query("DELETE FROM requests WHERE id = '" . $this->id . "'");
	}

	// Assumes $addressFrom/$addressTo are valid Address objects
	public function update($addressFrom, $addressTo, $date, $isMorning) {
		$mysqli = getDBConnection();

		$mysqlDate = validateConvertDateFromWireToMySQL($date);
		if (is_null($mysqlDate))
			return false;

		if (is_null($isMorning) || ($isMorning != "false" && $isMorning != "true"))
			return false;
		$mysqlIsMorning = $isMorning == "true" ? 1 : 0;

		$result = $mysqli->query("UPDATE requests SET addressFrom = '" . $addressFrom->getId() .
					"', addressTo = '" . $addressTo->getId() .
					"', date = '" . $mysqlDate . "', isMorning = '" . $mysqlIsMorning .
					"' WHERE id = '" . $this->id . "'");
		return $mysqli->affected_rows > 0;
	}
}

?>
