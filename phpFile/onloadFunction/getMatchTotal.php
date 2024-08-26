<?php
include '../connection/connection.php';

if (isset($_POST['matchId'])) {
    $matchId = $_POST['matchId'];

    // Prepare the SQL query to fetch match details
    $query = "SELECT `id`, `match_id`, `game_team`, `game_pts`, `game_2fgm`, `game_2pts`, `game_3fgm`, `game_3pts`, `game_ftm`, `game_ftpts`, `game_2fga`, `game_3fga`, `game_fta`, `game_ass`, `game_block`, `game_steal`, `game_ofreb`, `game_defreb`, `game_turn`, `game_foul` FROM `basketball_game_total` WHERE match_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $matchId);
    $stmt->execute();
    $result = $stmt->get_result();

    $team1Data = [];
    $team2Data = [];

    while ($row = $result->fetch_assoc()) {
        if ($row['game_team'] === 'TEAM 1') {
            $team1Data[] = $row;
        } else {
            $team2Data[] = $row;
        }
    }

    $response = [
        'status' => 'success',
        'team1Data' => $team1Data,
        'team2Data' => $team2Data
    ];

    echo json_encode($response);

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'No match ID provided']);
}
?>
