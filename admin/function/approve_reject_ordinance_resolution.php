<?php
session_start();
include '../../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = $_GET['id'] ?? null;
    $type = $_GET['type'] ?? null;

    if (!$id || !$type || $type !== 'ordinance') {
        $_SESSION['error'] = "Invalid parameters provided.";
        header('Location: ../pending_ordinance.php');
        exit();
    }

    $table = $type === 'ordinance' ? 'ordinances' : 'resolutions';
    $sql = "UPDATE $table SET status = 'Approved' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $_SESSION['success'] = ucfirst($type) . " has been approved successfully!";
    } else {
        $_SESSION['error'] = "Failed to approve the " . $type . ": " . $stmt->error;
    }

    header('Location: ../pending_' . $type . '.php');
    exit();
} else {
    $_SESSION['error'] = "Invalid request method.";
    header('Location: ../pending_ordinance.php');
    exit();
}
