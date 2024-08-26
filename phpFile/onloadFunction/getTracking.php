<?php
session_start();
include '../connection/connection.php';

// Check for database connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_SESSION['ath_email'])) {
    $loggedInUserEmail = $_SESSION['ath_email'];

    // Fetch athlete's data
    $stmt = $conn->prepare("SELECT * FROM athlete_tbl WHERE ath_email = ?");
    if (!$stmt) {
        echo json_encode(['status' => 'error', 'message' => 'Prepare failed: ' . $conn->error]);
        exit();
    }
    $stmt->bind_param("s", $loggedInUserEmail);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $loggedInUserData = $result->fetch_assoc();
        $ath_id = $loggedInUserData['ath_id'];

        // Fetch match names and IDs
        $stmt1 = $conn->prepare("SELECT bball_match_id, match_name FROM basketball_game_result");
        if (!$stmt1) {
            echo json_encode(['status' => 'error', 'message' => 'Prepare failed: ' . $conn->error]);
            exit();
        }
        $stmt1->execute();
        $result1 = $stmt1->get_result();
        $matches = [];
        while ($row = $result1->fetch_assoc()) {
            $matches[$row['bball_match_id']] = $row['match_name'];
        }

        // Fetch and sum points from basketball_game_tracking
        $stmt2 = $conn->prepare("
            SELECT 
                match_id,
                SUM(ath_pts) AS total_pts,
                SUM(ath_2pts) AS total_2pts,
                SUM(ath_3pts) AS total_3pts,
                SUM(ath_ftpts) AS total_ftpts,
                SUM(ath_fta) AS total_fta,
                SUM(ath_block) AS total_blocks,
                SUM(ath_steal) AS total_steals,
                SUM(ath_foul) AS total_fouls
            FROM basketball_game_tracking 
            WHERE ath_num = ? AND match_quarter IN (1, 2, 3, 4)
            GROUP BY match_id
        ");
        if (!$stmt2) {
            echo json_encode(['status' => 'error', 'message' => 'Prepare failed: ' . $conn->error]);
            exit();
        }
        $stmt2->bind_param("i", $ath_id);
        $stmt2->execute();
        $result2 = $stmt2->get_result();
        $stats = [];
        while ($row = $result2->fetch_assoc()) {
            $match_id = $row['match_id'];
            if (isset($matches[$match_id])) {
                $stats[] = [
                    'match_name' => $matches[$match_id],
                    'total_pts' => $row['total_pts'] ?? 0,
                    'total_2pts' => $row['total_2pts'] ?? 0,
                    'total_3pts' => $row['total_3pts'] ?? 0,
                    'total_ftpts' => $row['total_ftpts'] ?? 0,
                    'total_fta' => $row['total_fta'] ?? 0,
                    'total_blocks' => $row['total_blocks'] ?? 0,
                    'total_steals' => $row['total_steals'] ?? 0,
                    'total_fouls' => $row['total_fouls'] ?? 0
                ];
            }
        }

        // Output the data
        echo json_encode([
            'status' => 'success',
            'userType' => 'Athlete',
            'loggedInUserData' => $loggedInUserData,
            'gameStats' => $stats
        ]);
        exit();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Athlete not found.']);
        exit();
    }
}

echo json_encode(['status' => 'error', 'message' => 'No user logged in.']);
exit();
?>
