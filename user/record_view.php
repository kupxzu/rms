<?php
session_start();
require '../includes/db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $file_id = $_POST['file_id'];
    $user_id = $_SESSION['user_id'];

    // Check if the user has already viewed this file
    $check_query = "SELECT id FROM file_views WHERE file_id = ? AND user_id = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("ii", $file_id, $user_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 0) { // If no record exists, insert a new view
        $insert_query = "INSERT INTO file_views (file_id, user_id) VALUES (?, ?)";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("ii", $file_id, $user_id);
        $stmt->execute();
    }

    echo "Success";
}
?>
