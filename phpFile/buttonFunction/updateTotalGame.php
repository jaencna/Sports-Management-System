<?php
include '../connection/connection.php';
header('Content-Type: application/json'); // Ensure the content type is JSON

function updateGameTotal($conn, $matchId, $teamData) {
    $sql = "
        UPDATE `basketball_game_total`
        SET 
            `game_pts` = ?,
            `game_2fgm` = ?,
            `game_2pts` = ?,
            `game_3fgm` = ?,
            `game_3pts` = ?,
            `game_ftm` = ?,
            `game_ftpts` = ?,
            `game_2fga` = ?,
            `game_3fga` = ?,
            `game_fta` = ?,
            `game_ass` = ?,
            `game_block` = ?,
            `game_steal` = ?,
            `game_ofreb` = ?,
            `game_defreb` = ?,
            `game_turn` = ?,
            `game_foul` = ?
        WHERE `match_id` = ? AND `game_team` = ?
    ";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('iiiiiiiiiiiiiiiiiis', 
            $teamData['game_pts'],
            $teamData['game_2fgm'],
            $teamData['game_2pts'],
            $teamData['game_3fgm'],
            $teamData['game_3pts'],
            $teamData['game_ftm'],
            $teamData['game_ftpts'],
            $teamData['game_2fga'],
            $teamData['game_3fga'],
            $teamData['game_fta'],
            $teamData['game_ass'],
            $teamData['game_block'],
            $teamData['game_steal'],
            $teamData['game_ofreb'],
            $teamData['game_defreb'],
            $teamData['game_turn'],
            $teamData['game_foul'],
            $matchId,
            $teamData['game_team']
        );
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update the record.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to prepare the SQL statement.']);
    }
}

if (isset($_POST['matchId']) && isset($_POST['teamData'])) {
    $matchId = intval($_POST['matchId']);
    $teamData = $_POST['teamData'];
    updateGameTotal($conn, $matchId, $teamData);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid input.']);
}
?>
