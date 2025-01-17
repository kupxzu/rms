<?php
session_start();
include '../includes/db.php';

$currentUserId = $_SESSION['user_id'];
$searchQuery = $_POST['search_query'] ?? '';

// Modify SQL query to filter users based on the search query
$sql = "
SELECT 
    u.id AS user_id,
    u.username,
    u.firstname,
    u.lastname,
    d.name AS division_name,
    p.name AS position_name,
    COUNT(dm.id) AS unread_messages
FROM users u
LEFT JOIN department_position dp ON u.id_dp = dp.id
LEFT JOIN departments d ON dp.department_id = d.id
LEFT JOIN positions p ON dp.position_id = p.id
LEFT JOIN messages_with_attachments dm 
    ON dm.sender_id = u.id
    AND dm.receiver_id = $currentUserId
WHERE u.id != $currentUserId
";

// Apply search filter if a query exists
if (!empty($searchQuery)) {
    $sql .= " AND (u.username LIKE ? OR u.firstname LIKE ? OR u.lastname LIKE ? OR d.name LIKE ?)";
}

$sql .= " GROUP BY u.id ORDER BY unread_messages DESC, u.username ASC";

$stmt = $conn->prepare($sql);

if (!empty($searchQuery)) {
    $likeQuery = "%$searchQuery%";
    $stmt->bind_param("ssss", $likeQuery, $likeQuery, $likeQuery, $likeQuery);
}

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        ?>
        <div class="list-group-item">
            <div class="d-flex align-items-center">
                <!-- Profile Image -->
                <img src="profile.png" 
                     alt="Profile Image" 
                     class="rounded-circle mr-3" 
                     style="width:50px; height:50px;">
                <!-- Content -->
                <div class="flex-grow-1">
                    <h5 class="mb-1"><?php echo htmlspecialchars($row['firstname']); ?> <?php echo htmlspecialchars($row['lastname']); ?></h5>
                    <p class="mb-1">
                        <strong><?php echo htmlspecialchars($row['division_name'] ?? 'No Division'); ?></strong> &nbsp;
                        <br>
                        <small><?php echo htmlspecialchars($row['position_name'] ?? 'No Position'); ?></small>
                    </p>
                </div>

                <!-- Actions -->
                <div class="ml-auto">
                    <button 
                        class="btn btn-primary btn-sm btn-reply" 
                        data-username="<?php echo htmlspecialchars($row['username']); ?>"
                        data-user-id="<?php echo htmlspecialchars($row['user_id']); ?>">
                        <i class="fas fa-reply"></i> Reply
                    </button>
                </div>
            </div>
        </div>
        <?php
    }
} else {
    echo '<div class="text-center text-muted">No users found.</div>';
}
?>
