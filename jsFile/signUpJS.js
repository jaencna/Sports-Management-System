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
        // Reset positions dropdown if the default option is selecteds
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
                option.value = position.id;
                option.text = position.position;
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

        if (!validateEmailDomain(email)) {
            alert('Invalid email domain. Please use an allowed email provider.');
            return false;
        }

        if (validateStudentNumber()) {
            // Form data collection and AJAX submission
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

            // Send data to the appropriate PHP script based on Student Number


            $.ajax({
                url: 'phpFile/buttonFunction/signUpButton.php',
                type: 'POST',
                data: data,
                success: function(response) {
                    alert('Sign up successful');
                    // Clear the form
                    $('#athNum').val('');
                    $('#athFirst').val('');
                    $('#athLast').val('');
                    $('#sportInput').val('--');
                    $('#positionInput').val('--');
                    $('#athEmail').val('');
                    $('#athPass').val('');
                    $('#athConPass').val('');
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    alert('An error occurred while signing up. Please try again.');
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