<?php

$conn = new mysqli('localhost', 'root', 'root', 'lost_db');
if ($conn->connect_error) {
	die("Could not connect:" . $conn->connect_error);
}

$userid = $_GET['userid'];
?>

<html>
	<head>
		<title>My Trips</title>
		<script src="jquery-3.2.1.min.js"></script>
		<script>
			var tripsList = [];

			function getTrips() {



			}
		</script>



	<style>
		#trips {
			float: left;
			width: 25%;
		}
	</style>

	</head>
	<body>
		<div id="navigation">
				</div></br>


		<div id="trips">
			<h3>My Trips</h3>
		<?php
			$query = 'select * from trips where user_id = "' . $userid . '";';
			$result = $conn->query($query) or die('Error querying database.');

			while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
				echo $row['trip_name'] . ' on ' . $row['day'] . '</br>';
			}
			$conn->close();
		?>		
		</div>

	</body>
</html>
