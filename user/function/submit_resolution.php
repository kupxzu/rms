<?php
session_start();
include '../../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error'] = "Invalid request.";
    header('Location: ../submit_resolution.php');
    exit();
}

$title = $_POST['title'] ?? null;
$description = $_POST['description'] ?? null;
$user_id = $_SESSION['user_id'];

if (!$title || !$description) {
    $_SESSION['error'] = "All fields are required.";
    header('Location: ../submit_resolution.php');
    exit();
}

// Handle file upload
$attachment = null;
if (!empty($_FILES['attachment']['name'])) {
    $upload_dir = '../../uploads/resolution/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $file_name = time() . '_' . basename($_FILES['attachment']['name']);
    $target_file = $upload_dir . $file_name;

    if (move_uploaded_file($_FILES['attachment']['tmp_name'], $target_file)) {
        $attachment = $file_name;
    } else {
        $_SESSION['error'] = "Failed to upload the attachment.";
        header('Location: ../submit_resolution.php');
        exit();
    }
}

// Insert into database
$sql = "INSERT INTO resolutions (title, description, submitted_by, status, submission_date, attachment) 
        VALUES (?, ?, ?, 'Pending', NOW(), ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssis", $title, $description, $user_id, $attachment);

if ($stmt->execute()) {
    $_SESSION['success'] = "Resolution submitted successfully!";
} else {
    $_SESSION['error'] = "Failed to submit resolution: " . $stmt->error;
}

header('Location: ../submit_resolution.php');
exit();
?>
