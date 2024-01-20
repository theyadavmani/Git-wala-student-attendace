<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ua";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role']; // Added line

    $sql = "INSERT INTO user (username, password, role) VALUES ('$username', '$password', '$role')";

    if ($conn->query($sql) === TRUE) {
        echo "Registration successful.";
        header("Location: login.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>



<!-- signup.html -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="signup.css">
    <title>Sign Up</title>
</head>
<body>
    <div class="container">
        <h2>Sign Up</h2>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="username">Username:</label>
            <input type="text" name="username" required><br>

            <label for="password">Password:</label>
            <input type="password" name="password" required><br>

            <label for="role">Role:</label>
            <select name="role" required>
                <option value="student">Student</option>
                <option value="teacher">Teacher</option>
            </select><br>

            <input type="submit" value="Sign Up">
            <button><a href="login.php">LOG IN</a></button>
        </form>
    </div>
</body>
</html>
