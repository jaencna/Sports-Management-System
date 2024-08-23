<?php
    header('Content-Type: application/json');
    include "../connection/connection.php"; // Include your database connection script

    if (isset($_GET['type'])) {
        $type = $conn->real_escape_string($_GET['type']); // Sanitize input

        $query = "SELECT id, positions FROM position_tbl WHERE sport = '$type'";
        $result = $conn->query($query);

        $positions = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $positions[] = $row['positions'];
            }
        }

        echo json_encode($positions);
    }

    $conn->close();
?>