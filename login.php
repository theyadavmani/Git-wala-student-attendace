<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ua";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role']; // Added line

    $sql = "SELECT * FROM user WHERE username = '$username' AND role = '$role'"; // Updated line
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['username'] = $_POST['username'];
            $_SESSION['role'] = $row['role']; // Assuming the role is stored in the 'role' column
            if ($_SESSION['role'] === 'teacher') {
                header("Location: view_attendance.php");
            } elseif ($_SESSION['role'] === 'student') {
                header("Location: index.php");
            }
        } else {
            echo "Incorrect password.";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login.css">
    <title>Login</title>
</head>
<body>
    
    <div class="container">
    <h2>Login</h2>

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

        <input type="submit" value="Login">
    </form>
    </div>

</body>
</html>