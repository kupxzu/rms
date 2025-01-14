<?php
session_start();
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Add Department
    if (isset($_POST['add_department'])) {
        $name = $_POST['department_name'];
        $sql = "INSERT INTO departments (name) VALUES (?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $name);
        if ($stmt->execute()) {
            $_SESSION['success'] = "Department added successfully!";
        } else {
            $_SESSION['error'] = "Failed to add department: " . $conn->error;
        }
        header('Location: manage_d-p.php');
        exit();
    }

    // Edit Department
    if (isset($_POST['edit_department'])) {
        $id = $_POST['department_id'];
        $name = $_POST['department_name'];
        $sql = "UPDATE departments SET name = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $name, $id);
        if ($stmt->execute()) {
            $_SESSION['success'] = "Department updated successfully!";
        } else {
            $_SESSION['error'] = "Failed to update department: " . $conn->error;
        }
        header('Location: manage_d-p.php');
        exit();
    }

    // Delete Department
    if (isset($_POST['delete_department'])) {
        $id = $_POST['department_id'];
        $sql = "DELETE FROM departments WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $_SESSION['success'] = "Department deleted successfully!";
        } else {
            $_SESSION['error'] = "Failed to delete department: " . $conn->error;
        }
        header('Location: manage_d-p.php');
        exit();
    }

    // Add Position
    if (isset($_POST['add_position'])) {
        $name = $_POST['position_name'];
        $sql = "INSERT INTO positions (name) VALUES (?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $name);
        if ($stmt->execute()) {
            $_SESSION['success'] = "Position added successfully!";
        } else {
            $_SESSION['error'] = "Failed to add position: " . $conn->error;
        }
        header('Location: manage_d-p.php');
        exit();
    }

    // Edit Position
    if (isset($_POST['edit_position'])) {
        $id = $_POST['position_id'];
        $name = $_POST['position_name'];
        $sql = "UPDATE positions SET name = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $name, $id);
        if ($stmt->execute()) {
            $_SESSION['success'] = "Position updated successfully!";
        } else {
            $_SESSION['error'] = "Failed to update position: " . $conn->error;
        }
        header('Location: manage_d-p.php');
        exit();
    }

    // Delete Position
    if (isset($_POST['delete_position'])) {
        $id = $_POST['position_id'];
        $sql = "DELETE FROM positions WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $_SESSION['success'] = "Position deleted successfully!";
        } else {
            $_SESSION['error'] = "Failed to delete position: " . $conn->error;
        }
        header('Location: manage_d-p.php');
        exit();
    }
}
?>
