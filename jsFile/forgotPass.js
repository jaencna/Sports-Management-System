$(document).ready(function() {
    $('#retrievePasswordButton').on('click', function(e) {
        e.preventDefault();
        
        var email = $('#emailInput').val().trim();
        var $submitBtn = $('#retrievePasswordButton');
        
        if (email === '') {
            alert('Please enter your email address.');
            return;
        }

        // Disable the button and show spinner
        $submitBtn.prop('disabled', true);
        $submitBtn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Sending...');

        $.ajax({
            url: 'phpFile/verificationFunction/passwordReset.php',
            method: 'POST',
            data: { email: email },
            dataType: 'json',
            success: function(response) {
                console.log(response); // Log the response
                if (response.status === 'success') {
                    // Change button text to success icon
                    $submitBtn.html('<i class="bi bi-check-circle-fill"></i> Success');
                    
                    // Delay the alert to allow the button text to update
                    setTimeout(function() {
                        alert('Password reset link sent to your email.');
                    }, 500); // Adjust the delay time (in milliseconds) as needed
                } else {
                    // Re-enable the button and show error message
                    $submitBtn.prop('disabled', false);
                    $submitBtn.html('Submit');
                    alert('Error: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', status, error);
                // Re-enable the button and show error message
                $submitBtn.prop('disabled', false);
                $submitBtn.html('Submit');
                alert('An error occurred. Please try again.');
            }
        });
    });
});
