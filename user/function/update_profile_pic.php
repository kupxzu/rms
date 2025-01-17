<?php
session_start();
require '../../includes/db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id = $_POST['user_id'];

    // Check if a file is uploaded
    if (!empty($_FILES['new_profile_pic']['name'])) {
        $target_dir = "../../uploads/profile_pics/";
        $file_name = basename($_FILES["new_profile_pic"]["name"]);
        $imageFileType = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        // Allowed file types
        $allowed_types = ["jpg", "jpeg", "png"];
        if (!in_array($imageFileType, $allowed_types)) {
            $_SESSION['error'] = "Invalid file type. Only JPG and PNG are allowed.";
            header("Location: ../profile.php");
            exit();
        }

        // Limit file size (Max: 2MB)
        if ($_FILES["new_profile_pic"]["size"] > 2 * 1024 * 1024) {
            $_SESSION['error'] = "File size too large! Max: 2MB.";
            header("Location: ../profile.php");
            exit();
        }

        // Rename file (Avoid duplicate names)
        $new_file_name = "profile_" . $user_id . "_" . time() . "." . $imageFileType;
        $target_file = $target_dir . $new_file_name;

        // Move uploaded file
        if (!move_uploaded_file($_FILES["new_profile_pic"]["tmp_name"], $target_file)) {
            $_SESSION['error'] = "Failed to upload profile picture.";
            header("Location: ../profile.php");
            exit();
        }

        // Update database with new image
        $query = "UPDATE users SET profile_pic = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $new_file_name, $user_id);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Profile picture updated successfully!";
        } else {
            $_SESSION['error'] = "Failed to update profile picture.";
        }
    } else {
        $_SESSION['error'] = "No file selected.";
    }

    header("Location: ../profile.php");
    exit();
}
?>
