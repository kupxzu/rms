<?php
session_start();
include '../../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error'] = "Invalid request method.";
    header('Location: ../pending_resolution.php');
    exit();
}

$item_id = $_POST['item_id'] ?? null;
$item_type = 'resolution';
$rejection_title = $_POST['rejection_title'] ?? null;
$rejection_reason = $_POST['rejection_reason'] ?? null;

if (!$item_id || !$rejection_title || !$rejection_reason) {
    $_SESSION['error'] = "All fields are required.";
    header('Location: ../pending_resolution.php');
    exit();
}

try {
    $conn->begin_transaction();

    $update_sql = "UPDATE resolutions SET status = 'Rejected' WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("i", $item_id);
    if (!$update_stmt->execute()) {
        throw new Exception("Failed to update resolution status: " . $update_stmt->error);
    }

    $insert_sql = "INSERT INTO reject_reasons (item_id, item_type, rejection_title, rejection_reason, rejection_date) 
                   VALUES (?, ?, ?, ?, NOW())";
    $insert_stmt = $conn->prepare($insert_sql);
    $insert_stmt->bind_param("isss", $item_id, $item_type, $rejection_title, $rejection_reason);
    if (!$insert_stmt->execute()) {
        throw new Exception("Failed to log the rejection reason: " . $insert_stmt->error);
    }

    $conn->commit();
    $_SESSION['success'] = "Resolution rejected and reason logged successfully.";
} catch (Exception $e) {
    $conn->rollback();
    $_SESSION['error'] = $e->getMessage();
}

header('Location: ../pending_resolution.php');
exit();


?>
