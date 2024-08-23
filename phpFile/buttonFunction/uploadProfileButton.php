<?php
session_start();
include '../connection/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['profileImage']) && isset($_POST['fileName'])) {
        $file = $_FILES['profileImage'];
        $fileName = $_POST['fileName'];
        $fileTmpName = $file['tmp_name'];
        $fileError = $file['error'];
        $fileSize = $file['size'];
        $fileDestination = '../../images/profile/' . $fileName;

        if ($fileError === 0) {
            if (move_uploaded_file($fileTmpName, $fileDestination)) {
                // Determine user role and email
                if (isset($_SESSION['admin_email'])) {
                    $userRole = 'admin';
                    $userEmail = $_SESSION['admin_email'];
                } elseif (isset($_SESSION['coach_email'])) {
                    $userRole = 'coach';
                    $userEmail = $_SESSION['coach_email'];
                } elseif (isset($_SESSION['athlete_email'])) {
                    $userRole = 'athlete';
                    $userEmail = $_SESSION['athlete_email'];
                } else {
                    echo 'User role not found';
                    exit;
                }

                // Update database with new image path based on user role
                if ($userRole === 'admin') {
                    $stmt = $conn->prepare("UPDATE admin_tbl SET admin_img = ? WHERE admin_email = ?");
                } elseif ($userRole === 'coach') {
                    $stmt = $conn->prepare("UPDATE coach_tbl SET coach_img = ? WHERE coach_email = ?");
                } elseif ($userRole === 'athlete') {
                    $stmt = $conn->prepare("UPDATE athlete_tbl SET athlete_img = ? WHERE athlete_email = ?");
                }

                $stmt->bind_param("ss", $fileName, $userEmail);

                if ($stmt->execute()) {
                    echo 'success';
                } else {
                    echo 'Database update failed';
                }

                $stmt->close();
            } else {
                echo 'Failed to move uploaded file';
            }
        } else {
            echo 'Error in file upload';
        }
    } else {
        echo 'No file or filename provided';
    }
}
