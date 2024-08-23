<?php
include '../connection/connection.php';

// Check if selectedAthletes is set and is an array
if (isset($_POST['selectedAthletes']) && is_array($_POST['selectedAthletes'])) {
    $status = 'inactive';
    
    foreach ($_POST['selectedAthletes'] as $athlete) {
        $email = $athlete['email'];
        $sport = $athlete['sport'];
        
        // Prepare the SQL query to update the status for the given email and sport
        $query = "UPDATE athlete_tbl SET STATUS = ? WHERE ath_email = ? AND ath_sport = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('sss', $status, $email, $sport);

        // Execute the query
        if (!$stmt->execute()) {
            echo json_encode(['success' => false, 'error' => $stmt->error]);
            $stmt->close();
            $conn->close();
            exit; // Stop the script on error
        }

        $stmt->close();
    }

    echo json_encode(['success' => true]);
    $conn->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid input.']);
}
?>
