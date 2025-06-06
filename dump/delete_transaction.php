<?php
include '../includes/db.php';
session_start();

$id = $_POST['id'];

$sql = "DELETE FROM transactions WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    $_SESSION['success'] = "Transaction deleted successfully!";
} else {
    $_SESSION['error'] = "Error deleting transaction: " . $conn->error;
}

header('Location: manage_transactions.php');
exit();
?>