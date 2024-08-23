<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
include "../connection/connection.php";
include '../verificationFunction/sendVerificationEmail.php';

$response = [];

if (isset($_POST['athNum']) && isset($_POST['athFirst']) && isset($_POST['athLast']) && isset($_POST['athEmail']) && isset($_POST['athPass']) && isset($_POST['athSport']) && isset($_POST['athPosition'])) {

    $num = $_POST['athNum'];
    $first = $_POST['athFirst'];
    $last = $_POST['athLast'];
    $sport = $_POST['athSport'];
    $position = $_POST['athPosition'];
    $email = $_POST['athEmail'];
    $pass = $_POST['athPass'];
    $img = "sample.png";

    // Generate a unique OTP (6-digit code)
    $verificationCode = random_int(100000, 999999);

    // Insert the new athlete with OTP
    $stmt_register = $conn->prepare('INSERT INTO athlete_tbl (ath_num, ath_first, ath_last, ath_sport, ath_position, ath_email, ath_pass, ath_img, verification_code) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
    if ($stmt_register === false) {
        $response['status'] = 'error';
        $response['message'] = 'Prepare statement failed: ' . $conn->error;
        echo json_encode($response);
        exit();
    }

    $stmt_register->bind_param("issssssis", $num, $first, $last, $sport, $position, $email, $pass, $img, $verificationCode);

    if ($stmt_register->execute()) {
        // Send the OTP via email
        if (sendVerificationEmail($email, $verificationCode, 'athlete')) {
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
