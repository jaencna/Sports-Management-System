<?php
include "../connection/connection.php";

// Retrieve POST data
$id = $_POST['id'];
$field = $_POST['field'];
$value = $_POST['value'];

// Validate and sanitize inputs
if (!in_array($field, ['ath_first', 'ath_last', 'ath_position', 'ath_height', 'ath_weight'])) {
    echo "Invalid field name.";
    exit;
}

$value = $conn->real_escape_string($value);

// Prepare SQL statement
$sql = "UPDATE athlete_tbl SET $field = ? WHERE ath_id = ?";
$stmt = $conn->prepare($sql);

if ($stmt) {
    // Determine the type of the field (s = string, i = integer)
    $type = is_numeric($value) ? 'i' : 's';
    $stmt->bind_param($type . 'i', $value, $id);

    // Execute and check for success
    if ($stmt->execute()) {
        echo "Update successful";
    } else {
        error_log("Update failed: " . $stmt->error);
        echo "Update failed: " . $stmt->error;
    }
    
    $stmt->close();
} else {
    error_log("Prepare failed: " . $conn->error);
    echo "Prepare failed: " . $conn->error;
}

$conn->close();
?>
