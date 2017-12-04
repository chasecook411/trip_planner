<?php

$conn = new mysqli('localhost', 'root', '', 'lost_db');
if ($conn->connect_error) {
	die("Could not connect:" . $conn->connect_error);
}

$userid = "";
if (isset($_GET["userid"])) {
	$userid = $_GET["userid"];

$query = 'select * from trips where user_id = "' . $userid . '";';

$result = $conn->query($query) or die('Error querying database.');

$count = $result->num_rows;

$tripList = Array();

if ($count > 0) {
	while ($row = $result->fetch_assoc()) {
		array_push($tripList, $row);
	
	}
	print_r(json_encode($tripList));

}

$conn->close();
}

?>