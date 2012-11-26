<?php
require_once("Address.php");
require_once("mysql_settings.php");

class Request {
	private $id;
	private $addressFrom;
	private $addressTo;
	private $userId;
	private $date; //TODO MySQL format vs JSON/JS Format???
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
					$row['isMorning']);//TODO bool_value?
		}
		return null;
	}

	// Assumes $userId is a valid User ID
	public static function create($addressFrom, $addressTo, $userId, $date, $isMorning) {
		$mysqli = getDBConnection();

		//TODO check that $address{From, To} are ints
		$addressFromAddress = Address::getById($addressFrom);
		if (is_null($addressFromAddress))
			return null;

		$addressToAddress = Address::getById($addressTo);
		if (is_null($addressToAddress))
			return null;

		$mysqlDate = $date; //TODO escape, convert, validate

		if (is_null($isMorning))//TODO validate $isMorning
			return null;

		$result = $mysqli->query("INSERT INTO requests (addressFrom, addressTo, userId, ".
					"date, isMorning) VALUES (" . $addressFrom . ", " .
					$addressTo . ", " . $userId . ", " . $mysqlDate . ", "
					$isMorning . ")");
		if ($result) {
			$id = $mysqli->insert_id;
			// TODO $date vs $mysqlDate
			return new Request($id, $addressFromAddress, $addressToAddress,
					$userId, $date, $isMorning);
		}
		return null;
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
