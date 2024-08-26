<?php
// Include database connection
include '../connection/connection.php'; // Ensure you have this file with your database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $matchName = $_POST['matchName'];
    $matchDateTime = $_POST['matchDateTime'];
    $matchEnd = "YYYY-MM-DD HH:MI:SS";
    $team1 = "TEAM 1"; // Fixed team name
    $team2 = "TEAM 2"; // Fixed team name
    $status = "pending";
    
    // Prepare the SQL statement to insert into basketball_game_result
    $stmt = $conn->prepare("INSERT INTO basketball_game_result (match_name, team_1, team_2, team_1_score, team_2_score, match_win, match_lose, match_date_time, STATUS) VALUES (?, ?, ?, 0, 0, '--', '--', ?, ?)");
    $stmt->bind_param('sssss', $matchName, $team1, $team2, $matchEnd, $status);

    // Execute the statement
    if ($stmt->execute()) {
        $matchId = $stmt->insert_id; // Get the ID of the newly created match
        
        // Prepare the statement for notifications
        $stmtNotification = $conn->prepare("INSERT INTO basketball_created_match_notification (match_participant, match_id, match_date_time, match_team) VALUES (?, ?, ?, ?)");
        
        // Prepare the statement for game tracking
        $stmtTracking = $conn->prepare("INSERT INTO basketball_game_tracking (match_id, match_quarter, ath_num, ath_team, ath_game) VALUES (?, ?, ?, ?, ?)");
        
        // Prepare the statement for game quarter stats
        $stmtQuarter = $conn->prepare("INSERT INTO basketball_game_quarter (match_id, game_quarter, quarter_team, quarter_points, quarter_2fgm, quarter_3fgm, quarter_ftm, quarter_2pts, quarter_3pts, quarter_ftpts, quarter_2fga, quarter_3fga, quarter_fta, quarter_ass, quarter_block, quarter_steal, quarter_ofreb, quarter_defreb, quarter_turn, quarter_foul) VALUES (?, ?, ?, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0)");

        // Prepare the statement for game total stats
        $stmtTotal = $conn->prepare("INSERT INTO basketball_game_total (match_id, game_team, game_pts, game_2fgm, game_2pts, game_3fgm, game_3pts, game_ftm, game_ftpts, game_2fga, game_3fga, game_fta, game_ass, game_block, game_steal, game_ofreb, game_defreb, game_turn, game_foul) VALUES (?, ?, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0)");

        // Insert notifications, game tracking, and game quarter stats for TEAM-1
        foreach ($_POST['team1SelectedAthletes'] as $email) {
            $stmtNotification->bind_param('siss', $email, $matchId, $matchDateTime, $team1);
            $stmtNotification->execute();
            
            // Fetch athlete number for TEAM-1
            $stmtAthlete = $conn->prepare("SELECT ath_id FROM athlete_tbl WHERE ath_email = ?");
            $stmtAthlete->bind_param('s', $email);
            $stmtAthlete->execute();
            $result = $stmtAthlete->get_result();
            $athlete = $result->fetch_assoc();
            $athNum = $athlete['ath_id'];
            $stmtAthlete->close();

            // Insert game tracking data for TEAM-1
            foreach ([1, 2, 3, 4] as $quarter) {
                $athTeam = 'TEAM 1';
                $athGame = 1;
                $stmtTracking->bind_param('iiisi', $matchId, $quarter, $athNum, $athTeam, $athGame);
                $stmtTracking->execute();
            }
        }

        // Insert notifications, game tracking, and game quarter stats for TEAM-2
        foreach ($_POST['team2SelectedAthletes'] as $email) {
            $stmtNotification->bind_param('siss', $email, $matchId, $matchDateTime, $team2);
            $stmtNotification->execute();
            
            // Fetch athlete number for TEAM-2
            $stmtAthlete = $conn->prepare("SELECT ath_id FROM athlete_tbl WHERE ath_email = ?");
            $stmtAthlete->bind_param('s', $email);
            $stmtAthlete->execute();
            $result = $stmtAthlete->get_result();
            $athlete = $result->fetch_assoc();
            $athNum = $athlete['ath_id'];
            $stmtAthlete->close();

            // Insert game tracking data for TEAM-2
            foreach ([1, 2, 3, 4] as $quarter) {
                $athTeam = 'TEAM 2';
                $athGame = 1;
                $stmtTracking->bind_param('iiisi', $matchId, $quarter, $athNum, $athTeam, $athGame);
                $stmtTracking->execute();
            }
        }

        // Insert game quarter stats for TEAM-1 and TEAM-2 for all quarters
        foreach ([1, 2, 3, 4] as $quarter) {
            $quarterTeams = [$team1, $team2];
            foreach ($quarterTeams as $team) {
                $stmtQuarter->bind_param('iis', $matchId, $quarter, $team);
                $stmtQuarter->execute();
            }
        }

        // Insert game total stats for TEAM-1 and TEAM-2
        $teams = [$team1, $team2];
        foreach ($teams as $team) {
            $stmtTotal->bind_param('is', $matchId, $team);
            $stmtTotal->execute();
        }

        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $stmt->error]);
    }

    // Close the statements and connection
    $stmt->close();
    $stmtNotification->close();
    $stmtTracking->close();
    $stmtQuarter->close();
    $stmtTotal->close();
    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>
