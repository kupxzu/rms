<?php
session_start();
require '../includes/db.php';
require '../vendor/autoload.php'; // PHPMailer & FPDF

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $file_id = $_POST['file_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $file_type = $_POST['file_type'];
    $departments = isset($_POST['departments']) ? $_POST['departments'] : [];

    // Fetch old file details
    $old_file_query = "SELECT file_path, uploaded_by FROM ordinances_resolutions WHERE id = ?";
    $stmt = $conn->prepare($old_file_query);
    $stmt->bind_param("i", $file_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $old_file = $result->fetch_assoc();
    $old_file_path = $old_file['file_path'];
    $uploaded_by = $old_file['uploaded_by'];

    // Update file details
    $query = "UPDATE ordinances_resolutions SET title = ?, description = ?, file_type = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssi", $title, $description, $file_type, $file_id);
    $stmt->execute();

    $updated_file_path = $old_file_path; // Default: Keep old file

    // Handle file replacement (if a new file is uploaded)
    if (!empty($_FILES['file']['name'])) {
        $target_dir = "../../RMS/uploads/files/";
        $file_name = time() . "_" . basename($_FILES["file"]["name"]);
        $target_file = $target_dir . $file_name;
        $stored_path = "RMS/uploads/files/" . $file_name; // Path stored in DB

        if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
            // Update file path in database
            $update_file_query = "UPDATE ordinances_resolutions SET file_path = ? WHERE id = ?";
            $stmt = $conn->prepare($update_file_query);
            $stmt->bind_param("si", $stored_path, $file_id);
            $stmt->execute();
            $updated_file_path = $stored_path;
        } else {
            $_SESSION['error'] = "Error uploading the new file.";
            header("Location: view_upload.php");
            exit();
        }
    }

    // Remove old department permissions (unless it's an "Events" type file)
    if ($file_type !== "Events") {
        $delete_query = "DELETE FROM file_permissions WHERE file_id = ?";
        $stmt = $conn->prepare($delete_query);
        $stmt->bind_param("i", $file_id);
        $stmt->execute();

        // Insert new department permissions
        foreach ($departments as $department_id) {
            $insert_query = "INSERT INTO file_permissions (file_id, department_id) VALUES (?, ?)";
            $stmt = $conn->prepare($insert_query);
            $stmt->bind_param("ii", $file_id, $department_id);
            $stmt->execute();
        }
    } else {
        // If file type is "Events", assign to all departments
        $delete_query = "DELETE FROM file_permissions WHERE file_id = ?";
        $stmt = $conn->prepare($delete_query);
        $stmt->bind_param("i", $file_id);
        $stmt->execute();

        $all_departments_query = "SELECT id FROM departments";
        $result = $conn->query($all_departments_query);
        while ($row = $result->fetch_assoc()) {
            $department_id = $row['id'];
            $insert_query = "INSERT INTO file_permissions (file_id, department_id) VALUES (?, ?)";
            $stmt = $conn->prepare($insert_query);
            $stmt->bind_param("ii", $file_id, $department_id);
            $stmt->execute();
        }
    }

    // Fetch uploader details
    $uploaderQuery = "SELECT email, firstname, lastname FROM users WHERE id = ?";
    $uploaderStmt = $conn->prepare($uploaderQuery);
    $uploaderStmt->bind_param("i", $uploaded_by);
    $uploaderStmt->execute();
    $uploaderResult = $uploaderStmt->get_result();
    $uploader = $uploaderResult->fetch_assoc();
    $uploaderName = $uploader['firstname'] . " " . $uploader['lastname'];

    // Collect user emails from the assigned departments
    $departmentNames = [];
    $userEmails = [];

    if ($file_type == "Events") {
        $dep_query = "SELECT id, name FROM departments";
        $dep_result = $conn->query($dep_query);
        while ($row = $dep_result->fetch_assoc()) {
            $departmentNames[] = $row['name'];

            $userQuery = "SELECT email FROM users 
                          JOIN department_position ON users.id_dp = department_position.id
                          WHERE department_position.department_id = ?";
            $userStmt = $conn->prepare($userQuery);
            $userStmt->bind_param("i", $row['id']);
            $userStmt->execute();
            $userResult = $userStmt->get_result();
            while ($userRow = $userResult->fetch_assoc()) {
                $userEmails[] = $userRow['email'];
            }
        }
    } else {
        foreach ($departments as $department_id) {
            $dep_query = "SELECT name FROM departments WHERE id = ?";
            $dep_stmt = $conn->prepare($dep_query);
            $dep_stmt->bind_param("i", $department_id);
            $dep_stmt->execute();
            $dep_result = $dep_stmt->get_result();
            $department = $dep_result->fetch_assoc();
            $departmentNames[] = $department['name'];

            $userQuery = "SELECT email FROM users 
                          JOIN department_position ON users.id_dp = department_position.id
                          WHERE department_position.department_id = ?";
            $userStmt = $conn->prepare($userQuery);
            $userStmt->bind_param("i", $department_id);
            $userStmt->execute();
            $userResult = $userStmt->get_result();
            while ($userRow = $userResult->fetch_assoc()) {
                $userEmails[] = $userRow['email'];
            }
        }
    }

    // Remove duplicate emails
    $userEmails = array_unique($userEmails);

    // Send email notifications
    foreach ($userEmails as $email) {
        sendEmailWithAttachment($email, $uploaderName, $title, $description, $file_type, $departmentNames, "../" . $updated_file_path);
    }

    $_SESSION['success'] = "File updated successfully!";
    header("Location: admin_upload.php");
    exit();
}

/**
 * Function to send email with file attachment
 */
function sendEmailWithAttachment($recipientEmail, $uploaderName, $title, $description, $file_type, $departmentNames, $filePath)
{
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'lguenrile3@gmail.com';
        $mail->Password = 'mdex iehw zmhq zxap';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('lguenrile3@gmail.com', 'LGU Enrile');
        $mail->addAddress($recipientEmail);

        if (file_exists($filePath)) {
            $mail->addAttachment($filePath);
        }

        $mail->isHTML(true);
        $mail->Subject = "Updated File: " . $title;
        $mail->Body = "
            <h3>A file has been updated.</h3>
            <p><strong>Uploader:</strong> $uploaderName</p>
            <p><strong>Title:</strong> $title</p>
            <p><strong>Description:</strong> $description</p>
            <p hidden><strong hidden>File Type:</strong> $file_type</p>
            <p hidden><strong hidden>Departments:</strong> " . implode(', ', $departmentNames) . "</p>
            <p><strong>Download File:</strong> <a href='http://localhost/RMS/uploads/files/" . basename($filePath) . "'>Click Here</a></p>
        ";

        $mail->send();
    } catch (Exception $e) {
        error_log("Email could not be sent. Error: {$mail->ErrorInfo}");
    }
}
?>
