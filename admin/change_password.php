<?php
session_start();
require '../includes/db.php';
require '../vendor/autoload.php'; // Load PHPMailer via Composer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id = $_POST['user_id'];

    // Fetch user details
    $query = "SELECT email, firstname, lastname FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        $_SESSION['error'] = "User not found!";
        header("Location: admin_dashboard.php");
        exit();
    }

    // Generate a random password
    $new_password = bin2hex(random_bytes(4)); // Generates an 8-character random password
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT); // Hash before storing

    // Update database with new password
    $update_query = "UPDATE users SET password = ? WHERE id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("si", $hashed_password, $user_id);

    if ($stmt->execute()) {
        // Send Email with New Password using PHPMailer
        $mail = new PHPMailer(true);

        try {
            // SMTP Server Configuration
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Use your SMTP server
            $mail->SMTPAuth = true;
            $mail->Username = 'lguenrile3@gmail.com'; // Your email
            $mail->Password = 'mdex iehw zmhq zxap'; // Your email password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Email Headers
            $mail->setFrom('lguenrile3@gmail.com', 'Admin');
            $mail->addAddress($user['email'], $user['firstname'] . ' ' . $user['lastname']);

            // Email Content
            $mail->isHTML(true);
            $mail->Subject = "Your New Password";
            $mail->Body = "
                <p>Hello <b>{$user['firstname']}</b>,</p>
                <p>Your password has been reset.</p>
                <p><b>New Password:</b> <span style='color:blue;'>{$new_password}</span></p>
                <p>Please log in and change it immediately.</p>
                <p>Best regards,<br>Admin</p>
            ";

            // Send Email
            if ($mail->send()) {
                $_SESSION['success'] = "New password sent successfully!";
            } else {
                $_SESSION['error'] = "Password updated, but email sending failed.";
            }
        } catch (Exception $e) {
            $_SESSION['error'] = "Mail Error: " . $mail->ErrorInfo;
        }
    } else {
        $_SESSION['error'] = "Failed to update password.";
    }

    header("Location: user_change_password.php");
    exit();
}
?>
