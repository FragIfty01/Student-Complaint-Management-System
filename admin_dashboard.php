<?php
// Start session and check if the user is logged in
session_start();
if (!isset($_SESSION['UserID']) || $_SESSION['Role'] != 'admin') {
    header("Location: login.php");  // Redirect to login if not an admin
    exit();
}

// Include the database connection file
include('dbconnect.php');

// Get the logged-in admin's ID
$adminID = $_SESSION['UserID'];

// Fetch complaints for categories handled by the logged-in admin
$query = "
    SELECT c.ComplaintID, c.ComplaintText, c.Status, c.UserID
    FROM complaints c
    JOIN complaintcategory cc ON c.CategoryID = cc.CategoryID
    WHERE cc.AssignedAdminID = ?
";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $adminID);
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
    <title>Admin Dashboard - BUSCS</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>Admin Dashboard</header>
    <div class="dashboard-container">
        <h2>Complaint List</h2>

        <!-- Display Success or Error Messages -->
        <?php
        if (isset($_GET['status'])) {
            if ($_GET['status'] == 'completed') {
                echo "<div class='success'>Complaint marked as completed successfully!</div>";
            }
        }
        ?>

        <table>
            <thead>
                <tr>
                    <th>Complaint ID</th>
                    <th>Complaint</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($complaints as $complaint): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($complaint['ComplaintID']); ?></td>
                        <td><?php echo htmlspecialchars($complaint['ComplaintText']); ?></td>
                        <td><?php echo htmlspecialchars($complaint['Status']); ?></td>
                        <td>
                            <!-- View Complaint Button -->
                            <a href="view_complaint.php?complaintID=<?php echo htmlspecialchars($complaint['ComplaintID']); ?>">
                                <button class="btn-view">View</button>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
