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

    // Attach validation function to click event
    $('#signUpButton').click(function() {
        const email = $('#athEmail').val().trim();
        var $submitBtn = $('#signUpButton');
        var $buttonText = $('#buttonText1');
    
        if (!validateEmailDomain(email)) {
            alert('Invalid email domain. Please use an allowed email provider.');
            return false;
        }
    
        if (validateStudentNumber()) {
            // Disable the button and show spinner
            $submitBtn.prop('disabled', true);
            $buttonText.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Sending...');
    
            // Form data collection
            var studentNumber = $('#athNum').val().trim();
            var data = {
                athNum: studentNumber,
                athFirst: $('#athFirst').val().trim(),
                athLast: $('#athLast').val().trim(),
                athSport: $('#sportInput').val(),
                athPosition: $('#positionInput').val(),
                athEmail: email,
                athPass: $('#athPass').val().trim(),
                athConPass: $('#athConPass').val().trim()
            };
    
            // AJAX submission
            $.ajax({
                url: 'phpFile/buttonFunction/signUpButton.php',
                type: 'POST',
                data: data,
                success: function(response) {
                    if (response.status === "success") {
                        // Change button text to success icon
                        $buttonText.html('<i class="bi bi-check-circle-fill"></i> Success');
                        setTimeout(function() {
                            alert(response.message);
                            // Clear the form
                            $('#athNum').val('');
                            $('#athFirst').val('');
                            $('#athLast').val('');
                            $('#sportInput').val('--');
                            $('#positionInput').val('--');
                            $('#athEmail').val('');
                            $('#athPass').val('');
                            $('#athConPass').val('');
                            // Re-enable the button
                            $submitBtn.prop('disabled', false);
                            $buttonText.html('Sign Up');
                        }, 500);
                    } else if (response.status === "error") {
                        // Change button text to error icon
                        $buttonText.html('<i class="bi bi-x-circle-fill"></i> Error');
                        setTimeout(function() {
                            alert('Sign up unsuccessful: ' + response.message);
                            // Re-enable the button
                            $submitBtn.prop('disabled', false);
                            $buttonText.html('Sign Up');
                        }, 500);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    // Change button text to error icon
                    $buttonText.html('<i class="bi bi-x-circle-fill"></i> Error');
                    setTimeout(function() {
                        alert('An error occurred while signing up. Please try again.');
                        // Re-enable the button
                        $submitBtn.prop('disabled', false);
                        $buttonText.html('Sign Up');
                    }, 500);
                }
            });
        }
    });
    
    
    


    $('#signInButton').on('click', function() {
        

        const userEmailLog = $('#userEmailLog').val();
        const userPassLog = $('#userPassLog').val();

        $('#signInButton .spinner-border').show();
        $('#buttonText').text('Signing In...');

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
                    // $('#buttonText').text('Signed In');
                    window.location.href = response.redirectUrl;
                } 
                // else {
                //     alert('Error: ' + response.message);
                //     $('#buttonText').text('Incorrect Cridentials');
                //     setTimeout(function() {
                //         $('#buttonText').text('Log In');
                //     }, 2000);
                // }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', status, error);
                alert('AJAX Error: ' + status + ' - ' + error);
            },

            complete: function() {
                // Hide the spinner when the AJAX request is complete
                $('#signInButton .spinner-border').hide();
            }
        });
    });
    

});