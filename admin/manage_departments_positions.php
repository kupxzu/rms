<?php
session_start();
include '../includes/db.php';

// Add Department with Position
if (isset($_POST['add_department_with_position'])) {
    $department_id = $_POST['department_id'];
    $positions = $_POST['positions'];

    if ($department_id && !empty($positions)) {
        foreach ($positions as $position_id) {
            $sql = "INSERT INTO department_position (department_id, position_id) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $department_id, $position_id);
            $stmt->execute();
        }
        $_SESSION['success'] = "Department and positions added successfully!";
    } else {
        $_SESSION['error'] = "Please select a department and at least one position.";
    }
    header('Location: manage_dp.php');
    exit();
}

// Edit Department
if (isset($_POST['edit_department'])) {
    $department_id = $_POST['department_id'];
    $department_name = $_POST['department_name'];

    if ($department_name) {
        $sql = "UPDATE departments SET name = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $department_name, $department_id);
        if ($stmt->execute()) {
            $_SESSION['success'] = "Department updated successfully!";
        } else {
            $_SESSION['error'] = "Failed to update department.";
        }
    } else {
        $_SESSION['error'] = "Department name cannot be empty.";
    }
    header('Location: manage_dp.php');
    exit();
}

// Delete Department
if (isset($_POST['delete_department'])) {
    $department_id = $_POST['department_id'];

    $sql = "DELETE FROM departments WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $department_id);
    if ($stmt->execute()) {
        $_SESSION['success'] = "Department deleted successfully!";
    } else {
        $_SESSION['error'] = "Failed to delete department.";
    }
    header('Location: manage_dp.php');
    exit();
}

// Add Position
if (isset($_POST['add_position'])) {
    $position_name = $_POST['position_name'];

    if ($position_name) {
        $sql = "INSERT INTO positions (name) VALUES (?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $position_name);
        if ($stmt->execute()) {
            $_SESSION['success'] = "Position added successfully!";
        } else {
            $_SESSION['error'] = "Failed to add position.";
        }
    } else {
        $_SESSION['error'] = "Position name cannot be empty.";
    }
    header('Location: manage_dp.php');
    exit();
}

// Edit Position
if (isset($_POST['edit_position'])) {
    $position_id = $_POST['position_id'];
    $position_name = $_POST['position_name'];

    if ($position_name) {
        $sql = "UPDATE positions SET name = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $position_name, $position_id);
        if ($stmt->execute()) {
            $_SESSION['success'] = "Position updated successfully!";
        } else {
            $_SESSION['error'] = "Failed to update position.";
        }
    } else {
        $_SESSION['error'] = "Position name cannot be empty.";
    }
    header('Location: manage_dp.php');
    exit();
}

// Delete Position
if (isset($_POST['delete_position'])) {
    $position_id = $_POST['position_id'];

    $sql = "DELETE FROM positions WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $position_id);
    if ($stmt->execute()) {
        $_SESSION['success'] = "Position deleted successfully!";
    } else {
        $_SESSION['error'] = "Failed to delete position.";
    }
    header('Location: manage_dp.php');
    exit();
}
?>
