<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized access."]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $upload_dir = '../uploads/private/';

    if (!empty($_FILES['file']['name'])) {
        $file_name = $_FILES['file']['name'];
        $file_tmp = $_FILES['file']['tmp_name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        $allowed_extensions = ['pdf', 'docx', 'jpg', 'jpeg', 'png'];
        if (!in_array($file_ext, $allowed_extensions)) {
            echo json_encode(["success" => false, "message" => "Invalid file type. Allowed: PDF, DOCX, JPG, JPEG, PNG."]);
            exit();
        }

        // Generate a unique filename
        $new_file_name = uniqid() . '.' . $file_ext;
        $file_path = $upload_dir . $new_file_name;

        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        if (move_uploaded_file($file_tmp, $file_path)) {
            // Store in database
            $sql = "INSERT INTO private_files (uploaded_by, title, description, file_name, file_type) 
                    VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("issss", $user_id, $title, $description, $new_file_name, $file_ext);

            if ($stmt->execute()) {
                echo json_encode(["success" => true, "message" => "File uploaded successfully."]);
            } else {
                echo json_encode(["success" => false, "message" => "Database error: " . $stmt->error]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "File upload failed."]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Please select a file."]);
    }
}
