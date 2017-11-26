<?php

// need map

// need to be able to enter constraints (area)

// need to be able to search locations in area (text input queries google api)

// need place to enter locations you would like to see (textual input)

// need to be able to add locations (add button)

// need to be able to add additional constraints to each location (time there)

// need to be able to update map to show locations
$key = "<check discord>";

$userid = $_GET['userid'];
$trip_name = $_GET['tripname'];
?>



<html>
    <head>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDudH82XEdtorLPxfFh8MyX_616Ns_QX24&callback=initMap"
    async defer></script>
        <script src="jquery-3.2.1.min.js"></script>
        <style>

            #map {
                height: 500px;
                width: 500px;
                right: 50px;
            }
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
        var map = null;
        var totalDistance = 0;

        function initMap() {
            map = new google.maps.Map(document.getElementById('map'), {
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
                addButton.setAttribute('name', locationId);
                node = document.createTextNode("Add Location");
                addButton.appendChild(node);

                parent.appendChild(locationName);
                parent.appendChild(locationAddress);
                parent.appendChild(locationRating);
                parent.appendChild(addButton);
            });
        }

        function addLocation(locationId,priority) {
            debug('Got location! ' + locationId);
            debug('http://localhost/endpoints/get_location_data.php?placeid=' + locationId);
            $.ajax({
                url: 'http://localhost/endpoints/get_location_data.php?placeid=' + locationId,
                type: "GET",
                cache: false,
                async: false,
                success: function(result) {
                    parseLocationDetails(result, priority);
                },
                error: function(err) {
                    debug(err);
                }
            });
        }

        function parseLocationDetails(place, priority) {
            //debug('got place ' + place);
            place = JSON.parse(place).result;


            var rating = 99;
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
                opening_hours: place.opening_hours,
                isSkipped: false
            }

            map.setCenter({ lat: p.latitude, lng: p.longitude});

            var marker = new google.maps.Marker({
                map: map,
                position: {
                    lat: p.latitude,
                    lng: p.longitude
                },
                title: p.name
            });

            addedLocations.push(p);

            // at this point, we can consider this place to be the last in the list? 
            // let's assume that and try to build the total distance. 
            if (addedLocations.length > 1) {
                //debug('length of array greater than 1');
                var orig = addedLocations[addedLocations.length - 1].id;
                var dest = addedLocations[addedLocations.length - 2].id;
                debug('http://localhost/endpoints/get_distance.php?origplaceid=' + orig + '&destplaceid=' + dest);
                $.ajax({
                    url: 'http://localhost/endpoints/get_distance.php?origplaceid=' + orig + '&destplaceid=' + dest,
                    type: "GET",
                    cache: false,
                    //async: false,
                    success: function(result) {
                        parseDistanceVal(result);
                    },
                    error: function(err) {
                        debug(err);
                    }
                });
            }

            var parent = document.getElementById("itineraryList");
            // addedLocations.forEach(function(result) {

            //     console.log('adding to list', result);
            //     // if the element doesn't already exist
            //     // on the page, add it!

                //debug(result)

            // element doesn't already exist
            //debug('Element does not exist!! ' + JSON.stringify(p.name));

            if (!document.getElementById(p.id)) {
                var userLocation = document.createElement("h5");
                userLocation.setAttribute('id', p.id);
                var node = document.createTextNode("Location: " + p.name);
                userLocation.appendChild(node);
                userLocation.setAttribute("class", "locationNameClass");
                parent.appendChild(userLocation);

                if (priority && priority == -1) {
                    userLocation.setAttribute('class','skipped');
                }

                var lineBreak = document.createElement("br");
                parent.appendChild(lineBreak);

                var icon = document.createElement("img");
                icon.setAttribute("src", p.icon);
                icon.setAttribute("class", "iconClass");
                parent.appendChild(icon);

                var lineBreak = document.createElement("br");
                parent.appendChild(lineBreak);

                if (p.url) {
                    var website = document.createElement("a");
                    node = document.createTextNode("See more information");
                    website.appendChild(node);
                    website.setAttribute("href", p.url);
                    parent.appendChild(website);
                }


                var lineBreak = document.createElement("br");
                parent.appendChild(lineBreak);


                // if the API returned hours of operation
                if (p.opening_hours) {
                    var operation = document.createElement("p");
                    node = document.createTextNode("Hours of Operation");
                    operation.appendChild(node);
                    parent.appendChild(operation);

                    p.opening_hours.weekday_text.forEach(function(weekday) {
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

                var skipButton = document.createElement("button");
				skipButton.setAttribute('onclick','skip(\'' + p.id + '\')');
				var t = document.createTextNode("Skip Location");
                skipButton.appendChild(t);
                parent.appendChild(skipButton);
            }
            //});
        }

        function parseDistanceVal(result) {
            result = JSON.parse(result).rows[0].elements[0].distance;
            totalDistance += result.value;
            console.log(result);

            document.getElementById('total').innerHTML = (totalDistance * 0.0006) + " miles" ;
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
                        addLocation(place.place_id, place.priority);
                    });
                },
                error: function(err) {
                    debug(err);
                }
            });
        }

        // This skips an attraction on the list, but does not permanently remove it.
        // A skipped attraction is not considered when computing a route.
        function skip(pdiddy) {
            //accessing addLocations array to obtain Google place id
			for(key in addedLocations)	{
				key = addedLocations[key];
				// if the id matches, then we skip this location
				if(key.id == pdiddy) {
					key.isSkipped = true;
				}
			}
            var url = window.location.href;
            var index1 = url.search("tripid=");
            // if you have not saved your trip, there is no tripid and we have nothing
            // in the database to interact with at the moment. search returns -1 if not found.
            if(index1 >= 0) {
				// place.id from attraction in array
                var placeId = pdiddy; //place_id
                var index2 = url.substring(index1).search("&");
				// trip.id from url where 'tripid='up to '&'
                var tripId = url.substring(index1+7, index1+index2);
				console.log(tripId);
                $.ajax({
                    url: "http://localhost/endpoints/skip_location.php",
                    type: "POST",
                    data: {
                        place_id: placeId,
                        trip_id: tripId,
                    },
                    //this should cause a visual update (red or grey background for skipped?)
                    success: function(greyOut) {
                        //var greyedOut = document.getElementById(pdiddy);
                        //greyedOut.style.color = "red";
						var section = document.getElementsByTagName("h5");
						//var section = document.getElementById("itineraryList");
						console.log(section);
						for(i = 0; i < section.length; i++) {
							//console.log(s);
							if(section[i].id == pdiddy) {
								console.log("here");
                                    section[i].setAttribute("class", "skipped");
							}
						}
                    },
                    error: function(err) {
                        console.log("Error skipping location.");
                    }
                });
            }
        }

        function optimizeTrip() {
            debug('Optimizing trip... ');
            if (addedLocations.length > 2) {
                debug(JSON.stringify(addedLocations));
                $.ajax({
                    url: "http://localhost/endpoints/optimize_trip.php",
                    type: "POST",
                    data: JSON.stringify(addedLocations),
                    //this should cause a visual update (red or grey background for skipped?)
                    success: function(result) {
                        // just refresh the page with the new database update. 
                        //window.location.replace(window.location.href);
                        
                        console.log(result);
                    },
                    error: function(err) {
                        console.log("Error skipping location.");
                    }
                });
            } 
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

            .skipped {
                color: gray;
                text-decoration: line-through;
            }

        </style>
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
<!-- 
        Type: <input type="text" id="type" value="Bar"></br>
        Latitude: <input type="text" id="latitude" value="35"></br>
        Longitude: <input type="text" id="longitude" value="-90"></br>
        Radius: <input type="text" id="radius" value="1000"></br> -->

        Search Places: <input type="text" id="search" value="Tourist Attractions"></br>
        City/State: <input type="text" id="cityState" value="Memphis, TN"></br>
        Radius (miles): <input type="text" id="radius" value="10"></br>
        <button onclick="getLocations()" id="attractions_button">Find Attractions</button></br>
        Total Distance: <span id="total"></span><br>
        <button onclick="optimizeTrip()" id="optimize_button">Optimize</button>
        <div id="locations">
            <h3>Search Results</h3>
        </div>

        

        <div id="itineraryList">
            <h3>Added Locations</h3>
        </div>
        <div id="svbtn">
            List Name: <input type="text" id="list_name" value="<?php echo $trip_name; ?>"></input>
            <button onclick="saveList()" id="save_button">Save Your List</button>
        </div>

        <div id="map" onload="initMap()">
        </div>
    </body>
</html>
