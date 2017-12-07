<?php

$conn = new mysqli('localhost', 'root', 'root', 'lost_db');

if ($conn->connect_error) {
        die("Cound not connect: " . $conn->connect_error);
}

// place_id is the unique identifier of the attractions table and the
// trip_id is needed so we know which trip to operate on.
if(isset($_POST['place_id']) and isset($_POST['trip_id'])) {
    $place_id = $_POST['place_id'];
    $trip_id = $_POST['trip_id'];

    $query = "select * from attractions where trip_id = $trip_id " .
             "and place_id = '$place_id'";
	echo $query;

    $result = $conn->query($query);
    // This location does not exist in this trip.
    if(!$result) {
        header("HTTP/1.1 402 Not Found");
        die("No such attraction exists in your trip.");
    }

    else {
        $update = "UPDATE attractions " .
                  "SET priority = 127 " .
                  "WHERE place_id = '$place_id' and trip_id = $trip_id";
		echo $update;
        $conn->query($update);
		header("HTTP/1.1 200 OK");
    }

}

$conn->close()

?>
