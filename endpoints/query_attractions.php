<?php
// this is used to query attractions for the database when main_page.php 
// is loaded with a trip id

$conn = new mysqli('localhost', 'root', 'root', 'lostdb');

if ($conn->connect_error) {
	die("Cound not connect: " . $conn->connect_error);
}


if (isset($_GET['tripid']) && isset($_GET['userid'])) {
	$tripid = $_GET['tripid'];
	$userid = $_GET['userid'];

	$query = "SELECT a.place_id, a.priority FROM trips t INNER JOIN attractions a ON t.trip_id = a.trip_id WHERE t.user_id = $userid AND a.trip_id = $tripid ORDER BY priority ASC"; 

	$result = $conn->query($query);
	$places = Array();
	while($row = $result->fetch_assoc()) {
    	array_push($places, $row);
    };
	print_r(json_encode($places));
}

//ChIJ1QZkXjOHf4gRKEWuxnvX85g
?>