<?php
session_start();
require '../includes/db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $file_id = $_POST['file_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $file_type = $_POST['file_type'];
    $departments = isset($_POST['departments']) ? $_POST['departments'] : [];

    // File update query
    $query = "UPDATE ordinances_resolutions SET title = ?, description = ?, file_type = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssi", $title, $description, $file_type, $file_id);
    $stmt->execute();

    // Handle file replacement (optional)
    if (!empty($_FILES['file']['name'])) {
        $target_dir = "../uploads/files/";
        $file_name = basename($_FILES["file"]["name"]);
        $target_file = $target_dir . $file_name;

        if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
            // Update the file path in the database
            $update_file_query = "UPDATE ordinances_resolutions SET file_path = ? WHERE id = ?";
            $stmt = $conn->prepare($update_file_query);
            $stmt->bind_param("si", $file_name, $file_id);
            $stmt->execute();
        } else {
            $_SESSION['error'] = "Error uploading the new file.";
            header("Location: admin_upload.php");
            exit();
        }
    }

    // Remove old department permissions only if file type isn't 'Events'
    if ($file_type !== "Events") {
        $delete_query = "DELETE FROM file_permissions WHERE file_id = ?";
        $stmt = $conn->prepare($delete_query);
        $stmt->bind_param("i", $file_id);
        $stmt->execute();

        // Insert new department permissions
        foreach ($departments as $department_id) {
            $insert_query = "INSERT INTO file_permissions (file_id, department_id) VALUES (?, ?)";
            $stmt = $conn->prepare($insert_query);
            $stmt->bind_param("ii", $file_id, $department_id);
            $stmt->execute();
        }
    } else {
        // If file type is "Events", apply it to all departments
        $delete_query = "DELETE FROM file_permissions WHERE file_id = ?";
        $stmt = $conn->prepare($delete_query);
        $stmt->bind_param("i", $file_id);
        $stmt->execute();

        $all_departments_query = "SELECT id FROM departments";
        $result = $conn->query($all_departments_query);
        while ($row = $result->fetch_assoc()) {
            $department_id = $row['id'];
            $insert_query = "INSERT INTO file_permissions (file_id, department_id) VALUES (?, ?)";
            $stmt = $conn->prepare($insert_query);
            $stmt->bind_param("ii", $file_id, $department_id);
            $stmt->execute();
        }
    }

    $_SESSION['success'] = "File updated successfully!";
    header("Location: admin_upload.php");
    exit();
}
?>
