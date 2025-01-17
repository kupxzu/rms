<?php
session_start();
include 'includes/db.php'; // Include database connection

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Insert logout activity into user_activity table
    $activity_query = "INSERT INTO user_activity (user_id, action) VALUES (?, 'logout')";
    $stmt = $conn->prepare($activity_query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    // Destroy session
    session_destroy();
}

// Redirect to login page
header('Location: index.php');
exit();
?>
