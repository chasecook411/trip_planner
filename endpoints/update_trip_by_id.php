<?php

$conn = new mysqli('localhost', 'root', 'root', 'lost_db');
if ($conn->connect_error) {
    die("Cound not connect: " . $conn->connect_error);
}

$client_data = file_get_contents('php://input');

if ($client_data) {

	$trip_object = json_decode($client_data);
	print_r($trip_object);

	$tripId = $trip_object->tripId;
	$trip_array = $trip_object->trip;
	for ($i = 0; $i < count($trip_array); $i++) {

	 	$query = 'update attractions set priority = ' . $i . ' where trip_id = ' . $tripId . ' and place_id = "' . $trip_array[$i] . '"'; 
	 	$result = $conn->query($query);
	}

}

$conn->close();
?>