<?php
include "phpFile/connection/connection.php"; // Your DB connection script

$message = ''; // Initialize message variable

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $otp = $_POST['otp']; // OTP code entered by user

    // Check if the OTP exists in request_signup_tbl
    $stmt = $conn->prepare("SELECT * FROM request_signup_tbl WHERE verification_code = ?");
    $stmt->bind_param("s", $otp);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Update request_signup_tbl to mark the email as verified
        $updateStmt = $conn->prepare("UPDATE request_signup_tbl SET is_verified = 1, verification_code = NULL WHERE verification_code = ?");
        $updateStmt->bind_param("s", $otp);
        $updateStmt->execute();

        $message = '<div class="alert alert-success" role="alert">Email verified successfully!</div>';
    } else {
        $message = '<div class="alert alert-danger" role="alert">Invalid verification code.</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
    <!-- Bootstrap CSS CDN -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center align-items-center vh-100">
            <div class="col-md-6 col-lg-4">
                <div class="card">
                    <div class="card-header text-center">
                        <h4>Email Verification</h4>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($message)) echo $message; ?>
                        <form action="verifyEmail.php" method="POST">
                            <div class="form-group">
                                <label for="otp">Verification Code:</label>
                                <input type="text" id="otp" name="otp" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Verify Email</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>