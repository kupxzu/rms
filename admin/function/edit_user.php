<?php
session_start();
include '../../includes/db.php';

if (isset($_POST['edit_user'])) {
    $user_id = $_POST['user_id'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $age = $_POST['age'];
    $sex = $_POST['sex'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];
    $id_dp = $_POST['id_dp'];

    // Update user in the database
    $sql = "UPDATE users 
            SET firstname = ?, lastname = ?, age = ?, sex = ?, contact = ?, email = ?, id_dp = ?
            WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssisssii", $firstname, $lastname, $age, $sex, $contact, $email, $id_dp, $user_id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "User updated successfully!";
    } else {
        $_SESSION['error'] = "Failed to update user: " . $stmt->error;
    }

    header('Location: ../manage_users.php');
    exit();
}
?>
