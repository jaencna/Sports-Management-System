<?php
header('Content-Type: application/json');
include "../connection/connection.php";

if (isset($_POST['userEmailLog']) && isset($_POST['userPassLog'])) {
    $email = $_POST['userEmailLog'];
    $password = $_POST['userPassLog'];
    $status = "active";

    // Function to check credentials and redirect based on user type
    function authenticateUser($conn, $email, $password, $status) {
        $stmt = $conn->prepare("SELECT * FROM athlete_tbl WHERE ath_email = ? AND STATUS = ?");
        $stmt->bind_param("ss", $email, $status);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if ($password === $user['ath_pass']) { // Plain text comparison
                session_start();
                $_SESSION['ath_email'] = $user['ath_email'];
                echo json_encode(['status' => 'success', 'redirectUrl' => 'phpFile/pages/athlete.php']);
                exit();
            } else {
                return ['status' => 'error', 'message' => 'Incorrect password.'];
            }
        }

        $stmt = $conn->prepare("SELECT * FROM coach_tbl WHERE coach_email = ? AND STATUS = ?");
        $stmt->bind_param("ss", $email, $status);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if ($password === $user['coach_pass']) { // Plain text comparison
                session_start();
                $_SESSION['coach_email'] = $user['coach_email'];
                echo json_encode(['status' => 'success', 'redirectUrl' => 'phpFile/pages/coaching.php']);
                exit();
            } else {
                return ['status' => 'error', 'message' => 'Incorrect password.'];
            }
        }

        $stmt = $conn->prepare("SELECT * FROM admin_tbl WHERE admin_email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $admin = $result->fetch_assoc();
            if ($password === $admin['admin_pass']) { // Plain text comparison
                session_start();
                $_SESSION['admin_email'] = $admin['admin_email'];
                echo json_encode(['status' => 'success', 'redirectUrl' => 'phpFile/pages/admin.php']);
                exit();
            } else {
                return ['status' => 'error', 'message' => 'Incorrect password.'];
            }
        }

        return ['status' => 'error', 'message' => 'Email not found.'];
    }

    // Attempt to authenticate the user
    $authResult = authenticateUser($conn, $email, $password, $status);
    echo json_encode($authResult);
    exit();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid input.']);
    exit();
}
?>
