<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    echo "Unauthorized action!";
    exit();
}
include '../../includes/db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["user_id"])) {
    $user_id = $_POST["user_id"];

    $sql = "UPDATE users SET active = 1 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        echo "User successfully restored!";
    } else {
        echo "Error restoring user.";
    }
}
?>
