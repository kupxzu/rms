<?php
include '../includes/db.php';
session_start();

$id = $_POST['id'];

$sql = "DELETE FROM records WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    $_SESSION['success'] = "Record deleted successfully!";
} else {
    $_SESSION['error'] = "Error deleting record: " . $conn->error;
}

header('Location: manage_records.php');
exit();
?>
