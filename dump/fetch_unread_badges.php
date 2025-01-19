<?php
session_start();
include '../includes/db.php';

$receiverId = $_SESSION['user_id'];

$sql = "
    SELECT sender_id, COUNT(*) AS unread_count
    FROM messages_with_attachments
    WHERE receiver_id = ? AND is_read = 0
    GROUP BY sender_id
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $receiverId);
$stmt->execute();
$result = $stmt->get_result();

$unreadCounts = [];
while ($row = $result->fetch_assoc()) {
    $unreadCounts[$row['sender_id']] = $row['unread_count'];
}

echo json_encode($unreadCounts);
?>
