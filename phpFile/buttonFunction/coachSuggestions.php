<?php
include "../connection/connection.php";

$searchName = $_GET['searchName'];
$suggestions = [];

if ($searchName) {
    $stmt = $conn->prepare("SELECT coach_lname FROM coach_tbl WHERE coach_lname LIKE ?");
    $searchTerm = "%{$searchName}%";
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $suggestions[] = $row;
    }

    $stmt->close();
}

echo json_encode($suggestions);
$conn->close();
?>
