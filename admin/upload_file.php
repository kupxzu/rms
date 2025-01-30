<?php
session_start();
require '../includes/db.php';
require '../vendor/autoload.php'; // PHPMailer & FPDF

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use setasign\Fpdi\Fpdi;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $file_type = $_POST['file_type'];
    $uploaded_by = $_SESSION['user_id'];
    $departments = isset($_POST['departments']) ? $_POST['departments'] : []; // Array of selected departments

    // File Upload
    $target_dir = "../../RMS/uploads/files/";  // Ensure this folder exists
    $original_file_name = basename($_FILES["file"]["name"]);
    $file_extension = strtolower(pathinfo($original_file_name, PATHINFO_EXTENSION));
    $file_name = time() . "_" . $original_file_name;
    $target_file = $target_dir . $file_name;
    $stored_path = "RMS/uploads/files/" . $file_name; // Path stored in DB

    // Allowed file types
    $allowed_types = ["pdf", "doc", "docx", "png", "jpg"];
    if (!in_array($file_extension, $allowed_types)) {
        $_SESSION['error'] = "Invalid file type!";
        header("Location: admin_upload.php");
        exit();
    }

    // Move uploaded file to target directory
    if (!move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
        $_SESSION['error'] = "File upload failed!";
        header("Location: admin_upload.php");
        exit();
    }

    $converted_pdf_path = $target_file; // Default file path for PDFs and Word files

    // If it's an image (JPG/PNG), convert it to a PDF
    if (in_array($file_extension, ["jpg", "jpeg", "png"])) {
        $pdf_file_name = time() . "_converted.pdf";
        $converted_pdf_path = $target_dir . $pdf_file_name;

        $pdf = new Fpdi();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->SetTextColor(0, 0, 0);

        // Add image to PDF
        $pdf->Image($target_file, 10, 10, 190);
        $pdf->SetXY(10, 270);
        $pdf->Cell(190, 10, "Uploaded Document", 0, 1, 'C');

        // Save the converted PDF
        $pdf->Output($converted_pdf_path, 'F');

        // Remove the original image after conversion
        unlink($target_file);
    }

    // Insert file details into database
    $query = "INSERT INTO ordinances_resolutions (title, description, file_path, file_type, uploaded_by) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssi", $title, $description, $converted_pdf_path, $file_type, $uploaded_by);
    $stmt->execute();
    $file_id = $stmt->insert_id;

    // Fetch uploader details
    $uploaderQuery = "SELECT email, firstname, lastname FROM users WHERE id = ?";
    $uploaderStmt = $conn->prepare($uploaderQuery);
    $uploaderStmt->bind_param("i", $uploaded_by);
    $uploaderStmt->execute();
    $uploaderResult = $uploaderStmt->get_result();
    $uploader = $uploaderResult->fetch_assoc();
    $uploaderEmail = $uploader['email'];
    $uploaderName = $uploader['firstname'] . " " . $uploader['lastname'];

    // Assign file to departments and fetch user emails
    $departmentNames = [];
    $userEmails = [];

    if ($file_type == "Events") {
        // Assign file to ALL departments
        $dep_query = "SELECT id, name FROM departments";
        $dep_result = $conn->query($dep_query);
        while ($row = $dep_result->fetch_assoc()) {
            $departmentNames[] = $row['name'];
            $insert_query = "INSERT INTO file_permissions (file_id, department_id) VALUES (?, ?)";
            $stmt = $conn->prepare($insert_query);
            $stmt->bind_param("ii", $file_id, $row['id']);
            $stmt->execute();

            // Get all users in the department
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
            // Fetch department names
            $dep_query = "SELECT name FROM departments WHERE id = ?";
            $dep_stmt = $conn->prepare($dep_query);
            $dep_stmt->bind_param("i", $department_id);
            $dep_stmt->execute();
            $dep_result = $dep_stmt->get_result();
            $department = $dep_result->fetch_assoc();
            $departmentNames[] = $department['name'];

            // Insert file permissions
            $insert_query = "INSERT INTO file_permissions (file_id, department_id) VALUES (?, ?)";
            $stmt = $conn->prepare($insert_query);
            $stmt->bind_param("ii", $file_id, $department_id);
            $stmt->execute();

            // Get all users in the department
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

    // Send emails with attachment
    foreach ($userEmails as $email) {
        sendEmailWithAttachment($email, $uploaderName, $title, $description, $file_type, $departmentNames, $converted_pdf_path);
    }

    $_SESSION['success'] = "File uploaded successfully!";
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
        // SMTP Configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'lguenrile3@gmail.com'; // Your Gmail
        $mail->Password = 'mdex iehw zmhq zxap'; // Your App Password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Sender & Recipient
        $mail->setFrom('lguenrile3@gmail.com', 'Admin');
        $mail->addAddress($recipientEmail);

        // Attach the file
        $mail->addAttachment($filePath);

        // Email Content
        $mail->isHTML(true);
        $mail->Subject = "New File Uploaded: " . $title;
        $mail->Body = "
            <h3>A new file has been uploaded.</h3>
            <p><strong>Uploader: LGU Enrile</strong> $uploaderName</p>
            <p><strong>Title:</strong> $title</p>
            <p><strong>Description:</strong> $description</p>
            <p><strong>File Type:</strong> $file_type</p>
        ";

        // Send email
        $mail->send();
    } catch (Exception $e) {
        error_log("Email could not be sent. Mailer Error: {$mail->ErrorInfo}");
    }
}
?>
