<?php
include '../includes/db.php';
session_start();

$id = $_POST['id'];

$sql = "DELETE FROM categories WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    $_SESSION['success'] = "Category deleted successfully!";
} else {
    $_SESSION['error'] = "Error deleting category: " . $conn->error;
}

header('Location: manage_categories.php');
exit();
?>
