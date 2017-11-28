<?php

	$client_data = file_get_contents('php://input');
	if ($client_data) {
		$url = "http://localhost:5000/optimize";
		$ch = curl_init($url);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $client_data);
		
	    $response = curl_exec($ch);
	    curl_close($ch);
	    
	    // ??? putting substring here because I'm getting a 
	    // random '1' at the end of my response data...
	    print_r(substr($response, 0, count($response) - 2));
	}
?>