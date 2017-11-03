<?php


if (isset($_GET['url'])) {
    // for details API
    // todo, don't use the url as a parameter cuz its shitty. 
    if (isset($_GET['placeid'])) {
        $url = $_GET['url'] . '?placeid=' . $_GET['placeid'] . '&key=' . $_GET['key'];
        $ch = curl_init($url);
        $response = curl_exec($ch);
        curl_close($ch);

        // ??? putting substring here because I'm getting a 
        // random '1' at the end of my response data...
        print_r(substr($response, 0, count($response) - 2));
    }

    // for text searches API
    if (isset($_GET['query'])) {
        $url = $_GET['url'] . "&query=" . $_GET['query'] . "&location=" . $_GET['location'] . "&radius=" . $_GET['radius'];
        $ch = curl_init($url);
        $response = curl_exec($ch);
        curl_close($ch);

        // ??? putting substring here because I'm getting a 
        // random '1' at the end of my response data...
        print_r(substr($response, 0, count($response) -2));//substr($response, 0, ($response.length-2)));
    }

    
}


