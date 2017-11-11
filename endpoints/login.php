
<?php


$conn = new mysqli('localhost', 'root', '', 'lost_db');

if ($conn->connect_error) {
	die("Cound not connect: " . $conn->connect_error);
}


$email = "";
$password = "";
if (isset($_GET["email"]) && isset($_GET["password"])) {
	setCookie("12345", $_GET['email'] . ',' . $_GET['password'], time() + (86400 * 30), '/');
	$email = $_GET["email"];
	$password = $_GET["password"];
} else if (isset($_COOKIE["12345"])) {
	//$creds = array();
	$creds = explode(",",$_COOKIE["12345"]);
	$email = $creds[0];
	$password = $creds[1];
} 

$query = 'select * from users where email = "' . $email . '" and password ="' . $password . '";';
$result = $conn->query($query);

$count = $result->num_rows;

if ($count > 0) {
    while($row = $result->fetch_assoc()) {
    	print_r(json_encode($row));
    }
} else {
	// $error = array('error' => 'true', 'query' => $query);
	// print_r(json_encode($error));
    header("HTTP/1.1 401 Unauthorized");
}

$conn->close();

?>
