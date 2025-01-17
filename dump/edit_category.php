<?php
include '../includes/db.php';
session_start();

$id = $_POST['id'];
$name = $_POST['name'];

$sql = "UPDATE categories SET name = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $name, $id);

if ($stmt->execute()) {
    $_SESSION['success'] = "Category updated successfully!";
} else {
    $_SESSION['error'] = "Error updating category: " . $conn->error;
}

header('Location: manage_categories.php');
exit();
?>
