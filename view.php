<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ua";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if delete request is received
if (isset($_GET['delete'])) {
    $idToDelete = $_GET['delete'];
    $deleteSql = "DELETE FROM attendance WHERE id = $idToDelete";
    
    if ($conn->query($deleteSql) === TRUE) {
        echo json_encode(array("message" => "Record deleted successfully."));
    } else {
        echo json_encode(array("message" => "Error deleting record: " . $conn->error));
    }
} elseif (isset($_GET['update']) && isset($_GET['newStatus'])) {
    // Check if update request is received
    $idToUpdate = $_GET['update'];
    $newStatus = $_GET['newStatus'];
    
    $updateSql = "UPDATE attendance SET status = '$newStatus' WHERE id = $idToUpdate";
    
    if ($conn->query($updateSql) === TRUE) {
        echo json_encode(array("message" => "Record updated successfully."));
    } else {
        echo json_encode(array("message" => "Error updating record: " . $conn->error));
    }
} else {
    // Set the Content-Type header to application/json
    header('Content-Type: application/json');

    $sql = "SELECT * FROM attendance";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $attendanceData = array();
        while ($row = $result->fetch_assoc()) {
            $attendanceData[] = $row;
        }
        echo json_encode($attendanceData);
    } else {
        echo json_encode(array("message" => "No attendance data found."));
    }
}

$conn->close();
?>
