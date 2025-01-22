<?php
session_start();
include 'includes/db.php'; // Include database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query to check if the user exists and is active
    $sql = "SELECT * FROM users WHERE username = ? AND active = 1"; // Only active users can log in
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role']; // Optional if roles are defined

            // Insert login activity into user_activity table
            $activity_query = "INSERT INTO user_activity (user_id, action) VALUES (?, 'login')";
            $stmt = $conn->prepare($activity_query);
            $stmt->bind_param("i", $user['id']);
            $stmt->execute();

            // Redirect to dashboard or another page
            header('Location: admin/dashboard.php');
            exit();
        } else {
            $_SESSION['error'] = "Username or password is incorrect.";
        }
    } else {
        $_SESSION['error'] = "Login credentials don't match in our records.";
    }

    // Redirect back to the login page with an error message
    header('Location: index.php');
    exit();
}
?>
