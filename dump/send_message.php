<?php
session_start();
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Invalid request method.']);
    exit();
}

$sender_id = $_SESSION['user_id'];
$receiver_id = $_POST['receiver_id'];
$message = $_POST['message'] ?? null;
$item_id = $_POST['related_item_id'];
$item_type = $_POST['related_item_type'];

$attachment = null;
if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = '../uploads/messages/';
    $attachment = basename($_FILES['attachment']['name']);
    move_uploaded_file($_FILES['attachment']['tmp_name'], $uploadDir . $attachment);
}

$sql = "INSERT INTO messages_with_attachments (sender_id, receiver_id, message, attachment, related_item_id, related_item_type) 
        VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iissis", $sender_id, $receiver_id, $message, $attachment, $item_id, $item_type);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'Message sending failed.']);
}
?>
