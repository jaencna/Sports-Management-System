<?php
session_start();
include '../connection/connection.php';

// Check if an athlete is logged in
if (isset($_SESSION['athlete_email'])) {
    // Fetch the athlete's email from the session
    $loggedInUserEmail = $_SESSION['athlete_email'];
    // Prepare and execute the SELECT query to fetch all data of the logged-in athlete
    $stmt = $conn->prepare("SELECT * FROM athlete_tbl WHERE athlete_email = ?");
    $stmt->bind_param("s", $loggedInUserEmail);
    $stmt->execute();
    $result = $stmt->get_result();
    // Check if the user exists
    if ($result->num_rows > 0) {
        $loggedInUserData = $result->fetch_assoc();
        // Output the logged-in athlete's data as JSON response
        echo json_encode(['status' => 'success', 'userType' => 'Athlete', 'loggedInUserData' => $loggedInUserData]);
        exit();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Athlete not found.']);
        exit();
    }
}

if (isset($_SESSION['coach_email'])) {
    // Fetch the coach's email from the session
    $loggedInUserEmail = $_SESSION['coach_email'];
    // Prepare and execute the SELECT query to fetch all data of the logged-in coach
    $stmt = $conn->prepare("SELECT * FROM coach_tbl WHERE coach_email = ?");
    $stmt->bind_param("s", $loggedInUserEmail);
    $stmt->execute();
    $result = $stmt->get_result();
    // Check if the user exists
    if ($result->num_rows > 0) {
        $loggedInUserData = $result->fetch_assoc();
        // Output the logged-in coach's data as JSON response
        echo json_encode(['status' => 'success', 'userType' => 'Coach', 'loggedInUserData' => $loggedInUserData]);
        exit();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Coach not found.']);
        exit();
    }
}

// Check if a coach is logged in
if (isset($_SESSION['admin_email'])) {
    // Fetch the coach's email from the session
    $loggedInUserEmail = $_SESSION['admin_email'];
    // Prepare and execute the SELECT query to fetch all data of the logged-in coach
    $stmt = $conn->prepare("SELECT * FROM admin_tbl WHERE admin_email = ?");
    $stmt->bind_param("s", $loggedInUserEmail);
    $stmt->execute();
    $result = $stmt->get_result();
    // Check if the user exists
    if ($result->num_rows > 0) {
        $loggedInUserData = $result->fetch_assoc();
        // Output the logged-in coach's data as JSON response
        echo json_encode(['status' => 'success', 'userType' => 'admin', 'loggedInUserData' => $loggedInUserData]);
        exit();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Admin not found.']);
        exit();
    }
}

// If no user is logged in
echo json_encode(['status' => 'error', 'message' => 'No user logged in.']);
exit();
?>
