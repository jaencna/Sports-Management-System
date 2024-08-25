<?php
include '../connection/connection.php';

// Check if the last name or sport is set and sanitize input
$ath_last = isset($_GET['ath_last']) ? $conn->real_escape_string($_GET['ath_last']) : '';
$sport = isset($_GET['sport']) ? $conn->real_escape_string($_GET['sport']) : '--';
$status = "active";

// Prepare the SQL query based on the filters
if ($ath_last !== '' && $sport !== '--') {
    $query = "SELECT ath_first, ath_last, ath_email, ath_pass, ath_sport, ath_position, ath_height, ath_weight, ath_img, STATUS FROM athlete_tbl WHERE ath_last = ? AND ath_sport = ?  AND STATUS = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sss', $ath_last, $sport, $status);
} elseif ($ath_last !== '') {
    $query = "SELECT ath_first, ath_last, ath_email, ath_pass, ath_sport, ath_position, ath_height, ath_weight, ath_img, STATUS  FROM athlete_tbl WHERE ath_last = ?  AND STATUS = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ss', $ath_last, $status);
} elseif ($sport !== '--') {
    $query = "SELECT ath_first, ath_last, ath_email, ath_pass, ath_sport, ath_position, ath_height, ath_weight, ath_img, STATUS  FROM athlete_tbl WHERE ath_sport = ?  AND STATUS = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ss', $sport, $status);
} else {
    $query = "SELECT ath_first, ath_last, ath_email, ath_pass, ath_sport, ath_position, ath_height, ath_weight, ath_img, STATUS  FROM athlete_tbl  WHERE STATUS = ?";
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
