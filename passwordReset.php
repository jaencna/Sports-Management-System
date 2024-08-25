<?php
require 'phpFile/connection/connection.php'; // Include the database connection
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    // Validate email format and existence in the database
    $stmt = $conn->prepare("SELECT ath_email FROM athlete_tbl WHERE ath_email=? UNION SELECT coach_email FROM coach_tbl WHERE coach_email=?");
    $stmt->bind_param('ss', $email, $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Generate a secure token
        $token = bin2hex(random_bytes(16)); // 32-character token
        $expires = date('Y-m-d H:i:s', time() + 15 * 60); // 15 minutes expiry

        // Store the token in the database
        $stmt = $conn->prepare("REPLACE INTO password_reset (email, token, expires) VALUES (?, ?, ?)");
        $stmt->bind_param('sss', $email, $token, $expires);
        $stmt->execute();

        // Send password reset email
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'kyuuichi12@gmail.com'; // SMTP username
            $mail->Password = 'cayj eaug pwcx xqvn'; // SMTP password or App Password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Recipients
            $mail->setFrom('kyuuichi12@gmail.com', 'SPORTS MANAGEMENT SYSTEM'); // Sender's email
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            $mail->Body    = 'Click the following link to reset your password: <a href="http://localhost/sportManagementSystem/resetPasswordPage.php?token=' . $token . '">Reset Password</a>';

            $mail->send();
            echo json_encode(array("status" => "success", "message" => "Password reset link sent to your email."));
        } catch (Exception $e) {
            echo json_encode(array("status" => "error", "message" => "Email could not be sent. Mailer Error: {$mail->ErrorInfo}"));
        }
    } else {
        echo json_encode(array("status" => "error", "message" => "Email not found."));
    }
}
?>
