<?php
session_start();

// Initialize feedback counts if not already set
if (!isset($_SESSION['feedback_counts'])) {
    $_SESSION['feedback_counts'] = ['Lost' => 0, 'Just Right' => 0, 'Easy' => 0];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $servername = "oceanus.cse.buffalo.edu:3306";
    $username = "joeynorm";
    $password = "50425175";
    $dbname = "cse442_2024_summer_team_d_db";
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("". $conn->connect_error);
    }

    if (isset($_POST['action']) && $_POST['action'] === 'reset') {
        // Reset feedback counts
        $sql = $conn->prepare("UPDATE `Courses` SET feedback = null WHERE course_id = ? AND instructor = ?");
        $sql->bind_param("ss", $_POST["course_id"], $_POST["instructor"]);
        $sql->execute();
        $sql->store_result();
        $_SESSION['feedback_counts'] = ['Lost' => 0, 'Just Right' => 0, 'Easy' => 0];
    } elseif (isset($_POST['action']) && $_POST['action'] === 'get') {
        $_SESSION['feedback_counts'] = ['Lost' => 0, 'Just Right' => 0, 'Easy' => 0];
        //run query to get feedback counts from database
        
        $sql = $conn->prepare("SELECT feedback, COUNT(feedback) as count 
        FROM Courses 
        WHERE course_id = ? AND instructor = ? 
        GROUP BY feedback");

        $sql->bind_param("ss", $_POST["course_id"], $_POST["instructor"]);
        $sql->execute();
        $result = $sql->get_result();
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $_SESSION['feedback_counts'][$row['feedback']] = $row['count'];
            }
        }
        
    }

    // Return updated feedback counts as JSON
    echo json_encode(array_values($_SESSION['feedback_counts']));
} else {
    // Return current feedback counts as JSON
    echo json_encode(array_values($_SESSION['feedback_counts']));
}
?>
