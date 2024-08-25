<?php
include '../connection/connection.php';

if (isset($_POST['athleteEmails'])) {
    $athleteEmails = $_POST['athleteEmails'];
    
    if (!empty($athleteEmails)) {
        // Start transaction
        $conn->begin_transaction();

        try {
            // Prepare and execute fetching athlete_id for each email
            $placeholders = implode(',', array_fill(0, count($athleteEmails), '?'));
            $sql = "SELECT ath_id FROM athlete_tbl WHERE ath_email IN ($placeholders)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param(str_repeat('s', count($athleteEmails)), ...$athleteEmails);
            $stmt->execute();
            $stmt->bind_result($ath_id);

            // Collect ath_id values
            $athleteIds = [];
            while ($stmt->fetch()) {
                $athleteIds[] = $ath_id;
            }
            $stmt->close();

            // Delete from basketball_overall_raw and basketball_overall_percentage
            foreach ($athleteIds as $ath_id) {
                // Delete from basketball_overall_raw
                $sql = "DELETE FROM basketball_overall_raw WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('i', $ath_id);
                $stmt->execute();
                $stmt->close();

                // Delete from basketball_overall_percentage
                $sql = "DELETE FROM basketball_overall_percentage WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('i', $ath_id);
                $stmt->execute();
                $stmt->close();
            }

            // Delete from athlete_tbl
            $sql = "DELETE FROM athlete_tbl WHERE ath_email IN ($placeholders)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param(str_repeat('s', count($athleteEmails)), ...$athleteEmails);
            $stmt->execute();
            $stmt->close();

            // Commit transaction
            $conn->commit();

            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            // Rollback transaction if any error occurs
            $conn->rollback();
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }

        $conn->close();
    }
}
?>
