<?php
include '../connection/connection.php';

if (isset($_POST['athleteEmails'])) {
    $athleteEmails = $_POST['athleteEmails'];
    
    if (!empty($athleteEmails)) {
        $placeholders = implode(',', array_fill(0, count($athleteEmails), '?'));
        $sql = "DELETE FROM athlete_tbl WHERE ath_email IN ($placeholders)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(str_repeat('s', count($athleteEmails)), ...$athleteEmails);

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
