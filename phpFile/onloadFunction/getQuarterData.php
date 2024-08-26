<?php
// Include database connection
include '../connection/connection.php';

if (isset($_POST['matchId']) && isset($_POST['quarter'])) {
    $matchId = intval($_POST['matchId']);
    $quarter = intval($_POST['quarter']);

    $response = array();

    // Prepare the SQL query
    $sql = "
        SELECT bgt.`id`, bgt.`match_id`, bgt.`match_quarter`, bgt.`ath_num`, bgt.`ath_team`, bgt.`ath_pts`, bgt.`ath_2fgm`, bgt.`ath_2pts`, bgt.`ath_3fgm`, bgt.`ath_3pts`, bgt.`ath_ftm`, bgt.`ath_ftpts`, bgt.`ath_2fga`, bgt.`ath_3fga`, bgt.`ath_fta`, bgt.`ath_ass`, bgt.`ath_block`, bgt.`ath_steal`, bgt.`ath_ofreb`, bgt.`ath_defreb`, bgt.`ath_turn`, bgt.`ath_foul`, bgt.`ath_game`,
               a.`ath_first`, a.`ath_last`
        FROM `basketball_game_tracking` bgt
        JOIN `athlete_tbl` a ON bgt.`ath_num` = a.`ath_id`
        WHERE bgt.`match_id` = ? AND bgt.`match_quarter` = ?
    ";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('ii', $matchId, $quarter);
        $stmt->execute();
        $result = $stmt->get_result();

        $team1QuarterData = array();
        $team2QuarterData = array();

        while ($row = $result->fetch_assoc()) {
            if ($row['ath_team'] == 'TEAM 1') {
                $team1QuarterData[] = $row;
            } else {
                $team2QuarterData[] = $row;
            }
        }

        $response['status'] = 'success';
        $response['team1QuarterData'] = $team1QuarterData;
        $response['team2QuarterData'] = $team2QuarterData;
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Failed to prepare SQL statement';
    }

    echo json_encode($response);
}
?>
