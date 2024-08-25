<?php
require '../connection/connection.php'; // Include the database connection
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    // Retrieve user's first name from athlete_tbl or coach_tbl
    $stmt = $conn->prepare("SELECT ath_first AS first_name FROM athlete_tbl WHERE ath_email=? UNION SELECT coach_fname AS first_name FROM coach_tbl WHERE coach_email=?");
    $stmt->bind_param('ss', $email, $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($first_name);
        $stmt->fetch();

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

            // Email content
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            $mail->Body = '
                <html>
                <head>
                    <style>
                        .email-container {
                            font-family: Arial;
                            padding: 20px;
                            background-color: #dcdcdc;
                            color: #333;
                            border: 1px solid #ddd;
                            border-radius: 5px;
                            max-width: 600px;
                            margin: auto;
                        }
                        .email-header {
                            background-color: #3c3c3c;
                            color: #fff;
                            padding: 10px;
                            text-align: center;
                            border-radius: 5px 5px 0 0;
                        }
                        .email-body {
                            padding: 20px;
                        }
                        .email-footer {
                            padding: 10px;
                            text-align: center;
                            font-size: 12px;
                            color: #777;
                        }
                        .btn {
                            background-color: #4CAF50;
                            color: #ffffff;
                            padding: 10px 20px;
                            text-decoration: none;
                            border-radius: 5px;
                            display: inline-block;
                            margin-top: 20px;
                            margin-bottom: 20px;
                        }
                        a {
                            color: #ffffff !important;
                            text-decoration: none;
                        }
                        .btn:hover {
                            background-color: #45a049;
                        }
                    </style>
                </head>
                <body>
                    <div class="email-container">
                        <div class="email-header">
                            <h2>Password Reset Request</h2>
                        </div>
                        <div class="email-body">
                            <p>Hi ' . htmlspecialchars($first_name) . ',</p>
                            <p>You recently requested to reset your password for your account. Click the button below to reset it:</p>
                            <center><p><a class="btn" href="http://localhost/sportManagementSystem/resetPasswordPage.php?token=' . $token . '">Reset Password</a></p></center>
                            <p>If you did not request a password reset, please ignore this email or contact support if you have questions.</p>
                            <p>Thanks,</p>
                            <p>The Sports Management System Team</p>
                        </div>
                        <div class="email-footer">
                            <p>&copy; ' . date('Y') . ' Sports Management System. All rights reserved.</p>
                        </div>
                    </div>
                </body>
                </html>
            ';

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
