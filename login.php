<?php
// Include the database connection file
include('dbconnect.php');

// Start session to store login status
session_start();

// Handle login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userID = $_POST['UserID'];
    $password = $_POST['Password'];

    // Query to fetch the user with the provided UserID
    $query = "SELECT * FROM User WHERE UserID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $userID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // User found, check password
        $user = $result->fetch_assoc();
        if ($user['Password'] == $password) {
            // Correct password, set session variables
            $_SESSION['UserID'] = $user['UserID'];
            $_SESSION['Role'] = $user['Role'];

            // Redirect based on role
            if ($user['Role'] == 'admin') {
                header("Location: admin_dashboard.php");
            } else {
                header("Location: student_dashboard.php");
            }
        } else {
            echo "<script>alert('Wrong password!');</script>";
        }
    } else {
        echo "<script>alert('User not registered!');</script>";
    }

    // Close the statement
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - BUSCS</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>BUSCS</header>
    <form method="POST" action="">
        <h1>Login</h1>

        <label for="UserID">UserID:</label>
        <input type="text" id="UserID" name="UserID" required>

        <label for="Password">Password:</label>
        <input type="password" id="Password" name="Password" required>

        <button type="submit">Login</button>

        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </form>
</body>
</html>
