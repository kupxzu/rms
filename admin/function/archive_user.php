<?php
session_start();
require '../../includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];

    // Update user status to archived (active = 0)
    $sql = "UPDATE users SET active = 0 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "User archived successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to archive user."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request."]);
}
?>
