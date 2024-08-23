<?php
include '../connection/connection.php';

$field = $_POST['field'];
$value = $_POST['value'];
$id = $_POST['id'];

$query = "UPDATE coach_tbl SET $field = ? WHERE coach_email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $value, $id);

if ($stmt->execute()) {
    echo 'success';
} else {
    echo 'error';
}

$stmt->close();
$conn->close();
?>