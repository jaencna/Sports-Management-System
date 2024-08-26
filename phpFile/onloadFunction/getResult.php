<?php
include '../connection/connection.php';

if (isset($_POST['matchId'])) {
    $matchID = $_POST['matchId'];
    // Prepare the SQL query to fetch match data
    $query = "SELECT `bball_match_id`, `match_name`, `team_1`, `team_2`, `team_1_score`, `team_2_score`, `match_win`, `match_lose`, `match_date_time` FROM `basketball_game_result` WHERE `bball_match_id` = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $matchID);

    // Execute the query
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    // Fetch the first match (assuming there's only one)
    $match = $result->fetch_assoc();

    // Output the result as JSON
    echo json_encode($match);

    // Close the statement and connection
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'No match ID provided']);
}
?>
