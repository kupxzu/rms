<?php
include '../../includes/db.php';

header('Content-Type: application/json');

if (!isset($_POST['user_id']) || empty($_POST['user_id']) || !is_numeric($_POST['user_id'])) {
    echo json_encode(["error" => "User ID is missing or invalid."]);
    exit();
}

$user_id = intval($_POST['user_id']);

// Fetch both sent and received files
$sql = "SELECT id, title, description, attachment, file_type, uploaded_at, user_id AS sender, recipient_id 
        FROM ingoing 
        WHERE user_id = ? OR recipient_id = ? 
        ORDER BY uploaded_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $user_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

$files = [];
while ($row = $result->fetch_assoc()) {
    // Convert attachment path to a valid URL
    $row['attachment'] = str_replace("../", "", $row['attachment']);
    $files[] = $row;
}

if (empty($files)) {
    echo json_encode(["error" => "No uploaded files found."]);
} else {
    echo json_encode($files);
}
?>
