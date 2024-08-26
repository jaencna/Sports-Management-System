<?php
include '../connection/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $matchId = $_POST['matchId'];
    $team1Score = $_POST['team1Score'];
    $team2Score = $_POST['team2Score'];

    $sql = "UPDATE basketball_game_result SET team_1_score = ?, team_2_score = ? WHERE bball_match_id = ?";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("iii", $team1Score, $team2Score, $matchId);
        
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => $stmt->error]);
        }

        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => $conn->error]);
    }

    $conn->close();
}
?>
