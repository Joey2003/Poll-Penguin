<?php
require_once "credentialsValidation.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set the content type to JSON
header('Content-Type: application/json');


$postData = json_decode(file_get_contents('php://input'), true);
$email = isset($postData['email']) ? trim($postData['email']) : '';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // get all couses belonging to this email
    $courses = getEnrolledCourses($email);

    echo json_encode($courses);
}



function getEnrolledCourses($email)
{
    $servername = "127.0.0.1";
    $username = "root";
    $password = "50425175";
    $dbname = "poll_penguin_revamp"; // Your database name

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("error: ". $conn->connect_error);
    }
    $query = "SELECT course_id FROM Courses WHERE student_email = ? OR instructor = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $email, $email);
    $stmt->execute();
    $seenCourses = [];
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $courseId = $row['course_id'];
        if (!isset($seenCourses[$courseId])) {
            $seenCourses[$courseId] = true;
            $courses[] = $row;
        }
    }
    
    return $courses;
}
?>