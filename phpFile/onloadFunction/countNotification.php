<?php
include '../connection/connection.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['ath_email'])) {
    $loggedInUserEmail = $_SESSION['ath_email'];

    // Prepare and execute the SQL query to count the number of rows
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM basketball_created_match_notification WHERE match_participant = ?");
    $stmt->bind_param("s", $loggedInUserEmail); // 's' denotes the type of the parameter (string)
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    // Output the count as JSON
    echo json_encode(['count' => $row['count']]);
    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'No user logged in.']);
}

$conn->close();
?>
