<?php
session_start();
require '../includes/db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $file_type = $_POST['file_type'];
    $uploaded_by = $_SESSION['user_id'];
    $departments = $_POST['departments']; // Array of selected departments

    // File Upload
    $target_dir = "../uploads/files/";
    $file_name = time() . "_" . basename($_FILES["file"]["name"]);
    $target_file = $target_dir . $file_name;
    $file_extension = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Allowed file types
    $allowed_types = ["pdf", "doc", "docx", "png", "jpg"];
    if (!in_array($file_extension, $allowed_types)) {
        $_SESSION['error'] = "Invalid file type!";
        header("Location: admin_upload.php");
        exit();
    }

    if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
        // Insert into ordinances_resolutions table
        $query = "INSERT INTO ordinances_resolutions (title, description, file_path, file_type, uploaded_by) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssi", $title, $description, $file_name, $file_type, $uploaded_by);
        $stmt->execute();
        $file_id = $stmt->insert_id;

        // Assign file to departments
        if ($file_type == "Events") {
            // Assign file to ALL departments
            $dep_query = "SELECT id FROM departments";
            $dep_result = $conn->query($dep_query);
            while ($row = $dep_result->fetch_assoc()) {
                $insert_query = "INSERT INTO file_permissions (file_id, department_id) VALUES (?, ?)";
                $stmt = $conn->prepare($insert_query);
                $stmt->bind_param("ii", $file_id, $row['id']);
                $stmt->execute();
            }
        } else {
            // Assign file to selected departments
            foreach ($departments as $department_id) {
                $insert_query = "INSERT INTO file_permissions (file_id, department_id) VALUES (?, ?)";
                $stmt = $conn->prepare($insert_query);
                $stmt->bind_param("ii", $file_id, $department_id);
                $stmt->execute();
            }
        }

        $_SESSION['success'] = "File uploaded successfully!";
    } else {
        $_SESSION['error'] = "Failed to upload file.";
    }

    header("Location: admin_upload.php");
    exit();
}
?>
