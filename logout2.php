<?php
session_start();
include 'includes/db.php'; // Include database connection

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Destroy session
    session_destroy();
}

// Redirect to login page
header('Location: index.php');
exit();
?>
