<?php
require '../includes/db.php';
session_start();

$user_id = $_SESSION['user_id'];

// Fetch event notifications
$event_query = "SELECT COUNT(*) AS count FROM ordinances_resolutions WHERE file_type = 'Events'";
$event_result = $conn->query($event_query);
$event_count = $event_result->fetch_assoc()['count'];

// Fetch admin notifications (unviewed ordinances & resolutions)
$notif_query = "SELECT COUNT(*) AS count FROM ordinances_resolutions 
                WHERE file_type IN ('Ordinance', 'Resolution') 
                AND id NOT IN (SELECT file_id FROM file_views WHERE user_id = ?)";
$stmt = $conn->prepare($notif_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$notif_result = $stmt->get_result();
$notif_count = $notif_result->fetch_assoc()['count'];

// Fetch unread messages from messages_with_attachments
$msg_query = "SELECT COUNT(*) AS count FROM messages_with_attachments 
              WHERE receiver_id = ? AND is_read = 0"; // Only unread messages
$stmt = $conn->prepare($msg_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$msg_result = $stmt->get_result();
$msg_count = $msg_result->fetch_assoc()['count'];

echo json_encode([
    "event_count" => $event_count,
    "notif_count" => $notif_count,
    "msg_count" => $msg_count
]);
?>
