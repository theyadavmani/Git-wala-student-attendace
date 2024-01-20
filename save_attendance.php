<?php
// Retrieve the raw POST data
$input_data = file_get_contents("php://input");

// Decode the JSON data
$data = json_decode($input_data, true);

// Access the values from the decoded data
$status = $data['status'];
$time = $data['time'];
$clientusername = $data['username'];

// Example: Insert data into the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ua";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "INSERT INTO attendance (user, status, time) VALUES ('$clientusername', '$status', '$time')";

if ($conn->query($sql) === TRUE) {
    echo "Attendance saved successfully.";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>



