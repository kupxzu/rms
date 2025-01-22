<?php
include '../includes/db.php';

$response = [];

if (isset($_POST['contact'])) {
    $contact = $_POST['contact'];
    $stmt = $conn->prepare("SELECT id FROM users WHERE contact = ?");
    $stmt->bind_param("s", $contact);
    $stmt->execute();
    $stmt->store_result();
    $response['contact'] = ($stmt->num_rows > 0) ? "exists" : "available";
    $stmt->close();
}

if (isset($_POST['email'])) {
    $email = $_POST['email'];
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $response['email'] = ($stmt->num_rows > 0) ? "exists" : "available";
    $stmt->close();
}



echo json_encode($response);
?>
