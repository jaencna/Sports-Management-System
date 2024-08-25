<?php
include '../connection/connection.php';

// Check if the selectedEmails array is set and not empty
if (isset($_POST['selectedEmails']) && is_array($_POST['selectedEmails']) && !empty($_POST['selectedEmails'])) {
    $emails = $_POST['selectedEmails'];

    foreach ($emails as $email) {
        // Fetch the request data from request_signup_tbl based on the email
        $query = "SELECT * FROM request_signup_tbl WHERE req_email = ?";
        $stmt = $conn->prepare($query);

        if ($stmt) {
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $result = $stmt->get_result();
            $request = $result->fetch_assoc();
            $stmt->close();

            if ($request) {
                // Common variables
                $status = 'active';
                $sampleImg = 'sample.png';
                $zero = 0;

                // Determine the user type and insert into the appropriate table
                if ($request['req_user_type'] == 'ATHLETE') {
                    $insertQuery = "INSERT INTO athlete_tbl (ath_num, ath_first, ath_last, ath_sport, ath_position, ath_height, ath_weight, ath_email, ath_pass, ath_img, STATUS)
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    $insertStmt = $conn->prepare($insertQuery);
                    if ($insertStmt) {
                        $insertStmt->bind_param('sssssssssss', 
                            $request['req_stu_id'], 
                            $request['req_fname'], 
                            $request['req_lname'], 
                            $request['req_sport'], 
                            $request['req_position'],
                            $zero,
                            $zero, 
                            $request['req_email'], 
                            $request['req_pass'], 
                            $sampleImg, 
                            $status);
                        $insertStmt->execute();
                        $athleteId = $conn->insert_id; // Get the last inserted ID
                        $insertStmt->close();

                        // Insert into basketball_overall_percentage if the sport is BASKETBALL
                        if ($request['req_sport'] == 'BASKETBALL') {
                            $insertBasketballPercentQuery = "INSERT INTO basketball_overall_percentage (id, ath_num, percent_3pt, percent_2pt, percent_ft, percent_shooting, percent_passing, percent_ofreb, percent_offense, percent_block, percent_steal, percent_defreb, percent_defense, percent_total)
                                                              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                            $insertBasketballPercentStmt = $conn->prepare($insertBasketballPercentQuery);

                            if ($insertBasketballPercentStmt) {
                                // Initialize all percentages to 0
                                $percent_3pt = $percent_2pt = $percent_ft = $percent_shooting = $percent_passing = $percent_ofreb = $percent_offense = $percent_block = $percent_steal = $percent_defreb = $percent_defense = $percent_total = '0';
                                
                                $insertBasketballPercentStmt->bind_param('isssssssssssss', $athleteId, $request['req_stu_id'], $percent_3pt, $percent_2pt, $percent_ft, $percent_shooting, $percent_passing, $percent_ofreb, $percent_offense, $percent_block, $percent_steal, $percent_defreb, $percent_defense, $percent_total);
                                $insertBasketballPercentStmt->execute();
                                $insertBasketballPercentStmt->close();
                            }

                            $insertBasketballTotalQuery = "INSERT INTO basketball_overall_raw (id, ath_num, total_pts, total_ftpts, total_3pts, total_2pts, total_2fgm, total_3fgm, total_ftm, total_2miss, total_3miss, total_ftmiss, total_assist, total_turnover, total_steal, total_block, total_ofreb, total_defreb, total_foul, total_game)
                                                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                            $insertBasketballTotalStmt = $conn->prepare($insertBasketballTotalQuery);

                            if ($insertBasketballTotalStmt) {
                                // Initialize all totals to 0
                                $total_pts = $total_ftpts = $total_3pts = $total_2pts = $total_2fgm = $total_3fgm = $total_ftm = $total_2miss = $total_3miss = $total_ftmiss = $total_assist = $total_turnover = $total_steal = $total_block = $total_ofreb = $total_defreb = $total_foul =  $total_game = '0';
                                
                                $insertBasketballTotalStmt->bind_param('iissssssssssssssssss', $athleteId, $request['req_stu_id'], $total_pts, $total_ftpts, $total_3pts, $total_2pts, $total_2fgm, $total_3fgm, $total_ftm, $total_2miss, $total_3miss, $total_ftmiss, $total_assist, $total_turnover, $total_steal, $total_block, $total_ofreb, $total_defreb, $total_foul, $total_game);
                                $insertBasketballTotalStmt->execute();
                                $insertBasketballTotalStmt->close();
                            }
                        }
                    }
                } elseif ($request['req_user_type'] == 'COACH') {
                    $insertQuery = "INSERT INTO coach_tbl (coach_fname, coach_lname, coach_email, coach_pass, coach_sport, coach_position, coach_img, STATUS)
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                    $insertStmt = $conn->prepare($insertQuery);
                    if ($insertStmt) {
                        $insertStmt->bind_param('ssssssss', 
                            $request['req_fname'], 
                            $request['req_lname'], 
                            $request['req_email'], 
                            $request['req_pass'], 
                            $request['req_sport'], 
                            $request['req_position'], 
                            $sampleImg, 
                            $status);
                        $insertStmt->execute();
                        $insertStmt->close();
                    }
                }

                // Delete the request from request_signup_tbl after successful insertion
                $deleteQuery = "DELETE FROM request_signup_tbl WHERE req_email = ?";
                $deleteStmt = $conn->prepare($deleteQuery);
                if ($deleteStmt) {
                    $deleteStmt->bind_param('s', $email);
                    $deleteStmt->execute();
                    $deleteStmt->close();
                }
            }
        }
    }
}

$conn->close();
?>
