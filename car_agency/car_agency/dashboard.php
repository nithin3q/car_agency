<?php
// Initialize session (if not already started)
session_start();

// Check if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit();
}

// Include database connection
include('db_connection.php');

// Get user information
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$user_type = $_SESSION['user_type'];

// Depending on user type, display different content
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <!-- Add Bootstrap CSS link -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Add your custom CSS link -->
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <div class="container mt-5">
        <div class="jumbotron text-center">
            <h1 class="display-4">Welcome to Your Dashboard, <?php echo $username; ?>!</h1>
        </div>
        
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <?php
                if ($user_type === 'customer') {
                    // Display customer-specific content and actions
                    echo '<div class="alert alert-info" role="alert">';
                    echo '<p>You are logged in as a Customer.</p>';
                    echo '<p>Here are your available actions:</p>';
                    echo '<ul>';
                    echo '<li><a href="available_cars.php">View Available Cars</a></li>';
                    echo '<li><a href="your_bookings.php">View Your Bookings</a></li>';
                    echo '</ul>';
                    echo '</div>';
                } elseif ($user_type === 'agency') {
                    // Display car rental agency-specific content and actions
                    echo '<div class="alert alert-success" role="alert">';
                    echo '<p>You are logged in as a Car Rental Agency.</p>';
                    echo '<p>Here are your available actions:</p>';
                    echo '<ul>';
                    echo '<li><a href="add_car.php">Add New Car</a></li>';
                    echo '<li><a href="view_booked_cars.php">View Booked Cars</a></li>';
                    echo '</ul>';
                    echo '</div>';
                }
                ?>
                
                <p class="text-center"><a class="btn btn-primary" href="logout.php">Log Out</a></p>
            </div>
        </div>
    </div>

   
</body>
</html>

