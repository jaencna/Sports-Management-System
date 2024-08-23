<?php
include '../connection/connection.php';

// Check if the selectedEmails array is set
if (isset($_POST['selectedEmails']) && is_array($_POST['selectedEmails'])) {
    $emails = $_POST['selectedEmails'];
    
    foreach ($emails as $email) {

            // Delete the request from request_signup_tbl after successful insertion
            $deleteQuery = "DELETE FROM request_signup_tbl WHERE req_email = ?";
            $deleteStmt = $conn->prepare($deleteQuery);
            $deleteStmt->bind_param('s', $email);
            $deleteStmt->execute();
            $deleteStmt->close();
        }
        
        $stmt->close();
    }


$conn->close();
?>
