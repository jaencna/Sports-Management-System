<?php
include '../connection/connection.php';
header('Content-Type: application/json'); // Ensure the content type is JSON

function fetchAndAggregateGameTrackingData($conn, $matchId) {
    // Prepare the SQL statement to aggregate data by team and quarter
    $sql = "
        SELECT 
            `ath_team`,
            `match_quarter`,
            SUM(`ath_pts`) AS total_pts,
            SUM(`ath_2fgm`) AS total_2fgm,
            SUM(`ath_2pts`) AS total_2pts,
            SUM(`ath_3fgm`) AS total_3fgm,
            SUM(`ath_3pts`) AS total_3pts,
            SUM(`ath_ftm`) AS total_ftm,
            SUM(`ath_ftpts`) AS total_ftpts,
            SUM(`ath_2fga`) AS total_2fga,
            SUM(`ath_3fga`) AS total_3fga,
            SUM(`ath_fta`) AS total_fta,
            SUM(`ath_ass`) AS total_ass,
            SUM(`ath_block`) AS total_block,
            SUM(`ath_steal`) AS total_steal,
            SUM(`ath_ofreb`) AS total_ofreb,
            SUM(`ath_defreb`) AS total_defreb,
            SUM(`ath_turn`) AS total_turn,
            SUM(`ath_foul`) AS total_foul
        FROM 
            `basketball_game_tracking` 
        WHERE 
            `match_id` = ? 
        GROUP BY 
            `ath_team`, `match_quarter`
    ";

    // Initialize response array
    $response = array();

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('i', $matchId);
        $stmt->execute();
        $result = $stmt->get_result();

        // Initialize arrays to hold aggregated data by quarter
        $team1QuarterData = array();
        $team2QuarterData = array();

        // Process each row and group data by team and quarter
        while ($row = $result->fetch_assoc()) {
            $quarter = 'Quarter' . $row['match_quarter'];
            if ($row['ath_team'] == 'TEAM 1') {
                $team1QuarterData[$quarter] = $row;
            } else if ($row['ath_team'] == 'TEAM 2') {
                $team2QuarterData[$quarter] = $row;
            }
        }

        // Set response success
        $response['status'] = 'success';
        $response['data'] = array(
            'team1' => $team1QuarterData,
            'team2' => $team2QuarterData
        );
    } else {
        // Set response failure
        $response['status'] = 'error';
        $response['message'] = 'Failed to prepare the SQL statement.';
    }

    // Return the response as JSON
    echo json_encode($response);
}

// Call the function with the POSTed matchId
if (isset($_POST['matchId'])) {
    fetchAndAggregateGameTrackingData($conn, intval($_POST['matchId']));
} else {
    echo json_encode(array('status' => 'error', 'message' => 'No matchId provided.'));
}
?>
