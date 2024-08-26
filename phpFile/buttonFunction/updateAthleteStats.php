<?php
// Include database connection
include '../connection/connection.php';

// Get POST data
$match_id = $_POST['match_id'];
$match_quarter = $_POST['match_quarter'];
$ath_num = $_POST['ath_num'];
$ath_pts = $_POST['ath_pts'];
$ath_team = $_POST['ath_team'];
$ath_2fgm = $_POST['ath_2fgm'];
$ath_2pts = $_POST['ath_2pts'];
$ath_3fgm = $_POST['ath_3fgm'];
$ath_3pts = $_POST['ath_3pts'];
$ath_ftm = $_POST['ath_ftm'];
$ath_ftpts = $_POST['ath_ftpts'];
$ath_2fga = $_POST['ath_2fga'];
$ath_3fga = $_POST['ath_3fga'];
$ath_fta = $_POST['ath_fta'];
$ath_ass = $_POST['ath_ass'];
$ath_block = $_POST['ath_block'];
$ath_steal = $_POST['ath_steal'];
$ath_ofreb = $_POST['ath_ofreb'];
$ath_defreb = $_POST['ath_defreb'];
$ath_turn = $_POST['ath_turn'];
$ath_foul = $_POST['ath_foul'];

// Update athlete stats in database
$sql = "UPDATE basketball_game_tracking SET 
    ath_2fgm = ?, 
    ath_2pts = ?, 
    ath_3fgm = ?, 
    ath_3pts = ?, 
    ath_ftm = ?, 
    ath_ftpts = ?, 
    ath_2fga = ?, 
    ath_3fga = ?, 
    ath_fta = ?, 
    ath_ass = ?, 
    ath_block = ?, 
    ath_steal = ?, 
    ath_ofreb = ?, 
    ath_defreb = ?, 
    ath_turn = ?, 
    ath_foul = ?, 
    ath_pts = ? 
    WHERE match_id = ? 
    AND match_quarter = ? 
    AND ath_num = ? 
    AND ath_team = ?";

// Prepare and bind parameters
$stmt = $conn->prepare($sql);
$stmt->bind_param(
    'dddddddddddddddddssis', 
    $ath_2fgm, 
    $ath_2pts, 
    $ath_3fgm, 
    $ath_3pts, 
    $ath_ftm, 
    $ath_ftpts, 
    $ath_2fga, 
    $ath_3fga, 
    $ath_fta, 
    $ath_ass, 
    $ath_block, 
    $ath_steal, 
    $ath_ofreb, 
    $ath_defreb, 
    $ath_turn, 
    $ath_foul, 
    $ath_pts, 
    $match_id, 
    $match_quarter, 
    $ath_num, 
    $ath_team
);

// Execute the statement and check for errors
if ($stmt->execute()) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => $stmt->error]);
}

// Close statement and connection
$stmt->close();
$conn->close();
?>
