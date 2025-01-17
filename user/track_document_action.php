<?php
session_start();
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $documentId = $_POST['document_id'] ?? null;
    $documentType = $_POST['document_type'] ?? null;
    $action = $_POST['action'] ?? null;
    $userId = $_SESSION['user_id'];

    if (!$documentId || !$documentType || !$action) {
        echo json_encode(['success' => false, 'error' => 'Invalid parameters.']);
        exit();
    }

    $sql = "
        INSERT INTO document_views (user_id, document_id, document_type, action)
        VALUES (?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE action_timestamp = CURRENT_TIMESTAMP
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iiss', $userId, $documentId, $documentType, $action);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to log the action.']);
    }
    exit();
}
?>
