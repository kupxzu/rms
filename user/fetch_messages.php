<?php
session_start();
include '../includes/db.php';

$receiverId = $_GET['receiver_id'] ?? null;
$senderId = $_SESSION['user_id'];
$markAsSeen = isset($_GET['mark_as_seen']) ? (bool) $_GET['mark_as_seen'] : false;

if (!$receiverId) {
    echo json_encode([]);
    exit();
}

// Optionally mark messages as "seen"
if ($markAsSeen) {
    $updateSeenSql = "UPDATE messages_with_attachments SET is_read = 1 WHERE sender_id = ? AND receiver_id = ?";
    $stmt = $conn->prepare($updateSeenSql);
    $stmt->bind_param("ii", $receiverId, $senderId);
    $stmt->execute();
}

// Fetch messages
$sql = "
    SELECT 
        m.id,
        m.message,
        m.attachment,
        m.sent_at,
        CASE 
            WHEN m.sender_id = ? THEN 'You'
            ELSE u.username
        END AS sender
    FROM messages_with_attachments m
    JOIN users u ON m.sender_id = u.id
    WHERE (m.sender_id = ? AND m.receiver_id = ?)
       OR (m.sender_id = ? AND m.receiver_id = ?)
    ORDER BY m.sent_at ASC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iiiii", $senderId, $senderId, $receiverId, $receiverId, $senderId);
$stmt->execute();
$result = $stmt->get_result();

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}

echo json_encode($messages);
?>
