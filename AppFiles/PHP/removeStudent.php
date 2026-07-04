<?php

    error_reporting(E_ALL); // Enable error reporting
    ini_set('display_errors', 1); // Set display_errors to 1

    header('Content-Type: application/json'); // Response is JSON
    require_once 'db_connect.php'; // Connect to database

    $conn = new mysqli($servername, $username, $password, $dbname); // Connect to database

    if($conn->connect_error) // Check if connection failed
    {
        die(json_encode(['ok'=> false, 'message' => 'Database connection failed: ' . $conn->connect_error])); // Return error in JSON
    }

    function validStudent($email) // Definition of validStudent function
    {
        return strpos($email, '@buffalo.edu') !== false; // Check if email contains @buffalo.edu
    }

    function removeStudent($courseId, $email) // Definition of removeStudent function
    {
        global $conn; // Establish connection to database
        $query = "DELETE FROM Courses WHERE course_id = ? AND student_email = ?"; // SQL query to delete the student
        $stmt = $conn->prepare($query); // Prepare the query
        if($stmt === false) // Check if the query failed
        {
            return ['ok' => false, 'message'=> 'Query preparation failed: ' . $conn->error]; // Return error in JSON
        }
        $stmt->bind_param("ss", $courseId, $email); // Bind parameters
        if($stmt->execute())
        {
            return ['ok' => true, 'message'=> 'Student removed successfully']; // Return success in JSON
        }
        else
        {
            return ['ok' => false, 'message'=> 'Student removal failed: ' . $stmt->error]; // Return error in JSON
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') // Handle the POST request
    {
        $postInfo = json_decode(file_get_contents('php://input'), true); // Parse the POST data

        if ($postInfo === null && json_last_error() !== JSON_ERROR_NONE) // Check if POST data is invalid
        {
            die(json_encode(['ok' => false, 'message' => 'Invalid POST data'])); // Return error in JSON
        }

        $courseId = $postInfo['courseId']; // Get the course ID from the POST data
        $email = $postInfo['email']; // Get the email from the POST data

        if (!validStudent($email)) // Check if the email is valid
        {
            die(json_encode(['ok' => false, 'message' => 'Invalid student email'])); // Return error in JSON
        }

        $result = removeStudent($courseId, $email); // Call the removeStudent function

        echo json_encode($result); // Return the result in JSON
        $conn -> close();
    }