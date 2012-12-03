<?php
function validateConvertDateFromWireToMySQL($date) {
	$tmp = explode("/",$date);
	if (count($tmp) != 3)
                $tmp = explode("-",$date);

	if (count($tmp) != 3)
		return null;

	if(!is_numeric($tmp[0]) || !is_numeric($tmp[1]) || !is_numeric($tmp[2]))
		return null;

	return $tmp[2] . "-" . $tmp[0] . "-" . $tmp[1];
}

function validateConvertDateFromMySQLToWire($date) {
	$tmp = explode("-",$date);
	if (count($tmp) != 3)
		return null;

	if(!is_numeric($tmp[0]) || !is_numeric($tmp[1]) || !is_numeric($tmp[2]))
		return null;

	return $tmp[1] . "/" . $tmp[2] . "/" . $tmp[0];
}
?>
