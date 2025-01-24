<?php
session_start();
include '../../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id']; // The sender (who uploads the file)
    $recipient_id = $_POST['recipient_id'];

    if (empty($recipient_id) || !is_numeric($recipient_id)) {
        $_SESSION['error'] = "Please select a valid user before uploading a file.";
        header('Location: ../dashboard.php');
        exit();
    }

    $title = $_POST['title'];
    $description = $_POST['description'];

    $allowed_extensions = ['pdf', 'docx', 'jpg', 'jpeg', 'png'];
    $upload_dir = '../uploads/';

    if (!empty($_FILES['file']['name'])) {
        $file_name = $_FILES['file']['name'];
        $file_tmp = $_FILES['file']['tmp_name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        // Ensure the file extension is valid
        if (!in_array($file_ext, $allowed_extensions)) {
            $_SESSION['error'] = "Invalid file type. Allowed: PDF, DOCX, JPG, JPEG, PNG.";
            header('Location: ../dashboard.php');
            exit();
        }

        // Assign file type based on extension
        $file_type = '';
        if ($file_ext == 'pdf') {
            $file_type = 'pdf';
        } elseif ($file_ext == 'docx') {
            $file_type = 'docx';
        } elseif (in_array($file_ext, ['jpg', 'jpeg', 'png'])) {
            $file_type = 'image';
        } else {
            $_SESSION['error'] = "Unknown file type.";
            header('Location: ../dashboard.php');
            exit();
        }

        // Generate unique filename
        $new_file_name = uniqid() . '.' . $file_ext;
        $file_path = $upload_dir . $new_file_name;

        if (move_uploaded_file($file_tmp, $file_path)) {
            // Store file in database (Ensure `file_type` is not NULL)
            $sql = "INSERT INTO ingoing (user_id, recipient_id, title, description, attachment, file_type) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iissss", $user_id, $recipient_id, $title, $description, $file_path, $file_type);

            if ($stmt->execute()) {
                $_SESSION['success'] = "File uploaded successfully.";
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
