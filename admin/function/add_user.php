<?php
session_start();
include '../../includes/db.php';
require '../../vendor/autoload.php'; // Include PHPMailer autoload

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST['add_user'])) {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $age = $_POST['age'];
    $sex = $_POST['sex'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];
    $id_dp = $_POST['id_dp'];
    $role = 'user';

    // Generate random username and password
    $username = strtoupper(substr(md5(uniqid()), 0, 4)) . rand(1000, 9999);
    $password = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 8);
    $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Hash password

    // Insert user into database
    $sql = "INSERT INTO users (username, firstname, lastname, age, sex, contact, email, id_dp, password, role) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssissssss", $username, $firstname, $lastname, $age, $sex, $contact, $email, $id_dp, $hashed_password, $role);

    if ($stmt->execute()) {
        // Send email with username and password
        $mail = new PHPMailer(true);
        try {
            // SMTP settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // SMTP server
            $mail->SMTPAuth = true;
            $mail->Username = 'lguenrile3@gmail.com'; // Replace with your Gmail address
            $mail->Password = 'mdex iehw zmhq zxap'; // Replace with your Gmail app password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Email settings
            $mail->setFrom('lguenrile3@gmail.com', 'Your App Name'); // Sender
            $mail->addAddress($email, $firstname . ' ' . $lastname); // Recipient

            $mail->isHTML(true);
            $mail->Subject = 'Your Account Details';
            $mail->Body = "
                <h3>Hello $firstname,</h3>
                <p>Your account has been successfully created. Here are your login details:</p>
                <ul>
                    <li><strong>Username:</strong> $username</li>
                    <li><strong>Password:</strong> $password</li>
                </ul>
                <p>Please log in and change your password for security purposes.</p>
                <p>Best regards,<br>Your App Name</p>
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
