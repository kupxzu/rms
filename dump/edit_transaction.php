<?php
include '../includes/db.php';
session_start();

$id = $_POST['id'];
$record_id = $_POST['record_id'];
$user_id = $_POST['user_id'];
$transaction_type = $_POST['transaction_type'];

$sql = "UPDATE transactions SET record_id = ?, user_id = ?, transaction_type = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iisi", $record_id, $user_id, $transaction_type, $id);

if ($stmt->execute()) {
    $_SESSION['success'] = "Transaction updated successfully!";
} else {
    $_SESSION['error'] = "Error updating transaction: " . $conn->error;
}

header('Location: manage_transactions.php');
exit();
?>