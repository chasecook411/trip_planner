<?php

$conn = new mysqli('localhost', 'root', 'root', 'lost_db');
if ($conn->connect_error) {
    die("Cound not connect: " . $conn->connect_error);
}

$client_data = file_get_contents('php://input');

if ($client_data) {
	$trip = json_decode($client_data);

	$query = 'select * from trips where user_id = "' . $trip->userid . '" and trip_name = "' . $trip->trip_name . '";';

	$result = $conn->query($query);

	if($result->num_rows > 0) {
		header("HTTP/1.1 403 Forbidden");
	} else {
		$query = "insert into trips values(null," . $trip->userid . ', "' . $trip->trip_name . '", NOW());';
		if ($conn->query($query) === TRUE) {
			$query = 'select trip_id from trips where user_id = "' . $trip->userid . '" and trip_name = "' . $trip->trip_name . '";';

			$result = $conn->query($query);

			$row = $result->fetch_assoc();

			print_r("{ \"trip_id\" : \"" . $row["trip_id"] . "\" } ");

		}
	}
}

$conn->close();

?>
