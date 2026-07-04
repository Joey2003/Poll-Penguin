<?php
    /* Basic setup */
    error_reporting(E_ALL); // Enable error reporting
    ini_set('display_errors', 1); // Set display_errors to 1

    header('Content-Type: application/json'); // Response is JSON
    require_once 'db_connect.php'; // Connect to database

    /* Connect to database + error handling */
    $conn = new mysqli($servername, $username, $password, $dbname); // Connect to database

    if($conn->connect_error) // Check if connection failed
    {
        die(json_encode(['ok'=> false, 'message' => 'Database connection failed: ' . $conn->connect_error])); // Return error in JSON
    }

    /* Handling the POST request + function calls */
    if($_SERVER['REQUEST_METHOD'] === 'POST') // Handle the POST request
    {
        /* Parsing the POST data + error handling */
        $postInfo = json_decode(file_get_contents('php://input'), true); // Parse the POST data

        if($postInfo === null && json_last_error() !== JSON_ERROR_NONE) // Check if POST data is invalid
        {
            die(json_encode(['ok' => false, 'message' => 'Invalid POST data'])); // Return error in JSON
        }

        /* Gathering information from the POST data */
        $courseId = $postInfo['course_id'] ?? ''; // Getting the course ID from the POST data
        $email = $postInfo['email'] ?? ''; // Getting the email from the POST data

        /* Error Checking for POST data */
        if(empty($courseId) || empty($email)) // Check if the course ID or email is empty
        {
            die(json_encode(['ok' => false, 'message' => 'Missing course ID or email'])); // Return error in JSON
        }

        /* Retrieve the feedback entry */
        $query = "SELECT * FROM Courses WHERE course_id = ? AND student_email = ?"; // SQL query
        $stmt = $conn->prepare($query); // Prepare the query
        if (!$stmt) {
            die(json_encode(['ok' => false, 'message' => 'Failed to prepare statement: ' . $conn->error])); // Return error in JSON
        }
        $stmt->bind_param("ss", $courseId, $email); // Bind parameters
        $stmt->execute(); // Execute the query
        $result = $stmt->get_result(); // Get the result

        if ($result->num_rows > 0) {
            $entry = $result->fetch_assoc(); // Fetch the entry
            echo json_encode(['ok' => true, 'entry' => $entry]); // Return the entry in JSON
        } else {
            echo json_encode(['ok' => false, 'message' => 'Entry not found']); // Return error in JSON
        }

        $conn->close(); // Close the connection
    }