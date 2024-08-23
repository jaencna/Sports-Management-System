<?php
include '../connection/connection.php';

// Check if the last name or sport is set and sanitize input
$coach_lname = isset($_GET['coach_lname']) ? $conn->real_escape_string($_GET['coach_lname']) : '';
$sport = isset($_GET['sport']) ? $conn->real_escape_string($_GET['sport']) : '--';
$status = "active";

// Prepare the SQL query based on the filters
if ($coach_lname !== '' && $sport !== '--') {
    $query = "SELECT coach_fname, coach_lname, coach_email, coach_pass, coach_sport, coach_img, coach_position, STATUS FROM coach_tbl WHERE coach_lname = ? AND coach_sport = ? AND STATUS = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sss', $coach_lname, $sport, $status);
} elseif ($coach_lname !== '') {
    $query = "SELECT coach_fname, coach_lname, coach_email, coach_pass, coach_sport, coach_img, coach_position, STATUS FROM coach_tbl WHERE coach_lname = ? AND STATUS = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ss', $coach_lname, $status);
} elseif ($sport !== '--') {
    $query = "SELECT coach_fname, coach_lname, coach_email, coach_pass, coach_sport, coach_img, coach_position, STATUS FROM coach_tbl WHERE coach_sport = ? AND STATUS = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ss', $sport, $status);
} else {
    $query = "SELECT coach_fname, coach_lname, coach_email, coach_pass, coach_sport, coach_img, coach_position, STATUS FROM coach_tbl WHERE STATUS = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $status);
}

// Execute the query
$stmt->execute();
$result = $stmt->get_result();

$coaches = array();
while ($row = $result->fetch_assoc()) {
    $coaches[] = $row;
}

// Output the results as JSON
echo json_encode($coaches);

$stmt->close();
$conn->close();
?>
