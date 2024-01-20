<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("location: login.html");
} elseif ($_SESSION['role'] === 'teacher') {
    header("location: view_attendance.html");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="index.css">
    <title>ATTENDANCE BY LOCATION</title>
</head>
<body>
    <div class="header">
        <h1>ATTENDANCE BY LOCATION </h1>
    </div>
    <?php echo $_SESSION['username']; 
    ?>
        
    <div class="presence">

    <p id="attendanceStatus">Attendance status: Not Marked</p>
    <p id="time">Time: </p>
    </div>
    <button id="btn" onclick="startAttendance()">Mark Attendance</button>
    <!-- <button id="sav" onclick="saveAttendance()"> Save Attendance</button> -->

    <script>
        console.log('11');
        var isInsideCollege = false;
        var currentdate;
        var username = "<?php echo  $_SESSION['username']; ?>"; // Pass username from PHP
        console.log("username: " + username);

        function startAttendance() {
            setInterval(checkAttendance, 2000);
            checkAttendance();
        }

        function checkAttendance() {
            
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    position => {
                        var collegeLocation = { latitude: 23.01952, longitude: 72.6171648 };
                        console.log(position.coords.latitude);
                        console.log(position.coords.longitude);
                        
                        const distance = calculateDistance(
                            position.coords.latitude,
                            position.coords.longitude,
                            collegeLocation.latitude,
                            collegeLocation.longitude
                        );
                        console.log(distance)
                        

                        if (distance <= 100) {
                            
                            if (!isInsideCollege) {
                                console.log('1')
                                markAttendance(true);
                                isInsideCollege = true;

                                currentdate = new Date();
                                // saveAttendance(true);
                                printTime();
                            }
                        } else {
                            if (isInsideCollege) {
                                console.log('2')
                                
                                markAttendance(false);
                                isInsideCollege = false;
                                currentdate = new Date();
                                // saveAttendance(false);
                                printTime();
                            }
                        }
                    },
                    error => {
                        console.error('Error getting location:', error);
                    },{enableHighAccuracy: false, timeout: 2000, maximumAge: 0}
                );
            } else {
                alert('Geolocation is not supported by your browser.');
            }
        }

        function calculateDistance(lat1, lon1, lat2, lon2) {
            const R = 6371;
            const dLat = deg2rad(lat2 - lat1);
            const dLon = deg2rad(lon2 - lon1);
            const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) *
                Math.sin(dLon / 2) * Math.sin(dLon / 2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
            const distance = R * c;
            return distance;
        }

        function deg2rad(deg) {
            return deg * (Math.PI / 180);
        }

        function markAttendance(isPresent) {
    const attendanceStatusElement = document.getElementById('attendanceStatus');
    attendanceStatusElement.textContent = `Attendance status: ${isPresent ? 'Present' : 'Not Present'}`;

    const currentdate = new Date();
    const clientusername = "<?php echo $_SESSION['username']; ?>";
    const status = isPresent ? 'present' : 'absent';
    // date_default_timezone_set('Asia/Kolkata');
    const localTime = currentdate.toLocaleString('en-US', { timeZone: 'Asia/Kolkata' }); 
    const time = new Date(localTime).toISOString().slice(0, 19).replace("T", " ");

    // const time = currentdate.toString();

    const data = {
        status: status,
        time: time,
        username: clientusername
    };
    console.log('Data:', data);

    fetch('save_attendance.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(function(response) {
        if (response.ok) {
            console.log('Attendance saved successfully.');
        } else {
            console.error('Error saving attendance:', response.statusText);
        }
    })
    .catch(function(error) {
        console.error('Error saving attendance:', error);
    });
}
        function printTime() {
            const timeElement = document.getElementById('time');
            timeElement.textContent = `Time: ${currentdate}`;
        }
    </script>
</body>
</html>

