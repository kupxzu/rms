<?php
session_start();
include '../includes/db.php';

$currentUserId = $_SESSION['user_id'];
$searchQuery = $_POST['search'] ?? '';

// Validate input
$searchQuery = trim($searchQuery);
$searchQuery = $conn->real_escape_string($searchQuery);

$sql = "
SELECT 
    u.id AS user_id,
    u.username,
    u.firstname,
    u.lastname,
    u.profile_pic,  -- Fetch profile picture
    d.name AS division_name,
    p.name AS position_name
FROM users u
LEFT JOIN department_position dp ON u.id_dp = dp.id
LEFT JOIN departments d ON dp.department_id = d.id
LEFT JOIN positions p ON dp.position_id = p.id
WHERE (u.username LIKE '%$searchQuery%' 
    OR u.firstname LIKE '%$searchQuery%' 
    OR u.lastname LIKE '%$searchQuery%' 
    OR d.name LIKE '%$searchQuery%')
AND u.id != $currentUserId
ORDER BY u.username ASC
";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Use a default profile picture if none is set
        $profilePicture = !empty($row['profile_pic']) ? '../uploads/profile_pics/' . htmlspecialchars($row['profile_pic']) : '../uploads/profile_pics/default.png';
?>
    <div class="dropdown-item list-group-item">
        <div class="d-flex align-items-center">
            <!-- Profile Image -->
            <img src="<?= $profilePicture ?>" 
                 alt="Profile Image" 
                 class="rounded-circle mr-3" 
                 style="width:50px; height:50px; object-fit: cover;">

            <!-- User Details -->
            <div class="flex-grow-1">
                <h5 class="mb-1"><?= htmlspecialchars($row['firstname'] . ' ' . $row['lastname']); ?></h5>
                <p class="mb-1">
                    <strong><?= htmlspecialchars($row['division_name'] ?? 'No Division'); ?></strong><br>
                    <small><?= htmlspecialchars($row['position_name'] ?? 'No Position'); ?></small>
                </p>
            </div>

            <!-- Reply Button -->
            <div class="ml-auto">
                <button 
                    class="btn btn-primary btn-sm btn-reply" 
                    data-username="<?= htmlspecialchars($row['username']); ?>"
                    data-user-id="<?= htmlspecialchars($row['user_id']); ?>">
                    <i class="fas fa-reply"></i> Reply
                </button>
            </div>
        </div>
    </div>
<?php
    }
} else {
    echo '<div class="dropdown-item text-center text-muted">No users found.</div>';
}
?>
