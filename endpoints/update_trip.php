<?php

$conn = new mysqli('localhost', 'root', 'root', 'lost_db');
if ($conn->connect_error) {
    die("Cound not connect: " . $conn->connect_error);
}

// for accepting restful API calls
$client_data = file_get_contents('php://input');
// for creating a new trip
if ($client_data) {

    // expects a json object containing a userid, tripname, and an array containing locations
    $trip_object = json_decode($client_data);
    //print_r($trip_object);
    $attractions = $trip_object->trip;
    $query = 'select * from trips where user_id = "' . $trip_object->userid . '" and trip_name = "' . $trip_object->trip_name . '";';

    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $trip_id = $row['trip_id'];
        }
        $query = 'select * from attractions where trip_id = "' . $trip_id . '";';

        $result = $conn->query($query);
        for ($i = 0; $i < count($attractions); $i++) {
            $query = 'select * from attractions where address = "' . $attractions[$i]->formatted_address . '" and trip_id = ' . $trip_id . ';';
            //echo $query;
            $res = $conn->query($query);

            if ($res->num_rows > 0) {
                // exists in database, reset priorty, in case its changed
                //if ($attractions->isSkipped == false) {
                //    $update = 'update attractions set priority = ' . $i . ' where address = "' . $attractions[$i]->formatted_address . '";';
                //}
                //$res = $conn->query($update);
            } else {
                // doesn't exist in database, insert it
                $rating = "NULL";
                if (property_exists($attractions[$i], 'rating')) {
                    $rating = $attractions[$i]->rating;
                }
                $insert = 'insert into attractions values(null, ' . $trip_id . ', ' . $i . ', "' . $attractions[$i]->name . '", "' . $attractions[$i]->formatted_address . '", ' . $attractions[$i]->longitude . ', ' . $attractions[$i]->latitude . ', 5, ' . $rating . ', "' . $attractions[$i]->id . '");';
              //  echo $insert;
                //echo $insert;
                $res = $conn->query($insert);
            }
        }

        // and finally, we need to run through and delete attractions that are no longer in the list
        // if its in the database, but its no longer in attractions, delete it!
        while($row = $result->fetch_assoc()) {
            $trip_id = $row['trip_id'];
        }

        $query = 'select * from attractions where trip_id = ' . $trip_id . ';';

        $result = $conn->query($query);

        while ($row = $result->fetch_assoc()) {
            $is = 0;
            for ($i = 0; $i < count($attractions); $i++) {
                if ($attractions[$i]->formatted_address = $row['address']) {
                    $is = 1;
                }
            }

            if ($is == 0) {
                $q = 'delete * from attractions where address = ' . $row['address'];
            }
        }


    } else {
        // trip_id, user_id, trip_name, day
        $query = "insert into trips values(null," . $trip_object->userid . ', "' . $trip_object->trip_name . '", NOW());';
        //echo $query;

        $result = $conn->query($query);
        $query = 'select * from trips where trip_name = ' . '"' . $trip_object->trip_name . '";';

        //echo $query; 

        $result = $conn->query($query);
        $trip_id = "";
        while($row = $result->fetch_assoc()) {
            $trip_id = $row['trip_id'];
        }
        for ($i = 0; $i < count($attractions); $i++) {
            // attraction_id, trip_id, priority, address, longitude, latitude, time_spent, rating

            $rating = "NULL";
            if (property_exists($attractions[$i], 'rating')) {
                $rating = $attractions[$i]->rating;
            }
            $query = 'insert into attractions values(null, ' . $trip_id . ', ' . $i . ', "' . $attractions[$i]->name . '", "' . $attractions[$i]->formatted_address . '", ' . $attractions[$i]->longitude . ', ' . $attractions[$i]->latitude . ', 5, ' . $rating . ', "' . $attractions[$i]->id . '");';
            //echo $query;
            $result = $conn->query($query);  
            //print_r('Trip added!');  
        }   

        // $query = "select * from trips where trip_name = " . $trip_object->trip_name . ";";

        // $result = $conn->query($query);
        // $obj = new stdClass();
        // while($row = $result->fetch_assoc()) {
        //     $obj->trip_id = $row['trip_id'];
        // }

        print_r("{ \"trip_id\" : \"" . $trip_id . "\" } ");
    }
}
$conn->close();
?>