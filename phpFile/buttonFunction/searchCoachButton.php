<?php
require '../connection/connection.php'; // Adjust path as necessary

$sport = isset($_GET['sport']) ? $_GET['sport'] : '--';
$coachLname = isset($_GET['coach_lname']) ? $_GET['coach_lname'] : '';

$sql = "SELECT * FROM coaches WHERE 1=1";

if ($sport != '--') {
    $sql .= " AND coach_sport = ?";
}

if (!empty($coachLname)) {
    $sql .= " AND coach_lname LIKE ?";
}

$stmt = $conn->prepare($sql);

if ($sport != '--' && !empty($coachLname)) {
    $searchTerm = "%{$coachLname}%";
    $stmt->bind_param('ss', $sport, $searchTerm);
} elseif ($sport != '--') {
    $stmt->bind_param('s', $sport);
} elseif (!empty($coachLname)) {
    $searchTerm = "%{$coachLname}%";
    $stmt->bind_param('s', $searchTerm);
} else {
    $stmt->execute();
    $result = $stmt->get_result();
    $coaches = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode($coaches);
    exit();
}

$stmt->execute();
$result = $stmt->get_result();
$coaches = $result->fetch_all(MYSQLI_ASSOC);
echo json_encode($coaches);
?>
