<?php
session_start();
include 'includes/db.php'; // Include database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    try {
        // Prepare the statement for checking regular users
        $sql = "SELECT * FROM users WHERE username = ? AND active = 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Verify password for regular users
            if (password_verify($password, $user['password'])) {
                // Regenerate session to prevent session fixation
                session_regenerate_id(true);

                // Store session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role']; // Optional if roles exist

                // Log login activity
                $activity_query = "INSERT INTO user_activity (user_id, action) VALUES (?, 'login')";
                $log_stmt = $conn->prepare($activity_query);
                $log_stmt->bind_param("i", $user['id']);
                $log_stmt->execute();

                // Redirect to regular dashboard
                header('Location: admin/dashboard.php');
                exit();
            }
        }

        // If login fails
        $_SESSION['error'] = "Invalid username or password.";
        header('Location: index.php');
        exit();
    } catch (Exception $e) {
        // Log error and show a generic message
        error_log("Login Error: " . $e->getMessage());
        $_SESSION['error'] = "An unexpected error occurred. Please try again.";
        header('Location: index.php');
        exit();
    }
}
?>
