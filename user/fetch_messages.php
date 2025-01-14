<?php
session_start();
include '../includes/db.php';

$item_id = $_GET['item_id'] ?? null;
$item_type = $_GET['item_type'] ?? null;

if (!$item_id || !$item_type) {
    echo json_encode([]);
    exit();
}

$sql = "
    SELECT dm.*, u.firstname AS sender_name
    FROM direct_messages dm
    JOIN users u ON dm.sender_id = u.id
    WHERE dm.related_item_id = ? AND dm.related_item_type = ?
    ORDER BY dm.sent_at ASC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $item_id, $item_type);
$stmt->execute();
$result = $stmt->get_result();

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}

echo json_encode($messages);
?>
