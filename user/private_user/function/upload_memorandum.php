<?php
session_start();
include '../../../includes/db.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'Mayor' && $_SESSION['role'] !== 'ViceMayor')) {
    header("Location: ../../index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $vip_id = $_SESSION['user_id']; // VIP User ID
    $title = $_POST['title'];
    $description = $_POST['description'];

    $allowed_extensions = ['pdf', 'docx', 'jpg', 'jpeg', 'png'];
    $upload_dir = '../../../uploads/memorandums/';

    if (!empty($_FILES['file']['name'])) {
        $file_name = $_FILES['file']['name'];
        $file_tmp = $_FILES['file']['tmp_name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        if (!in_array($file_ext, $allowed_extensions)) {
            $_SESSION['error'] = "Invalid file type. Allowed: PDF, DOCX, JPG, JPEG, PNG.";
            header('Location: ../dashboard.php');
            exit();
        }

        // Determine file type
        $file_type = in_array($file_ext, ['jpg', 'jpeg', 'png']) ? 'image' : $file_ext;

        // Generate unique filename
        $new_file_name = uniqid() . '.' . $file_ext;
        $file_path = $upload_dir . $new_file_name;

        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        if (move_uploaded_file($file_tmp, $file_path)) {
            // Store memorandum in database
            $sql = "INSERT INTO memorandums (vip_id, title, description, attachment, file_type) 
                    VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("issss", $vip_id, $title, $description, $file_path, $file_type);

            if ($stmt->execute()) {
                $_SESSION['success'] = "Memorandum uploaded successfully.";
            } else {
                $_SESSION['error'] = "Database error: " . $stmt->error;
            }
        } else {
            $_SESSION['error'] = "File upload failed.";
        }
    } else {
        $_SESSION['error'] = "Please select a file.";
    }

    header('Location: ../dashboard.php');
    exit();
}
?>
