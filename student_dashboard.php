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

// Fetch the logged-in student's UserID
$userID = $_SESSION['UserID'];

// Fetch the complaints for the logged-in student
$query = "
    SELECT c.ComplaintID, c.ComplaintText, c.Status, 
           (SELECT GROUP_CONCAT(am.MessageText SEPARATOR '\n') 
            FROM adminmessages am 
            WHERE am.ComplaintID = c.ComplaintID) AS Feedback 
    FROM complaints c
    WHERE c.UserID = ?
";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $userID); // "s" for string
$stmt->execute();
$result = $stmt->get_result();

// Fetch complaints into an array
$complaints = [];
while ($row = $result->fetch_assoc()) {
    $complaints[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - BUSCS</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Popup Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
            padding-top: 50px;
        }

        .modal-content {
            background-color: #fff;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 50%;
            border-radius: 10px;
            position: relative;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <header class="header">
        <h1>BUSCS - Student Dashboard</h1>
    </header>
    <div class="dashboard-container">
        <h2>Your Complaints</h2>

        <!-- Button to Submit New Complaint -->
        <div class="submit-complaint">
            <a href="submit_complaint.php">
                <button class="btn-submit">Submit a New Complaint</button>
            </a>
        </div>

        <!-- Table to Display Complaints -->
        <table>
            <thead>
                <tr>
                    <th>Complaint ID</th>
                    <th>Complaint</th>
                    <th>Status</th>
                    <th>Feedback</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($complaints as $complaint): ?>
                    <tr>
                        <td><?php echo $complaint['ComplaintID']; ?></td>
                        <td><?php echo $complaint['ComplaintText']; ?></td>
                        <td><?php echo $complaint['Status']; ?></td>
                        <td>
                            <?php if ($complaint['Feedback']): ?>
                                <button class="btn-feedback" onclick="openModal(<?php echo $complaint['ComplaintID']; ?>)">View Feedback</button>
                                <!-- Hidden Modal Content -->
                                <div id="modal-<?php echo $complaint['ComplaintID']; ?>" class="modal">
                                    <div class="modal-content">
                                        <span class="close" onclick="closeModal(<?php echo $complaint['ComplaintID']; ?>)">&times;</span>
                                        <h3>Feedback for Complaint #<?php echo $complaint['ComplaintID']; ?></h3>
                                        <p><?php echo nl2br(htmlspecialchars($complaint['Feedback'])); ?></p>
                                    </div>
                                </div>
                            <?php else: ?>
                                No Feedback Available
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- JavaScript for Modal -->
    <script>
        function openModal(complaintID) {
            document.getElementById('modal-' + complaintID).style.display = 'block';
        }

        function closeModal(complaintID) {
            document.getElementById('modal-' + complaintID).style.display = 'none';
        }
    </script>
</body>
</html>
