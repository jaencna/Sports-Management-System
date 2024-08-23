<?php
include '../connection/connection.php';

// Check if the selectedEmails are set and are an array
if (isset($_POST['selectedEmails']) && is_array($_POST['selectedEmails'])) {
    $type = isset($_POST['type']) ? $_POST['type'] : '';  // Retrieve the 'type' parameter from POST
    $emails = $_POST['selectedEmails'];
    $status = 'inactive';
    $newStatus = 'active';

    foreach ($emails as $email) {
        if ($type == 'COACH') {
            $query = "UPDATE coach_tbl SET STATUS = ? WHERE STATUS = ? AND coach_email = ?";
        } else if ($type == 'ATHLETE') {
            $query = "UPDATE athlete_tbl SET STATUS = ? WHERE STATUS = ? AND ath_email = ?";
        } else {
            continue; // If the type is not recognized, skip this iteration
        }

        $stmt = $conn->prepare($query);
        $stmt->bind_param('sss', $newStatus, $status, $email);

        // Execute the query
        if ($stmt->execute()) {
            $response = ['success' => true];
        } else {
            $response = ['success' => false, 'error' => $stmt->error];
            break; // Exit the loop on failure
        }

        $stmt->close();
    }

    echo json_encode($response);
    $conn->close();
}
?>
