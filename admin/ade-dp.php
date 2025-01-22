<?php
session_start();
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Add Department
    if (isset($_POST['add_department'])) {
        $name = trim($_POST['department_name']);

        // Check if department already exists
        $check_sql = "SELECT id FROM departments WHERE name = ?";
        $stmt = $conn->prepare($check_sql);
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $_SESSION['error'] = "Department already exists!";
        } else {
            $sql = "INSERT INTO departments (name) VALUES (?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $name);
            if ($stmt->execute()) {
                $_SESSION['success'] = "Department added successfully!";
            } else {
                $_SESSION['error'] = "Failed to add department: " . $conn->error;
            }
        }
        header('Location: manage_d-p.php');
        exit();
    }

    // Edit Department
    if (isset($_POST['edit_department'])) {
        $id = $_POST['department_id'];
        $name = trim($_POST['department_name']);

        // Check if new name already exists (excluding current department)
        $check_sql = "SELECT id FROM departments WHERE name = ? AND id != ?";
        $stmt = $conn->prepare($check_sql);
        $stmt->bind_param("si", $name, $id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $_SESSION['error'] = "Department name already in use!";
        } else {
            $sql = "UPDATE departments SET name = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $name, $id);
            if ($stmt->execute()) {
                $_SESSION['success'] = "Department updated successfully!";
            } else {
                $_SESSION['error'] = "Failed to update department: " . $conn->error;
            }
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
        $name = trim($_POST['position_name']);

        // Check if position already exists
        $check_sql = "SELECT id FROM positions WHERE name = ?";
        $stmt = $conn->prepare($check_sql);
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $_SESSION['error'] = "Position already exists!";
        } else {
            $sql = "INSERT INTO positions (name) VALUES (?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $name);
            if ($stmt->execute()) {
                $_SESSION['success'] = "Position added successfully!";
            } else {
                $_SESSION['error'] = "Failed to add position: " . $conn->error;
            }
        }
        header('Location: manage_d-p.php');
        exit();
    }

    // Edit Position
    if (isset($_POST['edit_position'])) {
        $id = $_POST['position_id'];
        $name = trim($_POST['position_name']);

        // Check if new name already exists (excluding current position)
        $check_sql = "SELECT id FROM positions WHERE name = ? AND id != ?";
        $stmt = $conn->prepare($check_sql);
        $stmt->bind_param("si", $name, $id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $_SESSION['error'] = "Position name already in use!";
        } else {
            $sql = "UPDATE positions SET name = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $name, $id);
            if ($stmt->execute()) {
                $_SESSION['success'] = "Position updated successfully!";
            } else {
                $_SESSION['error'] = "Failed to update position: " . $conn->error;
            }
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
