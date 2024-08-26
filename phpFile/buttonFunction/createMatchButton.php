<?php
// Include database connection
include '../connection/connection.php'; // Ensure you have this file with your database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $matchName = $_POST['matchName'];
    $matchDateTime = $_POST['matchDateTime'];
    $matchEnd = "YYYY-MM-DD HH:MI:SS";
    $team1 = "TEAM 1"; // Fixed team name
    $team2 = "TEAM 2"; // Fixed team name

    // Prepare the SQL statement to insert into basketball_game_result
    $stmt = $conn->prepare("INSERT INTO basketball_game_result (match_name, team_1, team_2, team_1_score, team_2_score, match_win, match_lose, match_date_time) VALUES (?, ?, ?, 0, 0, '--', '--', ?)");
    $stmt->bind_param('ssss', $matchName, $team1, $team2, $matchEnd);

    // Execute the statement
    if ($stmt->execute()) {
        $matchId = $stmt->insert_id; // Get the ID of the newly created match
        
        // Insert notifications for TEAM-1
        foreach ($_POST['team1SelectedAthletes'] as $email) {
            $stmtNotification = $conn->prepare("INSERT INTO basketball_created_match_notification (match_participant, match_id, match_date_time, match_team) VALUES (?, ?, ?, ?)");
            $stmtNotification->bind_param('siss', $email, $matchId, $matchDateTime, $team1);
            $stmtNotification->execute();
        }

        // Insert notifications for TEAM-2
        foreach ($_POST['team2SelectedAthletes'] as $email) {
            $stmtNotification = $conn->prepare("INSERT INTO basketball_created_match_notification (match_participant, match_id, match_date_time, match_team) VALUES (?, ?, ?, ?)");
            $stmtNotification->bind_param('siss', $email, $matchId, $matchDateTime, $team2);
            $stmtNotification->execute();
        }

        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $stmt->error]);
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>
