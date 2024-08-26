function getSports() {
    $.ajax({
        url: 'phpFile/onloadFunction/getSports.php', // The PHP file that fetches sports data
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            var sportInput = document.getElementById("sportInput");
            sportInput.innerHTML = '<option value="--">--</option>'; // Reset options
            data.forEach(function(sport) {
                var option = document.createElement("option");
                option.value = sport;
                option.text = sport;
                sportInput.appendChild(option);
            });
        },
        error: function(error) {
            console.error('Error fetching sports:', error);
        }
    });
}

function getPositions(sport) {
    if (sport === "--") {
        // Reset positions dropdown if the default option is selected
        document.getElementById("positionInput").innerHTML = '<option value="--">--</option>';
        return;
    }

    $.ajax({
        url: 'phpFile/onloadFunction/getPosition.php', // The PHP file that fetches positions data
        method: 'GET',
        data: { sport: sport },
        dataType: 'json',
        success: function(data) {
            var positionInput = document.getElementById("positionInput");
            positionInput.innerHTML = '<option value="--">--</option>'; // Reset options
            data.forEach(function(position) {
                var option = document.createElement("option");
                option.value = position.positions;
                option.text = position.positions;
                positionInput.appendChild(option);
            });
        },
        error: function(error) {
            console.error('Error fetching positions:', error);  
        }
    });
}


function validateSignUp(){
    let isValid = true;
    const athNum = $('#athNum');

    if (athNum.val().trim() === '') {
        athNum.attr('placeholder', 'Invalid input. Enter 9-digit or N/A');
        isValid = false;
    } else {
        athNum.attr('placeholder', 'asd input. Enter 9-digit or N/A');
    }
   
    
    return isValid;
}

function validateEmailDomain(email) {
    // List of allowed email domains
    const allowedDomains = [
        'gmail.com', 'yahoo.com', 'outlook.com', 'icloud.com', 'protonmail.com',
        'zoho.com', 'aol.com', 'yandex.com', 'gmx.com', 'mail.com',
        'tutanota.com', 'fastmail.com', 'hushmail.com', 'cvsu.edu.ph'
    ];

    // Extract the domain from the email
    const emailDomain = email.split('@')[1];

    // Check if the domain is in the allowed list
    return allowedDomains.includes(emailDomain);
}

