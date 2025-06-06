<?php
require '../../includes/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
    $user_id = $_SESSION['user_id']; // Logged-in user (Recipient)
    $sender_id = $_POST['user_id']; // The sender whose messages we are marking as read

    // ✅ Only mark messages as read where the logged-in user is the recipient
    $query = "UPDATE ingoing SET is_read = 1 WHERE recipient_id = ? AND user_id = ? AND is_read = 0";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $user_id, $sender_id);
    $stmt->execute();
    
    echo json_encode(["status" => "success"]);
}
?>
<?php
require '../includes/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
    $user_id = $_SESSION['user_id']; // Logged-in user (Recipient)
    $sender_id = $_POST['user_id']; // The sender whose messages we are marking as read

    // ✅ Only mark messages as read where the logged-in user is the recipient
    $query = "UPDATE ingoing SET is_read = 1 WHERE recipient_id = ? AND user_id = ? AND is_read = 0";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $user_id, $sender_id);
    $stmt->execute();
    
    echo json_encode(["status" => "success"]);
}
?>
