<?php
header('Content-Type: application/json');
include "../connection/connection.php";
include '../verificationFunction/sendVerificationEmail.php';

$response = [];

// Check if all required POST parameters are set
if (isset($_POST['athNum']) && isset($_POST['athFirst']) && isset($_POST['athLast']) && isset($_POST['athEmail']) && isset($_POST['athPass']) && isset($_POST['athSport']) && isset($_POST['athPosition'])) {

    // Directly map POST data to column names
    $req_stu_id = $_POST['athNum'];
    $req_fname = $_POST['athFirst'];
    $req_lname = $_POST['athLast'];
    $req_sport = $_POST['athSport'];
    $req_position = $_POST['athPosition'];
    $req_email = $_POST['athEmail'];
    $req_pass = $_POST['athPass'];
    $req_img = "sample.png"; // Assuming a default image
    $req_user_type = ($req_stu_id === 'N/A') ? 'COACH' : 'ATHLETE';
    $is_verified = 0;
    $verification_code = random_int(100000, 999999); // Generate a unique OTP (6-digit code)

    // First, check if the email or student number already exists in athlete_tbl
    $stmt_check_athlete = $conn->prepare('SELECT ath_email, ath_num FROM athlete_tbl WHERE ath_email = ? OR ath_num = ?');
    $stmt_check_athlete->bind_param("ss", $req_email, $req_stu_id);
    $stmt_check_athlete->execute();
    $stmt_check_athlete->store_result();

    if ($stmt_check_athlete->num_rows > 0) {
        $response['status'] = 'error';
        $response['message'] = 'User already registered as an athlete';
        $stmt_check_athlete->close();
        echo json_encode($response);
        exit();
    }
    $stmt_check_athlete->close();

    // Then, check if the email exists in coach_tbl
    $stmt_check_coach = $conn->prepare('SELECT coach_email FROM coach_tbl WHERE coach_email = ?');
    $stmt_check_coach->bind_param("s", $req_email);
    $stmt_check_coach->execute();
    $stmt_check_coach->store_result();

    if ($stmt_check_coach->num_rows > 0) {
        $response['status'] = 'error';
        $response['message'] = 'User already registered as a coach';
        $stmt_check_coach->close();
        echo json_encode($response);
        exit();
    }
    $stmt_check_coach->close();

    
    // Then, check if the email exists in request_signup_tbl
    $stmt_check_req = $conn->prepare('SELECT req_email FROM request_signup_tbl WHERE req_email = ?');
    $stmt_check_req->bind_param("s", $req_email);
    $stmt_check_req->execute();
    $stmt_check_req->store_result();

    if ($stmt_check_req->num_rows > 0) {
        $response['status'] = 'error';
        $response['message'] = 'Email is already in used';
        $stmt_check_req->close();
        echo json_encode($response);
        exit();
    }
    $stmt_check_req->close();


    // Insert new user into request_signup_tbl
    $stmt_register = $conn->prepare('INSERT INTO request_signup_tbl (req_stu_id, req_fname, req_lname, req_sport, req_position, req_email, req_pass, req_user_type, is_verified, verification_code) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');

    if ($stmt_register === false) {
        $response['status'] = 'error';
        $response['message'] = 'Prepare failed: ' . $conn->error;
        echo json_encode($response);
        exit();
    }

    $stmt_register->bind_param(
        "ssssssssis", 
        $req_stu_id, $req_fname, $req_lname, $req_sport, $req_position, $req_email, $req_pass, $req_user_type, $is_verified, $verification_code
    );

    if ($stmt_register->execute()) {
        // Send the OTP via email
        if (sendVerificationEmail($req_email, $verification_code, $req_user_type)) {
            $response['status'] = 'success';
            $response['message'] = 'Registration successful. Please check your email for verification.';
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Registration successful, but failed to send verification email.';
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
