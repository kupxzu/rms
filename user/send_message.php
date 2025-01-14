<?php
session_start();
include '../includes/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Invalid request method.']);
    exit();
}

$sender_id = $_SESSION['user_id'];
$receiver_id = $_POST['receiver_id'] ?? null;
$item_id = $_POST['related_item_id'] ?? null;
$item_type = $_POST['related_item_type'] ?? null;
$message = $_POST['message'] ?? null;

if (!$receiver_id || !$item_id || !$item_type || !$message) {
    echo json_encode(['error' => 'All fields are required.']);
    exit();
}

try {
    // Insert message into the database
    $sql = "
        INSERT INTO direct_messages (sender_id, receiver_id, related_item_id, related_item_type, message, sent_at)
        VALUES (?, ?, ?, ?, ?, NOW())
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiiss", $sender_id, $receiver_id, $item_id, $item_type, $message);

    if ($stmt->execute()) {
        echo json_encode(['success' => 'Message sent successfully.']);
    } else {
        throw new Exception("Database error: " . $stmt->error);
    }
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
