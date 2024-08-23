<?php
include '../connection/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['newImage']) && isset($_POST['coachEmail'])) {
        $file = $_FILES['newImage'];
        $email = $_POST['coachEmail'];
        $fileTmpName = $file['tmp_name'];
        $fileError = $file['error'];
        $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION); // Get the file extension

        // Generate a random 10-digit number for the file name
        $randomFileName = mt_rand(1000000000, 9999999999) . '.' . $fileExtension;
        $fileDestination = '../../images/profile/' . $randomFileName;

        if ($fileError === 0) {
            if (move_uploaded_file($fileTmpName, $fileDestination)) {
                $stmt = $conn->prepare("UPDATE coach_tbl SET coach_img = ? WHERE coach_email = ?");
                $stmt->bind_param("ss", $randomFileName, $email);

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
        echo 'No file or email provided';
    }
    $conn->close();
}
?>
