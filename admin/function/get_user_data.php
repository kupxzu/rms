<?php
include '../../includes/db.php';

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    $sql = "SELECT 
                users.*, 
                dp.id AS id_dp, 
                d.id AS department_id, 
                p.name AS position_name 
            FROM users 
            LEFT JOIN department_position dp ON users.id_dp = dp.id 
            LEFT JOIN departments d ON dp.department_id = d.id 
            LEFT JOIN positions p ON dp.position_id = p.id 
            WHERE users.id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        echo json_encode(['success' => true, 'data' => $user]);
    } else {
        echo json_encode(['success' => false, 'message' => 'User not found.']);
    }
}
?>
