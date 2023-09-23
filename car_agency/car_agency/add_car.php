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

// Define variables to store form data
$vehicle_model = $vehicle_number = $seating_capacity = $rent_per_day = '';
$error = '';

// Define an array of allowed file extensions
$allowed_extensions = ['jpg', 'jpeg', 'png', 'gif','webp'];

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_car'])) {
    // Retrieve and sanitize form data
    $vehicle_model = $_POST['vehicle_model'];
    $vehicle_number = $_POST['vehicle_number'];
    $seating_capacity = intval($_POST['seating_capacity']);
    $rent_per_day = floatval($_POST['rent_per_day']);
    
    // Check if all required fields are filled
    if (empty($vehicle_model) || empty($vehicle_number) || empty($seating_capacity) || empty($rent_per_day)) {
        $error = 'All fields are required';
    } else {
        // Check if an image file is uploaded
        if (isset($_FILES['car_image']) && $_FILES['car_image']['error'] === UPLOAD_ERR_OK) {
            $file_extension = strtolower(pathinfo($_FILES['car_image']['name'], PATHINFO_EXTENSION));
            
            // Check if the file extension is in the allowed extensions array
            if (in_array($file_extension, $allowed_extensions)) {
                $image_tmp_path = $_FILES['car_image']['tmp_name'];
                
                // Generate a unique filename for the uploaded image
                $image_filename = uniqid() . '.' . $file_extension;
                
                // Define the target directory where the image will be stored
                $target_directory = 'uploads/'; // Change this to your target directory
                
                // Combine the target directory and image filename
                $target_path = $target_directory . $image_filename;
                
                // Move the uploaded file to the target directory
                if (move_uploaded_file($image_tmp_path, $target_path)) {
                    // File moved successfully
                    // You can now proceed with database insertion and use $target_path to store the image path in the database
                    $image_path = $target_path; // Store the image path

                    // Insert car details into the database
                    $sql = "INSERT INTO cars (vehicle_model, vehicle_number, seating_capacity, rent_per_day, agency_id, image_data) VALUES (?, ?, ?, ?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("ssidds", $vehicle_model, $vehicle_number, $seating_capacity, $rent_per_day, $_SESSION['user_id'], $image_path); // Use $image_path to store the image path

                    if ($stmt->execute()) {
                        // Car added successfully
                        header('Location: dashboard.php');
                        exit();
                    } else {
                        // Handle error (e.g., display an error message)
                        $error = 'Error adding car: ' . $stmt->error;
                    }
                    $stmt->close();
                } else {
                    $error = 'Error moving the uploaded file';
                }
            } else {
                $error = 'Invalid file type. Please select a valid image file with one of the following extensions: ' . implode(', ', $allowed_extensions);
            }
        } else {
            $error = 'File upload error: ' . $_FILES['car_image']['error'];
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add New Car</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    
</head>
<body>
    <div class="container card mt-5">
        <h2>Add New Car</h2>
        
        <form action="add_car.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="vehicle_model">Vehicle Model:</label>
                <input type="text" id="vehicle_model" name="vehicle_model" required value="<?php echo $vehicle_model; ?>">
            </div>
            <div class="form-group">
                <label for="vehicle_number">Vehicle Number:</label>
                <input type="text" id="vehicle_number" name="vehicle_number" required value="<?php echo $vehicle_number; ?>">
            </div>
            <div class="form-group">
                <label for="seating_capacity">Seating Capacity:</label>
                <input type="number" id="seating_capacity" name="seating_capacity" required value="<?php echo $seating_capacity; ?>">
            </div>
            <div class="form-group">
                <label for="rent_per_day">Rent per Day:</label>
                <input type="number" id="rent_per_day" name="rent_per_day" step="0.01" required value="<?php echo $rent_per_day; ?>">
            </div>
            <div class="form-group">
                <label for="car_image">Car Image:</label>
                <input type="file" id="car_image" name="car_image" required>
            </div>
            <div class="form-group">
                <button type="submit" name="add_car">Add Car</button>
            </div>
            <p class="error"><?php echo $error; ?></p>
        </form>
        
        <p><a href="dashboard.php">Back to Dashboard</a></p>
    </div>
</body>
</html>
