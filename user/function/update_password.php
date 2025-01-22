<?php
session_start();
require '../../includes/db.php'; // Adjust path if necessary

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $current_password = trim($_POST['current_password']);
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Fetch the current password from the database
    $query = "SELECT password FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->store_result();

    // If user exists, verify the current password
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashed_password);
        $stmt->fetch();

        // Check if current password is correct
        if (!password_verify($current_password, $hashed_password)) {
            $_SESSION['message'] = "<div class='alert alert-danger'>Current password is incorrect.</div>";
            header("Location: ../profile.php#password");
            exit();
        }

        // Check if new password matches confirmation
        if ($new_password !== $confirm_password) {
            $_SESSION['message'] = "<div class='alert alert-danger'>New password and confirm password do not match.</div>";
            header("Location: ../profile.php#password");
            exit();
        }

        // Validate password length
        if (strlen($new_password) < 6) {
            $_SESSION['message'] = "<div class='alert alert-warning'>New password must be at least 6 characters long.</div>";
            header("Location: ../profile.php#password");
            exit();
        }

        // Hash the new password
        $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Update the password in the database
        $update_query = "UPDATE users SET password = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("si", $new_hashed_password, $user_id);

        if ($update_stmt->execute()) {
            $_SESSION['message'] = "<div class='alert alert-success'>Password updated successfully!</div>";
            header("Location: ../profile.php#password");
            exit();
        } else {
            $_SESSION['message'] = "<div class='alert alert-danger'>Failed to update password. Try again.</div>";
            header("Location: ../profile.php#password");
            exit();
        }
    } else {
        $_SESSION['message'] = "<div class='alert alert-danger'>User not found.</div>";
        header("Location: ../profile.php#password");
        exit();
    }
} else {
    $_SESSION['message'] = "<div class='alert alert-danger'>Invalid request.</div>";
    header("Location: ../profile.php#password");
    exit();
}
?>
