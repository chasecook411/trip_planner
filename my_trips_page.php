<?php
$userid = $_GET['userid'];
?>

<html>
	<head>
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
			//redirects to main_page with the trip id as argument to load this trip
			function viewTrip(tripId, tripname) {
				url = "http://localhost/main_page.php?debug=true&userid=<?php echo $userid; ?>";
				url = url + "&tripid=" + tripId + '&tripname=' + tripname;
				window.location.assign(url);
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
	<body onload="getTrips()">
		<div id="navigation">
				</div></br>

		<div id="trips">
			<h3>My Trips</h3>
			
		</div>

	</body>
</html>