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

// Retrieve car details from the database
$sql = "SELECT * FROM cars WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $car_id);
$stmt->execute();
$result = $stmt->get_result();
$car = $result->fetch_assoc();
$stmt->close();

// Get user type
$user_type = $_SESSION['user_type'];

// Handle booking form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rent'])) {
    // Perform booking logic here
    // For example, insert booking details into the database
    // Remember to validate and sanitize user input
    
    // After successful booking, you can redirect the user to a confirmation page
    // header('Location: booking_confirmation.php');
    // exit();

    $user_id = $_SESSION['user_id'];

    // Sanitize and validate user input (e.g., rent_start_date, return_date)
    $rent_start_date = $_POST['rent_start_date'];
    $return_date = $_POST['return_date'];
    
    // ...
// Insert booking details into the database
$insert_sql = "INSERT INTO bookings (car_id, customer_id, booking_date, return_date) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($insert_sql);

if (!$stmt) {
    die('Error preparing statement: ' . mysqli_error($conn));
}

$stmt->bind_param("iiss", $car_id, $user_id, $rent_start_date, $return_date);

if ($stmt->execute()) {
    // Booking successful, redirect to confirmation page
    header('Location: booking_confirmation.php?car_id=' . $car_id);
    exit();
} else {
    // Handle error (e.g., display an error message)
    echo 'Error: ' . $stmt->error;
}

$stmt->close();

}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Rent Car</title>
    <!-- Add Bootstrap CSS link -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <div class="container">
        <h2 class="mt-4">Rent Car</h2>
        
        <?php

        // Display car details
        echo '<div class="card mt-4">';
        echo '<div class="row no-gutters">';
        echo '<div class="col-md-4">';
        echo '<img src="' . $car['image_data'] . '" alt="Car Image" class="img-fluid" style="max-height: 200px;">';
        echo '</div>';
        echo '<div class="col-md-8">';
        echo '<div class="card-body">';
        echo '<h5 class="card-title">' . $car['vehicle_model'] . '</h5>';
        echo '<p class="card-text"><strong>Vehicle Number:</strong> ' . $car['vehicle_number'] . '</p>';
        echo '<p class="card-text"><strong>Seating Capacity:</strong> ' . $car['seating_capacity'] . '</p>';
        echo '<p class="card-text"><strong>Rent per Day:</strong> $' . $car['rent_per_day'] . '</p>';
        echo '</div>'; // Close card-body
        echo '</div>'; // Close col-md-8
        echo '</div>'; // Close row
        echo '</div>'; // Close card
        ?>

        <?php
        // Example logic for renting the car (to be customized)
        $user_type = 'customer'; // Simulated user type (replace with actual user check)
        if ($user_type === 'customer') {
            echo '<form class="mt-4" action="#" method="POST">'; // Replace "#" with the actual action URL
            echo '<div class="form-group">';
            echo '<label for="rent_start_date">Start Date:</label>';
            echo '<input type="date" class="form-control" id="rent_start_date" name="rent_start_date" required>';
            echo '</div>';
            echo '<div class="form-group">';
            echo '<label for="return_date">Return Date:</label>';
            echo '<input type="date" class="form-control" id="return_date" name="return_date" required>';
            echo '</div>';
            echo '<button type="submit" class="btn btn-primary" name="rent">Rent This Car</button>';
            echo '</form>';
        } else {
            echo '<p class="mt-4">Only customers can rent cars. Please log in as a customer to rent a car.</p>';
        }
        ?>
        
        <p class="mt-4"><a href="available_cars.php">Back to Available Cars</a></p>
    </div>
</body>
</html>
