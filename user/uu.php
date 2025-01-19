<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "error" => "Unauthorized"]);
    exit();
}

$current_user_id = $_SESSION['user_id'];
$search_query = isset($_GET['search']) ? "%".$_GET['search']."%" : "%";

// Show all users if searching, otherwise only show users that were replied to
if (!empty($_GET['search'])) {
    // Search for all users (even if not chatted with before)
    $sql = "
        SELECT u.id, u.username,
               (SELECT COUNT(*) FROM messages_with_attachments 
                WHERE sender_id = u.id AND receiver_id = ? AND is_read = 0) AS unread_count,
               (SELECT MAX(sent_at) FROM messages_with_attachments 
                WHERE (sender_id = u.id AND receiver_id = ?) OR (sender_id = ? AND receiver_id = u.id)) 
                AS last_message_time
        FROM users u
        WHERE u.id != ? AND u.username LIKE ?
        ORDER BY last_message_time DESC, username ASC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiiis", $current_user_id, $current_user_id, $current_user_id, $current_user_id, $search_query);
} else {
    // Show only users where the logged-in user **sent or received** at least one message
    $sql = "
    SELECT 
        u.id, 
        u.username, 
        u.firstname, 
        u.lastname, 
        u.profile_pic, 
        d.name AS division, 
        p.name AS position,
        u.role, -- Get role to filter out admins
        (SELECT COUNT(*) FROM messages_with_attachments 
         WHERE sender_id = u.id AND receiver_id = ? AND is_read = 0) AS unread_count,
        (SELECT MAX(sent_at) FROM messages_with_attachments 
         WHERE (sender_id = u.id AND receiver_id = ?) OR (sender_id = ? AND receiver_id = u.id)) 
        AS last_message_time
    FROM users u
    LEFT JOIN department_position dp ON u.id_dp = dp.id
    LEFT JOIN departments d ON dp.department_id = d.id
    LEFT JOIN positions p ON dp.position_id = p.id
    WHERE u.id != ? 
    AND u.role != 'admin' -- 🔥 Hide admins
    AND u.username LIKE ?
    ORDER BY last_message_time DESC, username ASC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiiis", $current_user_id, $current_user_id, $current_user_id, $current_user_id, $search_query);
    $stmt->execute();
    $result = $stmt->get_result();
}

$stmt->execute();
$result = $stmt->get_result();

$users = [];
while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}

// Debugging: If no users are found, write to log file
if (empty($users)) {
    file_put_contents("debug_users.txt", "No users found for search: " . $_GET['search'] . "\n", FILE_APPEND);
}

echo json_encode($users);
$stmt->close();
$conn->close();
?>
