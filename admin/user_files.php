<?php
session_start();
require '../includes/db.php';

$user_id = $_SESSION['user_id'];

$query = "SELECT f.id, f.title, f.file_path FROM ordinances_resolutions f 
          JOIN file_permissions p ON f.id = p.file_id WHERE p.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    echo "<a href='../uploads/files/" . htmlspecialchars($row['file_path']) . "' target='_blank'>" . htmlspecialchars($row['title']) . "</a><br>";
}
?>
