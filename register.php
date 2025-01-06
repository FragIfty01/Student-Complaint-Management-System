<?php
// Include the database connection file
include('dbconnect.php');

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userID = $_POST['UserID'];
    $password = $_POST['Password'];

    try {
        $stmt = $conn->prepare("INSERT INTO user (UserID, Password, Role) VALUES (?, ?, 'student')");
        $stmt->bind_param("ss", $userID, $password);
        $stmt->execute();

        echo "<script>alert('Registration successful!');</script>";
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1062) {
            echo "<script>alert('Error: User already exists!');</script>";
        } else {
            echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
        }
    } finally {
        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - BUSCS</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>BUSCS </header>
    <form method="POST" action="">
        <h1>Register as a Student</h1>
        
        <label for="UserID">UserID:</label>
        <input type="text" id="UserID" name="UserID" required>
        
        <label for="Password">Password:</label>
        <input type="password" id="Password" name="Password" required>
        
        <button type="submit">Register</button>

        <p>Already registered? <a href="login.php">Login here</a></p>
    </form>
</body>
</html>
