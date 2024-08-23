<?php
include '../connection/connection.php';

// Check if the last name or sport is set and sanitize input
$type = isset($_GET['type']) ? $_GET['type'] : '';  // Retrieve the 'type' parameter
$sport = isset($_GET['sport']) ? $conn->real_escape_string($_GET['sport']) : '--';
$status = 'inactive';
// Prepare the SQL query based on the filters
if ($type == 'COACH') {

    if ($sport !== '--') {
        $query = "SELECT id, coach_fname, coach_lname, coach_email, coach_pass, coach_sport, coach_img, coach_position, STATUS FROM coach_tbl WHERE coach_sport = ? AND STATUS = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ss', $sport, $status);
    } else {
        $query = "SELECT id, coach_fname, coach_lname, coach_email, coach_pass, coach_sport, coach_img, coach_position, STATUS FROM coach_tbl WHERE STATUS = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('s', $status);
    }
    
    // Execute the query
    $stmt->execute();
    $result = $stmt->get_result();
    
    $request = array();
    while ($row = $result->fetch_assoc()) {
        $request[] = $row;
    }
    
    // Output the results as JSON
    echo json_encode($request);
    
    $stmt->close();
    $conn->close();

} else if ($type == 'ATHLETE') {

    if ($sport !== '--') {
        $query = "SELECT ath_num, ath_first, ath_last, ath_email, ath_pass, ath_sport, ath_position, ath_img, STATUS  FROM athlete_tbl WHERE ath_sport = ?  AND STATUS = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ss', $sport, $status);
    } else {
        $query = "SELECT ath_num, ath_first, ath_last, ath_email, ath_pass, ath_sport, ath_position, ath_img, STATUS  FROM athlete_tbl  WHERE STATUS = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('s', $status);
    }
    
    // Execute the query
    $stmt->execute();
    $result = $stmt->get_result();
    
    $request = array();
    while ($row = $result->fetch_assoc()) {
        $request[] = $row;
    }
    
    // Output the results as JSON
    echo json_encode($request);
    
    $stmt->close();
    $conn->close();

}

?>
