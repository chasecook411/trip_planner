<?php
$userid = $_GET['userid'];
?>

<html>
	<head>
		<link rel="stylesheet" href="CssStuff.css">
		<title>My Trips</title>
		<script src="jquery-3.2.1.min.js"></script>
		
		<script>
			//On page load will request server to process request for users trips
			function getTrips() {
				$.ajax({
					url: 'http://localhost/endpoints/load_trips.php?userid=' + <?php echo $userid ?>,
					type:"GET",
					cache: false,
					success: parseTrips,
					error: function(err) {
						debug(err);
					}
				});
			}
			//expects array of trips and will load this content on page
			function parseTrips(trips) {
				if (trips === "") {
					var noTrips = $("<p></p>").text("You have no trips. Use the field to create one now.");
					$("#trips").append(noTrips);
				} else {
					trips = JSON.parse(trips);
					var parent = document.getElementById("trips");
					trips.forEach(function (trip) {
						var tripName = document.createElement("p");
						var node = document.createTextNode("Name: " + trip.trip_name);
						tripName.appendChild(node);
						var tripDate = document.createElement("p");
						var node = document.createTextNode("Date: " + trip.day);
						tripDate.appendChild(node);
						var viewButton = document.createElement("BUTTON");
						var tripId = trip.trip_id;
						viewButton.setAttribute("id", tripId);
						viewButton.setAttribute('onclick',"viewTrip('" + tripId + "', '" + trip.trip_name + "')");
						node = document.createTextNode("View Trip");
						viewButton.appendChild(node);
						parent.appendChild(tripName);
						parent.appendChild(tripDate);
						parent.appendChild(viewButton);
					});
				}
			}

			//redirects to main_page with the trip id as argument to load this trip
			function viewTrip(tripId, tripname) {
				url = "http://localhost/main_page.php?debug=true&userid=<?php echo $userid; ?>";
				url = url + "&tripid=" + tripId + '&tripname=' + tripname;
				window.location.assign(url);
			}

			function createNewTrip() {
				var tripName = document.getElementById('trip_name').value;

				var tripObject = {
					'userid': <?php echo $userid; ?>,
					'trip_name': tripName
				};

				$.ajax({
					url: 'http://localhost/endpoints/create_trip.php',
					type: "POST",
					data: JSON.stringify(tripObject),
					contentType: "application/json",
					success: function(result) {
                    	if (result) {
                        	var tripData = JSON.parse(result);
                        	if (tripData && tripData.trip_id) {
                            	window.location.assign("http://localhost/main_page.php?debug=true&userid=" + <?php echo $userid; ?> + "&tripid=" + tripData.trip_id + "&tripname=" + tripName);
                        	}    
                    	}
                	},
                	error: function(err) {
                    	console.log('Error adding trip');
                	}
            	});
			}

		</script>



	<style>
		#trips {
			margin: auto;
			text-align: center;
			width: 50%;
		}
	</style>

	</head>
	<div class="jumbotron">
        <div class="container sight-seer">
         Sight Seer
        </div>
    </div>
<!-- Navigation Bar -->
<header>
    <div class="navigation">
        <ul>
            <li class="Info"><a href="#">Info</a></li>
            <li class="Dashboard"><a href="http://localhost/main_page.php">Dashboard</a></li>
            <li class="Trips"><a class="active" href="http://localhost/my_trips_page.php" >My Trips</a></li>
            <li class="Login"><a href="http://localhost/login_page.php">Login</a></li>
            <li class="Signup"><a href="http://localhost/login_page.php">Sign Up</a></li>
            <li class="Account"><a href="#">Account</a></li>
        </ul>
    </div>
</header>
	<body onload="getTrips()">


		</div>
		<div id="navigation">
				</div></br>

		<div id="trips">
			<h3>My Trips</h3>
			
		</div>

		<div id="newtrip">
		<input type=text id="trip_name"></input></br>
		<button onclick="createNewTrip()" id="newtripbutton">Create a Trip</button></br>

	</body>
</html>