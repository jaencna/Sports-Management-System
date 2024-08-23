<?php
include '../connection/connection.php';

// Check if selectedCoach is set and is an array
if (isset($_POST['selectedCoach']) && is_array($_POST['selectedCoach'])) {
    $status = 'inactive';
    
    foreach ($_POST['selectedCoach'] as $coach) {
        $email = $coach['email'];
        $sport = $coach['sport'];
        
        // Prepare the SQL query to update the status for the given email and sport
        $query = "UPDATE coach_tbl SET STATUS = ? WHERE coach_email = ? AND coach_sport = ?";
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
