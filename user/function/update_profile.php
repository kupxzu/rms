<?php
session_start();
require '../../includes/db.php'; 

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id = $_POST['user_id'];
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $email = trim($_POST['email']);

    // Validate inputs
    if (empty($firstname) || empty($lastname) || empty($email)) {
        $_SESSION['error'] = "All fields are required!";
        header("Location: ../profile.php");
        exit();
    }

    // Update user details (without profile picture)
    $query = "UPDATE users SET firstname = ?, lastname = ?, email = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssi", $firstname, $lastname, $email, $user_id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Profile updated successfully!";
    } else {
        $_SESSION['error'] = "Failed to update profile.";
    }

    header("Location: ../profile.php");
    exit();
}
?>
