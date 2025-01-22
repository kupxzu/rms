<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "error" => "Unauthorized"]);
    exit();
}

$current_user_id = $_SESSION['user_id'];
$search_query = isset($_GET['search']) ? "%".$_GET['search']."%" : "%";

// 🔹 If searching, show all users
if (!empty($_GET['search'])) {
    $sql = "
        SELECT u.id, u.username, u.firstname, u.lastname, u.profile_pic, d.name AS division, p.name AS position,
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
        AND u.role != 'admin' 
        AND (u.username LIKE ? OR u.firstname LIKE ? OR u.lastname LIKE ?) 
        ORDER BY last_message_time DESC, username ASC";

    // 🔹 Match placeholders (`?`) with correct parameters
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiiisss", $current_user_id, $current_user_id, $current_user_id, $current_user_id, $search_query, $search_query, $search_query);

} else {
    // 🔹 If NOT searching, show only users where messages were exchanged
    $sql = "
    SELECT 
        u.id, 
        u.username, 
        u.firstname, 
        u.lastname, 
        u.profile_pic, 
        d.name AS division, 
        p.name AS position,
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
    AND u.role != 'admin' 
    AND (
        EXISTS (SELECT 1 FROM messages_with_attachments m WHERE m.sender_id = ? AND m.receiver_id = u.id)
        OR EXISTS (SELECT 1 FROM messages_with_attachments m WHERE m.sender_id = u.id AND m.receiver_id = ?)
    ) -- 🔥 Show only users you exchanged messages with
    ORDER BY last_message_time DESC, username ASC";

    // 🔹 Ensure `bind_param()` matches `?` placeholders
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiiiii", $current_user_id, $current_user_id, $current_user_id, $current_user_id, $current_user_id, $current_user_id);
}

$stmt->execute();
$result = $stmt->get_result();

$users = [];
while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}

// 🔹 Debugging: If no users are found, write to log file
if (empty($users)) {
    file_put_contents("debug_users.txt", "No users found for search: " . ($_GET['search'] ?? "No search") . "\n", FILE_APPEND);
}

echo json_encode($users);
$stmt->close();
$conn->close();
?>
