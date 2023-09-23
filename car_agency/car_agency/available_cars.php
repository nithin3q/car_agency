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

// Retrieve available cars from the database
$sql = "SELECT * FROM cars WHERE is_available = 1";
$result = $conn->query($sql);

// Get user type
$user_type = $_SESSION['user_type'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Available Cars</title>
    <!-- Add Bootstrap CSS link -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Available Cars</h2>
        
        <?php
        if ($user_type === 'customer') {
            // echo '<p>Select a car to rent:</p>';
            echo '<div class="row">';
            
            while ($row = $result->fetch_assoc()) {
                echo '<div class="col-md-4 mb-4">';
                echo '<div class="card">';
                echo '<img src="' . $row['image_data'] . '" class="card-img-top" alt="Car Image" style="height: 200px;">';
                echo '<div class="card-body">';
                echo '<h5 class="card-title">' . $row['vehicle_model'] . '</h5>';
                echo '<p class="card-text"><strong>Vehicle Number:</strong> ' . $row['vehicle_number'] . '</p>';
                echo '<p class="card-text"><strong>Seating Capacity:</strong> ' . $row['seating_capacity'] . '</p>';
                echo '<p class="card-text"><strong>Rent per Day:</strong> $' . $row['rent_per_day'] . '</p>';
                echo '<a href="rent_car.php?car_id=' . $row['id'] . '" class="btn btn-primary">Rent This Car</a>';
                echo '</div>'; // Close card-body
                echo '</div>'; // Close card
                echo '</div>'; // Close col-md-4
            }
            
            echo '</div>'; // Close row
        } elseif ($user_type === 'agency') {
            echo '<p class="alert alert-warning">Car rental agencies cannot rent cars. Please log out and log in as a customer to rent a car.</p>';
        }
        ?>
        
        <p class="text-center mt-3"><a class="btn btn-secondary" href="dashboard.php">Back to Dashboard</a></p>
    </div>
</body>
</html>
