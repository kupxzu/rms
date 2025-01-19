<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['receiver_id'])) {
    echo json_encode(["success" => false, "error" => "Unauthorized"]);
    exit();
}

$receiver_id = intval($_GET['receiver_id']);
$sender_id = $_SESSION['user_id'];

// Fetch messages with timestamps
$sql = "SELECT id, sender_id, receiver_id, message, attachment, sent_at, is_read 
        FROM messages_with_attachments 
        WHERE (sender_id = ? AND receiver_id = ?) 
           OR (sender_id = ? AND receiver_id = ?) 
        ORDER BY sent_at ASC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iiii", $sender_id, $receiver_id, $receiver_id, $sender_id);
$stmt->execute();
$result = $stmt->get_result();

$messages = [];
while ($row = $result->fetch_assoc()) {
    // Convert timestamp to readable format (optional: format in PHP before sending to JS)
    $row['formatted_time'] = date("g:i A", strtotime($row['sent_at']));  // e.g., 3:45 PM
    $row['formatted_date'] = date("Y-m-d", strtotime($row['sent_at'])); // e.g., 2024-01-19
    $messages[] = $row;
}

// Mark messages as read when loaded
if (!empty($messages)) {
    $update_sql = "UPDATE messages_with_attachments SET is_read = 1 WHERE sender_id = ? AND receiver_id = ? AND is_read = 0";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ii", $receiver_id, $sender_id);
    $update_stmt->execute();
    $update_stmt->close();
}

echo json_encode($messages);

$stmt->close();
$conn->close();
?>
