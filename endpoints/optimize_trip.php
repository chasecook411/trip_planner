<?php

$conn = new mysqli('localhost', 'root', 'root', 'lost_db');
if ($conn->connect_error) {
    die("Cound not connect: " . $conn->connect_error);
}

// for accepting restful API calls
$client_data = file_get_contents('php://input');

$key = 'AIzaSyDudH82XEdtorLPxfFh8MyX_616Ns_QX24';

if ($client_data) {
	$trip_object = json_decode($client_data);


	$ch = curl_init($url);
    $response = curl_exec($ch);
    curl_close($ch);

    // ??? putting substring here because I'm getting a 
    // random '1' at the end of my response data...
    print_r(substr($response, 0, count($response) - 2));
}
if (isset($_GET['origplaceid']) && isset($_GET['destplaceid'])) {
    $placeid = $_GET['placeid'];
    $url = 'https://maps.googleapis.com/maps/api/distancematrix/json?origins=place_id:' . $_GET['origplaceid'] . '&destinations=place_id:' . $_GET['destplaceid'] . '&key=AIzaSyDudH82XEdtorLPxfFh8MyX_616Ns_QX24';
    //echo $url;
    
}

?>