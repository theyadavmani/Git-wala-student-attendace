<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'teacher') {
    header("location: login.html");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Attendance</title>
</head>
<body>
    <h1>View Attendance</h1>

    <div id="attendanceList"></div>

    <script>
        // Fetch attendance data from the server
        fetch('view.php')
            .then(response => response.json())
            .then(data => displayAttendance(data))
            .catch(error => console.error('Error fetching attendance data:', error));

        // Display attendance data on the page
        function displayAttendance(attendanceData) {
            const attendanceListElement = document.getElementById('attendanceList');

            if (attendanceData.length > 0) {
                const table = document.createElement('table');
                table.border = '1';

                // Create table header
                const headerRow = table.insertRow(0);
                for (const key in attendanceData[0]) {
                    const headerCell = headerRow.insertCell(-1);
                    headerCell.textContent = key;
                }

                // Populate table with data
                attendanceData.forEach((entry, index) => {
                    const row = table.insertRow(index + 1);
                    for (const key in entry) {
                        const cell = row.insertCell(-1);
                        cell.textContent = entry[key];
                    }

                    // Add delete and update buttons
                    const deleteButton = document.createElement('button');
                    deleteButton.textContent = 'Delete';
                    deleteButton.onclick = function () {
                        deleteRecord(entry['id']);
                    };
                    row.insertCell(-1).appendChild(deleteButton);

                    const updateButton = document.createElement('button');
                    updateButton.textContent = 'Update';
                    updateButton.onclick = function () {
                        updateRecord(entry['id'], prompt('Enter new status:'));
                    };
                    row.insertCell(-1).appendChild(updateButton);
                });

                attendanceListElement.appendChild(table);
            } else {
                attendanceListElement.textContent = 'No attendance data found.';
            }
        }

        // Function to delete a record
        function deleteRecord(id) {
            fetch(`view_attendance.php?delete=${id}`)
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    location.reload(); // Reload the page after deletion
                })
                .catch(error => console.error('Error deleting record:', error));
        }

        // Function to update a record
        function updateRecord(id, newStatus) {
            fetch(`view.php?update=${id}&newStatus=${newStatus}`)
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    location.reload(); // Reload the page after update
                })
                .catch(error => console.error('Error updating record:', error));
        }
    </script>
</body>
</html>
