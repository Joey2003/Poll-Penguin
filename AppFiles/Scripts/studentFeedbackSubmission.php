<?php 
    /* Basic setup */
    error_reporting(E_ALL); // Enable error reporting
    ini_set('display_errors', 1); // Set display_errors to 1

    header('Content-Type: application/json'); // Response is JSON
    require_once 'db_connect.php'; // Connect to database

    /* Helper functions */

    /* Check if the email is valid */
    function validStudent($email) // Definition of validStudent function
    {
        return strpos($email, '@buffalo.edu') !== false; // Check if email contains @buffalo.edu
    }

    /* Function to check if the course exists */
    function courseExists($courseId) // Definition of courseExists function
    {
        global $conn; // Establish connection to database
        $query = "SELECT COUNT(*) FROM Courses WHERE course_id = ?"; // SQL query
        $stmt = $conn->prepare($query); // Prepare the query
        $stmt->bind_param("s", $courseId); // Bind parameters
        $stmt->execute(); // Execute the query
        $stmt->bind_result($count); // Bind the result
        $stmt->fetch(); // Fetch the result
        return $count > 0; // Return true if the count is greater than 0
    }

    function checkIfStudentRegistered($courseId, $email) // Definition of checkIfStudentRegistered function
    {
        global $conn; // Establish connection to database
        $query = "SELECT COUNT(*) FROM Courses WHERE course_id = ? AND student_email = ?"; // SQL query
        $stmt = $conn->prepare($query); // Prepare the query

        if (!$stmt) // Check if the query failed
        {
            die(json_encode(['ok' => false, 'message' => 'Failed to prepare statement: ' . $conn->error])); // Return error in JSON
        }

        $stmt->bind_param("ss", $courseId, $email); // Bind parameters
        $stmt->execute(); // Execute the query
        $stmt->bind_result($count); // Bind the result
        $stmt->fetch(); // Fetch the result
    
        if ($count == 0) // Check if the count is 0
        {
            return false; // Student is not registered for the course
        }
        else
        {
            return true; // Student is registered for the course
        }
    }

    function addFeedback($courseId, $email, $feedback) // Definition of addFeedback function
    {
        global $conn; // Establish connection to database

        if(!checkIfStudentRegistered($courseId, $email)) // Check if the student is registered
        {
            die(json_encode(['ok' => false, 'message' => 'Student is not registered for the course'])); // Return error in JSON
        }

        $query = "UPDATE Courses SET feedback = ? WHERE course_id = ? AND student_email = ?"; // SQL query
        $stmt = $conn->prepare($query); // Prepare the query
        $stmt->bind_param("sss", $feedback, $courseId, $email); // Bind parameters

        /* Value to indicate success or failure */
        if($stmt->execute()) // Execute the query
        {
            return true; // Return true
        }
        else
        {
            return false; // Return false
        }

    }

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

        $courseId = $postInfo['course_id'] ?? ''; // Getting the course ID from the POST data + checking if it's empty
        $email = $postInfo['email'] ?? ''; // Getting the email from the POST data + checking if it's empty
        $feedback = $postInfo['feedback'] ?? ''; // Getting the feedback from the POST data + checking if it's empty

        //$instructor = $postInfo['instructor'] ?? null; // Defaulting to NULL if not provided, will work better with current DB setup

        /* Error Checking for POST data */

        /* Checking if information is valid */
        if(!validStudent($email)) // Check if the email is valid
        {
            die(json_encode(['ok' => false, 'message' => 'Invalid student email'])); // Return error in JSON
        }

        /* Making sure course is not  empty */
        if(empty($courseId)) // Check if the course ID is empty
        {
            die(json_encode(['ok' => false, 'message' => 'No Course Provided'])); // Return error in JSON
        }

        /* Making sure feedback isn't empty */
        if(empty($feedback)) // Check if the feedback is empty
        {
            die(json_encode(['ok' => false, 'message' => 'No feedback provided'])); // Return error in JSON
        }

        if (!courseExists($courseId)) // Check if the course exists
        {
            die(json_encode(['ok' => false, 'message' => 'Course does not exist'])); // Return error in JSON
        }

        /* Ensure valid feedback */
        $validFeedbackOptions = ['Lost', 'Just Right', 'Easy']; // List of valid feedback options
        if (!in_array($feedback, $validFeedbackOptions)) // Check if the feedback is valid
        {
            die(json_encode(['ok' => false, 'message' => 'Invalid feedback'])); // Return error in JSON
        }

        /* Add the feedback to the database */
        if (addFeedback($courseId, $email, $feedback)) // Add the feedback to the database
        {
            echo json_encode(['ok' => true, 'course_id' => $courseId, 'email' => $email, 'feedback' => $feedback, 'message' => 'Feedback submitted']); // Return success in JSON + other information
        }
        else
        {
            echo json_encode(['ok' => false, 'message' => 'Failed to submit feedback']); // Return error in JSON
        }

        $conn->close(); // Close the connection
    }