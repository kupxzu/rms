<?php
include '../includes/db.php';
session_start();

$id = $_POST['id'];
$title = $_POST['title'];
$description = $_POST['description'];
$category_id = $_POST['category_id'] ?: null;

$sql = "UPDATE records SET title = ?, description = ?, category_id = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssii", $title, $description, $category_id, $id);

if ($stmt->execute()) {
    $_SESSION['success'] = "Record updated successfully!";
} else {
    $_SESSION['error'] = "Error updating record: " . $conn->error;
}

header('Location: manage_records.php');
exit();
?>
