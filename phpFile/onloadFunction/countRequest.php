<?php
include '../connection/connection.php';

// Prepare and execute the SQL query to count the number of rows
$query = "SELECT COUNT(*) as count FROM request_signup_tbl WHERE is_verified = 1";
$result = $conn->query($query);
$row = $result->fetch_assoc();

// Output the count as JSON
echo json_encode(['count' => $row['count']]);

$conn->close();
?>
