<?php
// Initialize session (if not already started)
session_start();

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    // Destroy the session and unset all session variables
    session_unset();
    session_destroy();
    
// Redirect to the login page after logging out
    header('Location: index.php');
    exit();
} else {
    // If the user is not logged in, redirect to the login page
    header('Location: index.php');
    exit();
}
?>
