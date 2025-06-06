<?php
require '../../includes/db.php';
session_start();

$user_id = $_SESSION['user_id']; // Get logged-in user ID
$response = [];

$query = "SELECT recipient_id, COUNT(*) AS unread_files 
          FROM ingoing 
          WHERE is_read = 0 
          AND recipient_id = ?  -- Only show notifications for the recipient (User2)
          GROUP BY recipient_id";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $response[$row['recipient_id']] = $row['unread_files'];
}

echo json_encode($response);
?>
