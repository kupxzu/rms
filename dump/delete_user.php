<?php
include '../includes/db.php';
session_start();

$id = $_POST['id'];

$sql = "DELETE FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    $_SESSION['success'] = "User deleted successfully!";
} else {
    $_SESSION['error'] = "Error deleting user: " . $conn->error;
}

header('Location: manage_users.php');
exit();
?>
