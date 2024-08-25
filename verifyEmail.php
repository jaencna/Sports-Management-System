<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
    <!-- Bootstrap CSS CDN -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <style>
        body {
            background-color: #121212;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-image: url('images/login/BGcross.png');
            font-family: "helvetica";
        }

        .card {
            width: 500px; /* Adjust width to your preference */
            max-width: 500px; /* Set a maximum width */
            border: none;
            border-radius: 40px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            height: 428px;
        }

        .btnVerify {
            background-color: #4acb5f;
            color: #fff; /* Ensure text color is readable */
            width: 40%; /* Set button width to 40% of its container */
            height: 40px;
            margin: 20px auto; /* Center the button horizontally */
            display: block; /* Ensure the button is treated as a block element */
            padding: 8px; /* Adjust padding if necessary */
            border-radius: 10px; /* Optional: rounded corners for the button */
            font-size: 12pt; /* Optional: adjust font size */
        }

        .form-group {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .form-group label {
            margin-bottom: 10px;
            font-size: 12pt;
            color: black; /* Optional: Change label color */
            text-align: center; /* Center the label text */
            width: 100%; /* Ensure the label takes up the full width */
        }

        .form-group input {
            width: 80%; /* Set input width */
            text-align: center;
            border-radius: 10px;
        }

        .logo {
            display: block;
            margin: 0 auto;
        }

    </style>
</head>
<body>
    <?php
    include "phpFile/connection/connection.php"; // Your DB connection script

    $message = ''; // Initialize message variable

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $otp = $_POST['otp']; // OTP code entered by user

        // Initialize update statement variable
        $updateStmt = null;

        // Check if the OTP exists in request_signup_tbl
        $stmt = $conn->prepare("SELECT * FROM request_signup_tbl WHERE verification_code = ?");
        if ($stmt === false) {
            $message = '<div class="alert alert-danger" role="alert">Prepare statement failed: ' . $conn->error . '</div>';
        } else {
            $stmt->bind_param("s", $otp);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // Prepare and execute update statement to mark email as verified
                $updateStmt = $conn->prepare("UPDATE request_signup_tbl SET is_verified = 1, verification_code = NULL WHERE verification_code = ?");
                if ($updateStmt === false) {
                    $message = '<div class="alert alert-danger" role="alert">Prepare update statement failed: ' . $conn->error . '</div>';
                } else {
                    $updateStmt->bind_param("s", $otp);
                    $updateStmt->execute();

                    $message = '<div class="alert alert-success" role="alert">Email verified successfully!</div>';
                }
            } else {
                $message = '<div class="alert alert-danger" role="alert">Invalid verification code.</div>';
            }

            $stmt->close();
        }

        // Close the update statement if it was initialized
        if ($updateStmt !== null) {
            $updateStmt->close();
        }

        $conn->close();
    }
    ?>
    <div class="container">
        <div class="row justify-content-center align-items-center vh-100">
            <div>
                <img src="images/homepage/LOGO.png" alt="Logo" class="logo">
                <div class="card">
                    <div style="text-align: center; font-size:35px; padding-top:40px;">
                        <i class="fa-solid fa-envelope-open"></i>
                    </div>
                    <div style="text-align:center;">
                        <h4><b>Email Verification</b></h4>
                    </div>
                    <div style="text-align: center; margin: 20px 50px 0px 50px;">
                        <p>We sent a code to your email. Please enter the code below to confirm your email address.</p>
                    </div> 
                    <div class="card-body">
                        <?php if (!empty($message)) echo $message; ?>
                        <form action="verifyEmail.php" method="POST">
                            <div class="form-group">
                                <input type="text" id="otp" name="otp" class="form-control" required placeholder="Enter verification code">
                            </div>
                            <button type="submit" class="btn btnVerify btn-block">Verify Email</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.com/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrap.com/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
