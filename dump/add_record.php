<?php
include '../includes/db.php';
session_start();

$title = $_POST['title'];
$description = $_POST['description'];
$category_id = $_POST['category_id'] ?: null;

$sql = "INSERT INTO records (title, description, category_id) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssi", $title, $description, $category_id);

if ($stmt->execute()) {
    $_SESSION['success'] = "Record added successfully!";
} else {
    $_SESSION['error'] = "Error adding record: " . $conn->error;
}

header('Location: manage_records.php');
exit();
?>
