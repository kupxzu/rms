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
        } else {
            // If not found in `users`, check in `vip_users`
            $vip_sql = "SELECT * FROM vip_users WHERE username = ?";
            $vip_stmt = $conn->prepare($vip_sql);
            $vip_stmt->bind_param("s", $username);
            $vip_stmt->execute();
            $vip_result = $vip_stmt->get_result();

            if ($vip_result->num_rows > 0) {
                $vip_user = $vip_result->fetch_assoc();

                // Verify VIP password
                if (password_verify($password, $vip_user['password_hash'])) {
                    // Regenerate session to prevent session fixation
                    session_regenerate_id(true);

                    $_SESSION['user_id'] = $vip_user['id'];
                    $_SESSION['username'] = $vip_user['username'];
                    $_SESSION['role'] = $vip_user['position']; // Mayor or Vice Mayor

                    // Insert VIP login activity
                    $vip_activity_query = "INSERT INTO user_activity (user_id, action) VALUES (?, 'vip_login')";
                    $vip_log_stmt = $conn->prepare($vip_activity_query);
                    $vip_log_stmt->bind_param("i", $vip_user['id']);
                    $vip_log_stmt->execute();

                    // Redirect to VIP dashboard
                    header('Location: user/private_user/dashboard.php');
                    exit();
                }
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
