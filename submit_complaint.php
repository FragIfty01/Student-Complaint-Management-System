<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['UserID']) || $_SESSION['Role'] != 'student') {
    header("Location: login.php");  // Redirect to login page if not logged in
    exit();
}

// Include the database connection file
include('dbconnect.php');

// Initialize variables
$complaintText = "";
$attachment = "";
$categoryID = "";
$categories = [];

// Fetch complaint categories
$query = "SELECT CategoryID, CategoryName FROM ComplaintCategory";
$result = $conn->query($query);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
}

// When the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userID = $_SESSION['UserID'];
    $complaintText = $_POST['complaintText'];
    $categoryID = $_POST['category'];

    // Handle file upload
    if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] == 0) {
        $targetDir = __DIR__ . "/uploads/";
        $fileName = basename($_FILES["attachment"]["name"]);
        $targetFilePath = $targetDir . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'mp4', 'avi', 'mov'];
        if (in_array($fileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES["attachment"]["tmp_name"], $targetFilePath)) {
                $attachment = $fileName;
            } else {
                $errorMessage = "Sorry, there was an error uploading your file.";
            }
        } else {
            $errorMessage = "Sorry, only image and video files are allowed.";
        }
    }

    // Insert the complaint into the database
    if (empty($errorMessage)) {
        $query = "INSERT INTO complaints (UserID, ComplaintText, CategoryID, Status, Attachment) 
                  VALUES (?, ?, ?, 'Pending', ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssis", $userID, $complaintText, $categoryID, $attachment);
        if ($stmt->execute()) {
            $successMessage = "Complaint submitted successfully!";
        } else {
            $errorMessage = "Error submitting complaint. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Complaint - BUSCS</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header class="header">
        <h1>BUSCS - Submit a Complaint</h1>
    </header>
    <div class="form-container">
        <form action="submit_complaint.php" method="POST" enctype="multipart/form-data">
           
            <div class="form-group">
                <label for="category">Complaint Category:</label>
                <select name="category" id="category" required>
                    <option value="">Select a category</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['CategoryID']; ?>">
                            <?php echo $category['CategoryName']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
			
			 <div class="form-group">
                <input type="text" name="complaintText" id="complaintText" required placeholder="Enter complaint description" />
            </div>
            <div class="form-group">
                <label for="attachment">Attach a File (Image/Video):</label>
                <input type="file" name="attachment" id="attachment" accept="image/*, video/*" />
            </div>
            <?php if (!empty($errorMessage)): ?>
                <div class="error"><?php echo $errorMessage; ?></div>
            <?php endif; ?>
            <?php if (!empty($successMessage)): ?>
                <div class="success"><?php echo $successMessage; ?></div>
            <?php endif; ?>
            <button type="submit" class="btn">Submit Complaint</button>
        </form>
    </div>
</body>
</html>
