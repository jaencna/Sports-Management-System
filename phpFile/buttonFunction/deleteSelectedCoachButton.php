<?php
include '../connection/connection.php';

if (isset($_POST['coachEmails'])) {
    $coachEmails = $_POST['coachEmails'];
    
    if (!empty($coachEmails)) {
        $placeholders = implode(',', array_fill(0, count($coachEmails), '?'));
        $sql = "DELETE FROM coach_tbl WHERE coach_email IN ($placeholders)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(str_repeat('s', count($coachEmails)), ...$coachEmails);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => $stmt->error]);
        }
        
        $stmt->close();
    }
}

$conn->close();
?>
