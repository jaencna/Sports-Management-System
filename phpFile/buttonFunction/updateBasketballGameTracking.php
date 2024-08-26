<?php
// Connect to the database
include '../connection/connection.php';

// Get the input data from the request
$data = json_decode(file_get_contents('php://input'), true);

$match_id = $data['match_id'];
$match_quarter = $data['match_quarter'];
$ath_num = $data['ath_num'];
$ath_pts = $data['ath_pts'];
$ath_2fgm = $data['ath_2fgm'];
$ath_2pts = $data['ath_2pts'];
$ath_3fgm = $data['ath_3fgm'];
$ath_3pts = $data['ath_3pts'];
$ath_ftm = $data['ath_ftm'];
$ath_ftpts = $data['ath_ftpts'];
$ath_2fga = $data['ath_2fga'];
$ath_3fga = $data['ath_3fga'];
$ath_fta = $data['ath_fta'];
$ath_ass = $data['ath_ass'];
$ath_block = $data['ath_block'];
$ath_steal = $data['ath_steal'];
$ath_ofreb = $data['ath_ofreb'];
$ath_defreb = $data['ath_defreb'];
$ath_turn = $data['ath_turn'];
$ath_foul = $data['ath_foul'];

// Update the database
$sql = "UPDATE basketball_game_tracking 
        SET ath_pts='$ath_pts', ath_2fgm='$ath_2fgm', ath_2pts='$ath_2pts', 
            ath_3fgm='$ath_3fgm', ath_3pts='$ath_3pts', ath_ftm='$ath_ftm', 
            ath_ftpts='$ath_ftpts', ath_2fga='$ath_2fga', ath_3fga='$ath_3fga', 
            ath_fta='$ath_fta', ath_ass='$ath_ass', ath_block='$ath_block', 
            ath_steal='$ath_steal', ath_ofreb='$ath_ofreb', ath_defreb='$ath_defreb', 
            ath_turn='$ath_turn', ath_foul='$ath_foul'
        WHERE match_id='$match_id' AND match_quarter='$match_quarter' AND ath_num='$ath_num'";

if (mysqli_query($conn, $sql)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => mysqli_error($conn)]);
}

mysqli_close($conn);
?>