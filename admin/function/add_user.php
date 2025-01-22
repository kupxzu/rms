<?php
session_start();
include '../../includes/db.php';
require '../../vendor/autoload.php'; // Load PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST['add_user'])) {
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $age = $_POST['age'];
    $sex = $_POST['sex'];
    $contact = trim($_POST['contact']);
    $email = trim($_POST['email']);
    $id_dp = $_POST['id_dp'];
    $role = 'user';

    $_SESSION['error_email'] = "";
    $_SESSION['error_contact'] = "";

    // Validate Gmail email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match("/@gmail\.com$/", $email)) {
        $_SESSION['error_email'] = "Only Gmail accounts are allowed.";
        header('Location: ../manage_users.php');
        exit();
    }

    // Validate contact number (at least 10 digits)
    if (!preg_match("/^[0-9]{10,}$/", $contact)) {
        $_SESSION['error_contact'] = "Contact number must be at least 10 digits.";
        header('Location: ../manage_users.php');
        exit();
    }

    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $_SESSION['error_email'] = "Email is already in use.";
        header('Location: ../manage_users.php');
        exit();
    }
    $stmt->close();

    // Check if contact already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE contact = ?");
    $stmt->bind_param("s", $contact);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $_SESSION['error_contact'] = "Contact number is already in use.";
        header('Location: ../manage_users.php');
        exit();
    }
    $stmt->close();

    // Generate unique username and secure password
    $username = strtoupper(substr(md5(uniqid()), 0, 4)) . rand(1000, 9999);
    $password = bin2hex(random_bytes(4)); // Secure 8-character password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert user into database
    $stmt = $conn->prepare("INSERT INTO users (username, firstname, lastname, age, sex, contact, email, id_dp, password, role) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssissssss", $username, $firstname, $lastname, $age, $sex, $contact, $email, $id_dp, $hashed_password, $role);

    if ($stmt->execute()) {
        // Send email with username and password
        $mail = new PHPMailer(true);
        try {
            // SMTP settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'lguenrile3@gmail.com'; // Replace with your Gmail
            $mail->Password = 'mdex iehw zmhq zxap'; // Replace with your Gmail app password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Email settings
            $mail->setFrom('lguenrile3@gmail.com', 'LGU Enrile Admin');
            $mail->addAddress($email, $firstname . ' ' . $lastname);
            $mail->isHTML(true);
            $mail->Subject = 'LGU Enrile';
            $mail->Body = "
                <h3>Hello $firstname $lastname</h3>
                <p>Your account has been successfully created. Here are your login details:</p>
                <ul>
                    <li><strong>Username:</strong> $username</li>
                    <li><strong>Password:</strong> $password</li>
                </ul>
                <p>Please log in and change your password for security purposes.</p>
                <p>Best regards,<br>LGU Enrile</p>
            ";

            $mail->send();
            $_SESSION['success'] = "User added successfully! Login details have been sent to the user's email.";
        } catch (Exception $e) {
            $_SESSION['error'] = "User added, but email could not be sent. Error: {$mail->ErrorInfo}";
        }
    } else {
        $_SESSION['error'] = "Failed to add user: " . $stmt->error;
    }

    header('Location: ../manage_users.php');
    exit();
}
?>
