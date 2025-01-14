<?php
include '../../includes/db.php';

if (isset($_GET['department_id'])) {
    $department_id = $_GET['department_id'];
    $positions = $conn->query("
        SELECT dp.id, p.name 
        FROM department_position dp
        JOIN positions p ON dp.position_id = p.id
        WHERE dp.department_id = $department_id
    ");

    $result = [];
    while ($row = $positions->fetch_assoc()) {
        $result[] = $row;
    }

    echo json_encode($result);
}
?>
