<?php

$conn = new mysqli('localhost', 'root', 'root', 'lostdb');
if ($conn->connect_error) {
    die("Cound not connect: " . $conn->connect_error);
}

// for accepting restful API calls
$client_data = file_get_contents('php://input');

$key = 'AIzaSyDrf1CoJf5si6S2jo7_hxNKELjZgFBlIPk';

if ($client_data) {
	$trip_object = json_decode($client_data);

    //print_r($trip_object);
    $url = 'https://maps.googleapis.com/maps/api/distancematrix/json?key=AIzaSyDrf1CoJf5si6S2jo7_hxNKELjZgFBlIPk&origins=';

    for ($i = 0; $i < count($trip_object); $i++) {
        $url .= "place_id:" . $trip_object[$i]->id;

        if ($i < (count($trip_object) - 1)) {
            $url .= "|";
        }
    }

    $url .= "&destinations=";

    for ($i = 0; $i < count($trip_object); $i++) {
        $url .= "place_id:" . $trip_object[$i]->id;

        if ($i < (count($trip_object) - 1)) {
            $url .= "|";
        }
    }
	$ch = curl_init($url);
    $response = curl_exec($ch);
    curl_close($ch);

    // ??? putting substring here because I'm getting a 
    // random '1' at the end of my response data...
    print_r(substr($response, 0, count($response) - 2));
}

?>