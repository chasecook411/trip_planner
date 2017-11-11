

<?php
/*
https://maps.googleapis.com/maps/api/place/textsearch/output?parameters

Required parameters

query — The text string on which to search, for example: "restaurant" or "123 Main Street". The Google Places service will return candidate matches based on this string and order the results based on their perceived relevance. This parameter becomes optional if the type parameter is also used in the search request.
key — Your application's API key. This key identifies your application for purposes of quota management and so that places added from your application are made immediately available to your app. See Get a key for Google Places API Web Service to see how to create an API Project and obtain your key.
*/

$key = 'AIzaSyDudH82XEdtorLPxfFh8MyX_616Ns_QX24';
if (isset($_GET['query']) && isset($_GET['radius'])) {

	$query = urlencode($_GET['query']);

	$radius = urlencode($_GET['radius']);
	//echo $query;
	$url = 'https://maps.googleapis.com/maps/api/place/textsearch/json?key=' . $key . '&query=' . $query . '&radius=' . $radius;
	$ch = curl_init($url);
    $response = curl_exec($ch);
    curl_close($ch);

    // ??? putting substring here because I'm getting a 
    // random '1' at the end of my response data...
    print_r(substr($response, 0, count($response) - 2));
}

?>