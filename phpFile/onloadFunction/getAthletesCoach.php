<?php
include '../connection/connection.php';

// Check if the last name or sport is set and sanitize input
$position = isset($_GET['position']) ? $conn->real_escape_string($_GET['position']) : '--';
$status = "active";

// Prepare the SQL query based on the filters
if ($position !== '--') {
    $query = "SELECT ath_id, ath_first, ath_last, ath_email, ath_pass, ath_sport, ath_position, ath_height, ath_weight, ath_img, STATUS FROM athlete_tbl WHERE ath_position = ? AND STATUS = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ss', $position, $status);
} else {
    $query = "SELECT ath_id, ath_first, ath_last, ath_email, ath_pass, ath_sport, ath_position, ath_height, ath_weight, ath_img, STATUS  FROM athlete_tbl  WHERE STATUS = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $status);
}

// Execute the query
$stmt->execute();
$result = $stmt->get_result();

$athlete = array();
while ($row = $result->fetch_assoc()) {
    $athlete[] = $row;
}

// Output the results as JSON
echo json_encode($athlete);

$stmt->close();
$conn->close();
?>
