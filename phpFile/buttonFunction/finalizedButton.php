<?php
include '../connection/connection.php';

if (isset($_POST['team1Score']) && isset($_POST['team2Score']) && isset($_POST['dateTime'])) {
    $team1Score = $_POST['team1Score'];
    $team2Score = $_POST['team2Score'];
    $dateTime = $_POST['dateTime'];
    $matchId = $_POST['matchId']; // Assuming you pass matchId from your JavaScript
    $status = "done";
    
    // Determine match win and match lose
    $matchWin = $team1Score > $team2Score ? 'team_1' : ($team2Score > $team1Score ? 'team_2' : 'draw');
    $matchLose = $team1Score < $team2Score ? 'team_1' : ($team2Score < $team1Score ? 'team_2' : 'draw');

    // Prepare the SQL query to update the match results
    $query = "UPDATE `basketball_game_result` SET 
              `team_1_score` = ?, 
              `team_2_score` = ?, 
              `match_win` = ?, 
              `match_lose` = ?, 
              `match_date_time` = ?,
              `STATUS` = ?  
              WHERE `bball_match_id` = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('iissssi', $team1Score, $team2Score, $matchWin, $matchLose, $dateTime, $status,$matchId);

    // Execute the query
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Match results updated']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error updating match results']);
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Missing parameters']);
}
?>
