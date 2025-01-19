<?php
session_start();
include '../includes/db.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["error" => "Invalid request method."]);
    exit();
}

$sender_id = $_SESSION["user_id"];
$receiver_id = $_POST["receiver_id"] ?? null;
$message = $_POST["message"] ?? null;
$attachment = null;

// Handle attachment upload
if (!empty($_FILES["attachment"]["name"])) {
    $targetDir = "../uploads/messages/";
    $fileName = time() . "_" . basename($_FILES["attachment"]["name"]);
    $targetFilePath = $targetDir . $fileName;
    
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
    $allowedTypes = ["pdf", "png", "jpg", "jpeg", "doc", "docx"];
    
    if (in_array(strtolower($fileType), $allowedTypes)) {
        if (move_uploaded_file($_FILES["attachment"]["tmp_name"], $targetFilePath)) {
            $attachment = $fileName;
        }
    }
}

if (!$receiver_id || !$message) {
    echo json_encode(["error" => "All fields are required."]);
    exit();
}

// Insert message into database
$sql = "INSERT INTO messages_with_attachments (sender_id, receiver_id, message, attachment, sent_at) VALUES (?, ?, ?, ?, NOW())";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iiss", $sender_id, $receiver_id, $message, $attachment);

if ($stmt->execute()) {
    echo json_encode(["success" => "Message sent successfully."]);
} else {
    echo json_encode(["error" => "Failed to send message."]);
}
?>
