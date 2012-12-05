<?php
require_once("../inc/require_authentication.php");
require_once("../inc/Request.php");

$id= $_GET['profileId'];
$name= "";
		$mysqli = getDBConnection();
		$result = $mysqli->query("SELECT * FROM users WHERE id = '" . $id . "'");
		if ($result) {
			if ($result->num_rows == 0)
				return null;

			$row = $result->fetch_assoc();

			$name= $row['userName'];
		}
echo($name."'s profile");

// echo($name."'s offers");
 // echo("<br><br><tr><td> From </td><td> To </td><td> Date </td></tr>");
// if ($result = $mysqli->query("SELECT * FROM rides WHERE uesrId = '" . $id . "'")) {
// echo $result->num_rows;
    // /* fetch object array */
    // while ($row = $result->fetch_assoc()) {
    	// $from= $mysqli->query("SELECT addressLine FROM addresses WHERE id = '" . $row['addressFrom'] . "'");
		// $to= $mysqli->query("SELECT addressLine FROM addresses WHERE id = '" . $row['addressTo'] . "'");
		// $date= $row['date'];
        // echo("<tr><td>".$from."</td><td>".$to."</td><td>".$date."</td></tr><br>");
    // }
// 
    // /* free result set */
    // $result->close();
// }


echo($name."'s requests");
$request= getById($id);
 echo("<br><br><table><tr><td> From </td><td> To </td><td> Date </td></tr>");
 echo("<tr><td> ".$request->addressFrom." </td><td> ".$request->addressTo." </td><td> ".$request->date."</td></tr>");
 echo("</table>")

// echo($name."'s rating");
// 
// 
 // echo("<br><br><tr><td> From </td><td> Rating </td><td> Message </td></tr>");
// if ($result = $mysqli->query("SELECT * FROM ratings WHERE userTo = '" . $id . "'")) {
// echo $result->num_rows;
    // /* fetch object array */
    // while ($row = $result->fetch_assoc()) {
    	// $from= $mysqli->query("SELECT userName FROM users WHERE id = '" . $row['userFrom'] . "'");
		// $rating= $row['rating'];
		// $message= $row['message'];
        // echo("<tr><td>".$from."</td><td>".$rating."</td><td>".$message."</td></tr><br>");
    // }
// 
    // /* free result set */
    // $result->close();
// }
?>