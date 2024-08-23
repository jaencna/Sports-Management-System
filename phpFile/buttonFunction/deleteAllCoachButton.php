<?php

include('../connection/connection.php');
header('Content-Type: application/json');

$query = "DELETE FROM `coach_tbl`"; // Correct SQL Syntax
$stmt = $conn->prepare($query);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => $stmt->error]);
}

$stmt->close();
$conn->close();

?>
