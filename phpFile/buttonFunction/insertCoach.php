<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
include "../connection/connection.php";
include '../verificationFunction/sendVerificationEmail.php';

$response = [];

if (isset($_POST['coachNum']) && isset($_POST['coachFirst']) && isset($_POST['coachLast']) && isset($_POST['coachEmail']) && isset($_POST['coachPass']) && isset($_POST['coachSport']) && isset($_POST['coachPosition'])) {

    $num = $_POST['coachNum'];
    $first = $_POST['coachFirst'];
    $last = $_POST['coachLast'];
    $sport = $_POST['coachSport'];
    $position = $_POST['coachPosition'];
    $email = $_POST['coachEmail'];
    $pass = $_POST['coachPass'];
    $img = "sample.png";

    // Generate a unique OTP (6-digit code)
    $verificationCode = random_int(100000, 999999);

    // Insert the new coach with OTP
    $stmt_register = $conn->prepare('INSERT INTO coach_tbl (coach_num, coach_first, coach_last, coach_sport, coach_position, coach_email, coach_pass, coach_img, verification_code) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
    if ($stmt_register === false) {
        $response['status'] = 'error';
        $response['message'] = 'Prepare statement failed: ' . $conn->error;
        echo json_encode($response);
        exit();
    }

    $stmt_register->bind_param("issssssis", $num, $first, $last, $sport, $position, $email, $pass, $img, $verificationCode);

    if ($stmt_register->execute()) {
        // Send the OTP via email
        if (sendVerificationEmail($email, $verificationCode, 'coach')) {
            $response['status'] = 'success';
            $response['message'] = 'Verification email sent.';
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Failed to send verification email.';
        }
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Execute statement failed: ' . $stmt_register->error;
    }

    $stmt_register->close();
    $conn->close();

} else {
    $response['status'] = 'error';
    $response['message'] = 'Invalid input';
}

echo json_encode($response);
?>
