<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    die("Access denied.");
}

if (isset($_GET['file_id'])) {
    $file_id = intval($_GET['file_id']);
    $user_id = $_SESSION['user_id'];

    // Get file details
    $query = "SELECT * FROM private_file WHERE id = ? AND uploaded_by = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "is", $file_id, $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        $file_path = '../uploads/private/' . $row['file_path'];

        if (file_exists($file_path)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($row['file_name']) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file_path));
            readfile($file_path);
            exit;
        } else {
            die("Error: File not found.");
        }
    } else {
        die("Error: You are not authorized to download this file.");
    }
} else {
    die("Error: No file ID provided.");
}
?>
