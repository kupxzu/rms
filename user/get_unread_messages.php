<?php
require '../../includes/db.php';
session_start();

$user_id = $_SESSION['user_id']; // Logged-in user (Recipient)
$response = [];

// ✅ Fetch unread messages where the logged-in user is the recipient
$query = "SELECT user_id, COUNT(*) AS unread_files 
          FROM ingoing 
          WHERE is_read = 0 
          AND recipient_id = ?  -- ✅ Ensure only messages received by the user count
          GROUP BY user_id";  // ✅ Group by sender

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $response[$row['user_id']] = $row['unread_files'];  // ✅ Show unread count next to sender
}

// ✅ Return unread count for each sender
echo json_encode($response);
?>
<?php
require '../includes/db.php';
session_start();

$user_id = $_SESSION['user_id']; // Logged-in user (Recipient)
$response = [];

// ✅ Fetch unread messages where the logged-in user is the recipient
$query = "SELECT user_id, COUNT(*) AS unread_files 
          FROM ingoing 
          WHERE is_read = 0 
          AND recipient_id = ?  -- ✅ Ensure only messages received by the user count
          GROUP BY user_id";  // ✅ Group by sender

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $response[$row['user_id']] = $row['unread_files'];  // ✅ Show unread count next to sender
}

// ✅ Return unread count for each sender
echo json_encode($response);
?>
