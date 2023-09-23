<?php
// Initialize session (if not already started)
session_start();

// Include database connection
include('db_connection.php');

// Check if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit();
}

// Get the user ID of the logged-in customer
$user_id = $_SESSION['user_id'];

// Retrieve bookings for the logged-in customer
$sql = "SELECT b.*, c.vehicle_model, c.vehicle_number
        FROM bookings b
        JOIN cars c ON b.car_id = c.id
        WHERE b.customer_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Your Bookings</title>
    <!-- Add Bootstrap CSS link -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <div class="container">
        <h2 class="mt-4">Your Bookings</h2>
        
        <?php
        if ($result->num_rows > 0) {
            echo '<table class="table mt-4">';
            echo '<thead><tr><th>Vehicle Model</th><th>Vehicle Number</th><th>Start Date</th><th>Duration (days)</th></tr></thead>';
            echo '<tbody>';
            
            while ($row = $result->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . $row['vehicle_model'] . '</td>';
                echo '<td>' . $row['vehicle_number'] . '</td>';
                echo '<td>' . $row['booking_date'] . '</td>';
                echo '<td>' . $row['return_date'] . '</td>';
                echo '</tr>';
            }
            
            echo '</tbody></table>';
        } else {
            echo '<p>You have no bookings.</p>';
        }
        ?>
        
        <p class="mt-4"><a href="dashboard.php" class="btn btn-primary">Back to Dashboard</a></p>
    </div>
</body>
</html>

