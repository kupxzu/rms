<?php
session_start();
include '../../includes/db.php';

if (!isset($_GET['id'])) {
    $_SESSION['error'] = "Resolution ID not specified.";
    header('Location: ../pending_resolution.php');
    exit();
}

$id = $_GET['id'];

// Update the resolution status to "Approved"
$sql = "UPDATE resolutions SET status = 'Approved' WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    $_SESSION['success'] = "Resolution has been approved successfully!";
} else {
    $_SESSION['error'] = "Failed to approve the resolution: " . $stmt->error;
}

header('Location: ../pending_resolution.php');
exit();
?>
