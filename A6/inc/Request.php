<?php
require_once("Address.php");
require_once("mysql_settings.php");

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
		$result = $mysqli->query("SELECT * FROM requests WHERE id = " . $id);
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
					$row['isMorning']!=0);
		}
		return null;
	}

	private static function validateConvertDateFromWireToMySQL($date) {
		$tmp = explode("/",$date);
		if (count($tmp) != 3)
			return null;

		if(!is_numeric($tmp[0]) || !is_numeric($tmp[1]) || !is_numeric($tmp[2]))
			return null;

		return $tmp[2] . "-" . $tmp[0] . "-" . $tmp[1];
	}

	private static function validateConvertDateFromMySQLToWire($date) {
		$tmp = explode("-",$date);
		if (count($tmp) != 3)
			return null;

		if(!is_numeric($tmp[0]) || !is_numeric($tmp[1]) || !is_numeric($tmp[2]))
			return null;

		return $tmp[1] . "/" . $tmp[2] . "/" . $tmp[0];
	}

	// Assumes $userId is a valid User ID and $addressFrom/$addressTo are valid Address objects
	public static function create($addressFrom, $addressTo, $userId, $date, $isMorning) {
		$mysqli = getDBConnection();

		$mysqlDate = validateConvertDateFromWireToMySQL($date);
		if (is_null($mysqlDate))
			return null;

		if (is_null($isMorning) || ($isMorning != "false" && $isMorning != "true"))//validate $isMorning
			return null;

		$result = $mysqli->query("INSERT INTO requests (addressFrom, addressTo, userId, ".
					"date, isMorning) VALUES ('" . $addressFrom->getId() . "', '" .
					$addressTo->getId() . "', '" . $userId . "', '" . $mysqlDate . "', '".
					$isMorning . "')");

		if ($result) {
			$id = $mysqli->insert_id;
			return new Request($id, $addressFrom, $addressTo,
					$userId, $date, $isMorning!=0);
		}
		return null;
	}

	public static function getEncodedRequestsBySearch($addressFrom, $addressTo, $date, $isMorning) {
		$mysqli = getDBConnection();
		$result = $mysqli->query("SELECT * FROM requests WHERE " .
				!is_null($addressFrom) ? ("addressFrom = '" . $addressFrom->getId() . "'") : ("") .
				!is_null($addressTo) ? ("addressTo = '" . $addressTo->getId() . "'") : ("") .
				!is_null($date) ? ("date = '" . $date . "'") : ("") .//TODO date
				!is_null($addressTo) ? ("isMorning = '" . $isMorning . "'") : ("") .//TODO isMorning
				" LIMIT 25");
		$resultsRepresentation = array();
		if ($result) {
			if ($result->num_rows == 0)
				return $resultsRepresentation;

			while ($row = $result->fetch_assoc()) {
				if (is_null($addressTo)) {
					$addressTo = Address::getById($row['addressTo']);
					if (is_null($addressTo))
						continue;
				}

				if (is_null($addressFrom)) {
					$addressFrom = Address::getById($row['addressFrom']);
					if (is_null($addressFrom))
						continue;
				}

				$request = new Request($id, $addressFrom, $addressTo,
							intval($row['userId']), $row['date'],
							$row['isMorning']!=0);//TODO != 0???
				$resultsRepresentation[] = $request.getJSON();
			}
		}
		return $resultsRepresentation;
	}

	public function getJSON() {
		$representation = array();
		$representation['id'] = $id;
		$representation['addressFrom'] = $addressFrom->getJSON();
		$representation['addressTo'] = $addressTo->getJSON();
		$representation['userId'] = $userId;
		$representation['date'] = $date;
		$representation['isMorning'] = $isMorning; //TODO: bool
		return $representation;
	}

	public function getUserId() {
		return $userId;
	}
}

?>
