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
            $_SESSION['message'] = "<div class='alert alert-danger'>Invalid file type. Only JPG and PNG are allowed.</div>";
            header("Location: ../profile.php");
            exit();
        }

        // Limit file size (Max: 2MB)
        if ($_FILES["new_profile_pic"]["size"] > 2 * 1024 * 1024) {
            $_SESSION['message'] = "<div class='alert alert-warning'>File size too large! Max: 2MB.</div>";
            header("Location: ../profile.php");
            exit();
        }

        // Rename file (Avoid duplicate names)
        $new_file_name = "profile_" . $user_id . "_" . time() . "." . $imageFileType;
        $target_file = $target_dir . $new_file_name;

        // Move uploaded file
        if (!move_uploaded_file($_FILES["new_profile_pic"]["tmp_name"], $target_file)) {
            $_SESSION['message'] = "<div class='alert alert-danger'>Failed to upload profile picture.</div>";
            header("Location: ../profile.php");
            exit();
        }

        // Update database with new image
        $query = "UPDATE users SET profile_pic = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $new_file_name, $user_id);

        if ($stmt->execute()) {
            $_SESSION['message'] = "<div class='alert alert-success'>Profile picture updated successfully!</div>";
        } else {
            $_SESSION['message'] = "<div class='alert alert-danger'>Failed to update profile picture.</div>";
        }
    } else {
        $_SESSION['message'] = "<div class='alert alert-warning'>No file selected.</div>";
    }

    header("Location: ../profile.php");
    exit();
}
?>
