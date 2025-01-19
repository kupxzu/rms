<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "error" => "Unauthorized"]);
    exit();
}

$sender_id = $_SESSION['user_id'];
$receiver_id = intval($_POST['receiver_id']);
$message = isset($_POST['message']) ? trim($_POST['message']) : "";
$attachment = null;

// Handle File Upload
if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
    $target_dir = "../uploads/messages/";
    
    // Ensure directory exists
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    $file_name = time() . "_" . basename($_FILES["attachment"]["name"]);
    $target_file = $target_dir . $file_name;
    $file_extension = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'docx', 'txt'];
    
    if (in_array($file_extension, $allowed_extensions)) {
        if (move_uploaded_file($_FILES["attachment"]["tmp_name"], $target_file)) {
            $attachment = $file_name;
        } else {
            echo json_encode(["success" => false, "error" => "File upload failed"]);
            exit();
        }
    } else {
        echo json_encode(["success" => false, "error" => "Invalid file type"]);
        exit();
    }
}

// Insert message into database
$sql = "INSERT INTO messages_with_attachments (sender_id, receiver_id, message, attachment) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iiss", $sender_id, $receiver_id, $message, $attachment);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "attachment" => $attachment]);
} else {
    echo json_encode(["success" => false, "error" => $conn->error]);
}

$stmt->close();
$conn->close();
?>
