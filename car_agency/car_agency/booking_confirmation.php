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

// Get the car ID from the query parameter
if (isset($_GET['car_id'])) {
    $car_id = $_GET['car_id'];
} else {
    header('Location: available_cars.php'); // Redirect to available_cars.php if car ID is not provided
    exit();
}

// Retrieve car details from the database (for displaying confirmation)
$sql = "SELECT * FROM cars WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $car_id);
$stmt->execute();
$result = $stmt->get_result();
$car = $result->fetch_assoc();
$stmt->close();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Booking Confirmation</title>
    <!-- Add Bootstrap CSS link -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <div class="container">
        <h2 class="mt-4">Booking Confirmation</h2>
        
        <?php
        // Display confirmation message
        echo '<p>Your booking for the selected car has been confirmed.</p>';
        
        // Display car details
        echo '<div class="mt-4">';
        echo '<strong>Vehicle Model:</strong> ' . $car['vehicle_model'] . '<br>';
        echo '<strong>Vehicle Number:</strong> ' . $car['vehicle_number'] . '<br>';
        echo '<strong>Seating Capacity:</strong> ' . $car['seating_capacity'] . '<br>';
        echo '<strong>Rent per Day:</strong> $' . $car['rent_per_day'] . '<br>';
        echo '<img src="' . $car['image_data'] . '" alt="Car Image" style="max-width: 50%; height: auto;"><br>';
        echo '</div>';
        ?>
        <p class="mt-4"><a href="dashboard.php" class="btn btn-primary">Back to Dashboard</a></p>
    </div>
</body>
</html>
