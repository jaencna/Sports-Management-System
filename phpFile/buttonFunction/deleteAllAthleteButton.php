<?php

include('../connection/connection.php');
header('Content-Type: application/json');

// Start the transaction
$conn->begin_transaction();

try {
    // Delete from basketball_overall_raw
    $query2 = "DELETE FROM basketball_overall_raw";
    $stmt2 = $conn->prepare($query2);
    $stmt2->execute();
    $stmt2->close();

    // Delete from basketball_overall_percentage
    $query3 = "DELETE FROM basketball_overall_percentage";
    $stmt3 = $conn->prepare($query3);
    $stmt3->execute();
    $stmt3->close();

    // Delete from athlete_tbl
    $query1 = "DELETE FROM athlete_tbl";
    $stmt1 = $conn->prepare($query1);
    $stmt1->execute();
    $stmt1->close();

    // Commit the transaction
    $conn->commit();

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    // Rollback the transaction if something failed
    $conn->rollback();
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

// Close the connection
$conn->close();

?>
