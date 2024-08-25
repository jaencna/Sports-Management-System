<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
        border: none;
        border-radius: 40px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
    }
    .card-body {
        padding: 2rem;
        text-align: center; /* Center the content within the card-body */
    }
    .invalid-feedback ul {
        margin: 0;
        padding-left: 1rem;
    }

    .btnReset {
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
    
    .form-control {
        display: inline-block; /* Make input fields inline-block for centering */
        width: 80%; /* Adjust width if necessary */
        margin: 0 auto; /* Center the input fields horizontally */
    }
    .form-label {
        display: block; /* Ensure labels are block elements for proper alignment */
        text-align: left; /* Center the labels */
        padding-left: 60px;
        margin-bottom: 0.5rem; /* Add some space below the label */
    }
</style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center align-items-center vh-100">
            <div class="col-md-6">
            <img src="images/homepage/LOGO.png" alt="Logo" style="display: block; margin: 0 auto;">
                <div class="card">
                    <div class="card-body">
                    <div style="text-align: center; font-size:35px;">
                        <i class="fa-solid fa-unlock"></i>
                            </div>

                        <h2 class="card-title text-center mb-4"><b>Reset Password</b></h2>
                        <form id="resetPasswordForm" novalidate>
                            <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token']); ?>">
                            <div class="mb-3">
                                <label for="password" class="form-label" >New Password</label>
                                <input type="password" name="password" class="form-control" id="passwordInput" placeholder="Enter new password" required  >
                                <div class="invalid-feedback">
                                    Please enter a valid password.
                                    <ul>
                                        <li>Password must contain at least 8 characters.</li>
                                        <li>Password must contain at least one uppercase letter.</li>
                                        <li>Password must contain at least one lowercase letter.</li>
                                        <li>Password must contain at least one digit (0-9).</li>
                                        <li>Password must contain at least one special character (!@#$%^&*).</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="confirm_password" class="form-label" >Confirm New Password</label>
                                <input type="password" name="confirm_password" class="form-control" id="confirmPasswordInput" placeholder="Confirm new password" required>
                                <div class="invalid-feedback">
                                    Passwords do not match.
                                </div>
                            </div>
                            <button type="button" id="resetPasswordBtn" class="btn btnReset btn-block">Reset Password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#resetPasswordBtn').click(function() {
                var form = $('#resetPasswordForm')[0];
                var password = $('#passwordInput').val();
                var confirmPassword = $('#confirmPasswordInput').val();
                var valid = true;

                // Custom validation logic
                if (password.length < 8 ||
                    !/[A-Z]/.test(password) ||
                    !/[a-z]/.test(password) ||
                    !/\d/.test(password) ||
                    !/[!@#$%^&*]/.test(password)) {
                    $('#passwordInput').addClass('is-invalid');
                    valid = false;
                } else {
                    $('#passwordInput').removeClass('is-invalid').addClass('is-valid');
                }

                if (password !== confirmPassword) {
                    $('#confirmPasswordInput').addClass('is-invalid');
                    valid = false;
                } else {
                    $('#confirmPasswordInput').removeClass('is-invalid').addClass('is-valid');
                }

                if (valid) {
                    var formData = $(form).serialize();

                    $.ajax({
                        type: 'POST',
                        url: 'updatePass.php',
                        data: formData,
                        dataType: 'json',
                        success: function(response) {
                            if (response.status === 'success') {
                                alert(response.message);
                                window.location.href = 'index.php'; // Redirect to login page
                            } else {
                                alert(response.message);
                            }
                        }
                    });
                }
            });

            // Form validation for Bootstrap
            (function() {
                'use strict';
                var forms = document.querySelectorAll('.needs-validation');
                Array.prototype.slice.call(forms)
                    .forEach(function(form) {
                        form.addEventListener('submit', function(event) {
                            if (!form.checkValidity()) {
                                event.preventDefault();
                                event.stopPropagation();
                            }
                            form.classList.add('was-validated');
                        }, false);
                    });
            })();
        });
    </script>
</body>
</html>