$(document).ready(function() {

    getSports();

    document.getElementById("sportInput").addEventListener("change", function() {
        var selectedSport = this.value;
        getPositions(selectedSport);
    });

    $('#athNum').on('blur', function() {
        validateStudentNumber();
    });

    document.getElementById("sportInput").addEventListener("change", function() {
        var selectedSportInput = this.value;
        var studentNumber = $('#athNum').val();
        var selectedSport;

        if (studentNumber === "N/A") {
            selectedSport = "COACH";
        } else {
            selectedSport = selectedSportInput;
        }
        getPositions(selectedSport);

        console.log(selectedSport);
    });

    
    
    // Function to validate student number
    function validateStudentNumber() {
        var studentNumber = $('#athNum').val().trim();
        var valid = true;
        var errorMsg = '';

        // Validate Student Number
        if (studentNumber !== 'N/A' && !/^\d{9}$/.test(studentNumber)) {
            valid = false;
            errorMsg = 'Student Number must be exactly 9 digits or "N/A".';
            $('#athNum').addClass('is-invalid');
            $('#athNumError').text(errorMsg).show();
        } else {
            $('#athNum').removeClass('is-invalid');
            $('#athNumError').hide();
        }

        return valid;
    }



    // Function to validate password
    function validatePassword(password) {
        const passwordErrors = [];
        const passwordRequirements = {
            length: /^(?=.{8,}).*$/,
            uppercase: /^(?=.*[A-Z]).*$/,
            lowercase: /^(?=.*[a-z]).*$/,
            digit: /^(?=.*[0-9]).*$/,
            specialChar: /^(?=.*[!@#$%^&*]).*$/,
        };

        // Validate each requirement and add error messages if necessary
        if (!passwordRequirements.length.test(password)) {
            passwordErrors.push('Password must contain at least 8 characters.');
        }
        if (!passwordRequirements.uppercase.test(password)) {
            passwordErrors.push('Password must contain at least one uppercase letter.');
        }
        if (!passwordRequirements.lowercase.test(password)) {
            passwordErrors.push('Password must contain at least one lowercase letter.');
        }
        if (!passwordRequirements.digit.test(password)) {
            passwordErrors.push('Password must contain at least one digit (0-9).');
        }
        if (!passwordRequirements.specialChar.test(password)) {
            passwordErrors.push('Password must contain at least one special character (!@#$%^&*).');
        }

        return passwordErrors;
    }

    // Attach validation function to click event
    $('#signUpButton').click(function() {
        // Validation variables
        // Validation variables
        let athNum = $('#athNum').val();
        let athFirst = $('#athFirst').val().trim();
        let athLast = $('#athLast').val().trim();
        let athEmail = $('#athEmail').val().trim();
        let athPass = $('#athPass').val().trim();
        let athConPass = $('#athConPass').val().trim();
        let sportInput = $('#sportInput').val();
        let positionInput = $('#positionInput').val();

        let isValid = true;

        // Validate student number
        if (athNum === '') {
            $('#athNum').addClass('is-invalid');
            isValid = false;
        } else {
            $('#athNum').removeClass('is-invalid');
        }

        // Validate first and last name
        if (athFirst === '') {
            $('#athFirst').addClass('is-invalid');
            isValid = false;
        } else {
            $('#athFirst').removeClass('is-invalid');
        }

        if (athLast === '') {
            $('#athLast').addClass('is-invalid');
            isValid = false;
        } else {
            $('#athLast').removeClass('is-invalid');
        }

        // Validate sport and position
        if (sportInput === '--') {
            $('#sportInput').addClass('is-invalid');
            isValid = false;
        } else {
            $('#sportInput').removeClass('is-invalid');
        }

        if (positionInput === '--') {
            $('#positionInput').addClass('is-invalid');
            isValid = false;
        } else {
            $('#positionInput').removeClass('is-invalid');
        }

        // Validate email
        if (athEmail === '' || !validateEmailDomain(athEmail)) {
            $('#athEmail').addClass('is-invalid');
            isValid = false;
        } else {
            $('#athEmail').removeClass('is-invalid');
        }

        // Validate password
        let passwordErrors = validatePassword(athPass);
        if (passwordErrors.length > 0) {
            $('#athPass').addClass('is-invalid');
            isValid = false;
            setTimeout(function() {
                alert('Password Error:\n' + passwordErrors.join('\n'));
            }, 500); // Adjust the delay time (in milliseconds) as needed
        } else {
            $('#athPass').removeClass('is-invalid');
        }

        // Validate confirm password
        if (athConPass === '' || athConPass !== athPass) {
            $('#athConPass').addClass('is-invalid');
            isValid = false;
        } else {
            $('#athConPass').removeClass('is-invalid');
        }

        // Continue with the rest of the validation and AJAX submission only if all fields are valid
        if (isValid && validateStudentNumber() && validateEmailDomain(athEmail)) {
            // Disable the button and show spinner
            let $submitBtn = $('#signUpButton');
            let $buttonText = $('#buttonText1');
            $submitBtn.prop('disabled', true);
            $buttonText.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Sending...');

            // Form data collection
            let studentNumber = $('#athNum').val().trim();
            let data = {
                athNum: studentNumber,
                athFirst: athFirst,
                athLast: athLast,
                athSport: sportInput,
                athPosition: positionInput,
                athEmail: athEmail,
                athPass: athPass,
                athConPass: athConPass
            };

            // AJAX submission
            $.ajax({
                url: 'phpFile/buttonFunction/signUpButton.php',
                type: 'POST',
                data: data,
                success: function(response) {
                    if (response.status === "success") {
                        // Success handling
                        $buttonText.html('<i class="bi bi-check-circle-fill"></i> Success');
                        setTimeout(function() {
                            alert(response.message);
                            // Refresh the page instead of clearing the form
                            window.location.reload();
                        }, 500);
                    } else if (response.status === "error") {
                        // Error handling
                        $buttonText.html('<i class="bi bi-x-circle-fill"></i> Error');
                        setTimeout(function() {
                            alert('Sign up unsuccessful: ' + response.message);
                            $submitBtn.prop('disabled', false);
                            $buttonText.html('Sign Up');
                        }, 500);
                    }

                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    $buttonText.html('<i class="bi bi-x-circle-fill"></i> Error');
                    setTimeout(function() {
                        alert('An error occurred while signing up. Please try again.');
                        $submitBtn.prop('disabled', false);
                        $buttonText.html('Sign Up');
                    }, 500);
                }
            });
        }
    });
    
    
    
    


        $('#signInButton').on('click', function(e) {
            e.preventDefault(); // Prevent default button behavior
    
            const userEmailLog = $('#userEmailLog').val().trim();
            const userPassLog = $('#userPassLog').val().trim();
    
            var $submitBtn = $('#signInButton');
            var $userEmailInput = $('#userEmailLog');
            var $userPassInput = $('#userPassLog');
            let isValid = true;
    
            // Initialize message container
            var message = '';
    
            if (userEmailLog === '' && userPassLog === '') {
                // Both fields are empty
                message = 'Please enter your email address and password.';
                $userEmailInput.addClass('is-invalid');
                $userPassInput.addClass('is-invalid');
                isValid = false;
            } else if (userEmailLog === '') {
                // Only email is empty
                message = 'Please enter your email address.';
                $userEmailInput.addClass('is-invalid');
                $userPassInput.removeClass('is-invalid');
                isValid = false;
            } else if (userPassLog === '') {
                // Only password is empty
                message = 'Please enter your password.';
                $userPassInput.addClass('is-invalid');
                $userEmailInput.removeClass('is-invalid');
                isValid = false;
            } else {
                // Both fields are filled
                $userEmailInput.removeClass('is-invalid');
                $userPassInput.removeClass('is-invalid');
            }
    
            if (!isValid) {
                // Delay the alert to allow the browser to render the invalid feedback first
                setTimeout(function() {
                    alert(message);
                }, 100); // Short delay to ensure the warning is shown first
                return; // Stop further execution if validation fails
            }
    
            // Disable the button and show spinner
            $submitBtn.prop('disabled', true);
            $submitBtn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Signing in...');
    
    
    
            $.ajax({
                type: 'POST',
                url: 'phpFile/buttonFunction/signInButton.php',
                data: {
                    userEmailLog: userEmailLog,
                    userPassLog: userPassLog
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        alert('User logged in successfully!');
                        $('#userEmailLog').val('');
                        $('#userPassLog').val('');
                        window.location.href = response.redirectUrl;
                    } else {
                        alert('Error: ' + response.message);
                        $submitBtn.prop('disabled', false);
                        $submitBtn.html('Sign In'); // Reset button text
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', status, error);
                    alert('AJAX Error: ' + status + ' - ' + error);
                    $submitBtn.prop('disabled', false);
                    $submitBtn.html('Sign In'); // Reset button text
                },
                complete: function() {
                    // Hide the spinner when the AJAX request is complete
                    $submitBtn.html('Sign In'); // Reset button text
                }
            });
        });
    
    

});