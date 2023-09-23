<?php
// Initialize session (if not already started)
session_start();

// Check if the user is not logged in or is not a car rental agency
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'agency') {
    header('Location: login.php'); // Redirect to login page if not logged in or not an agency
    exit();
}

// Include database connection
include('db_connection.php');

// Get the logged-in agency's user ID
$agency_id = $_SESSION['user_id'];

// Fetch booked cars for the agency from the database
$sql = "SELECT c.id, c.vehicle_model, c.vehicle_number, c.seating_capacity, c.rent_per_day, c.image_data, b.booking_date, b.return_date
        FROM cars c
        INNER JOIN bookings b ON c.id = b.car_id
        WHERE c.agency_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $agency_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Booked Cars</title>
    <!-- Add Bootstrap CSS link -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <div class="container">
        <h2 class="mt-4">View Booked Cars</h2>
        
        <table class="table table-striped mt-4">
            <thead>
                <tr>
                    <th>Car Model</th>
                    <th>Car Number</th>
                    <th>Seating Capacity</th>
                    <th>Rent per Day</th>
                    <th>Booking Date</th>
                    <th>Return Date</th>
                    <th>Car Image</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . $row['vehicle_model'] . '</td>';
                    echo '<td>' . $row['vehicle_number'] . '</td>';
                    echo '<td>' . $row['seating_capacity'] . '</td>';
                    echo '<td>$' . $row['rent_per_day'] . '</td>';
                    echo '<td>' . $row['booking_date'] . '</td>';
                    echo '<td>' . $row['return_date'] . '</td>';
                    echo '<td><img src="' . $row['image_data'] . '" alt="Car Image" style="max-width: 100px;"></td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
        
        <p class="mt-4"><a href="dashboard.php" class="btn btn-primary">Back to Dashboard</a></p>
    </div>
</body>
</html>
