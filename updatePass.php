<?php

// HASH
// require 'phpFile/connection/connection.php'; // Include the database connection

// if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//     $token = $_POST['token'];
//     $password = $_POST['password'];
//     $confirm_password = $_POST['confirm_password'];

//     if ($password !== $confirm_password) {
//         echo json_encode(array("status" => "error", "message" => "Passwords do not match"));
//         exit();
//     }

//     // Validate token and check expiry
//     $stmt = $conn->prepare("SELECT email, expires FROM password_reset WHERE token=?");
//     $stmt->bind_param('s', $token);
//     $stmt->execute();
//     $stmt->bind_result($email, $expires);
//     $stmt->fetch();
//     $stmt->close();

//     if (!$email || strtotime($expires) < time()) {
//         echo json_encode(array("status" => "error", "message" => "Invalid or expired token"));
//         exit();
//     }

//     // Hash the new password before storing it
//     $hashed_password = password_hash($password, PASSWORD_DEFAULT);

//     // Update the user's password in athlete_tbl
//     $stmt = $conn->prepare("UPDATE athlete_tbl SET ath_pass=? WHERE ath_email=?");
//     $stmt->bind_param('ss', $hashed_password, $email);
//     $stmt->execute();

//     // Check if the password update affected any rows
//     if ($stmt->affected_rows === 0) {
//         // Try updating the coach_tbl if athlete_tbl didn't have the email
//         $stmt = $conn->prepare("UPDATE coach_tbl SET coach_pass=? WHERE coach_email=?");
//         $stmt->bind_param('ss', $hashed_password, $email);
//         $stmt->execute();
//     }

//     $stmt->close();

//     // Delete the token after the password has been reset
//     $stmt = $conn->prepare("DELETE FROM password_reset WHERE token=?");
//     $stmt->bind_param('s', $token);
//     $stmt->execute();
//     $stmt->close();

//     echo json_encode(array("status" => "success", "message" => "Password has been reset successfully"));
// }

require 'phpFile/connection/connection.php'; // Include the database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = $_POST['token'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        echo json_encode(array("status" => "error", "message" => "Passwords do not match"));
        exit();
    }

    // Validate token and check expiry
    $stmt = $conn->prepare("SELECT email, expires FROM password_reset WHERE token=?");
    $stmt->bind_param('s', $token);
    $stmt->execute();
    $stmt->bind_result($email, $expires);
    $stmt->fetch();
    $stmt->close();

    if (!$email || strtotime($expires) < time()) {
        echo json_encode(array("status" => "error", "message" => "Invalid or expired token"));
        exit();
    }

    // Update the user's password in athlete_tbl without hashing
    $stmt = $conn->prepare("UPDATE athlete_tbl SET ath_pass=? WHERE ath_email=?");
    $stmt->bind_param('ss', $password, $email);
    $stmt->execute();

    // Check if the password update affected any rows
    if ($stmt->affected_rows === 0) {
        // Try updating the coach_tbl if athlete_tbl didn't have the email
        $stmt = $conn->prepare("UPDATE coach_tbl SET coach_pass=? WHERE coach_email=?");
        $stmt->bind_param('ss', $password, $email);
        $stmt->execute();
    }

    $stmt->close();

    // Delete the token after the password has been reset
    $stmt = $conn->prepare("DELETE FROM password_reset WHERE token=?");
    $stmt->bind_param('s', $token);
    $stmt->execute();
    $stmt->close();

    echo json_encode(array("status" => "success", "message" => "Password has been reset successfully"));
}

?>
