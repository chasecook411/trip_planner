<?php 

// need map

// need to be able to enter constraints (area)

// need to be able to search locations in area (text input queries google api)

// need place to enter locations you would like to see (textual input)

// need to be able to add locations (add button)

// need to be able to add additional constraints to each location (time there)

// need to be able to update map to show locations
$key = "AIzaSyDudH82XEdtorLPxfFh8MyX_616Ns_QX24";

$userid = $_GET['userid'];
$trip_name = $_GET['tripname'];
?>



<html>
    <head>
        <script src="jquery-3.2.1.min.js"></script>
        <style>
          /* Always set the map height explicitly to define the size of the div
           * element that contains the map. */
          /*
          #map {
            height: 100%;
          }
          Optional: Makes the sample page fill the window. 
          html, body {
            height: 100%;
            margin: 0;
            padding: 0;
          }
          */

        </style>

        <script>

        var addedLocations = [];
        function debug(str) {
            <?php 
            if (isset($_GET['debug'])) {
                echo "console.log(str);";
            }
            ?>
        }

        <?php
        $lat = 10;
        $lon = 10;
        ?>
        function initMap() {
            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 8,
                center: {lat: <?php echo $lat; ?>, lng: <?php echo $lon; ?>}
            });
        }

        function getLocations() {
            var query = document.getElementById('search').value;
            var cityState = document.getElementById('cityState').value;
            var query = query + ' in ' + cityState;
            var radius = parseInt(document.getElementById('radius').value) * 1609;

            // have a max of 50000 meters in the google API radius
            if (radius && radius < 50000) {
                    $.ajax({
                    url: 'http://localhost/endpoints/get_locations.php?query=' + query + '&radius=' + radius, 
                    type: "GET",   
                    cache: false,
                    success: parseLocations,
                    error: function(err) {
                        debug(err);
                    }
                });
            } else {

                //todo - show user an error here...
                console.log('radius too large!');
            }
        }
        // expects an array of locations
        function parseLocations(locations) {
            //debug(locations);
            locations = JSON.parse(locations);
            locations = locations.results;
            var parent = document.getElementById("locations");
            // clear list of child nodes
            while (parent.hasChildNodes()) {
                parent.removeChild(parent.lastChild);
            }


            // we deleted the h4, so we need to put it back     
            var h = document.createElement("h3");
            var text = document.createTextNode("Search Results");
            h.appendChild(text);
            parent.appendChild(h);
            locations.forEach(function (location) {

                //debug(location.formatted_address);

                var locationName = document.createElement("p");
                var node = document.createTextNode("Name: " + location.name);
                locationName.appendChild(node);
                
                var locationAddress = document.createElement("p");
                node = document.createTextNode("Address: " + location.formatted_address);
                locationAddress.appendChild(node);
                
                if (location.rating) {
                    var locationRating = document.createElement("p");
                    node = document.createTextNode("Rating: " + location.rating);
                    locationRating.appendChild(node);
                } else {
                    var locationRating = document.createElement("p");
                    node = document.createTextNode("Rating currently unavaiable.");
                    locationRating.appendChild(node);
                }
                

                var addButton = document.createElement("BUTTON");
                var locationId = location.place_id;
                //debug(locationId);
                //addButton.setAttribute("id", locationId);
                addButton.setAttribute('onclick','addLocation(\'' + locationId + '\')');
                node = document.createTextNode("Add Location");
                addButton.appendChild(node);

                
                parent.appendChild(locationName);
                parent.appendChild(locationAddress);
                parent.appendChild(locationRating);
                parent.appendChild(addButton);
            });
        }

        function addLocation(locationId) {
            debug('Got location! ' + locationId);
            //var baseUrl = "https://maps.googleapis.com/maps/api/place/details/json&key=<?php echo $key; ?>";
            //baseUrl = baseUrl + "&placeid=" + locationId;
            //debug(baseUrl);
            debug('http://localhost/endpoints/get_location_data.php?placeid=' + locationId);
            $.ajax({
                url: 'http://localhost/endpoints/get_location_data.php?placeid=' + locationId, 
                type: "GET",   
                cache: false,
                success: parseLocationDetails,
                error: function(err) {
                    debug(err);
                }
            });
        }

        function removeLocation(LocationId) {
            debug('Removed Location! ' + LocationId);
            // remove location
            temp = addedLocations.indexOf(LocationId);
            addedLocations.splice(temp, 1); // temp is the index of the element and 1 is the amount of elements to remove

/*            $.ajax({
                url: 'http://localhost/endpoints/get_data.php?url=' + baseUrl,
                type: "POST",
                cache: false,
                success: parseLocationDetails,
                error: function(err){
                    debug(err);
                }
            })*/

        }

        function parseLocationDetails(place) {
            //debug('got place ' + place);
            place = JSON.parse(place).result;
            var rating = 99;
            //debug(JSON.stringify(place));
            if (place.rating) {
                rating = place.rating;
            }

            var p = {
                id: place.place_id,
                name: place.name,
                icon: place.icon,
                url: place.url,
                formatted_address: place.formatted_address,
                longitude: place.geometry.location.lng,
                latitude: place.geometry.location.lat,
                rating: place.rating,                        // default value of 99
                opening_hours: place.opening_hours
            }

            //debug('in parseLocationDetails')
            //debug('pushing locations ' + JSON.stringify(p))
            addedLocations.push(p);
            //debug('added locations', JSON.stringify(addedLocations));
            var parent = document.getElementById("itineraryList");
            addedLocations.forEach(function(result) {

                console.log('adding to list', result.id);
                // if the element doesn't already exist  
                // on the page, add it!

                //debug(result)
                if (!document.getElementById(result.id)) {
                    var userLocation = document.createElement("h5");
                    userLocation.setAttribute('id', result.id);
                    var node = document.createTextNode("Location: " + result.name);
                    userLocation.appendChild(node);
                    userLocation.setAttribute("class", "locationNameClass");
                    parent.appendChild(userLocation);

                    var lineBreak = document.createElement("br");
                    parent.appendChild(lineBreak);

                    var icon = document.createElement("img");
                    icon.setAttribute("src", result.icon);
                    icon.setAttribute("class", "iconClass");
                    parent.appendChild(icon);

                    var lineBreak = document.createElement("br");
                    parent.appendChild(lineBreak);

                    if (result.url) {
                        var website = document.createElement("a");
                        node = document.createTextNode("See more information");
                        website.appendChild(node);
                        website.setAttribute("href", result.url);
                        parent.appendChild(website);
                    }
                    var lineBreak = document.createElement("br");
                    parent.appendChild(lineBreak);

                    
                    // if the API returned hours of operation
                    if (result.opening_hours) {
                        var operation = document.createElement("p");
                        node = document.createTextNode("Hours of Operation");
                        operation.appendChild(node);
                        parent.appendChild(operation);

                        result.opening_hours.weekday_text.forEach(function(weekday) {
                            var hours = document.createElement("p");
                            hours.setAttribute("class", "weekdayClass");
                            node = document.createTextNode(weekday);
                            hours.appendChild(node);
                            parent.appendChild(hours)
                        })
                    } else {
                        var operation = document.createElement("p");
                        node = document.createTextNode("Hours of Operation Not Available at this time");
                        operation.appendChild(node);
                        parent.appendChild(operation);
                    }
                }
                var removeButton = document.createElement("BUTTON");
                removeButton.setAttribute("id", result.id);
                removeButton.setAttribute('onclick','removeLocation(\'' + result.id + '\')');
                node = document.createTextNode("remove Location");
                removeButton.appendChild(node);

                parent.appendChild(removeButton);
            });
        }

        function saveList() {
            debug(addedLocations);
            var listName = document.getElementById('list_name').value;
            var tripObject = {
                'userid': <?php echo $userid; ?>,
                'trip_name': listName,
                'trip': addedLocations
            }
            debug(JSON.stringify(tripObject));
            $.ajax({
                url: 'http://localhost/endpoints/update_trip.php',
                type: "POST",
                data: JSON.stringify(tripObject),
                contentType: "application/json",
                success: function(result) {
                    debug('added list to db');
                    debug(JSON.stringify(result));
                },
                error: function(err) {
                    console.log('Error adding list');
                }
            });
        }

        function loadList(tripId) {
            //debug('loading list! for trip id ' + tripId);
            $.ajax({
                url: 'http://localhost/endpoints/query_attractions.php?tripid=' + tripId + '&userid=' + '<?php echo $userid; ?>', 
                type: "GET",   
                cache: false,
                success: function(result) {
                    result = JSON.parse(result);
                    result.forEach(function(place) {
                        addLocation(place.place_id);
                    });
                },
                error: function(err) {
                    debug(err);
                }
            });
        }

        </script>


        <style>
            #locations {
                float: left;
                width: 25%;
            }

            #itineraryList {
                float: right;
                width: 25%;
            }

            #map {
                float: right;
                width: 50%;
            }

            .iconClass {
                height: 15px;
                width: 15px;
            }

            .weekdayClass {
                padding-left: 4px;
                margin: 0px;
            }

            .locationNameClass {
                margin: -13px;
                margin-top: 10px;
            }

        </style>
        <link rel="stylesheet" type="text/css" href="CssStuff.css">
        <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <title>Sight Seer - Find Your Path</title>
    </head>
    <?php
    
        if (isset($_GET['tripid'])) {
            // if the trip id is already set, then we want to update the page
            // to show the locations already on that trip. Everything else should 
            // stay the same
            $tripid = $_GET['tripid'];
            echo '<body onload="loadList(' . $tripid . ')">';
        } else {
            echo '<body>';
        }
    ?>
        <div class="jumbotron">
            <div class="container sight-seer">
             Sight Seer
            </div>
        </div>
    <!-- Navigation Bar -->
    <header>
        <div class="navigation">
            <ul>
                <li class="Info"><a class="active" href="#">Info</a></li>
                <li class="Dashboard"><a href="http://localhost/main_page.php">Dashboard</a></li>
                <li class="Trips"><a href="#">Trips</a></li>
                <li class="Login"><a href="http://localhost/login_page.php">Login</a></li>
                <li class="Signup"><a href="http://localhost/login_page.php">Sign Up</a></li>
                <li class="Account"><a href="#">Account</a></li>
            </ul>
        </div>
    </header>
        <div id="map" onload="initMap()">
        </div>
                <div id="map">
        </div>
<!-- 
        Type: <input type="text" id="type" value="Bar"></br>
        Latitude: <input type="text" id="latitude" value="35"></br>
        Longitude: <input type="text" id="longitude" value="-90"></br>
        Radius: <input type="text" id="radius" value="1000"></br> -->

        Search Places: <input type="text" id="search" value="Foo"></br>
        City/State: <input type="text" id="cityState" value="Memphis, TN"></br>
        Radius (miles): <input type="text" id="radius" value="10"></br>
        <button onclick="getLocations()">Click me</button></br>
        <div id="locations">
            <h3>Search Results</h3>
        </div>

        

        <div id="itineraryList">
            <h3>Added Locations</h3>
        </div>
        <div id="svbtn">
            List Name: <input type="text" id="list_name" value="<?php echo $trip_name; ?>"></input>
            <button onclick="saveList()">"Save Your List"</button>
        </div>

    </body>
</html>