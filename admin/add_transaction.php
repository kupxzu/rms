<?php
include '../includes/db.php';
session_start();

$record_id = $_POST['record_id'];
$user_id = $_POST['user_id'];
$transaction_type = $_POST['transaction_type'];

$sql = "INSERT INTO transactions (record_id, user_id, transaction_type) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iis", $record_id, $user_id, $transaction_type);

if ($stmt->execute()) {
    $_SESSION['success'] = "Transaction added successfully!";
} else {
    $_SESSION['error'] = "Error adding transaction: " . $conn->error;
}

header('Location: manage_transactions.php');
exit();
?>
