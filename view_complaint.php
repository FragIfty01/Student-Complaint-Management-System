<?php
session_start();
if (!isset($_SESSION['UserID']) || $_SESSION['Role'] != 'admin') {
    header("Location: login.php");
    exit();
}

include('dbconnect.php');

if (isset($_GET['complaintID'])) {
    $complaintID = $_GET['complaintID'];

    // Fetch complaint details
    $query = "
        SELECT c.ComplaintID, c.ComplaintText, c.Status, c.Attachment, cc.CategoryName, c.UserID AS StudentID
        FROM complaints c
        JOIN ComplaintCategory cc ON c.CategoryID = cc.CategoryID
        WHERE c.ComplaintID = ?
    ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $complaintID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $complaint = $result->fetch_assoc();
    } else {
        echo "<script>alert('Complaint not found!'); window.location.href='admin_dashboard.php';</script>";
        exit();
    }
} else {
    header("Location: admin_dashboard.php");
    exit();
}

// Handle message submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $messageText = $_POST['messageText'];
    $adminID = $_SESSION['UserID'];
    $studentID = $_POST['studentID'];

    // Insert the message into adminmessages table
    $insertQuery = "
        INSERT INTO AdminMessages (ComplaintID, AdminID, StudentID, MessageText)
        VALUES (?, ?, ?, ?)
    ";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("isss", $complaintID, $adminID, $studentID, $messageText);

    if ($stmt->execute()) {
        // Update complaint status to "Completed"
        $updateQuery = "UPDATE complaints SET Status = 'Reviewed' WHERE ComplaintID = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param("i", $complaintID);
        $updateStmt->execute();

        echo "<script>alert('Message sent and complaint marked as reviewed.'); window.location.href='admin_dashboard.php';</script>";
        exit();
    } else {
        echo "<script>alert('Failed to send message. Please try again.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Complaint - BUSCS</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>Admin Dashboard</header>
    <div class="complaint-details">
        <h2>Complaint Details</h2>
        <p><strong>Complaint ID:</strong> <?php echo $complaint['ComplaintID']; ?></p>
        <p><strong>Category:</strong> <?php echo $complaint['CategoryName']; ?></p>
        <p><strong>Complaint Text:</strong> <?php echo $complaint['ComplaintText']; ?></p>
        <p><strong>Status:</strong> <?php echo $complaint['Status']; ?></p>
        <p><strong>Submitted by (UserID):</strong> <?php echo $complaint['StudentID']; ?></p>

        <?php if ($complaint['Attachment']): ?>
            <p><strong>Attachment:</strong></p>
            <img src="uploads/<?php echo $complaint['Attachment']; ?>" alt="Attachment" style="max-width: 100%; height: auto;">
        <?php endif; ?>

        <h3>Send a Message to the Student</h3>
        <form method="POST">
            <label for="messageText">Message:</label>
            <textarea name="messageText" id="messageText" rows="4" required></textarea>
            <input type="hidden" name="studentID" value="<?php echo $complaint['StudentID']; ?>">
            <button type="submit">Send Message</button>
        </form>
    </div>
</body>
</html>
