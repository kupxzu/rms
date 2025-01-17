<?php
include '../includes/db.php';
session_start();

$name = $_POST['name'];

$sql = "INSERT INTO categories (name) VALUES (?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $name);

if ($stmt->execute()) {
    $_SESSION['success'] = "Category added successfully!";
} else {
    $_SESSION['error'] = "Error adding category: " . $conn->error;
}

header('Location: manage_categories.php');
exit();
?>
