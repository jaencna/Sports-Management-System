<?php
include '../connection/connection.php';

// Check if the last name or sport is set and sanitize input
$type = isset($_GET['type']) ? $conn->real_escape_string($_GET['type']) : '--';
$sport = isset($_GET['sport']) ? $conn->real_escape_string($_GET['sport']) : '--';
$verified = 1;

// Prepare the SQL query based on the filters
if ($type !== '--' && $sport !== '--') {
    $query = "SELECT req_stu_id, req_fname, req_lname, req_sport, req_position, req_user_type, req_email, req_pass FROM request_signup_tbl WHERE req_user_type = ? AND req_sport = ? AND is_verified = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ssi', $type, $sport, $verified);
} elseif ($type !== '--') {
    $query = "SELECT  req_stu_id, req_fname, req_lname, req_sport, req_position, req_user_type, req_email, req_pass FROM request_signup_tbl WHERE req_user_type = ? AND is_verified = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('si', $type, $verified);
} elseif ($sport !== '--') {
    $query = "SELECT req_stu_id, req_fname, req_lname, req_sport, req_position, req_user_type, req_email, req_pass FROM request_signup_tbl WHERE req_sport = ?  AND is_verified = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('si', $sport, $verified);
} else {
    $query = "SELECT req_stu_id, req_fname, req_lname, req_sport, req_position, req_user_type, req_email, req_pass FROM request_signup_tbl WHERE is_verified = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $verified);
}

// Execute the query
$stmt->execute();
$result = $stmt->get_result();

$request = array();
while ($row = $result->fetch_assoc()) {
    $request[] = $row;
}

// Output the results as JSON
echo json_encode($request);

$stmt->close();
$conn->close();
?>